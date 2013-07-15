<?php

class controller extends panada {
	
	public $className;
	public $methodName;
	
	public function __construct() {
		parent::__construct();
		
		$uri = uri::instance();
		$controllerName = $uri->getController();
		
		$this->methodName = $uri->getMethod();
		$this->className = str_replace( "controller_", "", $controllerName );
		
	}
	
	public function redirect( $destination = NULL ) {
		
        $destination = ( empty( $destination ) ) ? $this->location() : $destination;
		
        if ( !preg_match("/^http(s)\:\/\//i", $destination) )
            $destination = $this->location() . $destination;
        header( 'location: ' . trim( $destination ) );
        exit;
	}

    public function sredirect( $destination ) {
        header( 'location: ' . trim( $this->location($destination, true) ) );
        exit;
    }

	// $this->location('home', true);
	// http:// -> https://
	// $this->location('home', false);
	// https:// -> http://
	public function location( $path = NULL, $secure = null ) {
		
		if(substr($this->config->index_file, strlen($this->config->index_file)-1, 1) != "/" 
			&& strlen(trim($this->config->index_file)) > 0 )
			$this->config->index_file .= "/";
		$location = str_replace( "//", "/",  $this->config->index_file . $path );
		$base_url = $this->config->base_url();
		if( $secure === true )
			$base_url = preg_replace('/^http\:/i', 'https:', $base_url);
		else if( $secure === false )
			$base_url = preg_replace('/^https\:/i', 'http:', $base_url);
		return  $base_url . $location;
	}

    public function slocation( $path ) {
        $this->location($path, true);
    }
	
	public function assets( $path = NULL ) {
		$location = str_replace( "//", "/", $this->config->assets_folder . "/" . $path );
		return $this->config->base_url() . $location;		
	}
	
	public function library( $path = NULL ) {
		@include_once( APPLIBRARY . $path );
	}
	
	public function js_baseurl() {
		return '<script type="text/javascript">var base_url = \'' . $this->location() . '\';</script>';
	}
}