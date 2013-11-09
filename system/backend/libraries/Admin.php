<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
/*
 * author:ikol
 * date:2013-11-02
 */
class Admin {
    private $db;
    private $session;
    private $lang;

    public function __construct() {
        $CI =& get_instance();
        $this->db = &$CI->db;
        $this->session = &$CI->session;
        $this->lang = &$CI->lang;

    }
    /**
	 * 按父ID查找菜单子项
	 * @param integer $parentid   父菜单ID  
	 * @param integer $with_self  是否包括子菜单
	 */
	public function admin_menu($parentid, $child = TRUE) {
		$parentid = intval($parentid);

		$where = array($parentid,'1');
	    $tbl_pre=$this->db->dbprefix;
        $sql="select * from {$tbl_pre}menu where parentid=? and display=? order by listorder ASC";
		$query =$this->db->query($sql,$where);
        $result_array=$query->result_array();
        
        $result=array();
        if($child) {
            foreach($result_array as $v){
               $sql="select * from {$tbl_pre}menu where parentid={$v['id']}";
               $query_=$this->db->query($sql);
               $v['child']=$query_->result_array();
               $result[]=$v;
            }
        }

        /*
		if($with_self) {
            $sql="select * from {$tbl_pre}admin where id={$parentid} limit 1";
            $query_=$this->db->query($sql);
			$result_[] = $query_->row_array();
			$result = array_merge($result_,$result);
		}
         */
       
		//权限检查
        $roleid=$this->session->userdata('roleid');
 
		if( $roleid== 1) return $result;
       
        
		$array = array();
		foreach($result as $v) {
			$action = $v['m'];
			if(preg_match('/^public_/',$action)) {
				$array[] = $v;
			} else {
				if(preg_match('/^ajax_([a-z]+)_/',$action,$_match)) $action = $_match[1];
                $where=array('d'=>$v['d'], 'c'=>$v['c'], 'm'=>$v['m'], 'roleid'=>$roleid);
                $sql="select * from {$tbl_pre}admin_role_priv where d=? and c=? and m=? and roleid=? limit 1 ";
                $q=$this->db->query($sql,$where);
				$r = $q->row();
				if($r) $array[] = $v;
			}
		}
       
		return $array;
	}
    
    /**
	 * 当前位置
	 * 
	 * @param $id 菜单id
	 */
	public  function current_pos($id) {
        $this->lang->load('system');
		$tbl_pre = $this->db->dbprefix;
        
        $result=array();
        
        $sql="select id,name,parentid from {$tbl_pre}menu where `id`={$id}";
        $query = $this->db->query($sql);
        
        $result = $query->row_array();
		$str = '';
        
        $delimiter=">>";
		if(!empty($result['parentid'])) {
			$str = $this->current_pos($result['parentid']);
             $str .= $delimiter;
		}
		return $str.$this->lang->line($result['name']);
	}
}

/* End of file Admin.php */
