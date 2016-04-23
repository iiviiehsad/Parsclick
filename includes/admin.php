<?php //namespace Parsclick;

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

} // END of CLASS