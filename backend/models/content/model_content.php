<?php
/*
 * function:内容模型
 * author:ikscher
 * date:2013-12-31
 */

class Model_content extends CI_Model {

    private $tbl_prefix;
    private $table;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
        $this->table="content";
    }
    
   
    
}
?>