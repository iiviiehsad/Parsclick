<?php
require_once LIB_PATH . DS . 'config.php';

/**
 * Class PostgresSQLDatabase is created to use
 * PostgresSQL database and has only functions related to PostgresSQL
 */
class PostgresSQLDatabase implements Database
{
	public  $last_query;
	private $connection;
	private $magic_quotes_active;
	private $real_escape_string_exists;

	/**
	 * PostgresSQLDatabase constructor.
	 */
	public function __construct()
	{
		$this->open_connection();
		$this->magic_quotes_active       = get_magic_quotes_gpc();
		$this->real_escape_string_exists = function_exists('pg_escape_string');
	}

	/**
	 * Opens the connection
	 */
	public function open_connection()
	{
		$this->connection = pg_connect('host=' . PG_SERVER . ' port=' . PG_PORT . ' dbname=' . PG_NAME . ' user=' . PG_USER .
			' password=' . PG_PASS);
		pg_set_client_encoding($this->connection, 'LATIN1');
		if ( ! $this->connection) {
			die('Database connection failed!');
		}
	}

	/**
	 * Close the connection
	 */
	public function close_connection()
	{
		if (isset($this->connection)) {
			pg_close($this->connection);
			unset($this->connection);
		}
	}

	/**
	 * @param $sql
	 * @return resource
	 */
	public function query($sql)
	{
		$this->last_query = $sql;
		$result           = pg_query($this->connection, $sql);
		$this->confirm_query($result);

		return $result;
	}

	/**
	 * @param $value
	 * @return string
	 */
	public function escape_value($value)
	{
		if ($this->real_escape_string_exists) { // PHP v4.3.0+
			// undo any magic quote effects so mysqli_real_escape_string can do the work
			if ($this->magic_quotes_active) {
				$value = stripslashes($value);
			}
			$value = pg_escape_string($this->connection, $value); // PHP v5.0+
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
	 * @return array
	 */
	public function fetch_assoc($result_set)
	{
		return pg_fetch_assoc($result_set);
	}

	/**
	 * @param $result_set
	 * @return int
	 */
	public function num_rows($result_set)
	{
		return pg_num_rows($result_set);
	}

	/**
	 * @return string
	 */
	public function insert_id()
	{
		return pg_last_oid($this->last_query);
	}

	/**
	 * @return int
	 */
	public function affected_rows()
	{
		return pg_affected_rows($this->connection);
	}

	/**
	 * @param $result
	 */
	private function confirm_query($result)
	{
		if ( ! $result) {
			$ip1 = '127.0.0.1';
			$ip2 = '::1';
			if ($_SERVER['REMOTE_ADDR'] == $ip1 || $_SERVER['REMOTE_ADDR'] == $ip2) {
				$output1 = 'Database query failed! ' . '<br/><br/>';
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
}