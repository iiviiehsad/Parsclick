<?php

interface Database
{
	/**
	 * @return mixed
	 */
	public function open_connection();

	/**
	 * @return mixed
	 */
	public function close_connection();

	/**
	 * @param $sql
	 * @return mixed
	 */
	public function query($sql);

	/**
	 * @param $value
	 * @return mixed
	 */
	public function escape_value($value);

	/**
	 * @param $result_set
	 * @return mixed
	 */
	public function fetch_assoc($result_set);

	/**
	 * @param $result_set
	 * @return mixed
	 */
	public function num_rows($result_set);

	/**
	 * @return mixed
	 */
	public function insert_id();

	/**
	 * @return mixed
	 */
	public function affected_rows();
}