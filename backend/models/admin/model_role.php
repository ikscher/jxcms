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
    public function getRoles($where,$orderby='',$order='',$limit=''){
        $result=array();
        
        if(empty($orderby )) $orderby='roleid';
        if(empty($order))  $order='asc';
        if(empty($limit)) $limit=0;
        
        $perpage=$this->config->item('per_page');
        
        $sql="select roleid,rolename,description,listorder,disabled from {$this->tbl_prefix}admin_role {$where} order by {$orderby} {$order} limit {$limit},{$perpage}";
        $query=$this->db->query($sql);
        $result = $query->result_array();
        
        return $result;
    }
    
     /*
     * 返回全部角色
     */

    public function getAllRoles() {
        $result_array = array();
        $sql = "select roleid,rolename,description,listorder,disabled from {$this->tbl_prefix}admin_role";
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }
    
    /*
     * 返回管理员列表的总行数
     */
     public function getRolesTotal($where){
        $sql="select roleid from {$this->tbl_prefix}admin_role {$where} ";
        $query=$this->db->query($sql);
        
        
        return $query->num_rows()?$query->num_rows():0;
    }
    
    /*
     * 增加角色
     */
    public function add($info=  array()){
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
    
    /**
     * 更新角色
     */
    public function change_status($data,$where=''){
        $str ='';
        if(is_array($data)){
            $comma = '';          
            foreach($data as $k=>$v){
                $str .=$comma;
                $str .="`{$k}`={$v}";
                $comma = ',';
            }
        }else{
            $str = $data;
        }
        $sql = "update {$this->tbl_prefix}admin_role set {$str} where {$where}";
        return $this->db->query($sql);
    }
    
    /*
     * 删除角色
     */
    public function delete($roleid){
        if(empty($roleid)) return false;
        $sql="delete from {$this->tbl_prefix}admin_role where roleid='{$roleid}'";
        return $this->db->query($sql);
    }
    
    
     /*
     * 修改角色
     */
    public function edit($str,$roleid){
        if(empty($roleid)) return false;
        $sql="update {$this->tbl_prefix}admin_role  set {$str}  where roleid='{$roleid}'";
        return $this->db->query($sql);
    }
    
    /*
     * 返回指定角色下的所有成员
     */
    public function getRoleMembers($con=array()){
        $result=array();

        $sql="select userid,username,password,roleid,encrypt,lastloginip,lastlogintime,email,realname from {$this->tbl_prefix}admin where roleid=? order by userid asc limit ?,?";

        $query = $this->db->query($sql,$con);
        $result= $query->result_array();
        return $result;
    }
    
    /*
     * 返回指定角色下的成员总数
     */
    public function getRoleMembersTotal($roleid){
        $result=array();
        
        $sql="select userid,username,password,roleid,encrypt,lastloginip,lastlogintime,email,realname from {$this->tbl_prefix}admin where roleid='{$roleid}' ";
        $query = $this->db->query($sql);
        
        return  $query->num_rows()?$query->num_rows():0;
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
