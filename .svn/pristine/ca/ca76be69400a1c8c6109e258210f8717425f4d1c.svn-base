<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
            
           
           
           
            $this->load->model('try/mproduct');
            $data=$this->mproduct->getData();
            var_dump($data);
       }
	public function index()
	{       
                //$params=array('cookie_prefix'=>$this->config->item('cookie_prefix'),'cookie_domain'=>$this->config->item('cookie_domain'),'cookie_path'=>$this->config->item('cookie_path'));
                //$this->load->library('cookie',$params);
     
                $this->cookie->setCookie('test','sssss');
                $this->output->cache(1/60);
                $this->data['mm']=array('1','2','3');
                $this->data['xx']='ssss';
		$this->load->view('welcome_message',$this->data);
	}
        
        public function login(){
                $this->load->database();
      
		
                $this->load->library('user');
                $this->user->login('admin','123456');
        }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */