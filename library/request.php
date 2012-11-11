<?php 
class request {
	
	public function __construct() {
			
	}
	
	public function post($var) {
		return $_POST[$var];
	}
	
	public function get($var) {
		return $_GET[$var];
	}
	
	public function request($var) {
		return $_REQUEST[$var];
	}
}