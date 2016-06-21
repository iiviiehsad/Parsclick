<?php //namespace Parsclick;

class MySQLDatabase implements Database
{
	public  $last_query;
	private $connection;
	private $magic_quotes_active;
	private $real_escape_string_exists;

	/**
	 * Constructor will open the connection automatically whe the class is called or instantiated.
	 */
	public function __construct()
	{
		$this->open_connection();
		$this->magic_quotes_active       = get_magic_quotes_gpc();
		$this->real_escape_string_exists = function_exists('mysqli_real_escape_string');
	}

	/**
	 * This will open the connection
	 */
	public function open_connection()
	{
		$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		mysqli_set_charset($this->connection, 'latin1');
		$this->connection->set_charset('latin1');
		if ( ! $this->connection) {
			die('Database connection failed: ' . mysqli_connect_error() . ' (' . mysqli_connect_errno() . ' )');
		} else {
			$db_select = mysqli_select_db($this->connection, DB_NAME);
			if ( ! $db_select) {
				die('Database selection failed: ' . mysqli_connect_error() . ' (' . mysqli_connect_errno() . ' )');
			}
		}
	}

	/**
	 * This will close the connection
	 */
	public function close_connection()
	{
		if (isset($this->connection)) {
			mysqli_close($this->connection);
			unset($this->connection);
		}
	}

	/**
	 * Will get the SQL query and
	 * return the result if the SQL query is OK
	 *
	 * @param $sql string
	 * @return bool|mysqli_result
	 */
	public function query($sql)
	{
		$this->last_query = $sql;
		// mysqli_begin_transaction($this->connection);
		$result = mysqli_query($this->connection, $sql);
		// mysqli_commit($this->connection);
		// mysqli_close($this->connection);
		$this->confirm_query($result);

		return $result;
	}

	/**
	 * Checks if the query is OK
	 *
	 * @param $result boolean
	 */
	private function confirm_query($result)
	{
		if ( ! $result) {
			$ip1 = '127.0.0.1';
			$ip2 = '::1';
			if ($_SERVER['REMOTE_ADDR'] == $ip1 || $_SERVER['REMOTE_ADDR'] == $ip2) {
				$output1 = 'Database query failed! ' . mysqli_error($this->connection) . '<br/><br/>';
				$output2 = 'Last SQL Query: ' . $this->last_query;
				$output  = warning($output1, $output2);
			} else {
				$output1 = 'اوخ!';
				$output2 = 'درخواست شما ناقص یا ناهنجار است.';
				$output  = warning($output1, $output2);
			}
			die($output);
		}
	}

	/**
	 * Get the value and prepare it to put in MySQL and
	 * will return MySQL input got from somewhere else
	 *
	 * @param $value string
	 * @return string
	 */
	public function escape_value($value)
	{
		if ($this->real_escape_string_exists) { // PHP v4.3.0+
			// undo any magic quote effects so mysqli_real_escape_string can do the work
			if ($this->magic_quotes_active) {
				$value = stripslashes($value);
			}
			$value = mysqli_real_escape_string($this->connection, $value); // PHP v5.0+
		} else { // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			if ( ! $this->magic_quotes_active) {
				$value = addslashes($value);
			} // if magic quotes are active, then the slashes already exist
		}

		return $value;
	}

	/**
	 * @param $result_set
	 * @return array|null associative array
	 */
	public function fetch_assoc($result_set)
	{
		return mysqli_fetch_assoc($result_set);
	}

	/**
	 * @param $result_set
	 * @return int number of rows
	 */
	public function num_rows($result_set)
	{
		return mysqli_num_rows($result_set);
	}

	/**
	 * Gets the last id inserted over the current db connection
	 *
	 * @return int|string
	 */
	public function insert_id()
	{
		return mysqli_insert_id($this->connection);
	}

	/**
	 * How many rows were affected by the last query
	 *
	 * @return int
	 */
	public function affected_rows()
	{
		return mysqli_affected_rows($this->connection);
	}

} // END of CLASS

