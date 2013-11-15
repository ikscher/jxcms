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
    /*
     * 编辑用户信息
     */

    public function edit_info() {
        
        $this->lang->load('admin_manage');
        $userid = $this->session->userdata('userid');
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
     * 管理员自助修改密码
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
     *  修改个人信息
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
        
        $this->load->view('admin/admin_edit', $this->data);
   
    }
    
    public function delete(){
        $this->load->model('admin/model_manage');
        
        $userid = $this->input->post('userid');
        
        if(empty($userid)) return;
        $this->model_manage->deleteAdmin(array($userid));
    }

}

/* End of file manage.php */
    /* Location: ./application/controllers/admin/manage.php */

    