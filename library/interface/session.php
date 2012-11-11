<?php defined('PANADA') or die('Can\'t access directly!');

interface interface_session {
	
	// session variables getter and setter 
	public function set( $name, $value = NULL );
	public function get( $name );
	
	// remove/unset session variable(s)
	public function remove( $name );
	
	// completely destroy session
	public function destroy();
	
	// set no session
	public function nocache();
	
}