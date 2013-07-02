<?php defined('PANADA') or die('Can\'t access directly!');
/**
 * Panada Database Driver Wrapper.
 *
 * @package	Panada
 * @subpackage	Library
 * @originalauthor	Iskandar Soesman.
 * @author	Aryo Pinandito
 * @since	Version 0.3
 */

class db implements interface_database {
    
    private $db;
    private $config;
    
    public function __construct( $connection = 'default' ){   
        $this->config = config::instance();
        require_once GEAR . 'library/database/' . $this->config->db->$connection->driver . '.php';
        $class_name = 'database_'.$this->config->db->$connection->driver;
        $this->db = new $class_name( $this->config->db->$connection, $connection );
    }
    
	// informational templates	
	public function getVersion() {
		return $this->db->getVersion();
	}
	public function getLastInsertId() {
		return $this->db->getLastInsertId();
	}
	public function getLastError() { 
		return $this->db->getLastError();
	}	
	
	
	// manual query template
	public function query( $sql ) {
		return $this->db->query( $sql );	
	}
	 
	// non query template
	public function insert( $table, $values = array() ) {
		return $this->db->insert( $table, $values );
	}
	public function delete( $table, $where ) {
		return $this->db->delete( $table, $where );
	}
	public function update( $table, $values, $where ) {
		return $this->db->update( $table, $values, $where );
	}
	public function replace( $table, $values = array() ) {
		return $this->db->replace( $table, $values );
	}
	
	// query template
	public function getVar( $query ) { 
		return $this->db->getVar( $query );
	}
	public function getRow( $query ) { 
		return $this->db->getRow( $query );
	}
	public function getResults( $query ) {
		return $this->db->getResults( $query );	
	}

	// transaction templates
    public function begin() { 
		$this->db->begin();	
	}
    public function commit() { 
		$this->db->commit();
	}
    public function rollback() {
		$this->db->rollback();	
	}
	
	// functional templates
	public function escape( $data ) {
		return $this->db->escape( $data );
	}
		
	// drivers specific implementation
	// in function call, variables getter and setter
	public function __call($name, $arguments){    
        return call_user_func_array( array( $this->db, $name ), $arguments );
    }
    public function __get($name){
        return $this->db->$name;
    }
    public function __set($name, $value){
        $this->db->$name = $value;
    }
		    
} // End of database wrapper class
