<?php
/*
 * function:站点信息
 * author:ikscher
 * date:2013-12-10
 */

class Model_category extends CI_Model {

    private $tbl_prefix;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
    }
    
    /*
     * 列出栏目
     */
    public function getCategories($field='*',$where=''){
        $result = array();
        if($field=='*'){
           $sql = "select catid,module,type,modelid,parentid,arrparentid,child,arrchildid,catname,style,image,description,parentdir,catdir,url,items,hits,setting,listorder,ismenu,sethtml,letter,usable_type  from {$this->tbl_prefix}category {$where}";
        }else {
           $sql = "select {$field}  from {$this->tbl_prefix}category {$where}";
        }
        
        $query=$this->db->query($sql);
        $result = $query->result_array();
        
        return $result;
    }
    
    /*
     * 获取指定栏目信息
     */
    public function getCategory($where){
        $result = array();
        $sql = "select catid,module,type,modelid,parentid,arrparentid,child,arrchildid,catname,style,image,description,parentdir,catdir,url,items,hits,setting,listorder,ismenu,sethtml,letter,usable_type  from {$this->tbl_prefix}category {$where}";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        
        return $result;
    }
    
    /*
     * 更新栏目
     */
    public function updateCategory($str,$where){
        $sql = "update {$this->tbl_prefix}category set {$str} {$where}";
        $this->db->query($sql);
    }
    
    /*
     * 删除指定栏目
     */
    public function deleteCategory($id){
        $sql = "delete from {$this->tbl_prefix}category where catid=?";
        return $this->db->query($sql,$id);
    }
    
    
   
}
?>
