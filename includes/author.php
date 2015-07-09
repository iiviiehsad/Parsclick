<?php
require_once(LIB_PATH . DS . 'database.php');

/**
 * Class Author inherits from DatabaseObject Class to work with authors database table
 */
class Author extends DatabaseObject {

	protected static $table_name = "authors";
	protected static $db_fields  = array(
		'id',
		'username',
		'password',
		'first_name',
		'last_name',
		'email',
		'status',
		'photo',
		'token'
	);
	public           $id;
	public           $username;
	public           $password;
	public           $first_name;
	public           $last_name;
	public           $email;
	public           $status;
	public           $photo;
	public           $token;

	/**
	 * @return string containing first_name and last_name joined with an empty space
	 */
	public function full_name() {
		if(isset($this->first_name) && isset($this->last_name)) {
			return $this->first_name . " " . $this->last_name;
		} else {
			return "";
		}
	}

	/**
	 * @param string $search gets the search query
	 * @return array|null the result
	 */
	public static function search($search = "") {
		global $database;
		$sql = "SELECT * FROM " . self::$table_name . " WHERE ";
		$sql .= "username LIKE '%{$database->escape_value($search)}%' ";
		$sql .= "OR first_name LIKE '%{$database->escape_value($search)}%' ";
		$sql .= "OR last_name LIKE '%{$database->escape_value($search)}%' ";
		$sql .= "OR email LIKE '%{$database->escape_value($search)}%' ";
		$result_set = self::find_by_sql($sql);
		return !empty($result_set) ? $result_set : NULL;
	}

	/**
	 * Important: This function needs needs PHP v5.5+
	 * @param $password string gets the password from the user
	 * @return bool|string encrypts the password using Blowfish
	 */
	public function password_encrypt($password) {
		// password_hash() needs PHP v5.5+
		return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
	}

	/**
	 * Important: This function needs needs PHP v5.5+
	 * @param string $username gets the username from the user
	 * @param string $password gets the username from the user
	 * @return bool|mixed fields if username and password match to user's input
	 */
	public static function authenticate($username = "", $password = "") {
		$author = self::find_by_username($username);
		if($author) {
			// password_verify() needs PHP v5.5+
			if(password_verify($password, $author->password)) {
				return $author;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks the status to see if is TRUE or FALSE
	 */
	public function check_status() {
		if(!$this->status) {
			redirect_to("deactivated.php");
		}
	}

	/**
	 * @return bool TRUE if photo is deleted and FALSE if not
	 */
	public function remove_photo() {
		global $database;
		$sql = "UPDATE " . static::$table_name . " SET ";
		$sql .= " photo = NULL ";
		$sql .= " WHERE id=" . $database->escape_value($this->id);
		$database->query($sql);
		return ($database->affected_rows() == 1) ? TRUE : FALSE;
	}
}

?>