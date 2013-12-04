<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
/*
 * author:ikol
 * date:2013-12-04
 */
class RoleOp {
    private $db;
    private $session;
    private $lang;
    private $tbl_prefix;

    public function __construct() {
        $CI =& get_instance();
        $this->db = &$CI->db;
        $this->session = &$CI->session;
        $this->lang = &$CI->lang;
        $this->tbl_prefix = $this->db->dbprefix;

    }
    
    /**
	 * 获取角色中文名称
	 * @param int $roleid 角色ID
	 */
	public function getRoleName($roleid) {
		$roleid = intval($roleid);
		
        $where = array('roleid'=>$roleid);
        $sql = "select `roleid`,`rolename` from {$this->tbl_prefix}admin_role where roleid=?";
		$query = $this->db->query($sql,$where);
		return $query->result_array();
	}
		
	/**
	 * 检查角色名称重复
	 * @param $name 角色组名称
	 */
	public function checkName($name) {
        $sql = "select roleid from {$this->tbl_prefix}admin_role where rolename=?";
        
        $where = array('rolename'=>$name);
		$query = $this->db->query($sql,$where);
        $result = $query->row();
		if($result->roleid){
			return true;
		}
		return false;
	}
	
	/**
	 * 获取菜单表信息
	 * @param int $menuid 菜单ID
	 * @param int $menu_info 菜单数据
	 */
	public function getMenuInfo($menuid,$menu_info) {
		$menuid = intval($menuid);
		unset($menu_info[$menuid][id]);
		return $menu_info[$menuid];
	}
	
	/**
	 *  检查指定菜单是否有权限
	 * @param array $data menu表中数组
	 * @param int $roleid 需要检查的角色ID
	 */
	public function isChecked($data,$roleid,$priv_data) {
		$priv_arr = array('d','c','m','data');
		if($data['d'] == '') return false;
		foreach($data as $key=>$value){
			if(!in_array($key,$priv_arr)) unset($data[$key]);
		}
		$data['roleid'] = $roleid;
		//$data['siteid'] = $siteid;
		$info = in_array($data, $priv_data);
		if($info){
			return true;
		} else {
			return false;
		}
		
	}
	/**
	 * 是否为设置状态
	 */
	public function isSetting($roleid) {
		//$siteid = intval($siteid);
		$roleid = intval($roleid);
        $sql = "select roleid,d,c,m,data from {$this->tbl_prefix}admin_role_priv  where  `roleid` = '{$roleid}' AND `d` != ''";
	
		return  $this->db->simple_query($sql);
		
	}
	/**
	 * 获取菜单深度
	 * @param $id
	 * @param $array
	 * @param $i
	 */
	public function getLevel($id,$array=array(),$i=0) {
		foreach($array as $n=>$value){
			if($value['id'] == $id)
			{
				if($value['parentid']== '0') return $i;
				$i++;
				return $this->getLevel($value['parentid'],$array,$i);
			}
		}
	}
   
}
/* End of file Role.php */
