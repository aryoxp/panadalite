<?php defined('PANADA') or die('Can\'t access directly!');

interface interface_database {
	
	// informational templates
	public function getVersion();
	public function getLastInsertId();
	public function getLastError();
	
	// manual query template
	public function query( $sql );
	 
	// non query template
	public function insert( $table, $values = array() );
	public function delete( $table, $where );
	public function update( $table, $values, $where );
	public function replace( $table, $values = array() );
	
	// query template
	public function getVar( $query );
	public function getRow( $query );
	public function getResults( $query );

	// transaction templates
    public function begin();
    public function commit();
    public function rollback();
	
	// functional templates
	public function escape( $data );
	public function testConnect();
	public function testSelectDb();
	public function getError();
	
}
