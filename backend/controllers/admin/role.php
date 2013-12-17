<?php

/*
 * author:ikscher
 * date:2013-11-13
 * function:角色管理
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Role extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    /**
     * 角色管理列表
     */
    public function index() {
        
        $this->lang->load('admin_role');
        $this->load->model('admin/model_role');


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
        

        $rolename = $this->input->post('rolename') ? trim($this->input->post('rolename')) : '';
        $roledesc = $this->input->post('roledesc') ? trim($this->input->post('roledesc')) : '';
        $status = $this->input->post('status') ? $this->input->post('status') : '';

        

        if (!empty($rolename))
            $where .=" And `rolename` like '{$rolename}%'";
        if (!empty($roledesc))
            $where .=" And `description` like '{$roledesc}%'";
        if (isset($status) && $status != '')
            $where .=" And `disabled` = {$status}";
        $roles = $this->model_role->getRoles($where, $by, $order, $limit);
        
        //分页
        $this->load->library('pagination');
        $config['base_url'] = '?d=admin&c=role&m=index';
        $config['total_rows'] = $this->model_role->getRolesTotal($where);
        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();
        
        
        $this->data['order'] = $order;
        $this->data['roles'] = $roles;
        $this->data['rolename'] = $rolename;
        $this->data['roledesc'] = $roledesc;
        $this->data['status'] = $status;
        $this->data['pagination'] = $pagination;

        $this->load->view('admin/role_list', $this->data);
    }

    /**
     * 添加角色
     */
    public function add() {
        $this->lang->load('admin_role');
        $this->load->model('admin/model_role');

        $sbt = $this->input->post('dosubmit');
        if (!empty($sbt)) {

            $info = $this->input->post('info');

            $bl = $this->model_role->add($info);

            if ($bl)
                exit('yes');
            else
                exit('no');
        } else {
            $this->load->view('admin/role_add');
        }
    }

    /*
     * 删除角色
     */

    public function delete() {
        $this->load->model('admin/model_role');
        $roleid = $this->input->get('roleid');
        $bl = $this->model_role->delete($roleid);
        switch ($bl){
            case 'exist':
                exit('exist');
            case 'yes':
                exit('yes');
            case 'no':
                exit('no');
        }
         
    }

    /*
     * 修改角色
     */

    public function edit() {
        $this->lang->load('admin_role');
        $this->load->model('admin/model_role');

        $sbt = $this->input->post('dosubmit');
        if (!empty($sbt)) {
            $roleid = $this->input->post('roleid');
            $_info = $this->input->post('_info');
            $info = $this->input->post('info');

            $comma = '';
            $str = '';
            foreach ($_info as $k => $v) {
                $str .=$comma;
                $str .="`{$k}`='{$v}'";
                $comma = ',';
            }

            foreach ($info as $k => $v) {
                $str .=$comma;
                $str .="`{$k}`='{$v}'";
                $comma = ',';
            }

            $bl = $this->model_role->edit($str, $roleid);


            if ($bl)
                exit('yes');
            else
                exit('no');
        } else {
            $roleid = $this->input->get('roleid');
            $where = " where `roleid`='{$roleid}'";
            $info = $this->model_role->getRoles($where);
            $data = $info[0];
            $this->data['info'] = $data;
            $this->load->view('admin/role_edit', $this->data);
        }
    }

    /*
     * 是否存在同名的角色
     */

    public function exists() {
        $this->load->model('admin/model_role');


        $rolename = $this->input->post('rolename');

        $roleid = $this->model_role->isExistsRole($rolename);

        if ($roleid)
            exit('yes');
        else
            exit('no');
    }
    
    
    /**
	 * 更新角色状态
	 */
	public function change_status(){
        $this->load->model('admin/model_role');
		$roleid = intval($this->input->get('roleid'));
		$disabled = intval($this->input->get('disabled'));
        
        $bl=$this->model_role->change_status(array('disabled'=>$disabled)," roleid={$roleid}");
		
        if(!$bl) return false;
        //更新缓存
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        $role_serial=$this->cache->get('role');
        $roles=  unserialize($role_serial);
   
        $items = array();
        foreach($roles as $k=>$v){
            if($k==$roleid){
                $v['disabled']=$disabled;
            }
            $items[$k]=$v;
        }
        
        $result_serial = serialize($items);

        $this->cache->save('role', $result_serial);
        
        exit('yes');
		
	}
    
    /**
	 * 成员管理
	 */
	public function manage_member() {
        $this->lang->load('admin_role');
        $this->lang->load('admin_manage');
		$this->load->model('admin/model_role');
		$roleid = $this->input->get('roleid');
       
        
        //加载角色
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        $role_serial=$this->cache->get('role');
        $roles=  unserialize($role_serial);
    
        //分页显示
        $perpage = $this->config->item('per_page');
        $page = $this->input->get('per_page');
        $page = isset($page) && $page > 0 ? $page : 1;
        $limit = ($page - 1) * $perpage;

        $con=array($roleid,$limit,$perpage);
         
		$infos = $this->model_role->getRoleMembers($con);
 
      
        $this->load->library('pagination');
        $config['base_url'] = '?d=admin&c=role&m=manage_member&roleid='.$roleid;
        $config['total_rows'] = $this->model_role->getRoleMembersTotal($roleid);
        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();
        
        //输出变量
        $this->data['infos'] = $infos;
        $this->data['roles'] = $roles;
        $this->data['pagination'] = $pagination;
        $this->data['roleid'] = $roleid;
        
		
        $this->load->view('admin/admin_list',$this->data);
	}
    
    /*
     * 权限设置
     */
    public function setPriv(){
        $this->lang->load('admin_role');
        $this->lang->load('admin_manage');
        $this->lang->load('system');
        $this->load->model('admin/model_menu');
        $this->load->model('admin/model_privilege');
        $this->load->library('roleop');
     
        $sbt = $this->input->post('dosubmit');
       
        if(!empty($sbt)){
			if (is_array($this->input->post('menuid')) && count($this->input->post('menuid')) > 0) {
			   
				$this->model_privilege->delete(array('roleid'=>$this->input->post('roleid')));
				$menuinfo = $this->model_menu->getMenus();
                
				foreach ($menuinfo as $_v) $menu_info[$_v['id']] = $_v;
                
                $_field_ = array('roleid','d','c','m','data');
				foreach($this->input->post('menuid') as $menuid){
					$info = array();
					$info = $this->roleop->getMenuInfo(intval($menuid),$menu_info);
					$info['roleid'] = $this->input->post('roleid');
                    foreach($info as $_k=>$_v){
                        if(!in_array($_k,$_field_)){
                            unset($info[$_k]);
                        }
                    }
					$sql = $this->db->insert_string("{$this->db->dbprefix}admin_role_priv",$info);
                    $this->db->query($sql);
				}
                exit('yes');
			} else {
				$this->model_privilege->delete(array('roleid'=>$this->input->post('roleid')));
                exit('no');
			}
			
            //echo "<script type=\"text/javascript\">location.href=\"?d=admin&c=role&m=setPriv&roleid=".$this->input->post('roleid')."\"</script>";

		} else{

            $this->load->library('tree');

            $this->tree->icon = array('│ ','├─ ','└─ ');
            $this->tree->nbsp = '&nbsp;&nbsp;&nbsp;';

            $result = $this->model_menu->getMenus();

            $priv_data = $this->model_privilege->getRolePrivileges(); //获取权限表数据

            $modules = 'admin,announce,vote,system';
            foreach ($result as $n=>$t) {
                //$result[$n]['cname'] = L($t['name'],'',$modules);

                $result[$n]['cname'] = $this->lang->line($t['name'])."[{$t['name']}]";
                $result[$n]['checked'] = ($this->roleop->isChecked($t,$this->input->get('roleid'), $priv_data))? ' checked' : '';
                $result[$n]['level'] = $this->roleop->getLevel($t['id'],$result);
                $result[$n]['parentid_node'] = ($t['parentid'])? ' class="child-of-node-'.$t['parentid'].'"' : '';
            }
            $str  = "<tr id='node-\$id' \$parentid_node>
                        <td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$cname</td>
                    </tr>";

            $this->tree->init($result);
            $categories = $this->tree->getTree(0, $str);

            $this->data['categories'] = $categories;
            $this->data['roleid'] = $this->input->get('roleid');
            $this->data['rolename'] = $this->input->get('rolename');

            $this->load->view('admin/role_priv',$this->data);
        }
    }

}

?>
