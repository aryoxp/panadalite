<?php

class autoloader {
	public function __construct() {
		spl_autoload_register(array($this, 'library_loader'));
		spl_autoload_register(array($this, 'app_library_loader'));
		spl_autoload_register(array($this, 'controller_loader'));
		spl_autoload_register(array($this, 'model_loader'));
	}

	private function library_loader( $className ) {
		
		// convert the given class name to it's path
		$classPath = trim( str_replace("_", "/", $className), "/" );
		@include_once LIBRARY . $classPath . '.php';
		
	}

	private function app_library_loader( $className ) {
		
		// convert the given class name to it's path
		$classPath = trim( str_replace("_", "/", $className), "/" );
		@include_once APPLIBRARY . $classPath . '.php';
		
	}

	private function controller_loader( $className ) {
		
		// convert the given class name to it's path
		$classPath = trim( str_replace("_", "/", $className), "/" );
		@include_once CONTROLLER . trim( strstr( $classPath, "/" ), "/" ) .'.php';
		
	}

	private function model_loader( $className ) {
		
		// convert the given class name to it's path
		$classPath = trim( str_replace("_", "/", $className), "/" );
		@include_once MODEL . trim( strstr( $classPath, "/" ), "/" ) .'.php';
		
	}
	
	public static function register($loader) {
		spl_autoload_register($loader);	
	}
}

?>