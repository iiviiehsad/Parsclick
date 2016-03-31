<?php require_once(LIB_PATH . DS . 'database.php');

class Admin extends DatabaseObject
{
	protected static $table_name = "super_admin";
	protected static $db_fields  = ['id', 'username', 'password', 'first_name', 'last_name', 'email', 'token'];
	public           $id;
	public           $username;
	public           $password;
	public           $first_name;
	public           $last_name;
	public           $email;
	public           $token;

	/**
	 * @param string $search gets the search query from the user
	 * @return array|null result of an query search
	 */
	public static function search($search = "")
	{
		global $database;
		$sql = "SELECT * FROM " . self::$table_name . " WHERE ";
		$sql .= "username LIKE '%{$database->escape_value($search)}%' ";
		$sql .= "OR first_name LIKE '%{$database->escape_value($search)}%' ";
		$sql .= "OR last_name LIKE '%{$database->escape_value($search)}%' ";
		$sql .= "OR email LIKE '%{$database->escape_value($search)}%' ";
		$result_set = self::find_by_sql($sql);

		return ! empty($result_set) ? $result_set : NULL;
	}

	/**
	 * Important: This function needs PHP v5.5+
	 *
	 * @param string $username gets the username from the user
	 * @param string $password gets the password from the user
	 * @return bool|mixed user fields if username and password match to user's input
	 */
	public static function authenticate($username = "", $password = "")
	{
		$admin = self::find_by_username($username);
		if($admin) {
			// password_verify() needs PHP v5.5+
			if(password_verify($password, $admin->password)) {
				return $admin;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * @return string containing first_name and last_name joined with an empty space
	 */
	public function full_name()
	{
		if(isset($this->first_name) && isset($this->last_name)) {
			return $this->first_name . " " . $this->last_name;
		} else {
			return "";
		}
	}

	/**
	 * Important: This function needs needs PHP v5.5+
	 *
	 * @param $password string gets the password from the user
	 * @return bool|string encrypts the password using Blowfish
	 */
	public function password_encrypt($password)
	{
		// password_hash() needs PHP v5.5+
		return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
	}

} // END of CLASS