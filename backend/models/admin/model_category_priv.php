<?php
/*
 * function:站点信息
 * author:ikscher
 * date:2013-12-13
 */

class Model_category_priv extends CI_Model {

    private $tbl_prefix;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
    }
    
    /*
     * 列出栏目
     */
    public function getCategoryPrivs($catid){
        $results = array();
        $sql = "select catid,roleid,is_admin,action  from {$this->tbl_prefix}category_priv where catid=?";
        
        $query=$this->db->query($sql,$catid);
        $results = $query->result_array();
        
        return $results;
    }
    
    /*
     * 删除权限
     */
    public function deletePriv($data){
        $sql = "delete  from {$this->tbl_prefix}category_priv where catid=? and is_admin=?";
        return $this->db->query($sql,$data);

    }
    
    /*
     * 添加权限
     * $data :键值对
     */
   public function addPriv($data){
       $sql = $this->db->insert_string("{$this->tbl_prefix}category_priv",$data);

       return $this->db->query($sql);
   }
}
?>
