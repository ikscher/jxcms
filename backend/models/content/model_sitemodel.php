<?php
/*
 * function:站点模型类
 * author:ikscher
 * date:2013-12-13
 */

class Model_sitemodel extends CI_Model {

    private $tbl_prefix;
    private $table;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
        $this->table="model";
    }
    
    /*
     * 分页返回所有模型
     */
    public function getSiteModels($where,$orderby='',$order='',$limit=''){
        $results=array();
        
        if(empty($orderby )) $orderby='modelid';
        if(empty($order))  $order='asc';
        if(empty($limit)) $limit=0;
        
        $perpage=$this->config->item('per_page');
        
        $sql="select modelid,name,description,tablename,setting,addtime,items,enablesearch,disabled,default_style,category_template,list_template,show_template,js_template,admin_list_template,member_add_template,member_list_template,sort,type from {$this->tbl_prefix}{$this->table} {$where} order by {$orderby} {$order} limit {$limit},{$perpage}";
        $query=$this->db->query($sql);
        $results = $query->result_array();
        
        return $results;
    }
    
    /*
     * 返回单条模型
     */
    public function getSiteModel($modelid){
        $result=array();
        $sql="select modelid,name,description,tablename,setting,addtime,items,enablesearch,disabled,default_style,category_template,list_template,show_template,js_template,admin_list_template,member_add_template,member_list_template,sort,type from {$this->tbl_prefix}{$this->table} where modelid=?";
        $query=$this->db->query($sql,$modelid);
        $result = $query->row_array();
        
        return $result;
    }
    
    
    /*
     * 返回模型总数
     */
    public function getSiteModelTotal($where){
        $sql="select modelid from {$this->tbl_prefix}{$this->table} {$where} ";
        $query=$this->db->query($sql);
        
        return $query->num_rows()?$query->num_rows():0;
    }
    
    /*
     * 删除
     */
    public function deleteSiteModel($where){
        $sql = "delete from {$this->tbl_prefix}{$this->table} {$where}";
        $this->db->query($sql);
    }
    
    /*
     * 更新
     */
    public function updateSiteModel($sql,$modelid){
        $sql = "update {$this->tbl_prefix}{$this->table} set {$sql}  where modelid=?";
        return $this->db->query($sql,$modelid);
    }
}
?>