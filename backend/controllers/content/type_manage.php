<?php
/*
 * author:ikscher
 * date:2013-12-26
 * function:站点类别管理
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Type_manage extends CI_Controller {
    private $model;
    private $categories;

    private $catids_string;
    function __construct() {
        parent::__construct();
        $this->lang->load('content');
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        $this->model = unserialize($this->cache->get('model'));
        $this->load->helper('url');
    }
    
    /*
     * 類別列表
     */
    public function index () {
        $this->load->model('content/model_type');
		$datas = array();
        
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
        
       
        $result_datas = $this->model_type->getModelTypes($where, $by, $order, $limit);
       
        //分页
        $this->load->library('pagination');
        $config['base_url'] = '?d=content&c=type_manage&m=index';
        $config['total_rows'] = $this->model_type->getModelTypeTotal($where);
        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();
        
        
       
        $this->data['pagination'] = $pagination;
        $this->data['order'] = $order;

		foreach($result_datas as $r) {
			if (isset($this->model[$r['modelid']])) $r['modelname'] = $this->model[$r['modelid']]['name'];
			$datas[] = $r;
		}
        
        $this->data['datas'] = $datas;
		//$this->cache();
        
		$this->load->view('content/type_list',$this->data);
	}
    
    
    public function add() {
		if($this->input->post('dosubmit')) {
            $info = $this->input->post('info');
			$info['module'] = 'content';
	
			$ids = $this->input->post('ids');
            
            $table = $this->db->dbprefix.'type';
            $sql = $this->db->insert_string($table,$info);
            $this->db->query($sql);
            $typeid = $this->db->insert_id();
            
            $this->load->model('admin/model_category');
            $comma=',';
            if(!empty($ids)) {
                foreach ($ids as $catid) {
                    $r = $this->model_category->getCategory(" where catid=$catid");
                    if($r['usable_type']) {
                        $usable_type = $r['usable_type'];
                        $usable_type .= $comma;
                        $usable_type .= $typeid;
                    } else {
                        $usable_type = $typeid;
                    }
                    $this->model_category->updateCategory(" usable_type='{$usable_type}'"," where catid=$catid");
                }
            }
			
			$this->updateCategory();//更新栏目缓存，按站点
			
		} else {
			$categories = $this->getCategories();
            $this->data['categories'] = $categories;
			$this->load->view('content/type_add',$this->data);
		}
	}
    
    /*
     * 修改
     */
    public function edit() {
		if($this->input->post('dosubmit')) {

			$typeid = $this->input->post('typeid');
            $info = $this->input->post('info');
            
            $table = $this->db->dbprefix.'type';
            $sql = $this->db->update_string($table,$info,array('typeid'=>$typeid));
           
			$this->db->query($sql);
            
			$ids = $this->input->post('ids');
//            $this->output->set_content_type('application/json');
//            $this->output->set_output(json_encode($ids));
            
            $this->load->model('admin/model_category');
            
            //勾选上的 栏目
			if(!empty($ids)) {
                
				foreach ($ids as $catid) {
					$r = $this->model_category->getCategory(" where catid=$catid");
					if($r['usable_type']) {
						$usable_type = array();
						$usable_type_arr = explode(',', $r['usable_type']);
						foreach ($usable_type_arr as $_usable_type_arr) {
							if($_usable_type_arr && $_usable_type_arr!=$typeid) $usable_type[] = $_usable_type_arr;
						}
						$usable_type = implode(',', $usable_type);
						if (!empty($usable_type)) $usable_type = $usable_type.',';
                        $usable_type .=$typeid;
					} else {
						$usable_type = $typeid;
					}
					$this->model_category->updateCategory(" usable_type='{$usable_type}'"," where catid=$catid");
				}
			}
			
            //勾选掉的 栏目
			$catids_string = $this->input->post('catids_string');
			if($catids_string) {	
				$catids_string = explode(',', $catids_string);
				foreach ($catids_string as $catid) {
                    if(!in_array($catid, $ids)){ 
                        $r = $this->model_category->getCategory(" where catid=$catid");
                        $usable_type ='';
                        if($r['usable_type']) {
                            $usable_type_arr = explode(',', $r['usable_type']);
                            $key = array_search($typeid, $usable_type_arr);
                            if(isset($key)) unset($usable_type_arr[$key]);
                            $usable_type = implode(',', $usable_type_arr);

                        } else {
                            $usable_type = '';
                        }
                        $this->model_category->updateCategory(" usable_type='{$usable_type}'"," where catid=$catid");
                    }
				}
			}
	        
			$this->updateCategory();//更新栏目缓存，按站点
	
		} else {
			$typeid = intval($this->input->get('typeid'));
            $this->load->model('content/model_type');
			$r = $this->model_type->getModelType(array('typeid'=>$typeid));
			$this->data['r'] = $r;

			$categories = $this->getCategories($typeid);

            $this->data['categories'] = $categories;
			$this->data['catids_string'] = empty($this->catids_string) ? 0 : $this->catids_string = implode(',', $this->catids_string);
			$this->load->view('content/type_edit',$this->data);
		}
	}
    
    /*
     * 删除数组指定元素
     */
    public static function arrayDeleteElement($vaule,$key,$arr){
        $data = array();
        if(!in_array($arr,$value)) array_push($data,$value);
        
        return $data;
    }

    /**
	 * 选择可用栏目
	 */
	public function getCategories($typeid = 0) {
		$this->categories = unserialize($this->cache->get('category_content'));

		$this->load->library('tree');
		$this->tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$this->tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$categories_ = array();
		$this->catids_string = array();
        if(empty($this->categories)) return ;
		foreach($this->categories as $r) {
			if( $r['type']!=0) continue;
			if($r['child']) {
				$r['checkbox'] = '';
				$r['style'] = 'color:#8A8A8A;';
			} else {
				$checked = '';
				if($typeid && $r['usable_type']) {
					$usable_type = explode(',', $r['usable_type']);
					if(in_array($typeid, $usable_type)) {
						$checked = 'checked';
						$this->catids_string[] = $r['catid'];
					}
				}
				$r['checkbox'] = "<input type='checkbox' name='ids[]' value=\"{$r['catid']}\" {$checked}>";
				$r['style'] = '';
			}
			$categories_[$r['catid']] = $r;
		}
		$str  = "<tr>
					<td align='center'>\$checkbox</td>
					<td style='\$style'>\$spacer\$catname</td>
				</tr>";
		$this->tree->init($categories_);
		$categories_ = $this->tree->getTree(0, $str);
		return $categories_;
	}
    
    /**
	 * 排序
	 */
	public function listOrder() {
		$this->load->model('content/model_type');
        $listorders = $this->input->post('listorders');
        
//        $this->output->set_content_type('application/json');
//        $this->output->set_output(json_encode($listorders));
        
        foreach($listorders as $id => $listorder) {
            $this->model_type->updateModelType("`listorder`=$listorder",array('typeid'=>$id));
        }
        //$this->cache();//更新类别缓存，按站点
	}
    
    /*
     * 刪除
     */
    public function delete() {
        $this->load->model('content/model_type');
		$typeid = intval($this->input->get('typeid'));
		$this->model_type->deleteModelType(array('typeid'=>$typeid));
		//$this->cache();//更新类别缓存，按站点
		exit('1');
	}
    
    
     /**
	 * 更新栏目缓存
	 */
	private function updateCategory() {
		$categories = array();
        
        $this->load->helper('string');
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
   
    
    
  
    

  
}

?>
