<?php

/*
 * function:管理员模型类
 * author:ikscher
 * date:2013-11-12
 */

class Model_manage extends CI_Model {

    private $tbl_prefix;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
    }

    /*
     * 返回管理员的信息
     */

    public function getAdminInfo($userid=array()) {
        $sql = "select userid,username,password,encrypt,lastloginip,lastlogintime,email,realname from {$this->tbl_prefix}admin where `userid`=?";
        $query = $this->db->query($sql, $userid);
        return $query->row_array();
    }

    /*
     * 编辑管理员信息
     */

    public function editAdminInfo($str, $where) {
       
        $sql="update {$this->tbl_prefix}admin set $str where  `userid`='{$where}'";
        return $this->db->query($sql);

    }
    
    /*
     * 删除管理员
     */
    public function deleteAdmin($userid){
        $sql="delete from {$this->tbl_prefix}admin where `userid`=?";
        return $this->db->query($sql,$userid);
    }
   

}

