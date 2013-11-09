<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends CI_Controller {
    
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
       public function show(){
           header("Content-type: text/html; charset=utf-8"); 
           $this->load->driver('cache');
           $this->load->model('try/mproduct');
            $data=$this->mproduct->getData();
            var_dump($data);
           echo 'this is product';
           echo '<br>';
           
           echo $this->cache->memcached->get('test');

       }
       
       public function index(){
           $this->load->driver('cache');
           $this->cache->memcached->save('test','合肥金相投资咨询');
       }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */