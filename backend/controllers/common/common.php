<?php
/*
 * function :后台通用控制类
 * author   :ikscher
 * date     :2013-12-24
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common extends CI_Controller {
    
    function __construct(){
        $this->load->helper('url');
        $token=$this->input->get('token')?$this->input->get('token'):'';
        
        if (!$this->input->cookie('adminuserid') || !$this->session->userdata('userid') || $token!=$this->session->userdata('token')) {
            redirect('d=common&c=login&m=index');
        }
    }

 

}

?>