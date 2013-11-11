<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
   class Model_product extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    function __construct()
    {
        parent::__construct();
    }
    
    function getData()
    {
        //$this->load->database();
        $query = $this->db->query('select * from admin limit 10');
        return $query->result();
    }

   

}

?>
