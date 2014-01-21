<?php  if (!defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 *   推荐至推荐位接口类
 *
 * @author             ikscher
 * @lastmodify			2014-1-15
 */


class Pushapi {
 	private $db, $session,$lang,$tbl_prefix,$tree,$categories; //数据调用属性

	public function __construct() {
        $CI =& get_instance();
        $this->db = &$CI->db;
        $this->session = &$CI->session;
        $this->lang = &$CI->lang;
        $this->tbl_prefix = $this->db->dbprefix;
        $CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        
		//$this->db = pc_base::load_model('position_model');  //加载数据模型
	}

	/**
	 * 推荐位推送修改接口
	 * 适合在文章发布、修改时调用
	 * @param int $id 推荐文章ID
	 * @param int $modelid 模型ID
	 * @param array $posid 推送到的推荐位ID
	 * @param array $data 推送数据
	 * @param int $expiration 过期时间设置
	 * @param int $undel 是否判断推荐位去除情况
	 * @param string $model 调取的数据模型
	 * 调用方式
	 * $push = pc_base::load_app_class('push_api','admin');
	 * $push->position_update(323, 25, 45, array(20,21), array('title'=>'文章标题','thumb'=>'缩略图路径','inputtime'='时间戳'));
	 */
	public function positionUpdate($id, $modelid, $catid, $posid, $data, $expiration = 0, $undel = 0, $model = 'model_content') {
		$arr = $param = array();
		$id = intval($id);
		if($id == '0') return false;
		$modelid = intval($modelid);
		$data['inputtime'] = $data['inputtime'] ? $data['inputtime'] : SYS_TIME;

		//组装属性参数
		$arr['modelid'] = $modelid;
		$arr['catid'] =  $catid;
		$arr['posid'] =  $posid;
		$arr['dosubmit'] =  '1';

		//组装数据
		$param[0] = $data;
		$param[0]['id'] = $id;
		if ($undel==0)  $this->positionDel($catid, $id, $posid);
		return $this->positionList($param, $arr, $expiration, $model) ? true : false;
	}

	/**
	 * 推荐位删除计算
	 * Enter description here ...
	 * @param int $catid 栏目ID
	 * @param int $id 文章id
	 * @param array $input_posid 传入推荐位数组
	 */
	private function positionDel($catid,$id,$input_posid) {
		$array = array();
        $this->load->model('content/model_position_data');
		
		//查找已存在推荐位
		$r = $this->model_position_data->getPosition__(array('id'=>$id,'catid'=>$catid));
		if(!$r) return false;
		foreach ($r as $v) $array[] = $v['posid'];

		//差集计算，需要删除的推荐
		$real_posid = implode(',', array_diff($array,$input_posid));

		if (!$real_posid) return false;

		return  $this->model_position_data->deletePosition__($catid,$id,$real_posid) ? true : false;
	}

	/**
	 * 判断文章是否被推荐
	 * @param $id
	 * @param $modelid
	 */
	private function contentPos($id, $modelid) {
		$id = intval($id);
		$modelid = intval($modelid);
		if ($id && $modelid) {
            $this->load->model('content/model_position_data');
	        $this->load->model('content/model_content');
			
            $this->model_content->set($modelid);
			
			$posids = $this->model_position_data->getPosition___(array('id'=>$id,'modelid'=>$modelid)) ? 1 : 0;
			if ($posids==0) $this->model_content->updatePos(array('posids'=>$posids,'id'=>$id));
		}
		return true;
	}

	/**
	 * 接口处理方法
	 * @param array $param 属性 请求时，为模型、栏目数组。提交添加为二维信息数据 。例：array(1=>array('title'=>'多发发送方法', ....))
	 * @param array $arr 参数 表单数据，只在请求添加时传递。 例：array('modelid'=>1, 'catid'=>12);
	 * @param int $expiration 过期时间设置
	 * @param string $model 调取的数据库型名称
	 */
	public function positionList($param = array(), $arr = array(), $expiration = 0, $model = 'model_content') {
        global $CI;
		if (isset($arr['dosubmit']) && !empty($arr['dosubmit'])) {
			if (!$model) {
				$model = 'model_content';
			} else {
				$model = $model;
			}
         
            $CI->load->model("content/{$model}");
			$modelid = intval($arr['modelid']);
			$catid = intval($arr['catid']);
			$expiration = intval($expiration)>SYS_TIME ? intval($expiration) : 0;
			$CI->{$model}->set($modelid);
			$info = $r = array();
            $CI->load->model('content/model_position');
			$CI->load->model('content/model_position_data');
            
			$position_info = unserialize($CI->cache->get('position'));
			$fulltext_array = unserialize($CI->cache->get('model_field_'.$modelid));
			if (is_array($arr['posid']) && !empty($arr['posid']) && is_array($param) && !empty($param)) {
				foreach ($arr['posid'] as $pid) {
					$ext = $func_char = '';
					$r = $CI->model_position->getPosition(array('posid'=>$pid)); //检查推荐位是否启用了扩展字段
					$ext = $r['extention'] ? $r['extention'] : '';
					if ($ext) {
						$ext = str_replace(array('\'', '"', ' '), '', $ext);
						$func_char = strpos($ext, '(');
						if ($func_char) {
							$func_name = $param_k = $param_arr = '';
							$func_name = substr($ext, 0, $func_char);
							$param_k = substr($ext, $func_char+1, strrpos($ext, ')')-($func_char+1));
							$param_arr = explode(',', $param_k);
						}
					}
					foreach ($param as $d) {
						$info['id'] = $info['listorder'] = $d['id'];
						$info['catid'] = $catid;
						$info['posid'] = $pid;
						$info['module'] = $model == 'yp_content_model' ? 'yp' : 'content';
						$info['modelid'] = $modelid;
						$fields_arr = $fields_value = '';
						foreach($fulltext_array AS $key=>$value){
							$fields_arr[] = '{'.$key.'}';
							$fields_value[] = isset($d[$key])?$d[$key]:'';
							if($value['isposition']) {
								if ($d[$key]) $info['data'][$key] = $d[$key];
							}
						}
						if ($ext) {
							if ($func_name) {
								foreach ($param_arr as $k => $v) {
									$c_func_name = $c_param = $c_param_arr = $c_func_char = '';
									$c_func_char = strpos($v, '(');
									if ($c_func_char) {
										$c_func_name = substr($v, 0, $c_func_char);
										$c_param = substr($v, $c_func_char+1, strrpos($v, ')')-($c_func_char+1));
										$c_param_arr = explode(',', $c_param);
										$param_arr[$k] = call_user_func_array($c_func_name, $c_param_arr);
									} else {
										$param_arr[$k] = str_replace($fields_arr, $fields_value, $v);
									}
								}
								$info['extention'] = call_user_func_array($func_name, $param_arr);
							} else {
								$info['extention'] = $d[$ext];
							}
						}
						//颜色选择为隐藏域 在这里进行取值
						$info['data']['style'] = $d['style'];
						$info['thumb'] = isset($info['data']['thumb']) ? 1 : 0;
					
						$info['data'] = array2string($info['data']);
						$info['expiration'] = $expiration;
                        
                    
						if ($r = $CI->model_position_data->getPosition(array('id'=>$d['id'], 'posid'=>$pid, 'catid'=>$info['catid']))) {
                            $sql = $CI->db->update_string("{$this->tbl_prefix}position_data",$info,array('id'=>$d['id'], 'posid'=>$pid, 'catid'=>$info['catid']));
							if($r['synedit'] == '0') $CI->db->query($sql);
						} else {
                            $sql = $CI->db->insert_string("{$this->tbl_prefix}position_data",$info);
							$CI->db->query($sql);
						}
                       
						$CI->{$model}->updatePos(array('posids'=>1,'id'=>$d['id']));
						unset($info);
					}
					$maxnum = $position_info[$pid]['maxnum']+4;
                    $r= $CI->model_position_data->getPosition_(array('catid'=>$catid, 'posid'=>$pid),$maxnum);
		
					if ($r && $position_info[$pid]['maxnum']) {
						$listorder = $r[0]['listorder'];
						$where = '`catid`='.$catid.' AND `posid`='.$pid.' AND `listorder`<'.$listorder;
						$results = $CI->model_position_data->getAllPositions($where);
						foreach ($results as $r) {
							$CI->model_position_data->deletePosition_(array('id'=>$r['id'], 'posid'=>$pid, 'catid'=>$catid));
							$CI->contentPos($r['id'], $r['modelid']);
						}
					}
				}
			}
			return true;

		} else {
			$infos = $info = array();
			$where = '1';
			$category = unserialize($CI->cache->get('category_content'));

			$positions = unserialize($CI->cache->get('position'));
            
			if(!empty($positions)) {
				foreach ($positions as $pid => $p) {
					
					//获取栏目下所有子栏目
					if ($p['catid']) $catids = explode(',',$category[$p['catid']]['arrchildid']);
					if (($p['modelid']==0 || $p['modelid']==$param['modelid']) && ($p['catid']==0 || in_array($param['catid'], $catids))) {
						$info[$pid] = $p['name'];
					}
				}
				return array(
					'posid' => array('name'=>$this->lang->line('position'), 'htmltype'=>'checkbox', 'defaultvalue'=>'', 'data'=>$info, 'validator'=>array('min'=>1)),
				);
			}
		}
	}
    
    
    
    /**
	 * 接口处理方法
	 * @param array $param 属性 请求时，为模型、栏目数组。提交添加为二维信息数据 。例：array(1=>array('title'=>'多发发送方法', ....))
	 * @param array $arr 参数 表单数据，只在请求添加时传递。 例：array('modelid'=>1, 'catid'=>12); 
	 */
	public function categoryList($param = array(), $arr = array()) {
        global $CI;
        $CI->load->model('content/model_content');
        
		if (isset($arr['dosubmit']) && !empty($arr['dosubmit'])) {
			$id = $_POST['id'];
			if(empty($id)) return true;
			$id_arr = explode('|',$id);
			if(count($id_arr)==0) return true;
			$old_catid = intval($_POST['catid']);
			if(!$old_catid) return true;
			$ids = $_POST['ids'];
			if(empty($ids)) return true;
			$ids = explode('|', $ids);
	
			$this->categories = unserialize($this->cache->get('category_content'));

			$modelid = $this->categories[$old_catid]['modelid'];
			$CI->model_content->set($modelid);
			
			$CI->load->model('content/model_hits');
			foreach($id_arr as $id) {
				
				$r = $CI->model_content->getOne(array('id'=>$id));
				$linkurl = preg_match('/^http:\/\//',$r['url']) ? $r['url'] : self::siteurl($siteid).$r['url'];
				foreach($ids as $catid) {
					
					$this->categories = unserialize($this->cache->get('category_content'));
					$modelid = $this->categories[$catid]['modelid'];
					$CI->model_content->set($modelid);
                    $tablename = $CI->model_content->getTable();
                    $sql=$CI->db->insert_string($tablename,array('title'=>$r['title'],
                                                            'style'=>$r['style'],
                                                            'thumb'=>$r['thumb'],
                                                            'keywords'=>$r['keywords'],
                                                            'description'=>$r['description'],
                                                            'status'=>$r['status'],
                                                            'catid'=>$catid,
                                                            'url'=>$linkurl,
                                                            'sysadd'=>1,
                                                            'username'=>$r['username'],
                                                            'inputtime'=>$r['inputtime'],
                                                            'updatetime'=>$r['updatetime'],
                                                            'islink'=>1
                                                        ));
                    $CI->db->query($sql);
                    $newid = $CI->db->insert_id();
                    $table_name .='_data';
                    $sql = $CI->db->insert_string($tablename,array('id'=>$newid));
                    $CI->db->query($sql);
                    $hitsid = 'c-'.$modelid.'-'.$newid;
                    $this->model_hits->insert(array('hitsid'=>$hitsid,'catid'=>$catid,'updatetime'=>SYS_TIME));
				}
			}
			return true;
		} else {
			
			$this->categories = unserialize($this->cache->get('category_content'));
            $CI->load->library('tree');
			
			$this->tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
			$this->tree->nbsp = '&nbsp;&nbsp;&nbsp;';
			$categorys = array();
			$this->catids_string = array();
			if($this->session->userdata('roleid') != 1) {
                //write here
                $this->load->model('admin/model_category_priv');
				
				$priv_result = $this->model_category_priv->getCategoryPrivs_(array('action'=>'add','roleid'=>$this->session->userdata('roleid')));
				$priv_catids = array();
				foreach($priv_result as $_v) {
					$priv_catids[] = $_v['catid'];
				}
				if(empty($priv_catids)) return '';
			}

			foreach($this->categories as $r) {
				if( $r['type']!=0) continue;
				if($this->session->userdata('roleid') != 1 && !in_array($r['catid'],$priv_catids)) {
					$arrchildid = explode(',',$r['arrchildid']);
					$array_intersect = array_intersect($priv_catids,$arrchildid);
					if(empty($array_intersect)) continue;
				}
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
					$r['checkbox'] = "<input type='checkbox' name='ids[]' value='{$r[catid]}' {$checked}>";
					$r['style'] = '';
				}
				$categorys[$r['catid']] = $r;
			}
			$str  = "<tr>
						<td align='center'>\$checkbox</td>
						<td style='\$style'>\$spacer\$catname</td>
					</tr>";
			$this->tree->init($categorys);
			$categorys = $this->tree->get_tree(0, $str);
			return $categorys;
		}
     }
     
     
     
    /**
     * 获取站点域名
     */
    private static function siteurl() {
        global $CI;
        static $site;
        if(empty($site)) $site = unserialize($CI->cache->get('site'));
        $site_ = unserialize($site['data']);
        return substr($site_['domain'],0,-1);
    }
    
    
    /**
	 * 信息推荐至专题接口
	 * @param array $param 属性 请求时，为模型、栏目数组。 例：array('modelid'=>1, 'catid'=>12); 提交添加为二维信息数据 。例：array(1=>array('title'=>'多发发送方法', ....))
	 * @param array $arr 参数 表单数据，只在请求添加时传递。
	 * @return 返回专题的下拉列表 
	 */
	public function _get_special($param = array(), $arr = array()) {
        global $CI;
        $CI->lang->load('special');
        $CI->load->model('content/model_special_content');
		if (isset($arr['dosubmit']) && !empty($arr['dosubmit'])) {
			foreach ($param as $id => $v) {
				if (!$arr['specialid'] || !$arr['typeid']) continue;
				if (!$CI->model_special_content->get_(array('title'=>$v['title'], 'specialid'=>$arr['specialid']))) {
					$info['specialid'] = $arr['specialid'];
					$info['typeid'] = $arr['typeid'];
					$info['title'] = $v['title'];
					$info['thumb'] = $v['thumb'];
					$info['url'] = $v['url'];
					$info['curl'] = $v['id'].'|'.$v['catid'];
					$info['description'] = $v['description'];
					$info['userid'] = $v['userid'];
					$info['username'] = $v['username'];
					$info['inputtime'] = $v['inputtime'];
					$info['updatetime'] = $v['updatetime'];
					$info['islink'] = 1;
                    
                    
                    $CI->db->insert_string("{$this->tbl_prefix}special_content",$info);
					$CI->db->query($sql);
				}
			}
			return true;
		} else {
			$datas = unserialize($CI->cache->get('special'));
            $special = array();
            
			$special[] = $CI->lang->line('please_select');
			if (is_array($datas)) {
                
				foreach ($datas as $sid => $d) {
				   $special[] = $d['title'];
				}
			}

			return array(
				'specialid' => array('name'=>$CI->lang->line('special_list'), 'htmltype'=>'select', 'data'=>$special, 'ajax'=>array('name'=>$CI->lang->line('for_type'), 'action'=>'_get_type', 'm'=>'special', 'id'=>'typeid'))
			);
		}
	}
    
    /**
	 * 获取分类
	 * @param intval $specialid 专题ID
	 */
	public function _get_type($specialid = 0) {
        global $CI;
		$CI->load->model('content/model_type');
		$data = $arr = array();
		$data = $CI->model_type->getType(array('module'=>'special', 'parentid'=>$specialid));
		$CI->load->library('form');
		foreach ($data as $r) {
			$arr[$r['typeid']] = $r['name'];
		}
		return $CI->form->select($arr, '', 'name="typeid", id="typeid"', $CI->lang->line('please_select'));
	}
    
   
	
}


 ?>