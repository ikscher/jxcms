<?php

/*
 * function :后台导航主界面
 * author   :ikscher
 * date     :2013-10-30
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends CI_Controller {

    /*
     * 加载后台主界面
     */
    public function index() {
        $this->load->helper('url');
        $this->lang->load('system'); 

        if (!$this->input->cookie('adminuserid') || !$this->session->userdata('userid')) {
            redirect('d=common&c=login&m=index&token=' . $this->session->userdata('token'));
        }

        $this->lang->load('main');


        $this->data['heading_title']             = $this->lang->line('heading_title');
        $this->data['password']                  = $this->lang->line('password');
        $this->data['lockscreen_status']         = $this->lang->line('lockscreen_status');
        $this->data['password_can_not_be_empty'] = $this->lang->line('password_can_not_be_empty');
        $this->data['wait_1_hour_lock']          = $this->lang->line('wait_1_hour_lock');
        $this->data['wait_1_hour']          = $this->lang->line('wait_1_hour');
        $this->data['password_error_lock']       = $this->lang->line('password_error_lock');
        $this->data['password_error_lock2']      = $this->lang->line('password_error_lock2');

        //加载导航菜单
        $this->load->library('admin');
        
        $result=array();
        $menu  =array();
        $result = $this->admin->admin_menu(0);
   
        
        foreach($result as $v){
            $v['name']=$this->lang->line($v['name']);
            foreach($v['child'] as $k=>$v_){
                $v['child'][$k]['name']=$this->lang->line($v_['name']);
            }
            $menu[]=$v;
        }
        
        $this->data['menu']=$menu;
        $this->data['lock_screen']=$this->session->userdata('lock_screen');

        $this->load->view('common/main', $this->data);
    }
    
    /*
     * 锁屏
     */
    public function public_lock_screen(){
        $this->session->set_userdata('lock_screen', 1);
    }
    
    public function public_login_screenlock() {

		//if(empty($_GET['lock_password'])) showmessage(L('password_can_not_be_empty'));
		//密码错误剩余重试次数
	    $rtime=array();
        $r=array();
        
        $this->load->helper('global');
        
		$username = $this->cookie->AuthCode($this->input->cookie('adminusername'),'DECODE');

		$maxloginfailedtimes = $this->config->item('maxloginfailedtimes');
	
        $where=array($username,1);
        $tbl_pre=$this->db->dbprefix;
		$query= $this->db->query("select times,logintime from {$tbl_pre}times where username=? and isadmin=?",$where);
        $rtime=$query->row_array();

        $rtime['times']=isset($rtime['times'])?$rtime['times']:0;
        
        if( $rtime['times'] > $maxloginfailedtimes-1) {
            $minute = 60-floor((SYS_TIME-$rtime['logintime'])/60);
            exit('3');
        }
        //查询帐号
        $where_=array($this->session->userdata('userid'));
      
        $query_ = $this->db->query("select password,encrypt from {$tbl_pre}admin where userid=?",$where_);
        $r=$query_->row_array();
   
        $r['encrypt']=isset($r['encrypt'])?$r['encrypt']:'';
        
        $password = md5(md5($this->input->get('lock_password')).$r['encrypt']);
      
        $r['password']=isset($r['password'])?$r['password']:'';
        
        if( $r['password']!= $password) {
            $ip = getIp();
            if($rtime['times']<$maxloginfailedtimes && $query->num_rows()>0) {
                $times = $maxloginfailedtimes - intval($rtime['times']);
                $sql="update {$tbl_pre}times set `ip`='{$ip}',`isadmin`=1,`times`=`times`+1 where `username`='{$username}'";
                $this->db->query($sql);
            } else {
    
                $sql="insert into {$tbl_pre}times(username,`ip`,`isadmin`,`logintime`,`times`) values('{$username}','{$ip}',1,".SYS_TIME.",1)";
                $this->db->query($sql);
                $times = $maxloginfailedtimes;
            }
            exit('2|'.$times);//密码错误
        }
        
        if($query->num_rows()>0) $this->db->query("delete from {$tbl_pre}times where `username`='{$username}'");
		$this->session->set_userdata('lock_screen', 0);
		exit('1');
	}
    
    
    /*
     * 功能：左侧菜单
     */
	public function public_menu_left() {
        $this->lang->load('system');
        $this->load->library('admin');
		$menuid = intval($this->input->get('menuid'));
		$datas = $this->admin->admin_menu($menuid);
		
        $this->data['datas'] = $datas;
        $this->load->view('common/left',$this->data);
		
	}
    
    
    /*
     * 导航菜单显示的当前位置
     */
	public function public_current_pos() {
        $curpos='';
        $menuid=$this->input->get('menuid');
        $this->load->library('admin');
 
		$curpos=$this->admin->current_pos($menuid);
		
        $this->output->set_output($curpos);
	}


}

?>