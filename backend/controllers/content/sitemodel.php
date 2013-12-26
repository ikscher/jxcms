<?php
/*
 * author:ikscher
 * date:2013-12-21
 * function:站点模型管理
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sitemodel extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->lang->load('content');
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        $this->load->model('content/model_sitemodel');
        
        $this->load->helper('url');
    }

    /**
     * 模型管理列表
     */
    public function index() {
        
        
        $categories = unserialize($this->cache->get('category_content'));

        $where = ' where 1';
        $order = $this->input->get('order');
        $by = $this->input->get('by');

        if ($order == 'asc') {
            $order = 'desc';
        } else {
            $order = 'asc';
        }
        
        $this->data['menuid']=$this->input->get('menuid');
        $perpage = $this->config->item('per_page');
        $page = $this->input->get('per_page');
        $page = isset($page) && $page > 0 ? $page : 1;
        $limit = ($page - 1) * $perpage;
        
       
        $datas = $this->model_sitemodel->getSiteModels($where, $by, $order, $limit);
       
        //分页
        $this->load->library('pagination');
        $config['base_url'] = '?d=content&c=sitemodel&m=index';
        $config['total_rows'] = $this->model_sitemodel->getSiteModelTotal($where);
        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();
        
        
       
        $this->data['pagination'] = $pagination;
        $this->data['order'] = $order;
      
      
		//模型文章数array('模型id'=>数量);
		$items = array();
		foreach ($datas as $k=>$r) {
            $items[$r['modelid']]=0;
			foreach ($categories as $catid=>$cat) {
				if(intval($cat['modelid']) == intval($r['modelid'])) {
					$items[$r['modelid']] += intval($cat['items']);
				} else {
					$items[$r['modelid']] += 0;
				}
			}
			$datas[$k]['items'] = $items[$r['modelid']];
		}

        $this->data['datas'] = $datas;
		//$this->public_cache();
		//$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=content&c=sitemodel&a=add\', title:\''.L('add_model').'\', width:\'580\', height:\'420\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('add_model'));
		$this->load->view('content/sitemodel_manage', $this->data);
        
    }
    
    /*
     * 模型添加
     */
    public function add() {
		if($this->input->post('dosubmit')) {
            $info = array();
            $info = $this->input->post('info');
			$info['category_template'] = $this->input->post("setting['category_template']");
			$info['list_template'] = $this->input->post("setting[list_template']");
			$info['show_template'] = $this->input->post("setting['show_template']");
			/*if (isset($_POST['other']) && $_POST['other']) {
				$_POST['info']['admin_list_template'] = $_POST['setting']['admin_list_template'];
				$_POST['info']['member_add_template'] = $_POST['setting']['member_add_template'];
				$_POST['info']['member_list_template'] = $_POST['setting']['member_list_template'];
			} else {
				unset($_POST['setting']['admin_list_template'], $_POST['setting']['member_add_template'], $_POST['setting']['member_list_template']);
			}*/
            
            $sql = $this->db->insert_string("{$this->db->dbprefix}model",$info);
			$this->db->query($sql);
            $modelid = $this->db->insert_id();
            
            //新建模型表
			$model_sql = file_get_contents("././models/model.sql");
			$tablepre = $this->db->dbprefix;
			$tablename = $info['tablename'];
			$model_sql = str_replace('$basic_table', $tablepre.$tablename, $model_sql);
			$model_sql = str_replace('$table_data',$tablepre.$tablename.'_data', $model_sql);
			$model_sql = str_replace('$table_model_field',$tablepre.'model_field', $model_sql);
			$model_sql = str_replace('$modelid',$modelid,$model_sql);
			
			$this->sqlExecute($model_sql);
			$this->cacheField($modelid);
			//调用全站搜索类别接口
            $this->load->model('content/model_type');
		    
            $sql = $this->db->insert_string("{$tablepre}type",array('name'=>$this->input->post('info[name]'),'module'=>'search','modelid'=>$modelid));
            $this->db->query($sql);
  
			//$cache_api = pc_base::load_app_class('cache_api','admin');
			//$cache_api->cache('type');
			//$cache_api->search_type();
            exit('1');
			
		} else {
			$this->load->library('form');
            $this->load->helper('global');
            
            $info = unserialize($this->cache->get('site'));
			$style_list = getTemplateList($info, 0);
            
			foreach ($style_list as $k=>$v) {
				$style_list[$v['dirname']] = $v['name'] ? $v['name'] : $v['dirname'];
				unset($style_list[$k]);
			}
            
			$this->data['style_list'] = $style_list;
			$this->load->view('content/sitemodel_add',$this->data);
		}
	}
    
    /**
	 * 更新指定模型字段缓存
	 * 
	 * @param $modelid 模型id
	 */
	public function cacheField($modelid = 0) {
        $this->load->helper('string');
		$this->load->model('content/model_sitemodel_field');
		$field_array = array();
		$fields = $this->model_sitemodel_field->getSiteModelFields(" where modelid={$modelid} and disabled=0");
		foreach($fields as $_value) {
			$setting = string2array($_value['setting']);
			$_value = array_merge($_value,$setting);
			$field_array[$_value['field']] = $_value;
		}
		$this->cache->save('model_field_'.$modelid,  serialize($field_array));
		return true;
	}
    
    /*
     * 删除
     */
    public function delete() {
		$modelid = intval($this->input->post('modelid'));
		$this->load->model('admin/model_model');
        $result_array = $this->model_model->getAllModels();
        $model_cache=array();
        foreach($result_array as $v){
            $model_cache[$v['modelid']] = $v;
        }
        
        if(!isset($model_cache[$modelid]['tablename'])) return;
		$model_table = $model_cache[$modelid]['tablename'];
        $this->load->model('content/model_sitemodel_field');
		$this->model_sitemodel_field->deleteModelField(" where modelid=$modelid");
		$this->db->query("drop table {$model_table}");
		$this->db->query("drop table {$model_table}_data");
		
		$this->model_sitemodel->deleteSiteModel(" where modelid=$modelid");
		//删除全站搜索接口数据
		$this->load->model('content/model_type');
		$this->model_type->deleteModelType(" where module='search'  and modelid=$modelid");

		exit('1');
	}
    
    /*
     * 编辑、修改
     */
    public function edit() {
		if($this->input->post('dosubmit')) {
			$info = array();
			$modelid = intval($this->input->post('modelid'));
            
            $info = $this->input->post('info');
            
            $setting = $this->input->post('setting');
			$info['category_template'] = $setting['category_template'];
			$info['list_template'] = $setting['list_template'];
			$info['show_template'] = $setting['show_template'];
            
			$sql = $this->db->update_string("{$this->db->dbprefix}model",$info,array('modelid'=>$modelid));
 
			if($this->db->query($sql)){
               exit('1');
            }else{
               exit('0');
            }
		} else {
			$this->load->library('form');
            $this->load->helper('global');
            
            $info = unserialize($this->cache->get('site'));
			$style_list = getTemplateList($info, 0);
			foreach ($style_list as $k=>$v) {
				$style_list[$v['dirname']] = $v['name'] ? $v['name'] : $v['dirname'];
				unset($style_list[$k]);
			}
            $this->data['style_list'] = $style_list;
       
            
			$modelid = intval($this->input->get('modelid'));
            
            $this->load->model('content/model_sitemodel');
			$r = $this->model_sitemodel->getSiteModel(array('modelid'=>$modelid));
		    
            $this->data['r'] = $r;
			$this->load->view('content/sitemodel_edit',$this->data);
		}
	}
    
    /*
     * 启用或禁用
     */
    public function disabled() {
		$modelid = intval($this->input->get('modelid'));
        $status  = intval($this->input->get('disabled'));
        $status = $status == '1' ? '0' : '1';
        $this->load->model('content/model_sitemodel');
		$this->model_sitemodel->updateSiteModel("`disabled`=$status",array('modelid'=>$modelid));
        
	}

    
    /*
     * 批量执行sql语句
     */
    private function sqlExecute($sql) {
		$sqls = $this->sqlSplit($sql);
		if(is_array($sqls)) {
			foreach($sqls as $sql) {
				if(trim($sql) != '') {
					$this->db->query($sql);
				}
			}
		} else {
			$this->db->query($sqls);
		}
		return true;
	}
    
    /*
     * 分离多条sql语句
     */
	private function sqlSplit($sql) {
		if($this->db->version() > '4.1' && $this->db->char_set) {
			$sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=".$this->db->char_set,$sql);
		}
//		if($this->db->dbprefix != "phpcms_") $sql = str_replace("phpcms_", $this->db->dbprefix, $sql);
		$sql = str_replace("\r", "\n", $sql);
		$ret = array();
		$num = 0;
		$queriesarray = explode(";\n", trim($sql));
		unset($sql);
		foreach($queriesarray as $query) {
			$ret[$num] = '';
			$queries = explode("\n", trim($query));
			$queries = array_filter($queries);
			foreach($queries as $query) {
				$str1 = substr($query, 0, 1);
				if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
			}
			$num++;
		}
		return($ret);
	}

  
}

?>
