<?php
/**
 * smplPDO - PHP Mysql Database Helper Class
 *
 * smplPDO is a simple and light-weight class written to extend PHP Data Objects (PDO) 
 * with extremely useful features. Shorthand methods packed with it can reduce the 
 * amount of duplicate code and increase readability of your scripts as well as improve 
 * security and performance with automatically preparing & executing prepared statements.
 *
 * @package smplPDO
 * @version 1.1
 * @author Conrad Warhol
 * @link http://codecanyon.net/item/smplpdo-mysql-database-helper-class/3359730
 * @copyright 2012 Conrad Warhol
 *
 */

class smplPDO extends PDO { 
	
	#### VERSION ####
	
	/**
	 * smplPDO Version
	 * @var string
	 */	
	private $smplPDO_ver='1.1';
	
	/**
	 * PHP version required.
	 * @var string
	 */	
	private $php_ver='5.2';
	
	#### DATABASE CREDENTIALS ####
	
	/**
	 * Database Host.
	 * @var string
	 */		
	private $db_host='localhost';
	
	/**
	 * Database Name.
	 * @var string
	 */	
	private $db_name='';
	
	/**
	 * Database User.
	 * @var string
	 */	
	private $db_user='';
	
	/**
	 * Database Password.
	 * @var string
	 */	
	private $db_pass='';
	
	#### MISC. PROPERTIES #### 
	
	/**
	 * Holds catched errors.
	 * @var string
	 */
	public $error=null;
	
	/**
	 * Holds last insert ID.
	 * @var integer
	 */	
	public $insert_id=null;

	/**
	 * Holds number of rows affected.
	 * @var integer
	 */
	public $num_rows=0;

	/**
	 * field=>value pair of bind parameters.
	 * @var array
	 */
	public $bind=array();

	/**
	 * SQL query to execute.
	 * @var array
	 */
	public $sql=null;	
	
	/**
	 * ORDER BY for get methods.
	 * @var string
	 */
	public $order_by=null;		
	
	/**
	 * GROUP BY for get methods.
	 * @var string
	 */
	public $group_by=null;	
	
	/**
	 * LIMIT for get methods.
	 * @var string
	 */
	public $limit=null;	
	
	/**
	 * Result of the executed query.
	 * @var mixed
	 */
	public $result=null;
		
	/**
	 * Class Constructor. Establish a database connection with PDO. 
	 *
	 * Constructor accepts the same arguments with PDO as suggested. Alternatively you may
	 * initiate the connection using class properties. 
	 * 
	 * @param string $dsn Contains the information required to connect to the database. 
	 * @param string $username The user name for the DSN string. 
	 * @param string $password The password for the DSN string. 
	 * @param array $driver_options A key=>value array of driver-specific connection options. 
	 * 
	 */
	public function __construct( $dsn='', $username='', $password='', $driver_options=array() ) { 
		
		// Check if PDO extension is installed.
		if ( ! defined( 'PDO::ATTR_DRIVER_NAME' ) ) {
			$this->error = "smplPDO requires PDO extension.";
			exit( $this->error );
		}
		
		// Check if PHP version is compatible.
		if( version_compare( PHP_VERSION, $this->php_ver, '<') ) {
			$this->error = "smplPDO requires PHP version $this->php_ver and higher.";
			exit( $this->error );
		}
		
		// Set credentials from class properties if arguments are missing.
		if( empty( $dsn ) ) $dsn="mysql:host=$this->db_host;dbname=$this->db_name";
		if( empty( $username ) ) $username=$this->db_user;
		if( empty( $password ) ) $password=$this->db_pass;
		
		// Set driver options if not provided.
		if( empty( $driver_options ) ) { 
			$driver_options=array( 
				PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC, // Fetch as an associative array by default.
				PDO::ATTR_PERSISTENT=>true, // Keep the connection to improve performance.
				PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, // You may use PDO::ERRMODE_SILENT on production server. 
				PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES \'UTF8\'', // This is needed when working with utf-8.
			);
		}
				
		// Try to establish a database connection.
		try { 
			parent::__construct( $dsn, $username, $password, $driver_options ); 
		} catch ( PDOException $e ) {
			// You may prefer to fail silently on a production server.
			exit( $e->getMessage() );
		}
	}
	
