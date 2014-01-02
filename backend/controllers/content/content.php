<?php
/*
 * author:ikscher
 * date:2013-12-31
 * function:内容管理
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Content extends CI_Controller {
    private $categories;

    function __construct() {
        parent::__construct();
        $this->load->library('form');
        $this->load->library('admin');
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        $this->load->model('content/model_content');
        $this->load->helper('global');
        $this->load->helper('url');
        
        $this->categories = unserialize($this->cache->get('category_content'));
		//权限判断
		if(($this->input->get('catid') && $this->session->userdata('roleid') != 1 && $this->input->get('m')!='pass' && strpos($this->input->get('m'),'public_')===false)) {
			$catid = intval($this->input->get('catid'));
			$this->load->model('admin/model_category_priv');
			$action = $this->categories[$catid]['type']==0 ? $this->input->get('m') : 'init';
			$priv_datas = $this->model_category_priv->getCategoryPriv(array('catid'=>$catid,'action'=>$action));
			if(!$priv_datas) showMessage($this->lang->line('permission_to_operate'),'blank');
		}
    }
    
    /*
     * 類別列表
     */
    public function index () {
        $this->lang->load('content');
        $this->lang->load('message');
        //$show_header = $show_dialog  = $show_pc_hash = '';
		if($this->input->get('catid')) {
			$catid =  intval($this->input->get('catid'));
			$categories = $this->categories[$catid];
			$modelid = $categories['modelid'];
			$model_arr = getcache('model', 'commons');
			$MODEL = $model_arr[$modelid];
			unset($model_arr);
			$admin_username = param::get_cookie('admin_username');
			//查询当前的工作流
			$setting = string2array($category['setting']);
			$workflowid = $setting['workflowid'];
			$workflows = getcache('workflow_'.$this->siteid,'commons');
			$workflows = $workflows[$workflowid];
			$workflows_setting = string2array($workflows['setting']);

			//将有权限的级别放到新数组中
			$admin_privs = array();
			foreach($workflows_setting as $_k=>$_v) {
				if(empty($_v)) continue;
				foreach($_v as $_value) {
					if($_value==$admin_username) $admin_privs[$_k] = $_k;
				}
			}
			//工作流审核级别
			$workflow_steps = $workflows['steps'];
			$workflow_menu = '';
			$steps = isset($_GET['steps']) ? intval($_GET['steps']) : 0;
			//工作流权限判断
			if($_SESSION['roleid']!=1 && $steps && !in_array($steps,$admin_privs)) showmessage(L('permission_to_operate'));
			$this->db->set_model($modelid);
			if($this->db->table_name==$this->db->db_tablepre) showmessage(L('model_table_not_exists'));;
			$status = $steps ? $steps : 99;
			if(isset($_GET['reject'])) $status = 0;
			$where = 'catid='.$catid.' AND status='.$status;
			//搜索
			
			if(isset($_GET['start_time']) && $_GET['start_time']) {
				$start_time = strtotime($_GET['start_time']);
				$where .= " AND `inputtime` > '$start_time'";
			}
			if(isset($_GET['end_time']) && $_GET['end_time']) {
				$end_time = strtotime($_GET['end_time']);
				$where .= " AND `inputtime` < '$end_time'";
			}
			if($start_time>$end_time) showmessage(L('starttime_than_endtime'));
			if(isset($_GET['keyword']) && !empty($_GET['keyword'])) {
				$type_array = array('title','description','username');
				$searchtype = intval($_GET['searchtype']);
				if($searchtype < 3) {
					$searchtype = $type_array[$searchtype];
					$keyword = strip_tags(trim($_GET['keyword']));
					$where .= " AND `$searchtype` like '%$keyword%'";
				} elseif($searchtype==3) {
					$keyword = intval($_GET['keyword']);
					$where .= " AND `id`='$keyword'";
				}
			}
			if(isset($_GET['posids']) && !empty($_GET['posids'])) {
				$posids = $_GET['posids']==1 ? intval($_GET['posids']) : 0;
				$where .= " AND `posids` = '$posids'";
			}
			
			$datas = $this->db->listinfo($where,'id desc',$_GET['page']);
			$pages = $this->db->pages;
			$pc_hash = $_SESSION['pc_hash'];
			for($i=1;$i<=$workflow_steps;$i++) {
				if($_SESSION['roleid']!=1 && !in_array($i,$admin_privs)) continue;
				$current = $steps==$i ? 'class=on' : '';
				$r = $this->db->get_one(array('catid'=>$catid,'status'=>$i));
				$newimg = $r ? '<img src="'.IMG_PATH.'icon/new.png" style="padding-bottom:2px" onclick="window.location.href=\'?m=content&c=content&a=&menuid='.$_GET['menuid'].'&catid='.$catid.'&steps='.$i.'&pc_hash='.$pc_hash.'\'">' : '';
				$workflow_menu .= '<a href="?m=content&c=content&a=&menuid='.$_GET['menuid'].'&catid='.$catid.'&steps='.$i.'&pc_hash='.$pc_hash.'" '.$current.' ><em>'.L('workflow_'.$i).$newimg.'</em></a><span>|</span>';
			}
			if($workflow_menu) {
				$current = isset($_GET['reject']) ? 'class=on' : '';
				$workflow_menu .= '<a href="?m=content&c=content&a=&menuid='.$_GET['menuid'].'&catid='.$catid.'&pc_hash='.$pc_hash.'&reject=1" '.$current.' ><em>'.L('reject').'</em></a><span>|</span>';
			}
			//$ = 153fc6d28dda8ca94eaa3686c8eed857;获取模型的thumb字段配置信息
			$model_fields = getcache('model_field_'.$modelid, 'model');
			$setting = string2array($model_fields['thumb']['setting']);
			$args = '1,'.$setting['upload_allowext'].','.$setting['isselectimage'].','.$setting['images_width'].','.$setting['images_height'].','.$setting['watermark'];
			$authkey = upload_key($args);
			$template = $MODEL['admin_list_template'] ? $MODEL['admin_list_template'] : 'content_list';
			include $this->admin_tpl($template);
		} else {
              
			$this->load->view('content/index.php');
		}
	}
    
    
    /**
	 * 显示栏目菜单列表
	 */
	public function showCategories() {
        $this->load->library('tree');
		
		//$cfg = getcache('common','commons');
		//$ajax_show = intval($cfg['category_ajax']);
        $ajax_show = 0;
        
        $this->data['ajax_show'] =$ajax_show;
       
		$from = $this->input->get('from') && in_array($this->input->get('from'),array('block')) ? $this->input->get('from') : 'content';

		if($from=='content' && $this->session->userdata('roleid') != 1) {	
            $this->load->model('admin/model_category_priv');
			$priv_result = $this->model_category_priv->getCategoryPrivs_(array('action'=>'init','roleid'=>$this->session->userdata('roleid')));
			$priv_catids = array();
			foreach($priv_result as $_v) {
				$priv_catids[] = $_v['catid'];
			}
			if(empty($priv_catids)) return '';
		}
		$categories = array();
		if(!empty($this->categories)) {
			foreach($this->categories as $r) {
				if($r['type']==2 && $r['child']==0) continue;
				if($from=='content' && $this->session->userdata('roleid') != 1 && !in_array($r['catid'],$priv_catids)) {
					$arrchildid = explode(',',$r['arrchildid']);
					$array_intersect = array_intersect($priv_catids,$arrchildid);
					if(empty($array_intersect)) continue;
				}
				if($r['type']==1 || $from=='block') {
					if($r['type']==0) {
						$r['vs_show'] = "<a href='?m=block&c=block_admin&a=public_visualization&menuid=".$_GET['menuid']."&catid=".$r['catid']."&type=show' target='right'>[".L('content_page')."]</a>";
					} else {
						$r['vs_show'] ='';
					}
					$r['icon_type'] = 'file';
					$r['add_icon'] = '';
					$r['type'] = 'add';
				} else {
					$r['icon_type'] = $r['vs_show'] = '';
					$r['type'] = 'init';
					$r['add_icon'] = "<a target='right' href='?m=content&c=content&menuid=".$this->input->get('menuid')."&catid=".$r['catid']."' onclick=javascript:openwinx('?m=content&c=content&a=add&menuid=".$_GET['menuid']."&catid=".$r['catid']."&token=".$this->session->userdata('token')."','')><img src='views/default/image/add_content.gif' alt='".$this->lang->line('add')."'></a> ";
				}
				$categories[$r['catid']] = $r;
			}
		}
		if(!empty($categories)) {
			$this->tree->init($categories);
				switch($from) {
					case 'block':
						$strs = "<span class='\$icon_type'>\$add_icon<a href='?m=block&c=block_admin&a=public_visualization&menuid=".$_GET['menuid']."&catid=\$catid&type=list' target='right'>\$catname</a> \$vs_show</span>";
						$strs2 = "<img src='views/default/image/folder.gif'> <a href='?m=block&c=block_admin&a=public_visualization&menuid=".$_GET['menuid']."&catid=\$catid&type=category' target='right'>\$catname</a>";
					break;

					default:
						$strs = "<span class='\$icon_type'>\$add_icon<a href='?m=content&c=content&a=\$type&menuid=".$_GET['menuid']."&catid=\$catid' target='right' onclick='open_list(this)'>\$catname</a></span>";
						$strs2 = "<span class='folder'>\$catname</span>";
						break;
				}
			$categories = $this->tree->getTreeview(0,'category_tree',$strs,$strs2,$ajax_show);
		} else {
			$categories = $this->lang->line('please_add_category');
		}
        
        $this->data['categories'] = $categories;
        
        $this->load->view('content/category_tree',$this->data);
	}
    
    
  
}

?>
