<?php
/*
 * function :文章推送 
 * author   :ikscher
 * date     :2014-1-9(15)
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Push extends CI_Controller {

   function __construct() {
        parent::__construct();
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        $this->load->helper('url');
        $this->load->helper('string');
        $this->lang->load('content');
        $this->load->library('form');
        $this->load->library('pushapi');
        
        
        $roleid = $this->session->userdata('roleid');
        $ids=array();
        $ids = $this->input->post('ids');
        
        
        //权限判断，根据栏目里面的权限设置检查	
        $_no_=array();
        if(!empty($ids) && $this->session->userdata('roleid') != 1 ) {
			foreach($ids as $v){
                $id_ = explode(',',$v);
                $catid = intval($id_[2]);
                $this->load->model('admin/model_category_priv');
                $this->load->model('admin/model_category');

                $priv_datas = $this->model_category_priv->getCategoryPriv(array('roleid'=>$roleid,'catid'=>$catid,'action'=>'push'));
                if(empty($priv_datas['catid'])){
                   $result=$this->model_category->getCategoryById(array($catid));
                   array_push($_no_,$result['catname']);
                }else{
                   continue;
                }
            }
        }
        
        if(count($_no_)>0){
           $_no_=array_unique($_no_);
           $str = array2string_($_no_);
           exit('no_privileges'.$str);
        }
        
    }
    
    //撑起一片天
    public function index(){
       

        $module = $this->input->get('module');
        $module = (isset($module) && !empty($module)) ? $module : 'admin';
        $action = $this->input->get('action');
        $action = isset($action)?$action:'positionList';
        
        if ($this->input->post('dosubmit')) {

            $this->load->model('content/model_content');
            $modelid = $this->input->post('modelid');

			$this->model_content->set($modelid);
			$info = array();
			$ids = explode('|', $this->input->post('id'));
            
            $posid=$this->input->post('posid');
 
            if($posid){
                $catid = $this->input->post('catid');
                if(is_array($ids)) {
                    foreach($ids as $id) {
                        $info[$id] = $this->model_content->getContent($catid,$id);
                    }
                }
    //            var_dump($info);exit;
            }

            $this->pushapi->{$action}($info, $this->input->post());
            echo "<script type='text/javascript'>location.href='?d=content&c=content&m=index';</script>";
		} else {

            $html = $this->pushapi->{$action}(array('modelid'=>$this->input->get('modelid'), 'catid'=>$this->input->get('catid')));
            $tpl = $this->input->get('tpl');
            $tpl = !empty($tpl) ? 'push_to_category' : 'push_list';
  
            $this->data['html'] = $html;
            $this->data['module'] = $module;
            $this->data['modelid'] = $this->input->get('modelid');
            $this->data['catid'] = $this->input->get('catid');
            $this->data['id'] = $this->input->get('id');
            $this->data['action'] = $action;
            $this->load->view('content/'.$tpl,$this->data);
			
		}
    }
    
    public function public_ajax_get() {
		$action = $this->input->get('action');
        $action = isset($action)?$action:'positionList';

        $html = $this->pushapi->{$action}($_GET['html']);
        echo $html;
		
	}
    
    

    
}

?>