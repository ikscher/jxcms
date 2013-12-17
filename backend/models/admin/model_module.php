<?php

/*
 * function:菜单项
 * author:ikscher
 * date:2013-12-04
 */

class Model_module extends CI_Model {

    private $tbl_prefix;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
    }
    
    /*
     * 列出所有模块项
     */
    function getAllModules($where=''){
        $result = array();
        $sql = "select module,name,url,iscore,version,description,setting,listorder,disabled,installdate,updatedate from {$this->tbl_prefix}module {$where}";
        $query=$this->db->query($sql);
        $result = $query->result_array();
        
        return $result;
    }
    
   
}
?>
