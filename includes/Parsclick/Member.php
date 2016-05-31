<?php //namespace Parsclick;

class Member extends DatabaseObject
{
	// Really important: array and properties MUST be exactly the same name as db columns
	protected static $table_name = 'members';
	protected static $db_fields  = [
		'id',
		'username',
		'password',
		'first_name',
		'last_name',
		'gender',
		'address',
		'city',
		'email',
		'status',
		'token'
	];
	public           $id;
	public           $username;
	public           $password;
	public           $first_name;
	public           $last_name;
	public           $gender;
	public           $address;
	public           $city;
	public           $email;
	public           $status;
	public           $token;

	/**
	 * @param string $search gets the search query
	 * @return array|null the result
	 */
	public static function search($search = '')
	{
		global $database;
		$sql = 'SELECT * FROM ' . self::$table_name . ' WHERE ';
		$sql .= "username LIKE '%{$database->escape_value($search)}%' ";
		$sql .= "OR first_name LIKE '%{$database->escape_value($search)}%' ";
		$sql .= "OR last_name LIKE '%{$database->escape_value($search)}%' ";
		$sql .= "OR gender LIKE '%{$database->escape_value($search)}%' ";
		$sql .= "OR address LIKE '%{$database->escape_value($search)}%' ";
		$sql .= "OR city LIKE '%{$database->escape_value($search)}%' ";
		$sql .= "OR email LIKE '%{$database->escape_value($search)}%' ";
		$result_set = self::find_by_sql($sql);

		return ! empty($result_set) ? $result_set : NULL;
	}

	/**
	 * @param int $limit  limits members per page
	 * @param int $offset the pagination offset
	 * @return array of members in each page
	 */
	public static function find_members($limit = 0, $offset = 0)
	{
		$sql = 'SELECT * FROM ' . self::$table_name . " ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}";

		return self::find_by_sql($sql);
	}

	/**
	 * This method checks the member's status to see if this property
	 * which represents the database status column
	 * (BOOLEAN) is 0 or FALSE or 1 or TRUE.
	 * If the status is not TRUE then member will be redirected to the specific
	 * page to prevent the member from using the system.
	 */
	public function check_status()
	{
		if ($this->status == 0) {
			redirect_to('freezed');
		} elseif ($this->status == 2) {
			redirect_to('blocked');
		}
	}

	/**
	 * This method will send generate a token, stores it into the token
	 * database column and sends that to the member's email address,
	 * where then this token will be checked to see if the member is
	 * legitimate or not.
	 *
	 * @param $username string is taken from user by typing
	 * @return bool TRUE if email is sent ond FALSE if not
	 * @throws \Exception
	 * @throws \phpMailerException
	 */
	public function email_reset_token($username)
	{
		$user = self::find_by_username($username);
		if ($user && isset($user->token)) {
			$mail    = new Mail();
			$data    = 'http://www.parsclick.net/reset-password?token=' . $user->token;
			$subject = 'Reset Password Request';
			$content = '
				<p>اخیرا شما درخواست بازیافت پسوردتان را از ما نموده اید؟</p>
				<p>اگر شما این درخواست را نکردید هول نکنید, هیچ اقدامی لازم نیست انجام دهید. پسورد شما بدون کلیک کردن به لینک بالا قابل تغییر نخواهد بود.</p>
				<p>از لینک زیر برای عوض کردن پسورد خود استفاده کنید:</p>
			';

			return $mail->sendEmailTo([$user->email], $data, $content, $subject, $user->full_name());
			// return send_email($this->email, 'Reset Password Request', email($this->full_name(), DOMAIN, $data, $content));
		} else {
			return FALSE;
		}
	}

	/**
	 * This method will email the member's username
	 * to the member's email address on file.
	 *
	 * @param $email string is taken from the member by typing in the input field
	 * @return bool TRUE if email is sent and FALSE if not
	 * @throws \Exception
	 * @throws \phpMailerException
	 */
	public function email_username($email)
	{
		$user = self::find_by_email($email);
		if ($user && isset($user->token)) {
			$mail    = new Mail();
			$data    = $user->username;
			$subject = 'Username Reminder Request';
			$content = '<p>اسم کاربری را فراموش کردید؟</p>
						<p>لطفا به خاطر بسپارید که اسم کاربری را در جایی امن نگه داری کنید و این ایمیل را پاک کنید. این ایمیل را از سطل زباله ایمیل هم پاک کنید.</p>
						<p>اسم کاربری شما هست:</p>';

			return $mail->sendEmailTo([$user->email], $data, $content, $subject, $user->full_name());
		} else {
			return FALSE;
		}
	}

	/**
	 * This method will send a confirmation email
	 * to the recently registered members.
	 *
	 * @param $username
	 * @return bool
	 * @throws \phpmailerException
	 */
	public function email_confirmation_details($username)
	{
		$user = self::find_by_username($username);
		if ($user && isset($user->token)) {
			$mail    = new Mail();
			$data    = 'http://www.parsclick.net/confirm-email?token=' . $user->token;
			$subject = 'به پارس کلیک خوش آمدید';
			$content = '<p>خوب یک خوش آمد و استقبال گرم را از طرف بچه های پارس کلیک بپذیرید و ممنونیم که ما را انتخاب کردید.</p>
						<ul><li>از ایمیل آدرس شما برای بازیافت پسورد در موقعیت احتمالی گم کردن و فراموشی پسورد استفاده می شود. </li>
						<li>اگر سوالی در مورد دروس و مقالات داشتید در قسمت نظرات مربوط به هر کدام بنویسید.</li></ul>
						<p>لطفا روی لینک زیر جهت تایید ایمیل خود استفاده کنید:</p>';

			return $mail->sendEmailTo([$user->email], $data, $content, $subject, $user->full_name());
		} else {
			return FALSE;
		}
	}

	/**
	 * Deletes inactive users
	 *
	 * @return bool
	 */
	public static function delete_inactives()
	{
		global $database;
		$sql = 'DELETE FROM ' . self::$table_name . ' WHERE status = 0';
		$database->query($sql);

		return $database->affected_rows() ? TRUE : FALSE;
	}

} // END of CLASS