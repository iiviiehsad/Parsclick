<?php require_once(LIB_PATH . DS . 'database.php');

class FailedLogins extends DatabaseObject {

	protected static $table_name = "failed_logins";
	protected static $db_fields  = ['id', 'username', 'count', 'last_time'];
	public           $id;
	public           $username;
	public           $count;
	public           $last_time;

	/**
	 * @param $username string gets from user input
	 * @return bool TRUE if failed record is either created or updated and FALSE otherwise
	 */
	public function record_failed_login($username)
	{
		global $database;
		$failed_login = self::find_by_username($username);
		if(!$failed_login) {
			$this->id        = $database->insert_id();
			$this->username  = $database->escape_value($username);
			$this->count     = 1;
			$this->last_time = time();
			$this->create();
		} else {
			$failed_login->count += 1;
			$failed_login->last_time = time();
			$failed_login->update();
		}
		return TRUE;
	}

	/**
	 * @param $username string gets from user input
	 * @return bool TRUE if failed record is cleared and FALSE otherwise
	 */
	public static function clear_failed_logins($username)
	{
		$failed_login = self::find_by_username($username);
		if($failed_login) {
			$failed_login->count     = 0;
			$failed_login->last_time = time();
			$failed_login->update();
		}
		return TRUE;
	}

	/**
	 * @param $username string gets from user input
	 * @return float|int number of minutes user needs to wait and re-enter credentials again
	 */
	public static function throttle_failed_logins($username)
	{
		$throttle_at      = 5;
		$delay_in_minutes = 60;
		$delay            = 30 * $delay_in_minutes;
		$failed_login     = self::find_by_username($username);
		// Once failure count is over $throttle_at value,
		// user must wait for the $delay period to pass.
		if($failed_login && $failed_login->count >= $throttle_at) {
			$remaining_delay            = ($failed_login->last_time + $delay) - time();
			$remaining_delay_in_minutes = ceil($remaining_delay / 60);
			return $remaining_delay_in_minutes;
		} else {
			return 0;
		}
	}
}