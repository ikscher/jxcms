<?php
/*
 * function:站点信息
 * author:ikscher
 * date:2013-12-10
 */

class Model_site extends CI_Model {

    private $tbl_prefix;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
    }
    
    /*
     * 列出所有模块项
     */
    function getSiteInfo($where=''){
        $result = array();
        $sql = "select siteid,name,dirname,domain,site_title,keywords,description,release_point,default_style,template,setting,uuid  from {$this->tbl_prefix}site {$where}";
        $query=$this->db->query($sql);
        $result = $query->row_array();
        
        return $result;
    }
    
   
}
?>
