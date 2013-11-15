<?php

/*
 * author:ikscher
 * date:2013-11-13
 * function:角色管理
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Role extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    /**
     * 角色管理列表
     */
    public function index() {
        
        $this->lang->load('admin_role');
        $this->load->model('admin/model_role');


        $where = ' where 1';
        $order = $this->input->get('order');
        $by = $this->input->get('by');

        if ($order == 'asc') {
            $order = 'desc';
        } else {
            $order = 'asc';
        }
        
        $perpage = $this->config->item('per_page');
        $page = $this->input->get('per_page');
        $page = isset($page) && $page > 0 ? $page : 1;
        $limit = ($page - 1) * $perpage;
        

        $rolename = $this->input->post('rolename') ? trim($this->input->post('rolename')) : '';
        $roledesc = $this->input->post('roledesc') ? trim($this->input->post('roledesc')) : '';
        $status = $this->input->post('status') ? $this->input->post('status') : '';

        

        if (!empty($rolename))
            $where .=" And `rolename` like '{$rolename}%'";
        if (!empty($roledesc))
            $where .=" And `description` like '{$roledesc}%'";
        if (isset($status) && $status != '')
            $where .=" And `disabled` = {$status}";
        $roles = $this->model_role->getRoles($where, $by, $order, $limit);
        
        //分页
        $this->load->library('pagination');
        $config['base_url'] = '?d=admin&c=role&m=index';
        $config['total_rows'] = $this->model_role->getRolesTotal($where);
        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();
        
        
        $this->data['order'] = $order;
        $this->data['roles'] = $roles;
        $this->data['rolename'] = $rolename;
        $this->data['roledesc'] = $roledesc;
        $this->data['status'] = $status;
        $this->data['pagination'] = $pagination;

        $this->load->view('admin/role_list', $this->data);
    }

    /**
     * 添加角色
     */
    public function add() {
        $this->lang->load('admin_role');
        $this->load->model('admin/model_role');

        $sbt = $this->input->post('dosubmit');
        if (!empty($sbt)) {

            $info = $this->input->post('info');

            $bl = $this->model_role->add($info);

            if ($bl)
                exit('yes');
            else
                exit('no');
        } else {
            $this->load->view('admin/role_add');
        }
    }

    /*
     * 删除角色
     */

    public function delete() {
        $this->load->model('admin/model_role');
        $roleid = $this->input->get('roleid');
        $bl = $this->model_role->delete($roleid);
        if ($bl)
            exit('yes');
        else
            exit('no');
    }

    /*
     * 修改角色
     */

    public function edit() {
        $this->lang->load('admin_role');
        $this->load->model('admin/model_role');

        $sbt = $this->input->post('dosubmit');
        if (!empty($sbt)) {
            $roleid = $this->input->post('roleid');
            $_info = $this->input->post('_info');
            $info = $this->input->post('info');

            $comma = '';
            $str = '';
            foreach ($_info as $k => $v) {
                $str .=$comma;
                $str .="`{$k}`='{$v}'";
                $comma = ',';
            }

            foreach ($info as $k => $v) {
                $str .=$comma;
                $str .="`{$k}`='{$v}'";
                $comma = ',';
            }

            $bl = $this->model_role->edit($str, $roleid);


            if ($bl)
                exit('yes');
            else
                exit('no');
        } else {
            $roleid = $this->input->get('roleid');
            $where = " where `roleid`='{$roleid}'";
            $info = $this->model_role->getRoles($where);
            $data = $info[0];
            $this->data['info'] = $data;
            $this->load->view('admin/role_edit', $this->data);
        }
    }

    /*
     * 是否存在同名的角色
     */

    public function exists() {
        $this->load->model('admin/model_role');


        $rolename = $this->input->post('rolename');

        $roleid = $this->model_role->isExistsRole($rolename);

        if ($roleid)
            exit('yes');
        else
            exit('no');
    }
    
    /**
	 * 成员管理
	 */
	public function manage_member() {
        $this->lang->load('admin_role');
        $this->lang->load('admin_manage');
		$this->load->model('admin/model_role');
		$roleid = $this->input->get('roleid');
       
        
        //加载角色
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        $role_serial=$this->cache->get('role');
        $roles=  unserialize($role_serial);
        
        //分页显示
        $perpage = $this->config->item('per_page');
        $page = $this->input->get('per_page');
        $page = isset($page) && $page > 0 ? $page : 1;
        $limit = ($page - 1) * $perpage;

        $con=array($roleid,$limit,$perpage);
         
		$infos = $this->model_role->getRoleMembers($con);
 
      
        $this->load->library('pagination');
        $config['base_url'] = '?d=admin&c=role&m=manage_member&roleid='.$roleid;
        $config['total_rows'] = $this->model_role->getRoleMembersTotal($roleid);
        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();
        
        //输出变量
        $this->data['infos'] = $infos;
        $this->data['roles'] = $roles;
        $this->data['pagination'] = $pagination;
        $this->data['roleid'] = $roleid;
        
		
        $this->load->view('admin/admin_list',$this->data);
	}

}

?>
