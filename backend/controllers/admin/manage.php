<?php

/*
 * author:ikscher
 * date:2013-11-11
 * function:管理员信息
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manage extends CI_Controller {
    /*
     * 编辑用户信息
     */

    public function edit_info() {
        $this->load->helper('url');
        $this->lang->load('admin');
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

            $this->load->model('admin/model_admin');
            $bool = $this->model_admin->editAdminInfo($str, $userid);
            exit($bool);
        } else {
            $info = array();
            $this->load->model('admin/model_admin');
            $info = $this->model_admin->getAdminInfo(array($userid));

            $this->data['info'] = $info;


            $this->load->view('admin/edit_info', $this->data);
        }
    }

    /**
     * 管理员自助修改密码
     */
    public function edit_pwd() {
        $this->load->helper('url');
        $this->lang->load('admin');
        $userid = $this->session->userdata('userid');
        if ($this->input->post('dosubmit')) {
            $r = array();
            $this->load->model('admin/model_admin');
            $r = $this->model_admin->getAdminInfo(array($userid));
            $pwd = md5(md5($this->input->post('old_password')) . $r['encrypt']);
            $new_pwd=md5(md5($this->input->post('new_password')) . $r['encrypt']);
            
            if ($pwd != $r['password']) {
                exit('no');
            } else {
                $str = " `password`='{$new_pwd}' ";
                if ($this->model_admin->editAdminInfo($str,$userid)) {
                    exit('yes');
                } else {
                    exit('no');
                }
            }
        } else {
            $info = array();
            $this->load->model('admin/model_admin');
            $info = $this->model_admin->getAdminInfo(array($userid));

            $this->data['info'] = $info;
            $this->load->view('admin/edit_pwd', $this->data);
        }
    }

    /*
     * 密码确认
     */
    public function edit_pwd_ajax() {
        $userid = $this->session->userdata('userid');
        $this->load->model('admin/model_admin');
        $info = $this->model_admin->getAdminInfo(array($userid));
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
        $this->lang->load('admin');
        $this->load->helper('url');
        $this->data['roleid'] = $this->session->userdata('roleid');
//        if(isset($_POST['dosubmit'])) {
//			$memberinfo = $info = array();			
//			$info = checkuserinfo($_POST['info']);
//			if(isset($info['password']) && !empty($info['password']))
//			{
//				$this->op->edit_password($info['userid'], $info['password']);
//			}
//			$userid = $info['userid'];
//			$admin_fields = array('username', 'email', 'roleid','realname');
//			foreach ($info as $k=>$value) {
//				if (!in_array($k, $admin_fields)){
//					unset($info[$k]);
//				}
//			}
//			$this->db->update($info,array('userid'=>$userid));
//			showmessage(L('operation_success'),'','','edit');
//		} else {					
//			$info = $this->db->get_one(array('userid'=>$_GET['userid']));
//			extract($info);	
//			$roles = $this->role_db->select(array('disabled'=>'0'));	
//			$show_header = true;

        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));

        $roles = array();
        $roles = unserialize($this->cache->get('role'));
        $this->data['roles'] = $roles;
        $this->load->view('admin/edit', $this->data);
        //}
    }

}

/* End of file manage.php */
    /* Location: ./application/controllers/admin/manage.php */

    