<?php defined('PANADA') or die('Can\'t access directly!');

class session implements interface_session {
    
    private $session_driver;
    //private $config;
    
    public function __construct( $session_config ) {
        //$this->config = config::instance();
		//$this->config->session->secret_key = $this->config->secret_key;
        require_once GEAR . 'library/session/' . $session_config->driver.'.php';
        $class_name = 'session_'.$session_config->driver;
        $this->session_driver = new $class_name( $session_config );
				
    }
	
	// session variables getter and setter 
	public function set( $name, $value = NULL ) {
		$this->session_driver->set( $name, $value );	
	}
	public function get( $name ) {
		return $this->session_driver->get( $name );
	}
	
	// remove/unset session variable(s)
	public function remove( $name ) {
		$this->session_driver->remove( $name );
	}
	
	// completely destroy session
	public function destroy() {
		$this->session_driver->destroy();
	}
	
	// set no session
	public function nocache() {
		$this->session_driver->nocache();
	}
	
	// get the session id
	public function id() {
		$this->session_driver->id();	
	}

	// drivers specific implementation
	// in function call, variables getter and setter
	public function __call($name, $arguments){    
        return call_user_func_array( array( $this->session_driver, $name ), $arguments );
    }
    public function __get($name){
        return $this->session_driver->$name;
    }
    public function __set($name, $value){
        $this->session_driver->$name = $value;
    }			
} // End of session wrapper class