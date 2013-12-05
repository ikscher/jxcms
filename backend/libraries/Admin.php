<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
/*
 * author:ikol
 * date:2013-11-02
 */
class Admin {
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
	 * 按父ID查找菜单子项
	 * @param integer $parentid   父菜单ID  
	 * @param integer $with_self  是否包括他自己
	 */
	public function admin_menu($parentid, $with_child = TRUE) {
		$parentid = intval($parentid);

		$where = array($parentid,'1');
	    
        $sql="select * from {$this->tbl_prefix}menu where parentid=? and display=? order by listorder ASC";
        
		$query =$this->db->query($sql,$where);
        $result_array=$query->result_array();
        
       
        $result=array();
        if($with_child) {
            foreach($result_array as $v){
               $sql="select * from {$this->tbl_prefix}menu where parentid={$v['id']}";
               $query_=$this->db->query($sql);
               $v['child']=$query_->result_array();
               $result[]=$v;
            }
        }
       
		//权限检查
        $roleid=$this->session->userdata('roleid');
 
		if( $roleid== 1) return $result; //administrator
       
        
		$array = array();
		foreach($result as $v) {
			$action = $v['m'];
			if(preg_match('/^public_/',$action)) {
				$array[] = $v;
			} else {
				if(preg_match('/^ajax_([a-z]+)_/',$action,$_match)) $action = $_match[1];
                $where=array('d'=>$v['d'], 'c'=>$v['c'], 'm'=>$v['m'], 'roleid'=>$roleid);
                $sql="select * from {$this->tbl_prefix}admin_role_priv where d=? and c=? and m=? and roleid=? limit 1 ";
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
 
        $sql="select id,name,parentid from {$this->tbl_prefix}menu where id='{$id}'";

		$query = $this->db->query($sql);
        
        $r=$query->row_array();
       
		$str = '';
        $delimiter='>>';
		if(!empty($r['parentid'])) {
			$str = $this->current_pos($r['parentid']);
            $str .= $delimiter;
		}
		return $str.$this->lang->line($r['name']);
	}
}

/* End of file Admin.php */
