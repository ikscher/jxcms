<?php

/*
 * function:角色模型类
 * author:ikscher
 * date:2013-11-13
 */

class Model_role extends CI_Model {

    private $tbl_prefix;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
    }
    
    /*
     * 返回管理员角色列表
     */
    public function getRoles($where,$orderby,$order){
        $result=array();
        
        if(empty($orderby )) $orderby='listorder';
        if(empty($order))  $order='desc';
       
        $sql="select roleid,rolename,description,listorder,disabled from {$this->tbl_prefix}admin_role {$where} order by {$orderby} {$order}";

        $query=$this->db->query($sql);
        $result = $query->result_array();
        
        return $result;
    }
    
    /*
     * 增加角色
     */
    public function addRole($info=  array()){
        $comma='';
        $str='';
        foreach($info as $k=>$v){
            $str .=$comma;
            $str .="`{$k}`='{$v}'";
            $comma=',';
        }
        
        if(!empty($str)){
            $sql="insert into {$this->tbl_prefix}admin_role set {$str}";
            return $this->db->query($sql);
        }else{
            return false;
        }
    }
    
    /*
     *  是否存在指定名称的角色
     */
    public function isExistsRole($rolename=''){
        $sql="select roleid from {$this->tbl_prefix}admin_role where rolename='{$rolename}'";
        $query = $this->db->query($sql);
        
        return $query->row_array();
    }
}
?>
