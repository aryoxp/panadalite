<?php

class model extends panada {
	protected $db;
	
	public function __construct( $connection = 'default' ) {
		$this->db = new db($connection);
	}	
	
}
