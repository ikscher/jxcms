<?php
/*
 * function:站点模型类型
 * author:ikscher
 * date:2013-12-24
 */

class Model_type extends CI_Model {

    private $tbl_prefix;
    private $table;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
        $this->table="type";
    }
    
    /*
     * 分页返回所有模型类型
     */
    public function getModelTypes($where,$orderby='',$order='',$limit=''){
        $results=array();
        
        if(empty($orderby )) $orderby='typeid';
        if(empty($order))  $order='asc';
        if(empty($limit)) $limit=0;
        
        $perpage=$this->config->item('per_page');

        $sql="select `typeid`,`siteid`, `module`, `modelid` , `name` ,`parentid`, `typedir`,`url` , `template` , `listorder`,`description` from {$this->tbl_prefix}{$this->table} {$where} order by {$orderby} {$order} limit {$limit},{$perpage}";
        $query=$this->db->query($sql);
        $results = $query->result_array();
        
        return $results;
    }
    
    /*
     * 返回单条记录
     */
    public function getModelType($typeid){
        $result = array();
        $sql="select `typeid`,`siteid`, `module`, `modelid` , `name` ,`parentid`, `typedir`,`url` , `template` , `listorder`,`description` from {$this->tbl_prefix}{$this->table} where typeid=?";
        $query=$this->db->query($sql,$typeid);
        
        $result = $query->row_array();
        
        return $result;
        
    }
    
    /*
     * 返回模型总数
     */
    public function getModelTypeTotal($where){
        $sql="select typeid from {$this->tbl_prefix}{$this->table} {$where} ";
        $query=$this->db->query($sql);
        
        return $query->num_rows()?$query->num_rows():0;
    }
    
    /*
     * 刪除
     */
    public function deleteModelType($typeid){
        $sql = "delete from {$this->tbl_prefix}{$this->table} where typeid=?";
        return $this->db->query($sql,$typeid);
    }
    
    /*
     * 更新
     */
    public function updateModelType($sql,$typeid){
        $sql = "update {$this->tbl_prefix}{$this->table} set {$sql} where typeid=?";
        return $this->db->query($sql,$typeid);
    }
    
    
}
?>