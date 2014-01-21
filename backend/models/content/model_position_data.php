<?php
/*
 * function:推荐位置
 * author:ikscher
 * date:2014-1-16
 */

class Model_position_data extends CI_Model {

    private $tbl_prefix;
    private $table;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
        $this->table="position_data";
    }
    
    /*
     * 分页返回所有记录
     */
    public function getPositions($where,$orderby='',$order='',$limit=''){
        $results=array();
        
        if(empty($orderby )) $orderby='id';
        if(empty($order))  $order='asc';
        if(empty($limit)) $limit=0;
        
        $perpage=$this->config->item('per_page');

        $sql="select catid,posid,module,modelid,thumb,data,listorder,expiration,extension,synedit from {$this->tbl_prefix}{$this->table} {$where} order by {$orderby} {$order} limit {$limit},{$perpage}";
        $query=$this->db->query($sql);
        $results = $query->result_array();
        
        return $results;
    }
    
    
    /*
     * 返回所有记录
     */
    public function getAllPositions($where='',$orderby='',$order='',$limit=''){
        $results=array();
        
        if(empty($orderby )) $orderby='id';
        if(empty($order))  $order='asc';
        if(empty($limit)) $limit=1000;
  
        $sql="select pd.id,pd.catid,pd.posid,pd.module,pd.modelid,pd.thumb,pd.data,pd.listorder,pd.expiration,pd.extention,pd.synedit,p.name from {$this->tbl_prefix}{$this->table} pd left join {$this->tbl_prefix}position p on pd.posid=p.posid {$where} order by {$orderby} {$order} limit {$limit}";
        $query=$this->db->query($sql);
        $results = $query->result_array();
        
        return $results;
    }
    
    /*
     * 返回单条记录
     */
    public function getPosition($data){
        $result = array();
        $sql="select catid,posid,module,modelid,thumb,data,listorder,expiration,extention,synedit from {$this->tbl_prefix}{$this->table} where id=? and posid=? and catid=?";
        $query=$this->db->query($sql,$data);
        
        $result = $query->row_array();
        
        return $result;
        
    }
    
    /*
     * 返回一条记录
     */
    public  function getPosition_($data,$maxnum){
        $result = array();
        $sql="select id,listorder from {$this->tbl_prefix}{$this->table}  where   catid=? and posid=?  order by listorder desc,id desc limit {$maxnum},1";
        $query = $this->db->query($sql,$data);
        $result = $query->row_array();
        
        return $result;       
    }
    
     /*
     * 返回多条记录
     */
    public  function getPosition__($data){
        $results = array();
        $sql="select id,listorder from {$this->tbl_prefix}{$this->table}  where  id=? and  catid=? ";
        $query = $this->db->query($sql,$data);
        $results = $query->result_array();
        
        return $results;       
    }
    
    
     /*
     * 返回多条记录
     */
    public  function getPosition___($data){
        $sql="select id,listorder from {$this->tbl_prefix}{$this->table}  where  id=? and  modelid=? ";
        return $this->db->simple_query($sql,$data);
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
    public function deletePosition($id){
        $sql = "delete from {$this->tbl_prefix}{$this->table} where id=?";
        return $this->db->query($sql,$id);
    }
    
     /*
     * 刪除
     */
    public function deletePosition_($data){
        $sql = "delete from {$this->tbl_prefix}{$this->table} where id=? and posid=? and catid=?";
        return $this->db->query($sql,$data);
    }
    
    /*
     * 删除
     */
    public function deletePosition__($catid,$id,$real_posid){
        $sql = "delete from {$this->tbl_prefix}{$this->table} where `catid`='$catid' AND `id`='$id' AND `posid` IN ($real_posid)";
        return $this->db->query($sql);
    }
    
    /*
     * 更新
     */
    public function updatePosition($sql,$data){
        $sql = "update {$this->tbl_prefix}{$this->table} set {$sql} where id=? and posid=? and catid=?";
        return $this->db->query($sql,$data);
    }
    
    
}
?>