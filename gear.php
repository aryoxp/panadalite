<?php
// define that we're in Panada!
if (!defined('PANADA')) define('PANADA', true);

$GLOBALS['microtime_start'] = microtime(true);

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

$config		= config::instance();
date_default_timezone_set($config->default_timezone);

// instantiate the uri parser object, getting the controller and method
$uri = new uri();	
$controller = $uri->getController();
$method = $uri->getMethod();

// try to instantiate the controller and execute it's selected method
try {

	if ( !$controller ) {
	
		// invalid controller
		$error = new error( "Invalid controller or controller can not be found." );
		$error->show();
		exit;	
	} 

	// instantiate the controller
	$P = new $controller();	
	
	if( method_exists( $controller, $method ) ) {	
	
		// execute method
		call_user_func_array( array($P, $method), $uri->getArgs() ); 
		
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
