<?php

/**
 *  后台用户登录类
 */

class CI_User {

    private $userid;
    private $username;
    //private $permission = array();
    private $db;
    private $session;
    private $cookie;
    private $encrypt;
    private $tbl_prefix;
  

    public function __construct() {
        global $COOKIE,$IN;
        $CI = & get_instance();
        //$CI->load->database();//因为没有在autoload.php中自动加载数据库，所以这步需要写上
        $this->db = &$CI->db;
        //$CI->load->library('session');
        $this->session = &$CI->session;
        $this->cookie = &$COOKIE;
        $this->input=&$IN;
        $this->tbl_prefix=$this->db->dbprefix;
      
        $adminuserid=$this->input->cookie('adminuserid');
        
        $this->userid = $this->cookie->AuthCode(isset($adminuserid)? $this->input->cookie('adminuserid') : '', 'DECODE');

        if (empty($this->userid)) {
            //$sql="SELECT * FROM " . $this->tbl_prefix . "admin WHERE userid = '" . $this->userid . "' AND status = '1' limit 1";
            //$user_query = $this->db->query($sql);
         
            //if ($user_query->num_rows()>0) {
                //$row=$user_query->row();
                //$this->userid = $row->userid;
                //$this->username = $row->username;

                //$this->db->query("UPDATE " . $this->tbl_prefix . "admin SET `lastloginip` = " . $this->db->escape($this->input->server('REMOTE_ADDR')) . " `lastlogintime`=".SYS_TIME." WHERE userid = '" . (int) $this->userid . "'");


            //} else {
                $this->logout();
            //}
        }
    }

    public function login($username, $password) {
        
        $username = $this->db->escape($username);
        $username=trim($username);
        
        $sql = "SELECT encrypt FROM " . $this->tbl_prefix . "admin WHERE username ={$username}  AND status = '1' limit 1";

        $query=$this->db->query($sql);
        $row_=$query->row();
        $this->encrypt=$row_->encrypt;
        
        $password = $this->db->escape(md5(md5($password).$this->encrypt));
     
        $sql = "SELECT a.userid,a.username,a.roleid,ar.rolename FROM " . $this->tbl_prefix . "admin  a left join ".$this->tbl_prefix."admin_role ar on a.roleid=ar.roleid  WHERE a.username ={$username}  AND  a.password = {$password} AND a.status = '1' limit 1";
       
        $user_query = $this->db->query($sql);
//        var_dump($user_query->result());

        if ($user_query->num_rows() > 0) {
            $row = $user_query->row();
            $this->session->set_userdata('userid', $row->userid);
            $this->session->set_userdata('roleid', $row->roleid);
            $this->session->set_userdata('rolename',$row->rolename);
        
            $this->cookie->SetCookie("adminuserid", $this->cookie->AuthCode($row->userid, 'ENCODE'), 24 * 3600);
            $this->cookie->SetCookie("adminusername", $this->cookie->AuthCode($row->username, 'ENCODE'), 24 * 3600);

            $this->userid = $row->userid;
            $this->username = $row->username;
            
            $sql="UPDATE " . $this->tbl_prefix . "admin SET `lastloginip` = " . $this->db->escape($this->input->server('REMOTE_ADDR')) . ", `lastlogintime`=".SYS_TIME." WHERE userid = '" . (int) $this->userid . "'";
            $this->db->query($sql);

            
            return true;
        } else {
            return false;
        }
    }

    public function logout() {
        //unset($this->session->data['userid']);
        // if(isset($this->request->cookie['userid'])) $this->cookie->OCSetCookie("userid","",-24*3600);
        
        $this->userid = '';
        $this->username = '';
        
      
       
    }


    public function isLogged() {
        return $this->userid;
    }

    public function getId() {
        return $this->userid;
    }

    public function getUserName() {
        return $this->username;
    }

}

?>