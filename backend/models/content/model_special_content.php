<?php
/*
 * function:推荐位置
 * author:ikscher
 * date:2014-1-16
 */

class Model_special_content extends CI_Model {

    private $tbl_prefix;
    private $table;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
        $this->table="special_content";
    }
    
    /*
     * 分页返回所有记录
     */
    public function get($where,$orderby='',$order='',$limit=''){
        $results=array();
        
        if(empty($orderby )) $orderby='posid';
        if(empty($order))  $order='asc';
        if(empty($limit)) $limit=0;
        
        $perpage=$this->config->item('per_page');

        $sql="select * from {$this->tbl_prefix}{$this->table} {$where} order by {$orderby} {$order} limit {$limit},{$perpage}";
        $query=$this->db->query($sql);
        $results = $query->result_array();
        
        return $results;
    }
    
    
    /*
     * 返回所有记录
     */
    public function getAll($where='',$orderby='',$order='',$limit=''){
        $results=array();
        
        if(empty($orderby )) $orderby='id';
        if(empty($order))  $order='asc';
        if(empty($limit)) $limit=10000;
  
        $sql="select * from {$this->tbl_prefix}{$this->table} {$where} order by {$orderby} {$order} limit {$limit}";
        $query=$this->db->query($sql);
        $results = $query->result_array();
        
        return $results;
    }
    
    /*
     * 返回单条记录
     */
    public function get_($data){
        $result = array();
        $sql="select * from {$this->tbl_prefix}{$this->table} where title=? and specialid=?";
        $query=$this->db->query($sql,$data);
        
        $result = $query->row_array();
        
        return $result;
        
    }
    
    /*
     * 返回模型总数
     */
    public function getTotal($where){
        $sql="select id from {$this->tbl_prefix}{$this->table} {$where} ";
        $query=$this->db->query($sql);
        
        return $query->num_rows()?$query->num_rows():0;
    }
    
    /*
     * 刪除
     */
    public function delete($id){
        $sql = "delete from {$this->tbl_prefix}{$this->table} where id=?";
        return $this->db->query($sql,$id);
    }
    
    /*
     * 更新
     */
    public function update($sql,$id){
        $sql = "update {$this->tbl_prefix}{$this->table} set {$sql} where id=?";
        return $this->db->query($sql,$id);
    }
    
    
}
?>