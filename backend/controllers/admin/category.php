<?php

/*
 * author:ikscher
 * date:2013-11-11
 * function:管理员信息
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Category extends CI_Controller {

    private $privs = array();
    private $categories = array();

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    /**
     * 管理栏目
     */
    public function index() {
        $this->load->library('tree');
        $this->lang->load('category');

        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        $models = unserialize($this->cache->get('model'));
        $sitelist = unserialize($this->cache->get('site'));
       
        
        $category_items = array();

        if (!empty($models)) {
            foreach ($models as $modelid => $model) {
                $category_items[$modelid] = unserialize($this->cache->get('category_items_' . $modelid));
            }
        }

        $this->tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $this->tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        //读取缓存
        $result = unserialize($this->cache->get('category_content'));


        $show_detail = count($result) < 500 ? 1 : 0;
        $parentid = $this->input->get('parentid') ? intval($this->input->get('parentid')) : 0;

        $categories = array();
        $html_root = $this->config->item('html_root');
        $types = array(0 => $this->lang->line('category_type_system'), 1 => $this->lang->line('category_type_page'), 2 => $this->lang->line('category_type_link'));
        if (!empty($result)) {
            foreach ($result as $r) {
                if (!empty($r['modelid'])) {
                    $r['modelname'] = $models[$r['modelid']]['name'];
                } else {
                    $r['modelname'] = '';
                }

                $r['str_manage'] = '';
                if (!$show_detail) {
                    if ($r['parentid'] != $parentid)
                        continue;
                    $r['parentid'] = 0;
                    $r['str_manage'] .= '<a href="?d=admin&c=category&m=init&parentid=' . $r['catid'] . '&type=' . $r['type'] . '&token=' . $this->session->userdata('token') . '">' . $this->lang->line('manage_sub_category') . '</a> | ';
                }
                $r['str_manage'] .= '<a href="?d=admin&c=category&m=add&parentid=' . $r['catid'] . '&type=' . $r['type'] . '&token=' . $this->session->userdata('token') . '">' . $this->lang->line('add_sub_category') . '</a> | ';

                $r['str_manage'] .= '<a href="?d=admin&c=category&m=edit&catid=' . $r['catid'] . '&type=' . $r['type'] . '&token=' . $this->session->userdata('token') . '">' . $this->lang->line('edit') . '</a> | <a  class="delete" data-id="' . $r['catid'] . '" href="javascript:void(0);">' . $this->lang->line('delete') . '</a> ';
                $r['typename'] = $types[$r['type']];
                $r['display_icon'] = $r['ismenu'] ? '' : ' <img src ="views/default/image/gear_disable.png" title="' . $this->lang->line('not_display_in_menu') . '">';
                if ($r['type'] || $r['child']) {
                    $r['items'] = '';
                } else {
                    if (!empty($category_items))
                        $r['items'] = $category_items[$r['modelid']][$r['catid']];
                }
                $r['help'] = '';
                $setting = string2array($r['setting']);
                if ($r['url']) {
                    if (preg_match('/^(http|https):\/\//', $r['url'])) {
                        $catdir = $r['catdir'];
                        $prefix = $r['sethtml'] ? '' : $html_root;

                        $catdir = $prefix . '/' . $r['parentdir'] . $catdir;

                        if ($r['type'] == 0 && isset($setting['ishtml']) && strpos($r['url'], '?') === false && substr_count($r['url'], '/') < 4)
                            $r['help'] = '<img src="view/default/image/help.png" title="' . $this->lang->line('tips_domain') . $r['url'] . '&#10;' . $this->lang->line('directory_binding') . '&#10;' . $catdir . '/">';
                    } else {
                        $r['url'] = substr($sitelist['domain'], 0, -1) . $r['url'];
                    }
                    $r['url'] = "<a href='$r[url]' target='_blank'>" . $this->lang->line('vistor') . "</a>";
                } else {
                    $r['url'] = "<a href='?d=admin&c=category&m=public_cache&menuid=43&module=admin'><font color='red'>" . $this->lang->line('update_backup') . "</font></a>";
                }
                $categories[$r['catid']] = $r;
            }
        }

        $str = "<tr>
					<td align='center'><input name='listorders[\$id]' type='text' size='3' value='\$listorder' class='input-text-c'></td>
					<td align='center'>\$id</td>
					<td >\$spacer\$catname\$display_icon</td>
					<td align='center'>\$typename</td>
					<td align='center'>\$modelname</td>
					<td align='center'>\$items</td>
					<td align='center'>\$url</td>
					<td align='center'>\$help</td>
					<td align='center' >\$str_manage</td>
				</tr>";
        
        $this->tree->init($categories);
        $categories = $this->tree->getTree(0, $str);
      
        $this->data['categories'] = $categories;

        $this->load->view('category/category_manage', $this->data);
    }
    
    /**
	 * 添加栏目
	 */
	public function add() {
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        $this->lang->load('category');
        $this->load->helper('string');
        $info = array();
        
		if($this->input->post('dosubmit')) {
            
            $info=$this->input->post('info');
			
         
			$info['module'] = 'content';
			$setting = $this->input->post('setting');

            //栏目生成静态配置
            if($setting['ishtml']) {
                $setting['category_ruleid'] = $this->input->post('category_html_ruleid');
            } else {
                $setting['category_ruleid'] = $this->input->post('category_php_ruleid');
                $info['url'] = '';
            }

			
			//内容生成静态配置
			if(isset($setting['content_ishtml'])) {
				$setting['show_ruleid'] = $this->input->post('show_html_ruleid');
			} else {
				$setting['show_ruleid'] = $this->input->post('show_php_ruleid');
			}
            
			if(isset($setting['repeatchargedays']) && $setting['repeatchargedays']<1) $setting['repeatchargedays'] = 1;
			$info['sethtml'] = $setting['create_to_html_root'];
			$info['setting'] = array2string($setting);
			
            $catname =$this->config->item('charset')  == 'gbk' ? $this->post->info('info[catname]') : iconv('utf-8','gbk',$this->input->post('info[catname]'));
            //$letters = gbk_to_pinyin($catname);
            //$this->input->post('info[letter]') = strtolower(implode('', $letters));
            $info['letter'] = $this->input->post("info['catdir']");

            $sql=$this->db->insert_string("{$this->db->dbprefix}category",$info);

            $this->db->query($sql);
            
            $catid = $this->db->insert_id();

            $this->updatePriv($catid, $this->input->post('priv_roleid'));
            $this->updatePriv($catid, $this->input->post('priv_groupid'),0);

			$this->updateCache();
            exit('yes');

		} else {
			//获取站点模板信息
			$this->load->helper('global');
            $this->load->library('form');
            $info = unserialize($this->cache->get('site'));

			$template_list = getTemplateList($info, 0);
			foreach ($template_list as $k=>$v) {
				$template_list[$v['dirname']] = $v['name'] ? $v['name'] : $v['dirname'];
				unset($template_list[$k]);
			}
            
            
	        
            $parentid = intval($this->input->get('parentid'));
            
            if(empty($parentid)) return;
            
            $this->load->model('admin/model_category');
            $r = $this->model_category->getCategory(" where catid = $parentid");
        
            if (!empty($r)) {
                //extract($r,EXTR_SKIP);
                foreach($r as $k=>$v){
                    $this->data[$k]=$v;
                }

                $this->data['setting'] = string2array($r['setting']);
                $this->data['r'] = $r;
            }
            
            //if($r) extract($r,EXTR_SKIP);
            //$setting = string2array($setting);
            
            
            $role_priv = '';
            $roles = unserialize($this->cache->get('role'));
            foreach ($roles as $roleid => $role) {
                $disabled = $roleid == 1 ? 'disabled' : '';

                $role_priv .="
                <tr>
                <td align=\"left\">{$role['rolename']}</td>
                <td ><input type=\"checkbox\" name=\"priv_roleid[]\" $disabled "  . " value=\"index,$roleid\" /></td>
                <td ><input type=\"checkbox\" name=\"priv_roleid[]\" $disabled "  . " value=\"add,$roleid\" /></td>
                <td ><input type=\"checkbox\" name=\"priv_roleid[]\" $disabled "  . " value=\"edit,$roleid\" /></td>
                <td><input type=\"checkbox\" name=\"priv_roleid[]\" $disabled " .  " value=\"delete,$roleid\" /></td>
                <td ><input type=\"checkbox\" name=\"priv_roleid[]\" $disabled "  . " value=\"listorder,$roleid\" /></td>
                <td ><input type=\"checkbox\" name=\"priv_roleid[]\" $disabled "  . " value=\"push,$roleid\" /></td>
                <td ><input type=\"checkbox\" name=\"priv_roleid[]\" $disabled "  . " value=\"move,$roleid\" /></td>
               </tr>";
            }
            
            
            $this->data['role_priv']=$role_priv;
            
            
            $group_priv='';
            $group_cache = unserialize($this->cache->get('grouplist'));
            if(empty($group_cache)){
                $this->load->model('admin/model_member_group');
                $group_cache = $this->model_member_group->getMemberGroup();
            }
            foreach ($group_cache as $_key => $_value) {
                if ($_value['groupid'] == 1) continue;
               
                $group_priv .="<tr>
                    <td align=\"left\">{$_value['name']}</td>
                    <td align=\"left\"><input type=\"checkbox\" name=\"priv_groupid[]\"  value=\"visit,{$_value['groupid']}\" ></td>
                    <td align=\"left\"><input type=\"checkbox\" name=\"priv_groupid[]\"   value=\"add,{$_value['groupid']}\" ></td>
                </tr>";
                                   
            }

            $this->data['group_priv'] = $group_priv;
			
            $this->data['template_list'] = $template_list;
            $this->data['parentid'] = $parentid;
			
			$this->load->library('form');
			
            
            $category_items = unserialize($this->cache->get('category_items_' . $r['modelid']));
            $disabled = $category_items[$r['catid']] ? 'disabled' : '';
            $models=array();
            $models = unserialize($this->cache->get('model'));
            $model_datas = array();
            if(empty($models)){
                $this->load->model('admin/model_model');
                $result_array = $this->model_model->getAllModels();
                foreach($result_array as $v){
                    $models[$v['modelid']] = $v;
                }
            }
            
            foreach ($models as $_k => $_v) {
                $model_datas[$_v['modelid']] = $_v['name'];
            }
            
            $this->data['disabled'] = $disabled;
            $this->data['model_datas']=$model_datas;

            $this->load->view('category/category_add',$this->data);
			
		}
	}
    
    /**
     * 修改栏目
     */
    public function edit() {
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        $this->lang->load('category');
        $this->load->helper('string');
        $info = array();
        
        if ($this->input->post('dosubmit')) {
            $info=$this->input->post('info');
            //pc_base::load_sys_func('iconv');
            
            $catid = 0;
            $catid = intval($this->input->post('catid'));
             
            $setting = $this->input->post('setting');
            //栏目生成静态配置
         
            if (isset($setting['ishtml'])) {
                $setting['category_ruleid'] = $this->input->post('category_html_ruleid');
            } else {
                $setting['category_ruleid'] = $this->input->post('category_php_ruleid');
                $info['url'] = '';
            }
            
            
           

            
            //内容生成静态配置
            if (isset($setting['content_ishtml'])) {
                $setting['show_ruleid'] = $this->input->post('show_html_ruleid');
            } else {
                $setting['show_ruleid'] = $this->input->post('show_php_ruleid');
            }
            if (isset($setting['repeatchargedays']) && $setting['repeatchargedays'] < 1)
                $setting['repeatchargedays'] = 1;
            
            $info['sethtml'] = isset($setting['create_to_html_root'])?$setting['create_to_html_root']:'';
            $info['setting']= array2string($setting);
            $info['module'] = 'content';
       
           
            $catname = $this->config->item('charset') == 'gbk' ? $this->input->post('info[catname]') : iconv('utf-8', 'gbk', $this->input->post('info[catname]'));
            //$letters = gbk_to_pinyin($catname);
           // $_POST['info']['letter'] = strtolower(implode('', $letters));
            $info['letter'] = $this->input->post("info['catdir']");
            
            //应用权限设置到子栏目
            if ($this->input->post('priv_child')) {
                $this->load->model('admin/model_category');
                $arrchildid = $this->model_category->getCategory(" where catid = $catid");
                if (!empty($arrchildid['arrchildid'])) {
                    $arrchildid_arr = explode(',', $arrchildid['arrchildid']);
                    if (!empty($arrchildid_arr)) {
                        foreach ($arrchildid_arr as $arr_v) {
                            $this->updatePriv($arr_v, $this->input->post('priv_groupid'), 0);
                        }
                    }
                }
            }

            
            //应用模板到所有子栏目
         
            if ($this->input->post('template_child')) {
                $this->load->model('admin/model_category');
                $this->categories =$categories = $this->model_category->getCategories( '*', " where  module ='content' order by listorder ASC, catid ASC");
                $idstr = $this->getArrChildId($catid);
     
                if (!empty($idstr)) {
                    $arr = $this->model_category->getCategories('*'," where catid in($idstr)");
              
                    if (!empty($arr)) {
                        foreach ($arr as $v) {
                            $new_setting = array2string(
                                    array_merge(string2array($v['setting']), array('category_template' => isset($info['setting']['category_template'])?$info['setting']['category_template']:'', 'list_template' => isset($info['setting']['list_template'])?$info['setting']['list_template']:'', 'show_template' => isset($info['setting']['show_template'])?$info['setting']['show_template']:'')
                                    )
                            );
                            
                            $this->model_category->updateCategory("setting =\"{$new_setting}\"" ," where catid={$v['catid']}");
                        }
                    }
                }
            }
            
            $sql=$this->db->update_string("{$this->db->dbprefix}category",$info,array('catid' => $catid));
         
            $this->db->query($sql);
            
            
            $this->updatePriv($catid, $this->input->post('priv_roleid'));
            $this->updatePriv($catid, $this->input->post('priv_groupid'), 0);
            $this->updateCache();
            
            exit('yes');
            
            //更新附件状态
//            if ($_POST['info']['image'] && pc_base::load_config('system', 'attachment_stat')) {
//                $this->attachment_db = pc_base::load_model('attachment_model');
//                $this->attachment_db->api_update($_POST['info']['image'], 'catid-' . $catid, 1);
//            }
            //showmessage(L('operation_success') . '<script type="text/javascript">window.top.art.dialog({id:"test"}).close();window.top.art.dialog({id:"test",content:\'<h2>' . L("operation_success") . '</h2><span style="fotn-size:16px;">' . L("edit_following_operation") . '</span><br /><ul style="fotn-size:14px;"><li><a href="?m=admin&c=category&a=public_cache&menuid=43&module=admin" target="right"  onclick="window.top.art.dialog({id:\\\'test\\\'}).close()">' . L("following_operation_1") . '</a></li></ul>\',width:"400",height:"200"});</script>', '?m=admin&c=category&a=init&module=admin&menuid=43');
        } else {
            //获取站点模板信息

            $this->load->helper('global');
            $this->load->library('form');
            $info = unserialize($this->cache->get('site'));

            $template_list = getTemplateList($info, 0);
            foreach ($template_list as $k => $v) {
                $template_list[$v['dirname']] = $v['name'] ? $v['name'] : $v['dirname'];
                unset($template_list[$k]);
            }
            $this->data['template_list'] = $template_list;

            //$show_validator = $catid = $r = '';
            $catid = intval($this->input->get('catid'));
            
            if(empty($catid)) return;

            $this->load->model('admin/model_category');
            $r = $this->model_category->getCategory(" where catid={$catid}");
            //if($r) extract($r);
            if (!empty($r)) {
                //extract($r,EXTR_SKIP);
                foreach($r as $k=>$v){
                    $this->data[$k]=$v;
                }
                $this->data['setting'] = string2array($r['setting']);
                $this->data['r'] = $r;
            }

            

            $this->data['catid'] = $catid;
            $this->load->model('admin/model_category_priv');
            $this->privs = $this->model_category_priv->getCategoryPrivs(array('catid'=>$catid));
            
            $type = intval($this->input->get('type'));
             
             
            $role_priv = '';
            $roles=array();
            $roles = unserialize($this->cache->get('role'));
            if(empty($roles)){
                $this->load->model('admin/model_role');
                $result_array = $this->model_role->getAllRoles();

                foreach($result_array as $v){
                    $roles[$v['roleid']]=$v;
                }
            }
            foreach ($roles as $roleid => $role) {
                $disabled = $roleid == 1 ? 'disabled' : '';
                
                if($type==0){
                    $role_priv .="
                    <tr>
                    <td align=\"left\">{$role['rolename']}</td>
                    <td ><input type=\"checkbox\" name=\"priv_roleid[]\" $disabled " . $this->checkCategoryPriv('index', $roleid) . " value=\"index,$roleid\" /></td>
                    <td ><input type=\"checkbox\" name=\"priv_roleid[]\" $disabled " . $this->checkCategoryPriv('add', $roleid) . " value=\"add,$roleid\" /></td>
                    <td ><input type=\"checkbox\" name=\"priv_roleid[]\" $disabled " . $this->checkCategoryPriv('edit', $roleid) . " value=\"edit,$roleid\" /></td>
                    <td><input type=\"checkbox\" name=\"priv_roleid[]\" $disabled " . $this->checkCategoryPriv('delete', $roleid) . " value=\"delete,$roleid\" /></td>
                    <td ><input type=\"checkbox\" name=\"priv_roleid[]\" $disabled " . $this->checkCategoryPriv('listorder', $roleid) . " value=\"listorder,$roleid\" /></td>
                    <td ><input type=\"checkbox\" name=\"priv_roleid[]\" $disabled " . $this->checkCategoryPriv('push', $roleid) . " value=\"push,$roleid\" /></td>
                    <td ><input type=\"checkbox\" name=\"priv_roleid[]\" $disabled " . $this->checkCategoryPriv('move', $roleid) . " value=\"move,$roleid\" /></td>
                   </tr>";
                }elseif ($type==1){
                    $role_priv .="<tr>
				      <td align=\"left\">{$role['rolename']}</td>
				      <td align=\"center\"><input type=\"checkbox\" name=\"priv_roleid[]\" $disabled ". $this->checkCategoryPriv('init',$roleid)." value=\"init,$roleid\" ></td>
			        </tr>";
                }
            }
            
            $this->data['role_priv']=$role_priv;
            
            
            $group_priv='';
            $group_cache = unserialize($this->cache->get('grouplist'));
            
            if(empty($group_cache)){
               $this->load->model('admin/model_member_group');
               $group_cache = $this->model_member_group->getMemberGroup();
            }
            
            if(!empty($group_cache)){
                foreach ($group_cache as $_key => $_value) {
                    if ($_value['groupid'] == 1) continue;
                    
                    if($type==0){
                        $group_priv .="<tr>
                            <td align=\"left\">{$_value['name']}</td>
                            <td align=\"left\"><input type=\"checkbox\" name=\"priv_groupid[]\" ". $this->checkCategoryPriv('visit', $_value['groupid'], 0) . " value=\"visit,{$_value['groupid']}\" ></td>
                            <td align=\"left\"><input type=\"checkbox\" name=\"priv_groupid[]\"  ".  $this->checkCategoryPriv('add', $_value['groupid'], 0)." value=\"add,{$_value['groupid']}\" ></td>
                        </tr>";
                    }elseif($type==1){
                        $group_priv .="<tr>
				          <td align=\"left\">{$_value['name']}</td>
				          <td align=\"center\"><input type=\"checkbox\" name=\"priv_groupid[]\" ". $this->checkCategoryPriv('visit',$_value['groupid'],0)."  value=\"visit,{$_value['groupid']}\" ></td>
			            </tr>";
                    }

                }
            }
            
            $this->data['catid'] = $catid;
            $this->data['group_priv'] = $group_priv;

           
            if ($type == 0) {
                $this->load->view('category/category_edit', $this->data);
            } elseif ($type == 1) {
                $this->load->view('category/category_page_edit',$this->data);
            } else {
                $this->load->view('category_link');
            }
        }
    }

    /**
     * 排序
     */
    public function listOrder() {
        $this->load->model('admin/model_category');
        $listorders = $this->input->post('listorders');
        //$this->output->set_content_type('application/json');
        //$this->output->set_output(json_encode($listorders));

        foreach ($listorders as $id => $listorder) {
            $this->model_category->updateCategory(" listorder={$listorder}", " where catid={$id}");
        }
        $this->updateCache();
    }

    /**
     * 删除栏目
     */
    public function delete() {
        $catid = $this->input->get('catid');
        $this->load->model('admin/model_category');
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));

        $categories = unserialize($this->cache->get('category_content'));
        $modelid = $categories[$catid]['modelid'];

        $items = $this->cache->get('category_items_' . $modelid);

        $this->deleteChildren($catid, $modelid);
        $this->model_category->deleteCategory(array('catid' => $catid));
        if ($modelid != 0) {
            $this->delete_($catid, $modelid);
        }
        $this->updateCache();
    }

    /**
     * 递归删除栏目
     * @param $catid 要删除的栏目id
     */
    private function deleteChildren($catid, $modelid) {
        $catid = intval($catid);
        if (empty($catid))
            return false;

        $this->load->model('admin/model_category');
        $r = $this->model_category->getCategory("where parentid={$catid}");
        if ($r) {
            $this->deleteChildren($r['catid']);
            $this->model_category->deleteCategory(array('catid' => $r['catid']));
            if ($modelid != 0) {
                $this->delete_($r['catid'], $modelid);
            }
        }
        return true;
    }

    /**
     * 删除栏目分类下的文章、图片、下载、视频
     * @param $catid 要删除的栏目id
     */
    private function delete_($catid, $modelid) {
        $this->load->model('content/model_content');
        $this->model_content->set($modelid);
        $results = $this->model_content->select('id', array('catid' => $catid));
        if (is_array($results) && !empty($results)) {
            foreach ($results as $key => $val) {
                $this->model_content->delete($val['id'], $catid);
            }
        }
    }
    
    /**
	 * 
	 * 获取子栏目ID列表
	 * @param $catid 栏目ID
	 */
	private function getArrChildId($catid) {
		$arrchildid = $catid;
		if(is_array($this->categories)) {
			foreach($this->categories as $id => $cat) {
				if($cat['parentid'] && $id != $catid && $cat['parentid']==$catid) {
					$arrchildid .= ','.$this->getArrChildId($id);
				}
			}
		}
		return $arrchildid;
	}

    /**
     * 更新缓存
     */
    public function updateCache() {
        $categories = array();
        $this->load->helper('string');
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        $models = unserialize($this->cache->get('model'));

        $this->load->model('admin/model_category');
        
        if(!empty($models) || $models==false){
            $this->load->model('admin/model_model');
            $result_array = $this->model_model->getAllModels();
        
            foreach($result_array as $v){
                $models[$v['modelid']] = $v;
            }
        }
        
        foreach ($models as $modelid => $model) {
            $datas = $this->model_category->getCategories('catid,type,items', " where modelid={$modelid}");
            $array = array();
            foreach ($datas as $r) {
                if ($r['type'] == 0)
                    $array[$r['catid']] = $r['items'];
            }
            $this->cache->save('category_items_' . $modelid, serialize($array));
        }
        $array = array();
//		$categories = $this->db->select('`module`=\'content\'','catid,siteid',20000,'listorder ASC');
//		foreach ($categorys as $r) {
//			$array[$r['catid']] = $r['siteid'];
//		}
//		setcache('category_content',$array,'commons');

        $this->categories = $categories = $this->model_category->getCategories('*', " where  module='content' order by listorder ASC");
        foreach ($categories as $r) {
            unset($r['module']);
            $setting = string2array($r['setting']);
            $r['create_to_html_root'] = isset($setting['create_to_html_root']) ? $setting['create_to_html_root'] : null;
            $r['ishtml'] = isset($setting['ishtml']) ? $setting['ishtml'] : '';
            $r['content_ishtml'] = isset($setting['content_ishtml']) ? $setting['content_ishtml'] : '';
            $r['category_ruleid'] = isset($setting['category_ruleid']) ? $setting['category_ruleid'] : '';
            $r['show_ruleid'] = isset($setting['show_ruleid']) ? $setting['show_ruleid'] : '';
            $r['workflowid'] = isset($setting['workflowid']) ? $setting['workflowid'] : '';
            $r['isdomain'] = '0';
            if (!preg_match('/^(http|https):\/\//', $r['url'])) {
                $r['url'] = $this->config->item('web_path') . $r['url'];
            } elseif ($r['ishtml']) {
                $r['isdomain'] = '1';
            }
            $categories[$r['catid']] = $r;
        }
        $this->cache->save('category_content', serialize($categories));
        return true;
    }

    /**
     * 检查栏目权限
     * @param $action 动作
     * @param $roleid 角色
     * @param $is_admin 是否为管理组
     */
    private function checkCategoryPriv($action, $roleid, $is_admin = 1) {
        $checked = '';
        foreach ($this->privs as $priv) {
            if ($priv['is_admin'] == $is_admin && $priv['roleid'] == $roleid && $priv['action'] == $action)
                
                $checked = 'checked';
        }
        return $checked;
    }

    /**
     * json方式加载模板
     */
    public function publicTplFileList() {
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        $this->load->helper('string');
        $style = $this->input->get('style');
        $style = isset($style) && trim($style) ? trim($style) : exit(0);
        $catid = $this->input->get('catid');
        $catid = isset($catid) && intval($catid) ? intval($catid) : 0;

        $batch_str = $this->input->get('batch_str') ? '[' . $catid . ']' : '';
        if ($catid) {
            $cat = unserialize($this->cache->get('category_content'));
            $cat = $cat[$catid];
            $cat['setting'] = string2array($cat['setting']);
        }
        $this->load->library('form');
        $str = $this->form->selectTemplate($style, 'content', (isset($cat['setting']['category_template']) && !empty($cat['setting']['category_template']) ? $cat['setting']['category_template'] : 'category'), 'name="setting' . $batch_str . '[category_template]"', 'category');

    
        if ($this->input->get('type') == 1) {
            $html = array('page_template' => $this->form->selectTemplate($style, 'content', (isset($cat['setting']['page_template']) && !empty($cat['setting']['page_template']) ? $cat['setting']['page_template'] : 'category'), 'name="setting' . $batch_str . '[page_template]"', 'page'));
        } else {
           
            $html = array('category_template' => $this->form->selectTemplate($style, 'content', (isset($cat['setting']['category_template']) && !empty($cat['setting']['category_template']) ? $cat['setting']['category_template'] : 'category'), 'name="setting' . $batch_str . '[category_template]"', 'category'),
                'list_template' => $this->form->selectTemplate($style, 'content', (isset($cat['setting']['list_template']) && !empty($cat['setting']['list_template']) ? $cat['setting']['list_template'] : 'list'), 'name="setting' . $batch_str . '[list_template]"', 'list'),
                'show_template' => $this->form->selectTemplate($style, 'content', (isset($cat['setting']['show_template']) && !empty($cat['setting']['show_template']) ? $cat['setting']['show_template'] : 'show'), 'name="setting' . $batch_str . '[show_template]"', 'show')
            );
        }
        

        if ($this->input->get('module')) {
            unset($html);
            if ($this->input->get('templates')) {
                $templates = explode('|', $this->input->get('templates'));
                if ($this->input->get('id'))
                    $id = explode('|', $this->input->get('id'));
                if (is_array($templates)) {
                    foreach ($templates as $k => $tem) {
                        $t = $tem . '_template';
                        if ($id[$k] == '')
                            $id[$k] = $tem;
                        $html[$t] = $this->form->selectTemplate($style, $this->input->get('module'), $id[$k], 'name="' . $this->input->get('name') . '[' . $t . ']" id="' . $t . '"', $tem);
                    }
                }
            }
        }
		if ($this->config->item('charset') == 'gbk') {
			$html = array_iconv($html, 'gbk', 'utf-8');
		}
        $this->output->set_content_type('application/json');
        $this->output ->set_output(json_encode($html));

        //echo json_encode($html);
    }
    
    
    /**
	 * json方式读取风格列表，推送部分调用
	 */
	public function publicChangeTpl() {
		$this->load->library('form');
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
		$models = unserialize($this->cache->get('model'));
		$modelid = intval($this->input->get('modelid'));
		if($modelid) {
			$style = $models[$modelid]['default_style'];
			$category_template = $models[$modelid]['category_template'];
			$list_template = $models[$modelid]['list_template'];
			$show_template = $models[$modelid]['show_template'];
			$html = array(
				'template_list'=> $style, 
				'category_template'=> $this->form->selectTemplate($style, 'content',$category_template,'name="setting[category_template]"','category'), 
				'list_template'=>$this->form->selectTemplate($style, 'content',$list_template,'name="setting[list_template]"','list'),
				'show_template'=>$this->form->selectTemplate($style, 'content',$show_template,'name="setting[show_template]"','show')
			);
			if ($this->config->item('charset') == 'gbk') {
				$html = array_iconv($html, 'gbk', 'utf-8');
			}
            
            $this->output->set_content_type('application/json');
            $this->output ->set_output(json_encode($html));
//			echo json_encode($html);
		}
	}
    
    /**
	 * 更新权限
	 * @param  $catid
	 * @param  $priv_datas
	 * @param  $is_admin
	 */
	public function updatePriv($catid,$priv_datas,$is_admin = 1) {
		$this->load->model('admin/model_category_priv');
		$this->model_category_priv->deletePriv(array('catid'=>$catid,'is_admin'=>$is_admin));
		if(is_array($priv_datas) && !empty($priv_datas)) {
			foreach ($priv_datas as $r) {
				$r = explode(',', $r);
				$action = $r[0];
				$roleid = $r[1];
				$this->model_category_priv->addPriv(array('catid'=>$catid,'roleid'=>$roleid,'is_admin'=>$is_admin,'action'=>$action));
			}
		}
	}

}

/* End of file category.php */
    /* Location: ./application/controllers/admin/category.php */

    