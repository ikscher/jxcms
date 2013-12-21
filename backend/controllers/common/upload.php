<?php
/*
 * function :后台文件上传类
 * author   :ikscher
 * date     :2013-12-18
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }


    function start() {
        
        $this->load->helper('json');

        $config['upload_path'] = './../frontend/upload';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1024';
        $config['max_width'] = '1024';
        $config['max_height'] = '1768';
        //$config['file_name'] = date("His") ."_"  . rand(10000, 99999) ;
        
        if(!file_exists($filename)){
            @mkdir($config['upload_path'],0777,true);
        }
        
        
        
        $this->load->library('upload', $config);
        
        header('Content-type: text/html; charset=UTF-8');
		$json = new Services_JSON();
			
              
        if (!$this->upload->do_upload('imgFile')) {
            //$error = array('error' => $this->upload->display_errors());
            echo $json->encode(array('error' => 1, 'message' => '上传失败'));
			exit;
            
        } else {
            
            $data = array('upload_data' => $this->upload->data());
            
            $filename=$config['upload_path'].DIRECTORY_SEPARATOR.$data['upload_data']['file_name'];
            //$this->load->view('upload_success', $data);
            //var_dump($data);exit;
            echo $json->encode(array('error' => 0, 'url' =>$filename ));
			exit;
        }
    }

}

?>