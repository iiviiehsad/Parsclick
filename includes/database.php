<?php
require_once(LIB_PATH . DS . "config.php");

/**
 * Class MySQLDatabase is created to use MySQL database and has only functions related to MySQL
 */
class MySQLDatabase {

	private $connection;
	public  $last_query;
	private $magic_quotes_active;
	private $real_escape_string_exists;

	/**
	 * Constructor will open the connection automatically whe the class is called or instantiated.
	 */
	function __construct() {
		$this->open_connection();
		$this->magic_quotes_active       = get_magic_quotes_gpc();
		$this->real_escape_string_exists = function_exists("mysqli_real_escape_string");
	}

	/**
	 * This will open the connection
	 */
	public function open_connection() {
		$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if(!$this->connection) {
			die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . " )");
		} else {
			$db_select = mysqli_select_db($this->connection, DB_NAME);
			if(!$db_select) {
				die("Database selection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . " )");
			}
		}
	}

	/**
	 * This will close the connection
	 */
	public function close_connection() {
		if(isset($this->connection)) {
			mysqli_close($this->connection);
			unset($this->connection);
		}
	}

	/**
	 * @param $sql string will get the SQL query
	 * @return bool|mysqli_result will return the result if the SQL query is OK
	 */
	public function query($sql) {
		$this->last_query = $sql;
		$result           = mysqli_query($this->connection, $sql);
		$this->confirm_query($result);
		return $result;
	}

	/**
	 * @param $value string will get the value and prepare it to put in MySQL
	 * @return string will return MySQL input got from somewhere else
	 */
	public function escape_value($value) {
		if($this->real_escape_string_exists) { // PHP v4.3.0+
			// undo any magic quote effects so mysqli_real_escape_string can do the work
			if($this->magic_quotes_active) {
				$value = stripslashes($value);
			}
			$value = mysqli_real_escape_string($this->connection, $value); // PHP v5.0+
		} else { // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			if(!$this->magic_quotes_active) {
				$value = addslashes($value);
			} // if magic quotes are active, then the slashes already exist
		}
		return $value;
	}

	/**
	 * @param $result_set
	 * @return array|null associative array
	 */
	public function fetch_assoc($result_set) {
		return mysqli_fetch_assoc($result_set);
	}

	/**
	 * @param $result_set
	 * @return int number of rows
	 */
	public function num_rows($result_set) {
		return mysqli_num_rows($result_set);
	}

	/**
	 * @return int|string gets the last id inserted over the current db connection
	 */
	public function insert_id() {
		return mysqli_insert_id($this->connection);
	}

	/**
	 * @return int how many rows were affected by the last query
	 */
	public function affected_rows() {
		return mysqli_affected_rows($this->connection);
	}

	/**
	 * @param $result boolean checks if the query is OK
	 */
	private function confirm_query($result) {
		if(!$result) {
			$output = "Database query failed! " . mysqli_error($this->connection) . "<br /><br />";;
			$output .= "Last SQL Query: " . $this->last_query;
			die($output);
		}
	}
}

/**
 * Class PostgresSQLDatabase is created to use PostgresSQL database and has only functions related to PostgresSQL
 */
class PostgresSQLDatabase {

	private $connection;
	public  $last_query;
	private $magic_quotes_active;
	private $real_escape_string_exists;

	/**
	 * Constructor will open the connection automatically whe the class is called or instantiated.
	 */
	function __construct() {
		$this->open_connection();
		$this->magic_quotes_active       = get_magic_quotes_gpc();
		$this->real_escape_string_exists = function_exists("pg_escape_string");
	}

	/**
	 * This will open the connection
	 */
	public function open_connection() {
		$this->connection =
				pg_connect("host=" . PG_SERVER . " port=" . PG_PORT . " dbname=" . PG_NAME . " user=" . PG_USER . " password=" .
				           PG_PASS);
		if(!$this->connection) {
			die("Database connection failed!");
		}
	}

	/**
	 * This will close the connection
	 */
	public function close_connection() {
		if(isset($this->connection)) {
			pg_close($this->connection);
			unset($this->connection);
		}
	}

	/**
	 * @param $sql string will get the SQL query
	 * @return bool|mysqli_result will return the result if the SQL query is OK
	 */
	public function query($sql) {
		$this->last_query = $sql;
		$result           = pg_query($this->connection, $sql);
		$this->confirm_query($result);
		return $result;
	}

	/**
	 * @param $value string will get the value and prepare it to put in MySQL
	 * @return string will return MySQL input got from somewhere else
	 */
	public function escape_value($value) {
		if($this->real_escape_string_exists) { // PHP v4.3.0+
			// undo any magic quote effects so mysqli_real_escape_string can do the work
			if($this->magic_quotes_active) {
				$value = stripslashes($value);
			}
			$value = pg_escape_string($this->connection, $value); // PHP v5.0+
		} else { // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			if(!$this->magic_quotes_active) {
				$value = addslashes($value);
			} // if magic quotes are active, then the slashes already exist
		}
		return $value;
	}

	/**
	 * @param $result_set
	 * @return array|null associative array
	 */
	public function fetch_assoc($result_set) {
		return pg_fetch_assoc($result_set);
	}

	/**
	 * @param $result_set
	 * @return int number of rows
	 */
	public function num_rows($result_set) {
		return pg_num_rows($result_set);
	}

	/**
	 * @return int|string gets the last id inserted over the current db connection
	 */
	public function insert_id() {
		return pg_last_oid($this->last_query);
	}

	/**
	 * @return int how many rows were affected by the last query
	 */
	public function affected_rows() {
		return pg_affected_rows($this->connection);
	}

	/**
	 * @param $result boolean checks if the query is OK
	 */
	private function confirm_query($result) {
		if(!$result) {
			$output = "Database query failed!";;
			$output .= "Last SQL Query: " . $this->last_query;
			die($output);
		}
	}
}

$database = new MySQLDatabase();
//$database = new PostgresSQLDatabase();

// Reference the $database to $db so we can use $db or $database anyone we want
$db =& $database;
?>
?>
