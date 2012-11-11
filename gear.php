<?php

$GLOBALS['microtime_start'] = microtime(true);

if(!PANADA)
	die("no PANADA definition, Panadalite will now exit.");

if(!APPLICATION)
	die("Unable to find APPLICATION directory definition, Panadalite will now exit.");


// defining system and application structure paths
define('GEAR', dirname(__FILE__) . "/");
define('LIBRARY', GEAR . 'library' . '/');
define('CONTROLLER', APPLICATION . 'controller' . '/');
define('MODEL', APPLICATION . 'model' . '/');
define('VIEW', APPLICATION . 'view' . '/');
define('APPLIBRARY', APPLICATION . 'library' . '/');

// load the autoloader class file manually...
require_once LIBRARY . 'autoloader.php';

// instantiate the autoloader object
$autoloader = new autoloader();

// instantiate the uri parser object
$uri = new uri();	
$controller = $uri->getController();
$method = $uri->getMethod();
// try to instantiate the controller and execute it's selected method
try {
	
	if( method_exists( $controller, $method ) ) {	
	
		// instantiate the controller
		$P = new $controller();
		call_user_func_array( array($P, $method), $uri->getArgs() ); 
	} else if ( !$controller ) {
	
		// invalid controller
		$error = new error( "Invalid controller or controller can not be found." );
		$error->show();
	} else {
		
		$error = new error( "Method " . $method . " not exists in " . $controller . " controller " );
		$error->show();
	}
		
} catch (Exception $e) {
	
	// catch errors in case controller fails to load
	$error = new error( $e->getMessage() );
	$error->show();
	exit;
	
}

// do cleanup here ...
exit;