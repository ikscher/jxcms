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
        $this->load->helper('global');
        
        $where = array();
        $order = $this->input->get('order');
        $by = $this->input->get('by');

        if ($order == 'asc') {
            $order = 'desc';
        } else {
            $order = 'asc';
        }
        
		if($this->input->post('search')) {
			$start_time = strtotime($this->input->post('start_time'));
            $end_time = strtotime($this->input->post('end_time'));
            $posids = $this->input->post('posids');
            $searchtype = $this->input->post('searchtype');
            $status = $this->input->post('status');
            $status = isset($status)?$status:1;
            $keyword = $this->input->post('keyword') ? trim($this->input->post('keyword')) : '';
            switch ($searchtype) {
                case 0:
                    if(isset($keyword) && $keyword!='') array_push($where,"title like '{$keyword}%'");
                    break;
                case 1:
                    if(isset($keyword) && $keyword!='') array_push($where,"keywords like '{$keyword}%'");
                    break;
                case 2:
                    if(isset($keyword) && $keyword!='') array_push($where,"username like '{$keyword}%'");
                    break;
                case 3:
                    if(isset($keyword)) array_push($where,"id = '{$keyword}'");
                    break;
                default:
                    if(isset($keyword)) array_push($where,"id ='{$keyword}'");
                    break;
            }
            
            if(isset($posids) && is_numeric($posids)) {
                array_push($where," posids={$posids}");
            }
            
            if(!empty($start_time)) array_push($where,"inputtime>={$start_time}");
            if(!empty($end_time)) array_push($where,"inputtime<={$end_time}");
            if(!empty($status)) array_push($where,"`a`.`status`='{$status}'"); 
            
           
		} else {
            $posids='all';
            $searchtype=0;
            $status=1;
            array_push($where ,"`a`.`status`=1");
        }
        
        $modelid = $this->input->get('modelid');
        if(!empty($modelid)){
            array_push($where,"modelid={$modelid}");
        }
        
        if(empty($where)){
            $where =" where `a`.`status`=1";
        }else{
            $where = " where ".implode(' and ',$where);
        }
       
       
            
        $table_ = array();

        $model = unserialize($this->cache->get('model'));
        if(empty($model)){ 
            $this->load->model('admin/model_model');
            $model = $this->model_model->getAllModels();
        }


        foreach($model as $k=>$v){
            array_push($table_,$v['tablename']);
        }
        $table = array_unique($table_);

        $this->load->model('content/model_content');
        $contents = $this->model_content->getAllContents($table,$where, $by, $order);
        
        $this->load->model('admin/model_model');
        $this->load->model('content/model_hits');
        foreach($contents as $k=>$v){
            $r=$this->model_model->getModel($v['modelid']);
            $contents[$k]['modelname'] = isset($r['name'])?$r['name']:'';
            $contents[$k]['hits'] = $this->model_hits->getHits(array('c-'.$v['modelid'].'-'.$v['id']));
        }

        $perpage = $this->config->item('per_page');
        $page = $this->input->get('per_page');
        $page = isset($page) && $page > 0 ? $page : 1;
        $limit = ($page - 1) * $perpage;

        $datas=array_slice($contents,$limit,$perpage);

        //分页
        $this->load->library('pagination');
        $config['base_url'] = '?d=content&c=content&m=index';
        $config['total_rows'] = count($contents);
        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();


        $sitelist = unserialize($this->cache->get('site'));
        $release_siteurl = $sitelist['url'];
        $path_len = -strlen($this->config->item('web_path'));
        $release_siteurl = substr($release_siteurl, 0, $path_len);
        $this->data['release_siteurl'] = $release_siteurl;

        $this->data['order'] = $order;
        $this->data['start_time'] = $this->input->post('start_time');
        $this->data['end_time'] = $this->input->post('end_time');
        $this->data['posids'] = $posids;
        $this->data['searchtype'] = $searchtype;
        $this->data['keyword'] = isset($keyword)?$keyword:'';
        $this->data['pagination'] = $pagination;
        $this->data['datas'] = $datas;
        $this->data['status']=$status;
        $this->data['category']=array();
        $this->data['workflow_menu'] =1;

        $this->load->view('content/index.php',$this->data);
    }
	
    /**
	 * 发布内容=》审核,删除，还原，退稿
	 */
	public function operate() {
        $this->lang->load('content');
        $admin_username = $this->cookie->AuthCode($this->input->cookie('adminusername'), 'DECODE');
        
        $steps = $this->input->post('steps');
        if(empty($steps)) $steps=1;
        $admin_privs=array(1,2,3);//1:待审核，2:已审核（已发布），3：归档（删除）
	    
	    if($this->session->userdata('roleid')!=1 && $steps && !in_array($steps,$admin_privs)) { 
            //showmessage(L('permission_to_operate'));
            //$this->data['permission_to_operate'] = $this->lang->line('permission_to_operate');
        }
        
        //更改内容状态
        if($this->input->post('type')=='pass'){
            $status = 2;
        }elseif($this->input->post('type')=='reject'){
			$status = 1;
        }elseif($this->input->post('type')=='delete'){
            $status = 3;
        }elseif($this->input->post('type')=='restore'){
		    $status = 1;
        }
        //审核通过，检查投稿奖励或扣除积分
        $this->load->model('content/model_content');
        $this->load->model('admin/model_member');
        
        $ids = $this->input->post('ids');
        if (isset($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $id_=array();
                $id_=explode(',',$id);
               
                $id = $id_[0];
                $modelid = $id_[1];
                $this->model_content->set($modelid);
             
                $content_info = $this->model_content->select('username',array($id));
                if(isset($content_info['username'])){
                    $memberinfo = $this->model_member->getMemberInfo(array('username'=>$content_info['username']), 'userid, username');
                
//                $flag = $catid.'_'.$id;
//                if($setting['presentpoint']>0) {
//                    pc_base::load_app_class('receipts','pay',0);
//                    receipts::point($setting['presentpoint'],$memberinfo['userid'], $memberinfo['username'], $flag,'selfincome',L('contribute_add_point'),$memberinfo['username']);
//                } else {
//                    pc_base::load_app_class('spend','pay',0);
//                    spend::point($setting['presentpoint'], L('contribute_del_point'), $memberinfo['userid'], $memberinfo['username'], '', '', $flag);
//                }
                }
                $this->model_content->updateStatus(array($id),$status);
                //更新到全站搜索
//                $inputinfo = '';
//                $inputinfo['system'] = $content_info;
//                $this->db->search_api($id,$inputinfo);
            }
        } 

//        if(isset($_GET['ajax_preview'])) {
//            $_POST['ids'] = $_GET['id'];
//        }
       

		//showmessage(L('operation_success'),HTTP_REFERER);
	}
    
    /*
     * 批量移动（从一个栏目move到另一个栏目）
     */
    public function move(){
        $ids = $this->input->post('ids');
        if (isset($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $id_=array();
                $id_=explode(',',$id);
               
                $id = $id_[0];
                $modelid = $id_[1];
                $this->model_content->set($modelid);
                $this->model_content->move(array($id),$status);
            }
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
					$r['add_icon'] = "<a target='right' href='?d=content&c=content&menuid=".$this->input->get('menuid')."&catid={$r['catid']}&modelid={$r['modelid']}&token=".$this->session->userdata('token')."'><img src='views/default/image/add_content.gif' alt='".$this->lang->line('add')."'></a> ";
				}
				$categories[$r['catid']] = $r;
			}
		}

		if(!empty($categories)) {
			$this->tree->init($categories);
				switch($from) {
					case 'block':
						$strs = "<span class='\$icon_type'>\$add_icon<a href='?m=block&c=block_admin&a=public_visualization&menuid=".$this->input->get('menuid')."&catid=\$catid&type=list' target='right'>\$catname</a> \$vs_show</span>";
						$strs2 = "<img src='views/default/image/folder.gif'> <a href='?m=block&c=block_admin&a=public_visualization&menuid=".$this->input->get('menuid')."&catid=\$catid&type=category' target='right'>\$catname</a>";
					break;

					default:
						$strs = "<span class='\$icon_type'>\$add_icon<a href='?d=content&c=content&m=index&menuid=".$this->input->get('menuid')."&modelid=\$modelid' target='right' >\$catname</a></span>";
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
