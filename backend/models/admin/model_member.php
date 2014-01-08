<?php
/*
 * function:注册会员信息
 * author:ikscher
 * date:2014-1-6
 */

class Model_member extends CI_Model {

    private $tbl_prefix;
    private $table;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
        $this->table = 'member';
    }
    
    /*
     * 获取会员信息
     */
    function getMemberInfo($username,$fields=''){
        $result = array();
        if(!empty($fields)){
            $sql = "select `userid` ,`phpssouid` ,`username`  from {$this->tbl_prefix}{$this->table} where username=?";
        }else{
            $sql = "select `userid` ,`phpssouid` ,`username` ,`password` ,`encrypt` ,`nickname`,`regdate` ,`lastdate`,`regip` ,`lastip`,`loginnum`,`email` ,`groupid`,`areaid` ,`amount` ,`point` ,`modelid`,`message`,`islock` ,`vip`,`overduedate`,`siteid`,`connectid`,`from` , `mobile`  from {$this->tbl_prefix}{$this->table} where username=?";
        }
        $query=$this->db->query($sql,$username);
        $result = $query->row_array();
        
        return $result;
    }
    
   
}
?>
