<?php

/*
 * function:url规则模型类
 * author:ikscher
 * date:2013-12-16
 */

class Model_urlrule extends CI_Model {

    private $tbl_prefix;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
    }
    
    /*
     * 返回URL重写规则
     */
    function getUrlRules(){
        $result = array();
        $sql = "select urlruleid,module,file,ishtml,urlrule,example from {$this->tbl_prefix}urlrule order by urlruleid asc";
        $query = $this->db->query($sql);
        $result = $query->result_array();
		
        return $result;
    }
 
}
?>