	/**
	 * Run an SQL query over the current connection. 
	 *
	 * @param string $dsn Contains the information required to connect to the database. 
	 * @param array $driver_options A key=>value array of driver-specific connection options. 
	 * 
	 */	
	public function run( $sql=null, $bind=array() ) {
				
		// Flush the stored values.
		$this->flush();
		
		// Override arguments if supplied.
		if( !empty( $sql ) ) $this->sql=$sql;
		if( !empty( $bind ) ) $this->bind=$bind;
		
		// Stop if we don't have a query to execute.
		if( empty( $this->sql ) ){ 
			$this->error='No query to execute!';
			$this->result=false;
			return false;
		}
		
		// Try executing the query.
		try {
			$stmt=$this->prepare( $this->sql );
			if( false === $stmt->execute( $this->bind ) ) {
				// Query failed.
				$this->result=false;
			} else if( preg_match( "/^(insert|delete|update|replace|drop|create)\s+/i", $this->sql ) ) {
				if( preg_match( "/^(insert|replace)\s+/i", $this->sql ) ) {
					// Query was an INSERT. Store the last insert id.
					$this->insert_id=@$this->lastInsertId();
				}
				// Store the number of rows affected.
				$this->num_rows=@$stmt->rowCount();				
				$this->result=$this->num_rows;
			} else {
				// Query was a SELECT, return a PDO object to allow fetching.
				return $stmt;
			}
		} catch ( PDOException $e ) {
			// Store the error message thrown.
			$this->error=$e->getMessage();
			$this->result=false;
		}		
		return $this->result;
	}
	
	/**
	 * Shorthand INSERT method. 
	 *
	 * @param string $table Database table to insert
	 * @param array $data A field=>value array of the data to insert
	 * 
	 */		
	public function insert( $table=null, $data=array() ) {
		
		// Check if arguments are appropriate	
		if( empty( $table ) ) return false; 
		if( empty( $data ) || ! is_array( $data ) ) return false; 
		
		// Make the bind array.
		$bind=array();		
		foreach( array_keys( $data ) as $dn=>$dk ) {
			$bind[':' . $dk]=$data[$dk];
		}
		
		// Build the SQL Query.
		$sql="INSERT INTO `$table` (`" . implode( '`,`', array_keys( $data ) ) . 
			"`) VALUES (:" . implode( ', :', array_keys( $data ) ) . ");";
		
		// Run the query and return the result.
		return $this->run( $sql, $bind );
	}
	
	/**
	 * Shorthand UPDATE method. 
	 * 
	 * @param string $table Database table to update
	 * @param array $data A field=>value array of the data to set
	 * @param array $where A field=>value array of where clause
	 * 
	 */		
	public function update( $table=null, $data=array(), $where=array() ) {
		
		// Check if arguments are appropriate.	
		if( empty( $table ) ) return false; 
		if( empty( $data ) || ! is_array( $data ) ) return false; 
		if( empty( $where ) || ! is_array( $where ) ) return false;
		
		// Build the SQL Query and prepare bind parameters.
		$bind=array();	
		$sql="UPDATE `$table` SET ";		
		
		// SET part.
		foreach( array_keys( $data ) as $sn=>$sf ) {
			$bind[':set_' . $sf]=$data[$sf];
			$sql.="`$sf`=:set_$sf";
			if( $sn<count( $data ) - 1 ) $sql.=', ';
		}
		
		$sql.=' WHERE ';
		
		// WHERE part.
		foreach( array_keys( $where ) as $wn=>$wf ) {
			$bind[':where_' . $wf ]=$where[$wf];
			$sql.="`$wf`=:where_$wf";
			if( $wn<count( $where ) - 1 ) $sql.=' AND ';
		}
	
		$sql.=';'; // Be nice.	
		
		// Run the query and return the result.
		return $this->run( $sql, $bind );
	}
	
