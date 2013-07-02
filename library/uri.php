<?php 
defined('PANADA') or die('Can\'t access directly!');

class uri {

	private $uristring;
	private $fulluristring;
	private $controller;
	private $method;
	private $args;
	
	public static $instance;

    public function __construct(){

		$this->config		= config::instance();
		$this->fulluristring 	= $_SERVER['REQUEST_URI'];
		//var_dump($_SERVER['REQUEST_URI']); echo "<br>";
		$path = NULL;
		if(isset( $_SERVER['PATH_INFO'] )) $path = $_SERVER['PATH_INFO'];
		else {
			$prepath = str_replace("/index.php", "", $_SERVER['SCRIPT_NAME']);
			$path = str_replace($prepath, "", $_SERVER['REQUEST_URI']);
		} 
		$path = trim( $path, "/" ); // remove trailing and heading slash if any

		$this->uristring 		= $path;				
		$trailsegments = array(); // as arguments array storage

        $segments = explode( "/", $this->uristring );
		
		//echo "<pre>"; var_dump($path);exit;
		
		if( count( $segments ) && trim($segments[0]) != "" ) {
			$i = 0;
			while( count($segments) > 0 ) {
				
				/*
				echo "Iteration: ".($i+1)." : ";
				var_dump(implode("/",$segments));
				echo "<br />";
				*/
				
				$controller = CONTROLLER . implode("/", $segments);
				
				/*
				echo "checking: " . $controller . "/" . $this->config->default_controller . ".php : "; 
				var_dump( file_exists( $controller . "/" . $this->config->default_controller . ".php" ) ); 
                echo "<br />"; 
				//exit;
				*/
				
				if ( file_exists( $controller . "/" . $this->config->default_controller . ".php" ) && $i == 0 ) {
					$this->controller = implode("/", $segments) . "/" . $this->config->default_controller;
					break;
				} else {				

					/*
					echo "checking: " . $controller . ".php : "; 
					var_dump( file_exists( $controller . ".php" ) ); 
					echo "<br />";
					*/
					
					if ( file_exists( $controller . ".php" ) ) {
						$this->controller = implode("/", $segments);
						break;
					}
				}
				
				// stores unused trailing segments to be used as method and it's arguments
				$trailsegments[] = array_pop( $segments );
				$i++;
				
			}
			
			/*
			if(!$this->controller) {
				$this->controller = $this->config->default_controller;
				array_pop( $segments );
			}
			*/
				
		} else $this->controller = $this->config->default_controller;
		
		/*
		echo "Segments: ";
		var_dump($segments);
		echo "<br />";
		echo "Trailing Segments: ";
		var_dump($trailsegments);
		*/
		
		if($this->controller)
			$this->controller = "controller_" . str_replace( "/", "_",  $this->controller );
		else $this->controller = NULL;

		if( !$this->method = array_pop( $trailsegments ) )
			$this->method = $this->config->default_method;
			
		if( !$this->args = array_reverse( $trailsegments ) ) 
			$this->args = array();

    }

	public static function instance() {
		if( !self::$instance )
			self::$instance = new uri();
		return self::$instance;
	}

    public function getUriString(){
		return $this->uristring;
    }
	
	public function getFullUriString(){
		return $this->fulluristring;
	}
	
	public function getController() {
		if(@$this->config->maintenance) $this->controller = 'controller_maintenance';
		else if ($this->controller == "") {
			return NULL;
		} else if( isset( $this->config->filter_regex ) )
			$this->controller = preg_replace( $this->config->filter_regex, '', $this->controller );
		return $this->controller;
	}
	
	public function getMethod() {
		if( isset( $this->config->filter_regex ) )
			$this->method = preg_replace( $this->config->filter_regex, '', $this->method );
		
		if( method_exists($this->controller, $this->method) ) {
			return $this->method;
		} else if( method_exists($this->controller, $this->config->method_alias) ) {
			$this->args = array($this->method) + $this->args;
			$this->method = preg_replace( $this->config->filter_regex, '', $this->config->method_alias );
		}
		return $this->method;
	}
	
	public function getArgs() {
		return $this->args;
	}

} 

// end of library URI
