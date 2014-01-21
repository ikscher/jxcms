<?php
/*
 * function:field
 * author:ikscher
 * date:2014-1-16
 */

class Model_field extends CI_Model {

    private $tbl_prefix;
    private $table;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
        $this->table="model_field";
    }
    
    /*
     * 分页返回所有记录
     */
    public function getFields($where,$orderby='',$order='',$limit=''){
        $results=array();
        
        if(empty($orderby )) $orderby='fieldid';
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
    public function getAllFields($where,$orderby='',$order='',$limit=''){
        $results=array();
        
        if(empty($orderby )) $orderby='fieldid';
        if(empty($order))  $order='asc';
        if(empty($limit)) $limit=1000;
  
        $sql="select * from {$this->tbl_prefix}{$this->table} {$where} order by {$orderby} {$order} limit {$limit}";
        $query=$this->db->query($sql);
        $results = $query->result_array();
        
        return $results;
    }
    
    /*
     * 返回类型指定的记录
     */
    public function getFieldByModel($data){
        $results = array();
        $sql="select * from {$this->tbl_prefix}{$this->table} where modelid=? and disabled=? order by listorder asc";
        $query=$this->db->query($sql,$data);
        
        $results = $query->result_array();
        
        return $results;
        
    }
    
    /*
     * 返回模型总数
     */
    public function getFieldTotal($where){
        $sql="select fieldid from {$this->tbl_prefix}{$this->table} {$where} ";
        $query=$this->db->query($sql);
        
        return $query->num_rows()?$query->num_rows():0;
    }
    
    /*
     * 刪除
     */
    public function deletePosition($fieldid){
        $sql = "delete from {$this->tbl_prefix}{$this->table} where fieldid=?";
        return $this->db->query($sql,$fieldid);
    }
    
    /*
     * 更新
     */
    public function updatePosition($sql,$fieldid){
        $sql = "update {$this->tbl_prefix}{$this->table} set {$sql} where fieldid=?";
        return $this->db->query($sql,$fieldid);
    }
    
    
}
?>