<?php

/*
 * function:点击次数
 * author:ikscher
 * date:2014-1-3
 */

class Model_hits extends CI_Model {

    private $tbl_prefix;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
    }
    
    /*
     * 返回管理员角色列表
     */
    public function getHits($hitsid){
        $result=array();
        $sql="select catid,views,yesterdayviews,dayviews,weekviews,monthviews,updatetime from {$this->tbl_prefix}hits where hitsid=?";
        $query=$this->db->query($sql,$hitsid);
        $result = $query->row_array();
        
        return $result;
    }
    
    /*
     * 新增
     * $data :key/value对应的数组（key代表 表的字段名）
     */
    public function insert($data){
        if(!is_array($data)) return;
        $sql = $this->db->insert_string("{$this->tbl_prefix}hits",$data);
        return $this->db->query($sql);
    }

   
}
?>
