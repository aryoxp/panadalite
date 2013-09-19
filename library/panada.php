<?php

class panada {
	
	public $config;
	public $error;
	public $session;
	public $base_url;
	
	static public $instance;
	
	public function __construct() { 
	
		$this->config = config::instance();
		$this->error = error::instance();
		
		$this->base_url = $this->config->base_url();
		
		// disable magic quotes
		ini_set ('magic_quotes_gpc', 0);
	}

	public function view( $viewpath, $data = array() ) {

		// extract given data arguments array as variables
		extract( $data, EXTR_SKIP ); //var_dump( $data) ;
		if( is_readable( VIEW . $viewpath ) )
			include VIEW . $viewpath;
		else $this->error->notfound( "View: " . $viewpath . " could not be found!" );
	}
	
	public function asset( $asset ) {
		return $this->base_url . "assets/" . $asset;
	}
	
	static public function instance() {
		if( !self::$instance )
			self::$instance = new panada();
		return ( self::$instance );
	}

	public function getExecutionTime() {
		return microtime( true ) - $GLOBALS['microtime_start'];
	}
			
}