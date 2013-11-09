<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006 - 2012 EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 2.0
 * @filesource	
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Memcached Caching Class 
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Core
 * @author		ExpressionEngine Dev Team
 * @link		
 */

class CI_Cache_memcached extends CI_Driver {

	//private $_memcached;	// Holds the memcached object
    private $config;
    private $_memcached;
    private $client_type;
    private $ci;
    protected $errors = array();

//	protected $_memcache_conf 	= array(
//					'default' => array(
//						'default_host'		=> '127.0.0.1',
//						'default_port'		=> 11211,
//						'default_weight'	=> 1
//					)
//				);

	// ------------------------------------------------------------------------	

	/**
	 * Fetch from cache
	 *
	 * @param 	mixed		unique key id
	 * @return 	mixed		data on success/false on failure
	 */	
	public function get($key=null)
	{	
		if($this->_memcached)
                {
                        if(is_null($key))
                        {
                                $this->errors[] = 'The key value cannot be NULL';
                                return FALSE;
                        }

                        if(is_array($key))
                        {
                                foreach($key as $n=>$k)
                                {
                                        $key[$n] = $this->key_name($k);
                                }
                                return $this->_memcached->getMulti($key);
                        }
                        else
                        {
                                return $this->_memcached->get($this->key_name($key));
                        }
                }
                return FALSE;
	}

	// ------------------------------------------------------------------------
    
	/**
	 * Save
	 *
	 * @param 	string		unique identifier
	 * @param 	mixed		data being cached
	 * @param 	int			time to live
	 * @return 	boolean 	true on success, false on failure
	 */
	 public function save($key = NULL, $value = NULL, $expiration = 60)
        {
                if(is_null($expiration))
                {
                        $expiration = $this->config['config']['expiration'];
                }
                if(is_array($key))
                {
                        foreach($key as $multi)
                        {
                                if(!isset($multi['expiration']) || $multi['expiration'] == '')
                                {
                                        $multi['expiration'] = $this->config['config']['expiration'];
                                }
                                $this->save($this->key_name($multi['key']), $multi['value'], $multi['expiration']);
                        }
                }
                else
                {
                        switch($this->client_type)
                        {
                                case 'Memcache':
                                        $add_status = $this->_memcached->save($this->key_name($key), $value, $this->config['config']['compression'], $expiration);
                                        break;

                                default:
                                case 'Memcached':
                                        $add_status = $this->_memcached->save($this->key_name($key), $value, $expiration);
                                        break;
                        }

                        return $add_status;
                }
        }


	// ------------------------------------------------------------------------
	
	/**
	 * Delete from Cache
	 *
	 * @param 	mixed		key to be deleted.
	 * @return 	boolean 	true on success, false on failure
	 */
	 public function delete($key, $expiration = NULL)
        {
                if(is_null($key))
                {
                        $this->errors[] = 'The key value cannot be NULL';
                        return FALSE;
                }

                if(is_null($expiration))
                {
                        $expiration = $this->config['config']['delete_expiration'];
                }

                if(is_array($key))
                {
                        foreach($key as $multi)
                        {
                                $this->delete($multi, $expiration);
                        }
                }
                else
                {
                        return $this->_memcached->delete($this->key_name($key), $expiration);
                }
        }

	// ------------------------------------------------------------------------
	
	/**
	 * Clean the Cache
	 *
	 * @return 	boolean		false on failure/true on success
	 */
	public function clean()
	{
		return $this->_memcached->flush();
	}

	// ------------------------------------------------------------------------

	/**
	 * Cache Info
	 *
	 * @param 	null		type not supported in memcached
	 * @return 	mixed 		array on success, false on failure
	 */
    
    public function cache_info($type="items")
        {
                switch($this->client_type)
                {
                        case 'Memcache':
                                $stats = $this->_memcached->getStats($type);
                                break;

                        default:
                        case 'Memcached':
                                $stats = $this->_memcached->getStats();
                                break;
                }
                return $stats;
        }

	// ------------------------------------------------------------------------
	
	/**
	 * Get Cache Metadata
	 *
	 * @param 	mixed		key to get cache metadata on
	 * @return 	mixed		FALSE on failure, array on success.
	 */
	public function get_metadata($id)
	{
		$stored = $this->_memcached->get($id);

		if (count($stored) !== 3)
		{
			return FALSE;
		}

		list($data, $time, $ttl) = $stored;

		return array(
			'expire'	=> $time + $ttl,
			'mtime'		=> $time,
			'data'		=> $data
		);
	}

	// ------------------------------------------------------------------------

	/**
	 * Setup memcached.
	 */
	private function _setup_memcached()
	{
		// Try to load memcached server info from the config file.
		 $this->ci =& get_instance();
             
                // Load the memcached library config
                $this->ci->load->config('memcached');
                $this->config = $this->ci->config->item('memcached');

                // Lets try to load Memcache or Memcached Class
                $this->client_type = class_exists($this->config['config']['engine']) ? $this->config['config']['engine'] : FALSE;

                if($this->client_type)
                {
                        // Which one should be loaded
                        switch($this->client_type)
                        {
                                case 'Memcached':
                                        $this->_memcached = new Memcached();
                                        break;
                                case 'Memcache':
                                        $this->_memcached = new Memcache();
                                        // Set Automatic Compression Settings
                                        if ($this->config['config']['auto_compress_tresh'])
                                        {
                                                $this->setcompressthreshold($this->config['config']['auto_compress_tresh'], $this->config['config']['auto_compress_savings']);
                                        }
                                        break;
                        }
                        log_message('debug', "Memcached Library: " . $this->client_type . " Class Loaded");

                        $this->auto_connect();
                }
                else
                {
                        log_message('error', "Memcached Library: Failed to load Memcached or Memcache Class");
                }
	}

