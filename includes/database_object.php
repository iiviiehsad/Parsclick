<?php
require_once(LIB_PATH . DS . 'database.php');
require_once("vendor/autoload.php");

class DatabaseObject {

	// OPTIONAL
	protected static $table_name;
	protected static $db_fields;

	public static function find_all()
	{
		return static::find_by_sql("SELECT * FROM " . static::$table_name);
	}

	public static function find_by_sql($sql = "")
	{
		global $database;
		$result_set   = $database->query($sql);
		$object_array = [];
		while($row = $database->fetch_assoc($result_set)) {
			$object_array[] = static::instantiate($row);
		}

		return $object_array;
	}

	private static function instantiate($record)
	{
		$object = new static;
		foreach($record as $attribute => $value) {
			if($object->has_attribute($attribute)) {
				$object->$attribute = $value;
			}
		}

		return $object;
	}

	private function has_attribute($attribute)
	{
		$object_vars = $this->attributes();

		return array_key_exists($attribute, $object_vars);
	}

	protected function attributes()
	{
		$attributes = [];
		foreach(static::$db_fields as $field) {
			if(property_exists($this, $field)) {
				$attributes[$field] = $this->$field;
			}
		}

		return $attributes;
	}

	public static function find_by_id($id = 0)
	{
		global $database;
		$result_array = static::find_by_sql("SELECT * FROM " . static::$table_name . " WHERE id = " . $database->escape_value($id) . " LIMIT 1");

		return ! empty($result_array) ? array_shift($result_array) : FALSE;
	}

	public static function find_by_email($email = "")
	{
		global $database;
		$result_array = static::find_by_sql("SELECT * FROM " . static::$table_name . " WHERE email = '" . $database->escape_value($email) . "' LIMIT 1");

		return ! empty($result_array) ? array_shift($result_array) : FALSE;
	}

	public static function find_by_token($token = "")
	{
		global $database;
		$result_array = static::find_by_sql("SELECT * FROM " . static::$table_name . " WHERE token = '" . $database->escape_value($token) . "' LIMIT 1");

		return ! empty($result_array) ? array_shift($result_array) : FALSE;
	}

	public static function count_all()
	{
		global $database;
		$sql        = "SELECT COUNT(*) FROM " . static::$table_name;
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

	public static function num_rows()
	{
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . static::$table_name;
		$sql .= " ORDER BY position ASC";
		$subject_set = $database->query($sql);

		return $database->num_rows($subject_set);
	}

	public function save()
	{
		return isset($this->id) ? $this->update() : $this->create();
	}

	public function update()
	{
		global $database;
		$attributes      = $this->sanitized_attributes();
		$attribute_pairs = [];
		foreach($attributes as $key => $value) {
			$attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE " . static::$table_name . " SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id=" . $database->escape_value($this->id);
		$database->query($sql);

		return ($database->affected_rows() == 1) ? TRUE : FALSE;
	}

	protected function sanitized_attributes()
	{
		global $database;
		$clean_attributes = [];
		foreach($this->attributes() as $key => $value) {
			$clean_attributes[$key] = $database->escape_value($value);
		}

		return $clean_attributes;
	}

	public function create()
	{
		global $database;
		$attributes = $this->sanitized_attributes();
		$sql        = "INSERT INTO " . static::$table_name . " (";
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		if($database->query($sql)) {
			$this->id = $database->insert_id();

			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function delete()
	{
		global $database;
		$sql = "DELETE FROM " . static::$table_name;
		$sql .= " WHERE id = " . $database->escape_value($this->id);
		$sql .= " LIMIT 1";
		$database->query($sql);

		return ($database->affected_rows() == 1) ? TRUE : FALSE;
	}

	public function create_reset_token($username)
	{
		$token = $this->reset_token();

		return $this->set_user_reset_token($username, $token);
	}

	private function reset_token()
	{
		return md5(uniqid(mt_rand()));
	}

	public function set_user_reset_token($username, $token_value)
	{
		$user = static::find_by_username($username);
		if($user) {
			$user->token = $token_value;
			$user->update();

			return TRUE;
		} else {
			return FALSE;
		}
	}

	public static function find_by_username($username = "")
	{
		global $database;
		$result_array = static::find_by_sql("SELECT * FROM " . static::$table_name . " WHERE username = '" . $database->escape_value($username) . "' LIMIT 1");

		return ! empty($result_array) ? array_shift($result_array) : FALSE;
	}

	public function delete_reset_token($username)
	{
		$token = NULL;

		return $this->set_user_reset_token($username, $token);
	}

	public function email_reset_token($username)
	{
		$user = static::find_by_username($username);
		if($user && isset($user->token)) {
			//$mail = new PHPMailer();
			//$mail->IsSMTP();
			//$mail->IsHTML(TRUE);
			//$mail->CharSet    = 'UTF-8';
			//$mail->Host       = SMTP;
			//$mail->SMTPSecure = TLS;
			//$mail->Port       = PORT;
			//$mail->SMTPAuth   = TRUE;
			//$mail->Username   = EMAILUSER;
			//$mail->Password   = EMAILPASS;
			//$mail->FromName   = DOMAIN;
			//$mail->From       = EMAILUSER;
			//$mail->AddAddress($user->email, "Reset Password");
			//$mail->Subject = "Reset Password Request";
			$content = "
			آیا اخیرا درخواست بازیافت پسوردتان را کردید؟ <br/>
			اگر جواب مثبت است لطفا از لینک زیر برای بازیافت پسوردتان استفاده کنید:<br/>
			";

			//$mail->Body    = email($user->full_name(), DOMAIN, "http://www.parsclick.net/admin/reset_password.php?token={$user->token}", $content);
			return send_email($user->email, "Reset Password Request", email($user->full_name(), DOMAIN, "http://www.parsclick.net/admin/reset_password.php?token={$user->token}", $content));
			//return $mail->Send();
		} else {
			return FALSE;
		}
	}
	
} // END of CLASS