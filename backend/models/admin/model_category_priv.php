<?php
/*
 * function:站点信息
 * author:ikscher
 * date:2013-12-13
 */

class Model_category_priv extends CI_Model {

    private $tbl_prefix;
    private $table;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
        $this->table='category_priv';
    }
    
    /*
     * 列出对于栏目操作权限
     */
    public function getCategoryPrivs($catid){
        $results = array();
        $sql = "select catid,roleid,is_admin,action  from {$this->tbl_prefix}{$this->table} where catid=?";
        
        $query=$this->db->query($sql,$catid);
        $results = $query->result_array();
        
        return $results;
    }
    
    /*
     * 获取单条操作
     */
    public function getCategoryPriv($data){
        $result = array();
        $sql = "select cp.catid,cp.roleid,cp.is_admin,cp.action,c.catname  from {$this->tbl_prefix}{$this->table} cp left join {$this->tbl_prefix}category c on cp.catid=c.catid where cp.roleid=? and cp.catid=?  and cp.action=?";
        
        $query=$this->db->query($sql,$data);
        $result = $query->row_array();
        
        return $result;
    }
    
    
    /*
     * 获取栏目的权限操作
     */
    public function getCategoryPrivs_($data){
        $results = array();
        $sql = "select catid,roleid,is_admin,action  from {$this->tbl_prefix}{$this->table} where action=? and is_admin=1 and roleid=?";
        
        $query=$this->db->query($sql,$data);
        $results = $query->result_array();
        
        return $results;
    }
    
    
    /*
     * 删除权限
     */
    public function deletePriv($data){
        $sql = "delete  from {$this->tbl_prefix}{$this->table} where catid=? and is_admin=?";
        return $this->db->query($sql,$data);

    }
    
    /*
     * 添加权限
     * $data :键值对
     */
   public function addPriv($data){
       $sql = $this->db->insert_string("{$this->tbl_prefix}{$this->table}",$data);

       return $this->db->query($sql);
   }
}
?>
