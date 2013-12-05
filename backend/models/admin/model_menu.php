<?php

/*
 * function:菜单项
 * author:ikscher
 * date:2013-12-04
 */

class Model_menu extends CI_Model {

    private $tbl_prefix;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
    }
    
    /*
     * 列出所有菜单项
     */
    function getMenus($where=''){
        $result = array();
        $sql = "select id,name,parentid,d,c,m,data,listorder,`display` from {$this->tbl_prefix}menu {$where}";
        $query=$this->db->query($sql);
        $result = $query->result_array();
        
        return $result;
    }
    
   
}
?>
