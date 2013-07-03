<?php defined('PANADA') or die('Can\'t access directly!');

class database_mysql implements interface_database {

	private $db_config;

	private $port = 3306;
	private $client_flags = NULL;
	
	private $link;
	private $connection_name;
	
	private $last_query;
	private $last_error;
	private $last_insert_id;
	private $affected_rows;

	private $error; // error objects container
	
    function __construct( $config_instance, $connection_name ){
		$this->db_config = $config_instance;
		$this->connection_name = $connection_name;
		$this->error = new error();
    }
    
    private function connect(){
			
		if( $this->db_config->persistent ){
			
			$arguments = array(
				$this->db_config->host,
				$this->db_config->user,
				$this->db_config->password,
				$this->client_flags
				);
			
			$function = 'mysql_pconnect';
			
		} else {
			
			$arguments = array(
				$this->db_config->host.':'.$this->port,
				$this->db_config->user,
				$this->db_config->password,
				$this->new_link = true,
				$this->client_flags
				);
	
			$function = 'mysql_connect';
			
		}
	
		return call_user_func_array($function, $arguments);
    }
    
    private function init(){
	
	if( is_null($this->link) )
	    $this->link = $this->connect();
        $this->error->database('Unable to connect to database. '.mysql_error());
        $collation_query = '';
        
        if ( !empty($this->db_config->charset) ) {
            $collation_query = "SET NAMES '".$this->db_config->charset."'";
		    if ( !empty($this->db_config->collate) )
                $collation_query .= " COLLATE '".$this->db_config->collate."'";
		}
	
        if ( !empty($collation_query) ) $this->query( $collation_query );

        $this->selectDb( $this->db_config->database );
    }
    
    private function selectDb($dbname){
	
		if( is_null( $this->link ) )
			$this->init();
		if ( $res = @mysql_select_db( $dbname, $this->link ) ) {
			return $res;
		} else 
			$this->error->database( 'Unable to select database. '.mysql_error() );
		
    }

	// transaction sets
    public function begin(){
	$this->query("START TRANSACTION");
	$this->query("BEGIN");       
    }
    
    public function commit(){
	$this->query("COMMIT");
    }

    public function rollback(){
	$this->query("ROLLBACK");
    }
    

	// functional template
    public function escape( $string ) {        
		return addslashes( $string );
    }
    
    /**
     * Main query function
     */
    public function query($sql, $type = 'object'){
	
		if( is_null($this->link) )
		    $this->init();

        $result = mysql_query($sql, $this->link);
        $this->last_query = $sql;
        
        if ( $this->last_error = mysql_error( $this->link ) ) {
	    $this->error->database($this->last_error);
            return false;
	}
		
        if( preg_match( "/^(select|show)/i", $sql ) ) {
			while ($row = @mysql_fetch_object($result)) {            
				if($type == 'array') $return[] = (array) $row;
				else $return[] = $row;
	        }
			if( isset( $return ) ) return $return;
			else return NULL;
		} else if( preg_match( "/^(set names)/i", $sql ) ) {
			return NULL;
		} else {
			$this->affected_rows = mysql_affected_rows( $this->link );		
			return $this->affected_rows;
		}
    }
        
    public function getVar( $query ) {
        
		if($result = $this->query( $query )) {
			$result = (array) $result[0];
			$keys = array_keys( $result );
			return $result[$keys[0]];
		} else return NULL;
    }
    
    public function getRow( $query ) {	
	
		$result = $this->query( $query );
		if( count( $result ) ) {
			return $result[0];
		}
		return NULL;		
    }
    
    public function getResults( $query ) {
		
		$result = $this->query( $query );
		return $result;
		
    }
    
	public function select($table, $columns, $where = NULL) {
		
		$query = "SELECT ".implode(", ", $columns)." FROM `$table` ";
		
		if(is_array( $where )) {						 
			$ws = array();
			foreach($where as $key => $val)
				array_push($ws, "$key = '".$this->escape($val)."'");
				
			$query .= "WHERE " . implode(" AND ", $ws);
				
		} else if ($where != NULL) {
			$query .= "WHERE " . $where;
		}

		return $this->query( $query );
		
	}
	
