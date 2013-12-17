<?php

/*
 * function:url规则模型类
 * author:ikscher
 * date:2013-12-16
 */

class Model_member_group extends CI_Model {

    private $tbl_prefix;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
    }
    
    /*
     * 返回会员组
     */
    function getMemberGroup(){
        $results = array();
        $sql = "select groupid,name,issystem,starnum,point,allowmessage,allowvisit,allowpost,allowpostverify,allowsearch,allowupgrade,allowsendmessage,allowpostnum,allowattachment,price_y,price_m,price_d,icon,usernamecolor,description,sort,disabled from {$this->tbl_prefix}member_group order by groupid";
		
        $query = $this->db->query($sql);
        $results = $query->result_array();
		
        return $results;
    }
 
}
?>
