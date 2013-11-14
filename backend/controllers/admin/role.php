<?php

/*
 * author:ikscher
 * date:2013-11-13
 * function:角色管理
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Role extends CI_Controller {

    /**
     * 角色管理列表
     */
    public function index() {
        $this->load->helper('url');
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
        
       

        $rolename = $this->input->post('rolename') ? trim($this->input->post('rolename')) : '';
        $roledesc = $this->input->post('roledesc') ? trim($this->input->post('roledesc')) : '';
        $status = $this->input->post('status') ? $this->input->post('status') : 0;

        if (!empty($rolename))
            $where .=" And `rolename` like '{$rolename}%'";
        if (!empty($roledesc))
            $where .=" And `description` like '{$roledesc}%'";
        if (isset($status))
            $where .=" And `disabled` = {$status}";
        $roles = $this->model_role->getRoles($where, $by,$order);

        $this->data['order'] = $order;
        $this->data['roles'] = $roles;
        $this->data['rolename'] = $rolename;
        $this->data['roledesc'] = $roledesc;
        $this->data['status'] = $status;

        $this->load->view('admin/role_list', $this->data);
    }

    /**
     * 添加角色
     */
    public function add() {
        $this->load->helper('url');
        $this->lang->load('admin_role');
        $this->load->model('admin/model_role');

        $sbt = $this->input->post('dosubmit');
        if (!empty($sbt)) {

            $info = $this->input->post('info');

            $bl = $this->model_role->addRole($info);

            if ($bl)
                exit('yes');
            else
                exit('no');
        } else {
            $this->load->view('admin/role_add');
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

}

?>
