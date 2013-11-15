<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


$config['per_page'] = 10;
$config['page_query_string'] = true;
$config['use_page_numbers'] = TRUE;
$config['first_link'] = '第一页';
$config['first_tag_open'] = '<li>';
$config['first_tag_close'] = '</li>';
$config['cur_tag_open'] = '<li class="active"><a>';
$config['cur_tag_close'] = '</a></li>';
$config['last_link'] = '最后一页';
$config['last_tag_open'] = '<li>';
$config['last_tag_close'] = '</li>';
//$config['num_links'] = 5;
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';

$config['next_link'] = '后页';
$config['next_tag_open'] = '<li>';
$config['next_tag_close'] = '</li>';

$config['prev_link'] = '前页';
$config['prev_tag_open'] = '<li>';
$config['prev_tag_close'] = '</li>';
/* End of file memcached.php */
/* Location: ./system/application/config/pagination.php */
?>
