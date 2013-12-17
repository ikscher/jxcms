<?php

/*
 * function :后台导航主界面
 * author   :ikscher
 * date     :2013-12-10
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cache extends CI_Controller {
    /*
     * 更新缓存
     */

    public function updateCache() {
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        
        $this->updateRole();
        $this->updateModule();
        $this->updateModel();
        $this->updateSite();
        $this->updateCategory();
        $this->updateUrlRule();
        $this->memberGroup();
        
    }
    
    /*
     * 更新角色缓存
     */
    private function updateRole(){
        $result_array = array();
        $result_serial = '';
        if (!$this->cache->get('role')) {
            $this->load->model('admin/model_role');
            $result_array = $this->model_role->getAllRoles();
     
            $roles=array();
            foreach($result_array as $v){
                $roles[$v['roleid']]=$v;
            }
            $result_serial = serialize($roles);

            $this->cache->save('role', $result_serial);
        }
    }
    
    /*
     * 更新模块缓存
     */
    private function updateModule(){
        $result_array = array();
        $result_serial = '';
        if (!$this->cache->get('module')) {
            $this->load->model('admin/model_module');
            $result_array = $this->model_module->getAllModules();
     
           
            $result_serial = serialize($result_array);

            $this->cache->save('module', $result_serial);
        }
    }
    
    /*
     * 更新模型缓存
     */
    private function updateModel(){
        $result_array = array();
        $result_serial = '';
        //if (!$this->cache->get('model')) {
            $this->load->model('admin/model_model');
            $result_array = $this->model_model->getAllModels();
            
            $item=array();
            foreach($result_array as $v){
                $item[$v['modelid']] = $v;
            }
           
            $result_serial = serialize($item);

            $this->cache->save('model', $result_serial);
        //}
    }
    
    /**
	 * 设置站点缓存
	 */
	private function updateSite() {
         $result_array = array();
        $result_serial = '';
        //if (!$this->cache->get('site')) {
            $this->load->model('admin/model_site');
            $result_array = $this->model_site->getSiteInfo();
            
            $web_path=$this->config->item('web_path');

            $result_array['url'] = $result_array['domain'] ? $result_array['domain'] : $web_path.$result_array['dirname'].'/';
         
           
            $result_serial = serialize($result_array);

            $this->cache->save('site', $result_serial);
        //}
        
		
	}
    
    /**
	 * 更新栏目缓存
	 */
	private function updateCategory() {
		$categories = array();
        
        $this->load->helper('string');
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
		$models = unserialize($this->cache->get('model'));
        
        $this->load->model('admin/model_category');
		if (is_array($models)) {
			foreach ($models as $modelid=>$model) {
				$datas = $this->model_category->getCategories('catid,type,items'," where modelid={$modelid}");
				$array = array();
				foreach ($datas as $r) {
					if($r['type']==0) $array[$r['catid']] = $r['items'];
				}
				$this->cache->save('category_items_'.$modelid, serialize($array));
			}
		}
        
//		$array = array();
//		$categories = $this->model_category->getCategories('catid,siteid', " where  `module`='content' order by listorder ASC");
//        
//		foreach ($categories as $r) {
//			$array[$r['catid']] = $r['siteid'];
//		}
//    
//		$this->cache->save('category_content',$array);
        
		
		$categories = $this->model_category->getCategories('*'," where  module='content' order by  listorder ASC");
        
		foreach($categories as $r) {
			unset($r['module']);
			$setting = string2array($r['setting']);
            
			$r['create_to_html_root'] = isset($setting['create_to_html_root'])?$setting['create_to_html_root']:null;
			$r['ishtml'] = isset($setting['ishtml'])?$setting['ishtml']:'';
			$r['content_ishtml'] = isset($setting['content_ishtml'])?$setting['content_ishtml']:'';
			$r['category_ruleid'] = isset($setting['category_ruleid'])?$setting['category_ruleid']:'';
			$r['show_ruleid'] = isset($setting['show_ruleid'])?$setting['show_ruleid']:'';
			$r['workflowid'] = isset($setting['workflowid'])?$setting['workflowid']:'';
			$r['isdomain'] = '0';
			if(!preg_match('/^(http|https):\/\//', $r['url'])) {
				$r['url'] = $this->config->item('web_path').$r['url'];
			} elseif ($r['ishtml']) {
				$r['isdomain'] = '1';
			}
			$categories[$r['catid']] = $r;
		}
        
		$this->cache->save('category_content',  serialize($categories));
		
	}
    
    /*
     * 更新URL规则缓存
     */
    private function updateUrlRule(){
        $results = array();
        $this->load->model('admin/model_urlrule');
        $results = $this->model_urlrule->getUrlRules();
		$basic_results = array();
		foreach($results as $k=>$v) {
			$basic_results[$k] = $v['urlrule'];;
		}
		$this->cache->save('urlrules_detail',  serialize($results));
        $this->cache->save('urlrules',  serialize($basic_results));
    }
    
    /**
	 * 更新会员组缓存
	 */
	private function memberGroup() {
        $results = array();
        $this->load->model('admin/model_member_group');
        $results = $this->model_member_group->getMemberGroup();
		$this->cache->save('grouplist', serialize($results));
		
	}
	

}

?>