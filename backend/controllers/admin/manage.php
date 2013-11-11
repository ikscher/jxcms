<?php
/*
 * author:ikscher
 * date:2013-11-11
 * function:管理员信息
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manage extends CI_Controller {

    /**
     *  修改个人信息
     */
    public function edit_info() {
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
			$this->load->view('admin/edit',$this->data);		
		//}
    }

}

/* End of file manage.php */
/* Location: ./application/controllers/admin/manage.php */