	/**
	 * 
	 * Shorthand DELETE method. 
	 * 
	 * @param string $table Database table to delete from
	 * @param array $where A field=>value array of where clause
	 * 
	 */		
	public function delete( $table=null, $where=array() ) {
		
		// Check if arguments are appropriate.
		if( empty( $table ) ) return false;
		if( empty( $where ) || ! is_array( $where ) ) return false;
		
		// Build the SQL Query and prepare bind parameters.
		$bind=array();
		$sql="DELETE FROM `$table` WHERE ";
		
		// WHERE part.
		foreach( array_keys( $where ) as $wn=>$wf ) {
			$bind[':' . $wf]=$where[$wf];
			$sql.="`$wf`=:$wf";
			if( $wn<count( $where ) - 1 ) $sql.=' AND ';
		}
	
		$sql.=';'; // Be nice.
		
		// Run the query and return the result.
		return $this->run( $sql, $bind );
	}
	
	/**
	 * 
	 * Shorthand method to check if a record exists. 
	 * 
	 * @param string $table Database table to check
	 * @param array $where A field=>value array of where clause
	 * 
	 */		
	public function exists( $table=null, $where=array() ) {
	
		// Check if arguments are appropriate.
		if( empty( $table ) ) return false;
		if( empty( $where ) || ! is_array( $where ) ) return false;			
		
		// Build the SQL Query and prepare bind parameters.
		$bind=array();		
		$sql="SELECT COUNT(*) FROM `$table` WHERE ";		
		
		// WHERE part.
		foreach( array_keys( $where ) as $wn=>$wf ) {
			$bind[':' . $wf]=$where[$wf];
			$sql.="`$wf`=:$wf";
			if( $wn<count( $where ) - 1 ) $sql.=' AND ';
		}
		
		$sql.=';'; // Be nice.
		
		// Run the query and return the result.
		$stmt=$this->run( $sql, $bind );
		
		if( ! is_object( $stmt ) ) {
			$this->result=false;
			return false;
		}
		
		$this->result=$stmt->fetchColumn();
		// TRUE if result is not empty.
		if( !empty( $this->result ) ) return true;
		// Record doesn't exist.
		return false;
	}
	
	/**
	 * 
	 * Shorthand get method to fetch all matching records
	 * 
	 * @param string $table Database table to fetch from
	 * @param array $where A field=>value array of where clause
	 * @param string $fields Fields to include in result
	 * 
	 */		
	public function get_all( $table=null, $where=array(), $fields='*' ) {
		return $this->get( $table, $where, $fields, 'all' );
	}
	
	/**
	 * 
	 * Shorthand get method to fetch a single row
	 * 
	 * @param string $table Database table to fetch from
	 * @param array $where A field=>value array of where clause
	 * @param string $fields Fields to include in result
	 * 
	 */		
	public function get_row( $table=null, $where=array(), $fields='*' ) {
		return $this->get( $table, $where, $fields, 'row' );
	}
	
	/**
	 * 
	 * Shorthand get method to fetch a column as numerical array
	 * 
	 * @param string $table Database table to fetch from
	 * @param array $where A field=>value array of where clause
	 * @param string $fields Fields to include in result
	 * 
	 */		
	public function get_col( $table=null, $where=array(), $fields='*' ) {
		return $this->get( $table, $where, $fields, 'col' );
	}
	
	/**
	 * 
	 * Shorthand get method to fetch a single variable
	 * 
	 * @param string $table Database table to fetch from
	 * @param array $where A field=>value array of where clause
	 * @param string $fields Fields to include in result
	 * 
	 */		
	public function get_var( $table=null, $where=array(), $field=null ) {
		return $this->get( $table, $where, $field, 'var' );
	}
	
