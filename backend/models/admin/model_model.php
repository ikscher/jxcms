<?php

/*
 * function:菜单项
 * author:ikscher
 * date:2013-12-04
 */

class Model_model extends CI_Model {

    private $tbl_prefix;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
    }
    
    /*
     * 列出所有模块项
     */
    function getAllModels($where=''){
        $result = array();
        $sql = "select modelid,name,description,tablename,setting,addtime,items,enablesearch,disabled,default_style,category_template,list_template,show_template,js_template,admin_list_template,member_add_template,member_list_template,sort,type  from {$this->tbl_prefix}model {$where}";
        $query=$this->db->query($sql);
        $result = $query->result_array();
        
        return $result;
    }
    
   
}
?>
