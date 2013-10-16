<?php 
defined('PANADA') or die("This file can't be accessed directly!");

class config {
    
    static private $instance;
    
    public function __construct() {
    	require_once GEAR . 'config.php';
        require_once APPLICATION . 'config.php';       
		
        foreach($CONFIG as $key => $val)
            $this->$key = tools::arrayToObject($val);
				
		// set the error reporting environment setup 
		switch ($this->environment) {
			case 'development':
				error_reporting(E_ALL); break;
			case 'production':
				error_reporting(0); break;
			default:
				exit('The application environment is not set correctly. Please check your environment configuration on config.php file.');
		}
    }
    
    public static function instance(){   
        if( !self::$instance ) {
            self::$instance = new config();
        }
		return self::$instance;
    }
    
    /**
     * Get Base URL
     * 
     * @author  Aris S Ripandi
     * @since	Version 0.3.1
     *
     * @access public
     * @return void
     */
    public function base_url(){
        $base_url  = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';	// protocol
        $base_url .= preg_replace('/:(80|443)$/', '', $_SERVER['HTTP_HOST']);							// host[:port]
        $base_url .= str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));							// path
        if (substr($base_url, -1) == '/') $base_url = substr($base_url, 0, -1);
        $base_url = $base_url . '/';
        return $base_url;
    }

} // End Library_config