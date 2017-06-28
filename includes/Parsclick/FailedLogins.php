<?php

class FailedLogins extends DatabaseObject
{
	protected static $table_name = 'failed_logins';
	protected static $db_fields  = ['id', 'username', 'count', 'last_time'];
	public           $id;
	public           $username;
	public           $count;
	public           $last_time;

	/**
	 * @param $username
	 * @return bool
	 */
	public static function clear_failed_logins($username)
	{
		global $database;
		$failed_login = self::find_by_username($database->escape_value($username));
		if ($failed_login) {
			$failed_login->count     = 0;
			$failed_login->last_time = time();
			$failed_login->update();
		}

		return TRUE;
	}

	/**
	 * @param $username
	 * @return float|int
	 */
	public static function throttle_failed_logins($username)
	{
		global $database;
		$throttle_at      = 5;
		$delay_in_minutes = 60;
		$delay            = 30 * $delay_in_minutes;
		$failed_login     = self::find_by_username($database->escape_value($username));
		// Once failure count is over $throttle_at value,
		// user must wait for the $delay period to pass.
		if ($failed_login && $failed_login->count >= $throttle_at) {
			$remaining_delay = ($failed_login->last_time + $delay) - time();

			return ceil($remaining_delay / 60);
		} else {
			return 0;
		}
	}

	/**
	 * @param $username
	 * @return bool
	 */
	public function record_failed_login($username)
	{
		global $database;
		$failed_login = self::find_by_username($database->escape_value($username));
		if ( ! $failed_login) {
			$this->id        = $database->insert_id();
			$this->username  = $database->escape_value($username);
			$this->count     = 1;
			$this->last_time = time();
			$this->create();
		} else {
			$failed_login->count++;
			$failed_login->last_time = time();
			$failed_login->update();
		}

		return TRUE;
	}
}