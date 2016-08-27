<?php //namespace Parsclick;

class Admin extends DatabaseObject
{
	protected static $table_name = 'super_admin';
	protected static $db_fields  = ['id', 'username', 'password', 'first_name', 'last_name', 'email', 'token'];
	public           $id;
	public           $username;
	public           $password;
	public           $first_name;
	public           $last_name;
	public           $email;
	public           $token;
} // END of CLASS