<?php
/*
 * function:推荐位置
 * author:ikscher
 * date:2014-1-16
 */

class Model_position extends CI_Model {

    private $tbl_prefix;
    private $table;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
        $this->table="position";
    }
    
    /*
     * 分页返回所有记录
     */
    public function getPositions($where,$orderby='',$order='',$limit=''){
        $results=array();
        
        if(empty($orderby )) $orderby='posid';
        if(empty($order))  $order='asc';
        if(empty($limit)) $limit=0;
        
        $perpage=$this->config->item('per_page');

        $sql="select posid,modelid,catid,name,maxnum,extension,listorder,thumb from {$this->tbl_prefix}{$this->table} {$where} order by {$orderby} {$order} limit {$limit},{$perpage}";
        $query=$this->db->query($sql);
        $results = $query->result_array();
        
        return $results;
    }
    
    
    /*
     * 返回所有记录
     */
    public function getAllPositions($where='',$orderby='',$order='',$limit=''){
        $results=array();
        
        if(empty($orderby )) $orderby='posid';
        if(empty($order))  $order='asc';
        if(empty($limit)) $limit=1000;
  
        $sql="select posid,modelid,catid,name,maxnum,extention,listorder,thumb from {$this->tbl_prefix}{$this->table} {$where} order by {$orderby} {$order} limit {$limit}";
        $query=$this->db->query($sql);
        $results = $query->result_array();
        
        return $results;
    }
    
    /*
     * 返回单条记录
     */
    public function getPosition($posid){
        $result = array();
        $sql="select posid,modelid,catid,name,maxnum,extention,listorder,thumb from {$this->tbl_prefix}{$this->table} where posid=?";
        $query=$this->db->query($sql,$posid);
        
        $result = $query->row_array();
        
        return $result;
        
    }
    
    /*
     * 返回模型总数
     */
    public function getPositionTotal($where){
        $sql="select id from {$this->tbl_prefix}{$this->table} {$where} ";
        $query=$this->db->query($sql);
        
        return $query->num_rows()?$query->num_rows():0;
    }
    
    /*
     * 刪除
     */
    public function deletePosition($posid){
        $sql = "delete from {$this->tbl_prefix}{$this->table} where posid=?";
        return $this->db->query($sql,$posid);
    }
    
    /*
     * 更新
     */
    public function updatePosition($sql,$posid){
        $sql = "update {$this->tbl_prefix}{$this->table} set {$sql} where posid=?";
        return $this->db->query($sql,$posid);
    }
    
    
}
?>