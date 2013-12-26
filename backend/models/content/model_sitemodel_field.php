<?php
/*
 * function:站点模型字段类
 * author:ikscher
 * date:2013-12-23
 */

class Model_sitemodel_field extends CI_Model {

    private $tbl_prefix;
    private $table;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
        $this->table="model_field";
    }
    
    /*
     * 分页返回所有模型
     */
    public function getSiteModelFields($where,$orderby='',$order='',$limit=''){
        $results=array();
        
        if(empty($orderby )) $orderby='fieldid';
        if(empty($order))  $order='asc';
        if(empty($limit)) $limit=0;
        
        $perpage=$this->config->item('per_page');
        
        $sql="select fieldid,modelid,field,name,tips,css,minlength,maxlength,pattern,errortips,formtype,setting,formattribute,unsetgroupids,unsetroleids,iscore,issystem,isunique,isbase,issearch,isadd,isfulltext,isposition,listorder,disabled,isomnipotent from {$this->tbl_prefix}{$this->table} {$where} order by {$orderby} {$order} limit {$limit},{$perpage}";
        $query=$this->db->query($sql);
        $results = $query->result_array();
        
        return $results;
    }
    
    /*
     * 返回模型总数
     */
    public function getSiteModelFieldTotal($where){
        $sql="select modelid from {$this->tbl_prefix}{$this->table} {$where} ";
        $query=$this->db->query($sql);
        
        return $query->num_rows()?$query->num_rows():0;
    }
    
    public function deleteModelField($where){
        $sql = "delete from {$this->tbl_prefix}{$this->table} {$where}";
        $this->db->query($sql);
    }
    
}
?>