	// ------------------------------------------------------------------------
     /*
        +-------------------------------------+
                Name: auto_connect
                Purpose: runs through all of the servers defined in
                the configuration and attempts to connect to each
                @param return : none
        +-------------------------------------+
        */
        private function auto_connect()
        {
                foreach($this->config['servers'] as $key=>$server)
                {
                        if(!$this->add_server($server))
                        {
                                $this->errors[] = "Memcached Library: Could not connect to the server named $key";
                                log_message('error', 'Memcached Library: Could not connect to the server named "'.$key.'"');
                        }
                        else
                        {
                                log_message('debug', 'Memcached Library: Successfully connected to the server named "'.$key.'"');
                        }
                }
        }
        
         /*
        +-------------------------------------+
                Name: setcompresstreshold
                Purpose: Set When Automatic compression should kick-in
                @param return TRUE/FALSE
        +-------------------------------------+
        */
        public function setcompressthreshold($tresh, $savings=0.2)
        {
                switch($this->client_type)
                {
                        case 'Memcache':
                                $setcompressthreshold_status = $this->_memcached->setCompressThreshold($tresh, $savings=0.2);
                                break;

                        default:
                                $setcompressthreshold_status = TRUE;
                                break;
                }
                return $setcompressthreshold_status;
        }

        
         /*
        +-------------------------------------+
                Name: add_server
                Purpose:
                @param return : TRUE or FALSE
        +-------------------------------------+
        */
        public function add_server($server)
        {
                extract($server);
                return $this->_memcached->addServer($host, $port, $weight);
        }

       
         /*
        +-------------------------------------+
                Name: add
                Purpose: add an item to the memcache server(s)
                @param return : TRUE or FALSE
        +-------------------------------------+
        */
        public function add($key = NULL, $value = NULL, $expiration = NULL)
        {
                if(is_null($expiration))
                {
                        $expiration = $this->config['config']['expiration'];
                }
                if(is_array($key))
                {
                        foreach($key as $multi)
                        {
                                if(!isset($multi['expiration']) || $multi['expiration'] == '')
                                {
                                        $multi['expiration'] = $this->config['config']['expiration'];
                                }
                                $this->add($this->key_name($multi['key']), $multi['value'], $multi['expiration']);
                        }
                }
                else
                {
                        switch($this->client_type)
                        {
                                case 'Memcache':
                                        $add_status = $this->_memcached->add($this->key_name($key), $value, $this->config['config']['compression'], $expiration);
                                        break;

                                default:
                                case 'Memcached':
                                        $add_status = $this->_memcached->add($this->key_name($key), $value, $expiration);
                                        break;
                        }

                        return $add_status;
                }
        }
        
          /*
        +-------------------------------------+
                Name: getversion
                Purpose: Get Server Vesion Number
                @param Returns a string of server version number or FALSE on failure.
        +-------------------------------------+
        */
        public function getversion()
        {
                return $this->_memcached->getVersion();
        }

        /*
        +-------------------------------------+
        
         /*
        +-------------------------------------+
                Name: replace
                Purpose: replaces the value of a key that already exists
                @param return : none
        +-------------------------------------+
        */
        public function replace($key = NULL, $value = NULL, $expiration = NULL)
        {
                if(is_null($expiration))
                {
                        $expiration = $this->config['config']['expiration'];
                }
                if(is_array($key))
                {
                        foreach($key as $multi)
                        {
                                if(!isset($multi['expiration']) || $multi['expiration'] == '')
                                {
                                        $multi['expiration'] = $this->config['config']['expiration'];
                                }
                                $this->replace($multi['key'], $multi['value'], $multi['expiration']);
                        }
                }
                else
                {
                        switch($this->client_type)
                        {
                                case 'Memcache':
                                        $replace_status = $this->_memcached->replace($this->key_name($key), $value, $this->config['config']['compression'], $expiration);
                                        break;

                                default:
                                case 'Memcached':
                                        $replace_status = $this->_memcached->replace($this->key_name($key), $value, $expiration);
                                        break;
                        }

                        return $replace_status;
                }
        }


	/**
	 * Is supported
	 *
	 * Returns FALSE if memcached is not supported on the system.
	 * If it is, we setup the memcached object & return TRUE
	 */
	public function is_supported()
	{
//		if ( ! extension_loaded('memcached'))
//		{
//			log_message('error', 'The Memcached Extension must be loaded to use Memcached Cache.');
//			
//			return FALSE;
//		}
		
		$this->_setup_memcached();
//		return TRUE;
	}

	// ------------------------------------------------------------------------

}
// End Class

/* End of file Cache_memcached.php */
/* Location: ./system/libraries/Cache/drivers/Cache_memcached.php */