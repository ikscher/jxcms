<?php
/*
 * author:ikscher
 * date:2013-11-11
 * function:管理员信息
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manage extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }
    
    
    /**
	 * 管理员管理列表
	 */
	public function index() {
        $this->load->model('admin/model_manage');
        $this->lang->load('admin_manage');
        
        $this->data['adminusername'] = $this->cookie->AuthCode($this->input->cookie('adminusername'), 'DECODE');
        $this->data['rolename'] = $this->session->userdata('userid');
        
	    $where = ' where 1';//初始化
        
        $status   = $this->input->post('status')?$this->input->post('status'):1;
        $field    = $this->input->post('field')?$this->input->post('field'):'username';
        $keyword  = $this->input->post('keyword')?$this->input->post('keyword'):'';
        
        $where .=" and `status`={$status}";
        
        if(!empty($keyword)) $where .=" and `{$field}` like '{$keyword}%'";
        
        //分页显示
        $perpage = $this->config->item('per_page');
        
        $page = intval($this->input->get('per_page'));
        $page = isset($page) && $page > 0 ? $page : 1;
        $limit = ($page - 1) * $perpage;

        $con=array($limit,$perpage);
         
		$infos = $this->model_manage->getAdmins($where,$con);

        $this->load->library('pagination');
        $config['base_url'] = '?d=admin&c=manage&m=index';
        $config['total_rows'] = $this->model_manage->getAdminsTotal($where);
        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();
        
	
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        $role_serial=$this->cache->get('role');
        $roles=  unserialize($role_serial);
        
        $this->cookie->SetCookie("page", $this->cookie->AuthCode($page, 'ENCODE'), 24 * 3600);
        $this->data['infos'] = $infos;
        $this->data['roles'] = $roles;
        $this->data['pagination'] = $pagination;
        
        $this->data['status'] = $status;
        $this->data['field'] = $field;
        $this->data['keyword'] = $keyword;

        
		$this->load->view('admin/admin_list',$this->data);
	}
    
    /*
     * 管理员个人信息修改
     */

    public function edit_info() {
        
        $this->lang->load('admin_manage');
        $userid = $this->session->userdata('userid');
        
        if(empty($userid)) return;
        if ($this->input->post('dosubmit')) {
            $admin_fields = array('email', 'realname');
            $info = array();
            $info = $this->input->post('info');

            foreach ($info as $k => $value) {
                if (!in_array($k, $admin_fields)) {
                    unset($info[$k]);
                }
            }

            $comma = '';
            $str = '';
            foreach ($info as $k => $v) {
                $str .= $comma;
                $str .= "`$k`='{$v}'";
                $comma = ",";
            }

            $this->load->model('admin/model_manage');
            $bool = $this->model_manage->editAdminInfo($str, $userid);
            exit($bool);
        } else {
            $info = array();
            $this->load->model('admin/model_manage');
            $info = $this->model_manage->getAdminInfo(array($userid));

            $this->data['info'] = $info;


            $this->load->view('admin/edit_info', $this->data);
        }
    }

    /**
     * 管理员自身修改密码
     */
    public function edit_pwd() {
        $this->lang->load('admin_manage');
        $userid = $this->session->userdata('userid');
        if ($this->input->post('dosubmit')) {
            $r = array();
            $this->load->model('admin/model_manage');
            $r = $this->model_manage->getAdminInfo(array($userid));
            $pwd = md5(md5($this->input->post('old_password')) . $r['encrypt']);
            $new_pwd=md5(md5($this->input->post('new_password')) . $r['encrypt']);
            
            if ($pwd != $r['password']) {
                exit('no');
            } else {
                $str = " `password`='{$new_pwd}' ";
                if ($this->model_manage->editAdminInfo($str,$userid)) {
                    exit('yes');
                } else {
                    exit('no');
                }
            }
        } else {
            $info = array();
            $this->load->model('admin/model_manage');
            $info = $this->model_manage->getAdminInfo(array($userid));

            $this->data['info'] = $info;
            $this->load->view('admin/edit_pwd', $this->data);
        }
    }

    /*
     * 密码确认
     */
    public function edit_pwd_ajax() {
        $userid = $this->session->userdata('userid');
        $this->load->model('admin/model_manage');
        $info = $this->model_manage->getAdminInfo(array($userid));
        $pwd=$this->input->post('old_password');
        $pwd=md5(md5($pwd).$info['encrypt']);
        if($pwd==$info['password']){
            exit('yes');
        }else{
            exit('no');
        }
    }

    /**
     *  编辑管理员信息
     */
    public function edit() {
        $this->lang->load('admin_manage');
        $this->load->model('admin/model_manage');
         

        $admin_roleid = $this->session->userdata('roleid');
        $roleid = $this->input->get('roleid');
        $userid = $this->input->get('userid');//这是a链接过来的，直接显示的
        $sbt = $this->input->post('dosubmit');
      
        if(!empty($sbt)) {
			
			$info = $this->input->post('info');
            
            $userid=$info['userid']; //这是ajax post提交的，上面的$userid是不能在这使用的

            if(empty($userid)) exit("no");
            
            $admin_fields=array('password','realname','email','encrypt','roleid');
			
            $comma='';
            $str='';
			foreach ($info as $k=>$v) {
				if (!in_array($k, $admin_fields) || empty($v)){
					unset($info[$k]);
				} 
			}
            
            if(isset($info['password'])){
                $info['password']=md5(md5($info['password']).$info['encrypt']);
            }

            
            foreach($info as $k=>$v){
                $str .= $comma;

                $str .= "`{$k}`='{$v}'";
                $comma=',';
            }
            

            
			$bl=$this->model_manage->editAdminInfo($str,$userid);
            
            if($bl)
                exit('yes');
            else
                exit('no');
	
		} 
        
       
        if (empty($userid)) return ;
        
        $info = $this->model_manage->getAdminInfo(array($userid));

        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));

        $roles = array();
        $roles = unserialize($this->cache->get('role'));
        
        $this->data['roles'] = $roles;
        $this->data['info']  = $info;
        $this->data['roleid'] = $roleid;
        $this->data['admin_roleid'] = $admin_roleid;
        $this->data['page']=$this->cookie->AuthCode($this->input->cookie('page'), 'DECODE');
        
        $this->load->view('admin/admin_edit', $this->data);
   
    }
    
    /*
     * 删除管理员
     */
    public function delete(){
        $this->load->model('admin/model_manage');
        
        $userid = $this->input->post('userid');
        
        if(empty($userid)) return;
        $this->model_manage->deleteAdmin(array($userid));
    }

}

/* End of file manage.php */
    /* Location: ./application/controllers/admin/manage.php */

    