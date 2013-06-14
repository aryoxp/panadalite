<?php defined('PANADA') or die('Can\'t access directly!');
/**
 * EN: Panada Lite installation configuration
 */

$CONFIG['assets_folder'] = 'assets';

/**
 * Environment and error reporting
 * valid values:
 *    development, production
 */
$CONFIG['environment'] = 'development';

/**
 * Set this value to empty string instead of 'index.php' 
 * if you wish an url without "index.php" .
 * but don't forget to configure the .htaccess file if you change this
 */ 
$CONFIG['index_file']                       = ''; 

/**
 * EN: Database configuration.
 */
$CONFIG['db']['default']['driver']          = 'mysql';
$CONFIG['db']['default']['host']            = 'localhost'; 
$CONFIG['db']['default']['user']            = 'root'; 
$CONFIG['db']['default']['password']        = ''; 
$CONFIG['db']['default']['database']        = '';
$CONFIG['db']['default']['charset']         = 'utf8';
$CONFIG['db']['default']['collate']         = 'utf8_general_ci';
$CONFIG['db']['default']['persistent']      = false;

/**
 * Session configuration.
 */
$CONFIG['session']['expiration']        = 7200; /* 2 hour. */
$CONFIG['session']['name']              = 'PAN_SID';
$CONFIG['session']['cookie_expire']     = 0;
$CONFIG['session']['cookie_path']       = '/';
$CONFIG['session']['cookie_secure']     = false;
$CONFIG['session']['cookie_domain']     = '';
$CONFIG['session']['driver']            = 'native';
$CONFIG['session']['driver_connection'] = 'default'; /* Connection name for the driver. */
$CONFIG['session']['storage_name']      = 'sessions';

/**
 * regular expression in filtering method and controller names
 * comment out the following filter_regex configuration if you don't prefer filtering out 
 * controller and method name paresed from URI string
 */
$CONFIG['filter_regex'] = "/[^a-z0-9_]/i"; 

/** 
 * set the default controller and method if not set from the URL
 */
$CONFIG['default_controller'] = 'home';
$CONFIG['default_method'] = 'index';

/**
 * session secret_key
 */
$CONFIG['secret_key'] = "tH1s/is#4+seCREt/key''couLD bE\\anyThInG";

/**
 * set up maintenance mode
 */
$CONFIG['maintenance'] = false;
$CONFIG['method_alias'] = 'alias';
