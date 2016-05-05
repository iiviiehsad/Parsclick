<?php

// namespace Parsclick;
abstract class DatabaseObject
{
	// OPTIONAL
	protected static $table_name;
	protected static $db_fields;

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
		return array_key_exists($attribute, $this->attributes());
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

	protected function sanitized_attributes()
	{
		global $database;
		$clean_attributes = [];
		foreach($this->attributes() as $key => $value) {
			$clean_attributes[$key] = $database->escape_value($value);
		}

		return $clean_attributes;
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

	public static function find_all($public = TRUE)
	{
		$sql = "SELECT * ";
		$sql .= " FROM " . static::$table_name;
		if($public && in_array('visible', static::$db_fields)) {
			$sql .= " WHERE visible = 1 ";
		}
		if(in_array('position', static::$db_fields)) {
			$sql .= " ORDER BY position ASC ";
		} else {
			$sql .= " ORDER BY id ASC ";
		}

		return static::find_by_sql($sql);
	}

	public static function count_all()
	{
		global $database;
		$sql        = "SELECT COUNT(*) FROM " . static::$table_name;
		$result_set = $database->query($sql);
		$row        = $database->fetch_assoc($result_set);

		return array_shift($row);
	}

	public static function find_by_id($id = 0, $public = TRUE)
	{
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . static::$table_name;
		$sql .= " WHERE id = " . $database->escape_value($id);
		if($public && in_array('visible', static::$db_fields)) {
			$sql .= " AND visible = 1 ";
		}
		$sql .= " LIMIT 1";
		$result_array = static::find_by_sql($sql);

		return ! empty($result_array) ? array_shift($result_array) : FALSE;
	}

	public static function find_by_username($username = "")
	{
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . static::$table_name;
		$sql .= " WHERE username = '" . $database->escape_value($username) . "'";
		$sql .= " LIMIT 1";
		$result_array = static::find_by_sql($sql);

		return ! empty($result_array) ? array_shift($result_array) : FALSE;
	}

	public static function find_by_email($email = "")
	{
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . static::$table_name;
		$sql .= " WHERE email = '" . $database->escape_value($email) . "'";
		$sql .= " LIMIT 1";
		$result_array = static::find_by_sql($sql);

		return ! empty($result_array) ? array_shift($result_array) : FALSE;
	}

	public static function find_by_token($token = "")
	{
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . static::$table_name;
		$sql .= " WHERE token = '" . $database->escape_value($token) . "'";
		$sql .= " LIMIT 1";
		$result_array = static::find_by_sql($sql);

		return ! empty($result_array) ? array_shift($result_array) : FALSE;
	}

	public function full_name()
	{
		return isset($this->first_name) && isset($this->last_name) ? $this->first_name . ' ' . $this->last_name : '';
	}

	public static function num_rows()
	{
		global $database;
		$sql = "SELECT * ";
		$sql .= " FROM " . static::$table_name;
		if(in_array('position', static::$db_fields)) {
			$sql .= " ORDER BY position ASC ";
		} else {
			$sql .= " ORDER BY id ASC ";
		}
		$subject_set = $database->query($sql);

		return $database->num_rows($subject_set);
	}

	public static function find_newest($public = TRUE)
	{
		$sql = "SELECT * FROM " . static::$table_name;
		if($public && in_array('visible', static::$db_fields)) {
			$sql .= " WHERE visible = 1 ";
		}
		$sql .= " ORDER BY id DESC LIMIT 1";
		$array_result = static::find_by_sql($sql);

		return ! empty($array_result) ? array_shift($array_result) : FALSE;
	}

	public static function search($search = "")
	{
		global $database;
		$sql = "SELECT * FROM " . static::$table_name . " WHERE ";
		$sql .= "username LIKE '%{$database->escape_value($search)}%' ";
		$sql .= "OR first_name LIKE '%{$database->escape_value($search)}%' ";
		$sql .= "OR last_name LIKE '%{$database->escape_value($search)}%' ";
		$sql .= "OR email LIKE '%{$database->escape_value($search)}%' ";
		$result_set = static::find_by_sql($sql);

		return ! empty($result_set) ? $result_set : NULL;
	}

	public static function authenticate($username = "", $password = "")
	{
		$user = static::find_by_username($username);

		return $user ? (password_verify($password, $user->password) ? $user : FALSE) : FALSE;
	}

	public function password_encrypt($password)
	{
		return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
	}

	public function update()
	{
		global $database;
		$attributes      = $this->sanitized_attributes();
		$attribute_pairs = [];
		foreach($attributes as $key => $value) {
			$attribute_pairs[] = "{$key} = '{$value}'";
		}
		$sql = "UPDATE " . static::$table_name . " SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id = " . $database->escape_value($this->id);
		$database->query($sql);

		return ($database->affected_rows() == 1) ? TRUE : FALSE;
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

	public function save()
	{
		return isset($this->id) ? $this->update() : $this->create();
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
		return $this->set_user_reset_token($username, $this->reset_token());
	}

	private function reset_token()
	{
		return md5(uniqid(mt_rand()));
	}

	public function set_user_reset_token($username, $token)
	{
		$user = static::find_by_username($username);
		if($user) {
			$user->token = $token;
			$user->update();

			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function delete_reset_token($username)
	{
		return $this->set_user_reset_token($username, NULL);
	}

	public function email_reset_token($username)
	{
		$user = static::find_by_username($username);
		if($user && isset($user->token)) {
			$mail = new PHPMailer();
			$mail->isSMTP();
			$mail->isHTML(TRUE);
			$mail->CharSet    = 'UTF-8';
			$mail->Host       = SMTP;
			$mail->SMTPSecure = TLS;
			$mail->Port       = PORT;
			$mail->SMTPAuth   = TRUE;
			$mail->Username   = EMAILUSER;
			$mail->Password   = EMAILPASS;
			$mail->FromName   = DOMAIN;
			$mail->From       = EMAILUSER;
			$mail->addAddress($user->email, "Reset Password");
			$mail->Subject = "Reset Password Request";
			$content       = "
				<p>آیا اخیرا درخواست بازیافت پسوردتان را کردید؟</p>
				<p>اگر جواب مثبت است لطفا از لینک زیر برای بازیافت پسوردتان استفاده کنید:</p>
			";
			$mail->Body    = email($user->full_name(), DOMAIN, "http://www.parsclick.net/admin/reset_password.php?token={$user->token}", $content);

			//return send_email($user->email, "Reset Password Request", email($user->full_name(), DOMAIN, "http://www.parsclick.net/admin/reset_password.php?token={$user->token}", $content));
			return $mail->send();
		} else {
			return FALSE;
		}
	}

} // END of CLASS