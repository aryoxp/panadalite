<?php

class error {
	
	static public $instance;

	private $message;
	public $database = array();
	public $common = array();
	
	function __construct( $message = NULL ) {
		$this->message[] = $message;
	}
	
	static public function instance( $message = NULL ) {
		if( !self::$instance )
			self::$instance = new error( $message );
		return self::$instance;
	}
		
	public function show( $kind = 'all', $code = NULL, $template = NULL ) {
		switch($kind) {
			case 'database':
				foreach ($this->database as $e) {
					echo '<p>'.$e.'</p>';
				}
				break;
			case 'common':
				foreach ($this->common as $e) {
					echo '<p>'.$e.'</p>';
				}
				break;
			default:
				$errors = array_merge($this->database, $this->common);
				foreach ($errors as $e) {
					echo '<p>'.$e.'</p>';
				}
				break;			
		}	
	}

	public function common($message = NULL) {
		$this->common[] = $message;
	}

	public function database( $message = NULL, $code = 500 ) {
		$this->database[] = "Database: ".$message;
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
		//exit;
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