    public function insert($table, $data = array()) {
		
        $fields = array_keys($data);
        foreach($data as $key => $val) {
            $escaped_data[$key] = $this->escape($val);
			if( $escaped_data[$key] != NULL ) $escaped_data[$key] = "'".$escaped_data[$key]."'";
			else $escaped_data[$key] = 'NULL';
		}
        
		$query = 	"INSERT INTO `$table` (`" . implode('`,`',$fields) . "`) " . 
					"VALUES (".implode(",",$escaped_data).")";
		
		$this->last_insert_id = NULL;		
		
		if( $this->affected_rows = $this->query( $query ) ) {
			$this->last_insert_id = $this->insertId();
			return $this->affected_rows;
		} 
		$this->close();
		return 0;
    }
    
    public function replace($table, $data = array()) {
        
        $fields = array_keys($data);
        foreach($data as $key => $val)
            $escaped_date[$key] = $this->escape($val);
			
		$query = 	"REPLACE INTO `$table` (`" . implode('`,`',$fields) . "`) " . 
					"VALUES ('".implode("','",$escaped_date)."')";
					
		$this->last_insert_id = NULL;
		if( $this->affected_rows = $this->query( $query ) ) {
			$this->last_insert_id = $this->insertId();
			return $this->affected_rows;
		} 
		return 0;
    }

    public function update($table, $data, $where) {
        
        foreach($data as $key => $val)
            $data[$key] = $this->escape( $val );
        
        $bits = $wheres = array();
        foreach ( (array) array_keys($data) as $k ) {
			if($data[$k] == NULL) $bits[] = "`$k` = NULL";
            else $bits[] = "`$k` = '$data[$k]'";
		}
        
		if ( is_array( $where ) ) {
			foreach ( $where as $c => $v )
				$wheres[] = "$c = '" . $this->escape( $v ) . "'";    
		    $criteria = implode( ' AND ', $wheres );
		} else $criteria = $where;
	
		$query = "UPDATE `$table` SET " . implode( ', ', $bits ) . ' WHERE ' . $criteria;
        $this->affected_rows = $this->query( $query );	
		return $this->affected_rows;
    }
    
    public function delete( $table, $where ){
	
        if ( is_array( $where ) ) {
            foreach ( $where as $c => $v )
                $wheres[] = "$c = '" . $this->escape( $v ) . "'";
		    $criteria = implode( ' AND ', $wheres );
		} else $criteria = $where;
        
        $query = "DELETE FROM `$table` WHERE " . $criteria;
		$this->affected_rows = $this->query( $query );
		return $this->affected_rows;
    }

    private function insertId() {
		$result = @mysql_insert_id( $this->link );
		return $result;
    }
	
	public function getLastInsertId() {
		return $this->last_insert_id;
	}
	    
    public function printError() {
	    if ( error_reporting() == 0 ) return false; // avoid displaying errors 
		    
        $error 	= htmlspecialchars($this->getLastError());
        $query 	= htmlspecialchars($this->last_query);
        $caller = error::get_caller(2);
		
        error::database($error.'<br /><b>Query</b>: '.$query.'<br /><b>Backtrace</b>: '.$caller);
    }
	
    public function getVersion() {
		return $this->getVar( "SELECT version() AS version" );
    }
	    
    public function close(){	
		@mysql_close( $this->link );
    }
    
	/**
	 *
	 * Description: Getting last error message of mysql query
	 * Notes:
	 * As of Panada (v0.2.1) the $getLastError() property was public, 
	 * in Panada v0.3.1 the $getLastError() property has become a private property
	 * so this function is used as an accessor (getter) for $getLastError() property.
	 * As of Panada (v0.2.1) $last_error property can be accessed by $this->db->last_error
	 * in Panada v0.3.1 the $last_error property can be accessed through this method
	 * for example: $error = $this->db->last_error();
	 *
	 * @return error message string
	 * added by Aryo Pinandito ( aryoxp@gmail.com )
	 */
	public function getLastError(){
		return $this->last_error;
	}
	
	public function testConnect(){
		if($res = $this->connect()) {
			$this->close();
			return $res;
		}
	}
	
	public function testSelectDb() {
		if($res = $this->connect()) {
			$res = $this->selectDb( $this->db_config->database );
			$this->close();
			return $res;
		}
	}
	
	public function getError() {
		return $this->error;
	}
		
} // End database_mysql Class
