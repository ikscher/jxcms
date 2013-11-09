<?php
/*  
 * function :后台登录頁面 
 * author   :ikscher
 * date     :2013-11-01
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');



class Logout extends CI_Controller {
	public function index() { 

        $this->load->helper('url');
      
 		//$this->session->unset_userdata('token');
        $this->session->sess_destroy();
		
        $adminuserid=$this->input->cookie('adminuserid');
        $adminusername=$this->input->cookie('adminusername');
		if(isset($adminuserid)) $this->cookie->SetCookie("adminuserid","",-24*3600);
		if(isset($adminusername)) $this->cookie->SetCookie("adminusername","",-24*3600);


		redirect('d=common&c=login&m=index');
  	}
}  
?>