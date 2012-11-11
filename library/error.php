<?php

class error {
	
	static public $instance;
	private $message;
	
	function __construct( $message = NULL ) {
		$this->message = $message;
	}
	
	static public function instance( $message = NULL ) {
		if( !self::$instance )
			self::$instance = new error( $message );
		return self::$instance;
	}
		
	public function show( $message = NULL, $code = 500, $template = "500" ) {
		if( $message ) 
			$this->message = $message;
		@header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, $code);
		if( is_readable( VIEW . "error/" . $template . ".php" ) ) {
			$panada = panada::instance();
			$panada->view( "error/" . $template . ".php", array( "message" => $this->message ) );
		} else 
			echo $this->message;
		exit;	
	}

	public function notfound( $message = NULL, $code = 404, $template = "404" ) {
		if( $message ) 
			$this->message = $message;
		@header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, $code);
		if( is_readable( VIEW . "error/" . $template . ".php" ) ) {
			$panada = panada::instance();
			$panada->view( "error/" . $template . ".php", array( "message" => $this->message ) );
		} else 
			echo $this->message;
		exit;
	}	
	
	static public function database( $message = NULL, $code = 500 ) {
		@header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, $code);
		echo "Database Error: " . $message;
		exit;
	}
		
	static public function get_caller($offset = 1) {
        
		$caller = array();
        $bt = debug_backtrace(false);
		$bt = array_slice($bt, $offset);
        $bt = array_reverse( $bt );
	
        foreach ( (array) $bt as $call ) {
	    
			if ( ! isset( $call['class'] ) )  continue;
			if ( @$call['class'] == __CLASS__ ) continue;
			$function = $call['class'] . '->'.$call['function'];
			if( isset($call['line']) ) $function .= ' line '.$call['line'];	    
            $caller[] = $function;
        }

        return implode( ', ', $caller );
    }
	
}