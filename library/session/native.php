<?php

class session_native implements interface_session {
	
	private $name;
	//private $session_id;
	
	public function __construct( $config ) {
		
		$this->config = $config;
		$this->name = $config->name;
		
		ini_set('session.gc_maxlifetime', $config->expiration);
		
		session_set_cookie_params(
			$config->cookie_expire,
			$config->cookie_path,
			$config->cookie_domain,
			$config->cookie_secure
		);
		
		session_name($config->name);
		
		if ( !session_id() ) 
			@session_start();
		//$this->session_id = session_id();
	}
	
	// session variables getter and setter 
	public function set( $name, $value = NULL ) {
		if( is_array( $name ) ) {
			foreach($name as $key => $val) 
				$_SESSION[$key] = $val;
		}
		else $_SESSION[$name] = $value;	
	}
	public function get( $name ) {
		if( isset( $_SESSION[$name] ) )
			return $_SESSION[$name];
		return NULL;
	}
	
	// remove/unset session variable(s)
	public function remove( $name ) {
		unset( $_SESSION[$name] );
	}
	
	// completely destroy session
	public function destroy() {
		$cookie_params = session_get_cookie_params();
		
		setcookie($this->name, '', time() - 42000,
			$cookie_params['path'], $cookie_params['domain'],
			$cookie_params['secure'], $cookie_params['httponly']
		);
		
		session_unset();
		session_destroy();		
	}
	
	// set no session
	public function nocache() {
		header('Expires: Mon, 1 Jan 1980 00:00:01 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Last-Modified: ' . gmdate( 'D, j M Y H:i:s' ) . ' GMT' );
        
        $this->destroy();		
	}
	
	public function id() {
		return session_id();	
	}
	
}