<?php

interface Database
{
	/**
	 * This will open the connection
	 */
	public function open_connection();

	/**
	 * This will close the connection
	 */
	public function close_connection();

	/**
	 * @param $sql string will get the SQL query
	 * @return bool|mysqli_result will return the result if the SQL query is OK
	 */
	public function query($sql);

	/**
	 * @param $value string will get the value and prepare it to put in MySQL
	 * @return string will return MySQL input got from somewhere else
	 */
	public function escape_value($value);

	/**
	 * @param $result_set
	 * @return array|null associative array
	 */
	public function fetch_assoc($result_set);

	/**
	 * @param $result_set
	 * @return int number of rows
	 */
	public function num_rows($result_set);

	/**
	 * @return int|string gets the last id inserted over the current db connection
	 */
	public function insert_id();

	/**
	 * @return int how many rows were affected by the last query
	 */
	public function affected_rows();
}