	/**
	 * 
	 * get_* methods helper. Runs a SELECT query on the database.
	 * 
	 * @param string $table Database table to fetch from
	 * @param array $where A field=>value array of where clause
	 * @param string $fields Fields to include in result
	 * 
	 */
	public function get( $table=null, $where=array(), $fields='*', $type='all' ) {
		
		// Check if arguments are appropriate.
		if( empty( $table ) ) return false;
		if( empty( $where ) || ! is_array( $where ) ) $where='1';
		if( empty( $fields ) ) $fields = '*';
		
		// Build the SQL Query and prepare bind parameters.
		$bind=array();
		
		// FIELDS part
		if( is_array( $fields ) ) {
			$fields='`' . implode( '`,`', $fields ) . '`';
		} 
				
		$sql="SELECT $fields FROM `$table` WHERE ";
		
		// WHERE part.
		if( is_array( $where ) ) {
			foreach( array_keys( $where ) as $wn=>$wf ) {
				$bind[":" . $wf]=$where[$wf];
				$sql.="`$wf`=:$wf";
				if( $wn<count( $where ) - 1 ) $sql.=" AND ";
			}
		} else {
			$sql.=$where; // We're fetching everything.
			$bind=null; // Nothing to bind.
		}
		
		// Do we have group by, order by or limit/offset to append?
		if( ! empty( $this->group_by ) ) $sql .= " GROUP BY $this->group_by";		
		if( ! empty( $this->order_by ) ) $sql .= " ORDER BY $this->order_by";
		if( ! empty( $this->limit ) ) $sql .= " LIMIT $this->limit";
		
		$sql.=';'; // Be nice...
		
		// Run the query.
		$stmt=$this->run( $sql, $bind );
		
		if( ! is_object( $stmt ) ) {
			$this->result=false;
			return false;
		} 
		
		if( $type == 'var' ) {
			$this->result=$stmt->fetchColumn(); // Fetch a single variable.
		} else if( $type == 'row' ) {
			$this->result=$stmt->fetch(); // Fetch a single row.
		} else if( $type == 'col' ) {
			$this->result=$stmt->fetchAll( PDO::FETCH_COLUMN, 0 ); // Fetch a married column.
		} else {
			$this->result=$stmt->fetchAll(); // Fetch everything.
		}
		
		// Store the number of rows returned.
		$this->num_rows=is_array( $this->result ) ? count( $this->result ) : 1;
		
		// Return results.
		return $this->result;
	}
	
	/**
	 * 
	 * Get the count of matching records.
	 * 
	 * @param string $table Database table to fetch from
	 * @param array $where A field=>value array of where clause
	 * 
	 */			
	public function get_count( $table=null, $where=array() ) {
	
		// Check if arguments are appropriate.
		if( empty( $table ) ) return false;
		if( empty( $where ) || ! is_array( $where ) ) $where='1';
		
		// Build the SQL Query and prepare bind parameters.
		$bind=array();		
		
		$sql="SELECT COUNT(*) FROM `$table` WHERE ";
		
		// WHERE part.
		if( is_array( $where ) ) {
			foreach( array_keys( $where ) as $wn=>$wf ) {
				$bind[":" . $wf]=$where[$wf];
				$sql.="`$wf`=:$wf";
				if( $wn<count( $where ) - 1 ) $sql.=" AND ";
			}
		} else {
			$sql.=$where; // We're fetching everything.
			$bind=null; // Nothing to bind.
		}
		
		$sql.=';'; // Be nice...
		
		// Run the query.
		$stmt=$this->run( $sql, $bind );
		
		if( ! is_object( $stmt ) ) {
			$this->result=false;
			return false;
		}
		
		// Fetch the result.
		$this->result=$stmt->fetchColumn();
		
		// Return an integer.
		return (int) $this->result;
	}
	
	/**
	 * 
	 * Simple debugger to print out stored class variables.
	 * Can be removed safely on production server.
	 * 
	 * @param string $table Database table to fetch from
	 * @param array $where A field=>value array of where clause
	 * @param string $fields Fields to include in result
	 * 
	 */		
	public function debug() {
		
		echo '<pre>';
		echo 'smplPDO - PHP Mysql Database Helper Class - v' . $this->smplPDO_ver . "\r\n\r\n";
		
		if( !empty( $this->sql ) ) {	
			echo "Last Query: \r\n$this->sql \r\n\r\n";	
			if( !empty( $this->error ) ) echo "Error Catched: \r\n$this->error \r\n\r\n";					
			if( !empty( $this->num_rows ) ) echo "Number of Rows: \r\n$this->num_rows \r\n\r\n";
			if( !empty( $this->insert_id ) ) echo "Last Insert ID: \r\n$this->insert_id \r\n\r\n";
			echo "Last Result: \r\n";
				var_dump( $this->result );
		} else {
			echo "No executed query to show!.\r\n";
		}
		
		echo "\r\nCopyright (c) 2012 - Conrad Warhol - conradwarhol@gmail.com";
		echo '</pre>';
	}
	
	/**
	 * Set properties to null.
	 * 
	 */	
	private function flush() {
		$this->order_by=null;
		$this->group_by=null;
		$this->limit=null;
		$this->result=null;
		$this->error=null;
		$this->insert_id=null;
		$this->num_rows=0;	
	}
}