<?php

/*
 * function :后台登录頁面 
 * author   :ikscher
 * date     :2013-10-30
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    public function index() {

        $this->load->helper('url');
        $this->lang->load('login');


        $token = $this->input->get('token');
        if ($this->input->cookie('adminuserid') && isset($token) && (isset($token) ? $token : '' == $this->session->userdata('token'))) {

            redirect('d=common&c=main&m=index&token=' . $this->session->userdata('token'));
        }

        if (($this->input->server('REQUEST_METHOD') == 'POST') && $this->validate()) {
 
            $this->session->set_userdata('token', md5(mt_rand()));

            if ($this->input->post('redirect')) {
                redirect($this->input->post('redirect') . '&token=' . $this->session->userdata('token'));
            } else {

                redirect('d=common&c=main&m=index&token=' . $this->session->userdata('token'));
            }
        }

        $this->data['heading_title'] = $this->lang->line('heading_title');
        $this->data['text_login'] = $this->lang->line('text_login');
        $this->data['text_forgotten'] = $this->lang->line('text_forgotten');

        $this->data['entry_username'] = $this->lang->line('entry_username');
        $this->data['entry_password'] = $this->lang->line('entry_password');

        $this->data['button_login'] = $this->lang->line('button_login');


        if (($this->session->userdata('token') && !isset($token)) || ((isset($token) && ($this->session->userdata('token') && ($token != $this->session->userdata('token')))))) {
            $this->error['warning'] = $this->lang->line('error_token');
        }

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if ($this->session->userdata('success')) {
            $this->data['success'] = $this->session->userdata('success');
        } else {
            $this->data['success'] = '';
        }

        // $this->data['action'] = 'index.php/common/login';

        if ($this->input->post('username')) {
            $this->data['username'] = $this->input->post('username');
        } else {
            $this->data['username'] = '';
        }

        if ($this->input->post('password')) {
            $this->data['password'] = $this->input->post('password');
        } else {
            $this->data['password'] = '';
        }


//        $this->data['forgotten'] = anchor('common/forgotten');


        $this->load->view('common/login', $this->data);
    }

    private function validate() {

        $this->load->library('user');

        if ($this->input->post('username') && $this->input->post('password') && !$this->user->login($this->input->post('username'), $this->input->post('password'))) {
            $this->error['warning'] = $this->lang->line('error_login');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

}

?>