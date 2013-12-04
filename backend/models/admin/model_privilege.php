<?php

/*
 * function:菜单项
 * author:ikscher
 * date:2013-12-04
 */

class Model_privilege extends CI_Model {

    private $tbl_prefix;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
    }
    
    /*
     * 列出所有菜单项
     */
    function getRolePrivileges(){
        $result = array();
        $sql = "select roleid,d,c,m,`data` from {$this->tbl_prefix}admin_role_priv ";
        $query=$this->db->query($sql);
        $result = $query->result_array();
        
        return $result;
    }
    
   
}
?>
