<?php

/*
 * function:主导航界面模型类
 * author:ikscher
 * date:2013-11-11
 */

class Model_main extends CI_Model {

    private $tbl_prefix;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
    }

    /*
     * 返回全部角色
     */

    public function getRoles() {
        $result_array = array();
        $sql = "select roleid,rolename,description,listorder,disabled from {$this->tbl_prefix}admin_role";
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }

    /*
     * 返回用户登录次数相关信息
     */

    public function getLoginTimes($where) {
        $rtime = array();
        $query = $this->db->query("select times,logintime from {$this->tbl_prefix}times where username=? and isadmin=?", $where);
        $rtime = $query->row_array();

        return $rtime;
    }

    /*
     * 返回用户信息
     */

    public function getAdminInfo($where) {
        $r = array();
        $query_ = $this->db->query("select lastlogintime,lastloginip,password,encrypt from {$this->tbl_prefix}admin where userid=?", $where);
        $r = $query_->row_array();
        return $r;
    }
    
    /*
     * 更新登录次数
     */
    public function updateLoginTimes($ip,$username){
        $sql="update {$this->tbl_prefix}times set `ip`='{$ip}',`isadmin`=1,`times`=`times`+1 where `username`='{$username}'";
        $this->db->query($sql);
    }
    
    /*
     * 插入登录次数
     */
    public function insertLoginTime($username,$ip){
         $sql="insert into {$this->tbl_prefix}times(username,`ip`,`isadmin`,`logintime`,`times`) values('{$username}','{$ip}',1,".SYS_TIME.",1)";
         $this->db->query($sql);
    }
    
    /*
     * 删除登录信息
     */
    public function deleteLoginInfo($username){
        $this->db->query("delete from {$this->tbl_prefix}times where `username`='{$username}'");
    }
}

?>
