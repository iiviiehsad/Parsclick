<?php
require_once(LIB_PATH . DS . 'database.php');
require_once("vendor/autoload.php");

class Member extends DatabaseObject {

	// Really important: array and properties MUST be exactly the same name as db columns
	protected static $table_name = "members";
	protected static $db_fields  = [
		'id',
		'username',
		'hashed_password',
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
	public           $hashed_password;
	public           $first_name;
	public           $last_name;
	public           $gender;
	public           $address;
	public           $city;
	public           $email;
	public           $status;
	public           $token;
	public           $customer;

	/**
	 * @param string $search gets the search query
	 * @return array|null the result
	 */
	public static function search($search = "")
	{
		global $database;
		$sql = "SELECT * FROM " . self::$table_name . " WHERE ";
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
	 * @param string $username gets username
	 * @param string $password gets the password
	 * @return bool|mixed TRUE if matches and FALSE if not
	 */
	public static function authenticate($username = "", $password = "")
	{
		$user = self::find_by_username($username);
		if($user) {
			if(self::password_check($password, $user->hashed_password)) {
				return $user;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * @param $password      string gets the password
	 * @param $existing_hash string has the existing hash
	 * @return bool TRUE if existing hash matches the password and FALSE if not
	 */
	private static function password_check($password, $existing_hash)
	{
		// existing hash contains format and salt at start
		$hash = crypt($password, $existing_hash);
		if($hash === $existing_hash) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * @return string jones the first name and last name with an space
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
	 * @return string encrypts the password using Blowfish
	 */
	public function password_encrypt($password)
	{
		// Newer version PHP v5.5+
		// password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
		$hash_format     = "$2y$10$";   // Tells PHP to use Blowfish with a "cost" of 10
		$salt_length     = 22;   // Blowfish salts should be 22-characters or more
		$salt            = $this->generate_salt($salt_length);
		$format_and_salt = $hash_format . $salt;
		$hash            = crypt($password, $format_and_salt);

		return $hash;
	}

	/**
	 * @param $length int length of salt
	 * @return string the salt
	 */
	private function generate_salt($length)
	{
		// Not 100% unique, not 100% random, but good enough for a salt
		// MD5 returns 32 characters
		$unique_random_string = md5(uniqid(mt_rand(), TRUE));
		// Valid characters for a salt are [a-zA-Z0-9./]
		$base64_string = base64_encode($unique_random_string);
		// But not '+' which is valid in base64 encoding
		$modified_base64_string = str_replace('+', '.', $base64_string);
		// Truncate string to the correct length
		$salt = substr($modified_base64_string, 0, $length);

		return $salt;
	}

	/**
	 * This method checks the member's status to see if this property which represents the database status column
	 * (BOOLEAN) is 0 or FALSE or 1 or TRUE. If the status is not TRUE then member will be redirected to the specific
	 * page to prevent the member from using the system.
	 */
	public function check_status()
	{
		if($this->status == 0) {
			redirect_to("freezed");
		} elseif($this->status == 2) {
			redirect_to("blocked");
		}
	}

	/**
	 * This method will send generate a token, stores it into the token database column and sends that to the member's
	 * email address, where then this token will be checked to see if the member is legitimate or not.
	 *
	 * @param $username string is taken from user by typing
	 * @return bool TRUE if email is sent ond FALSE if not
	 * @throws \Exception
	 * @throws \phpMailerException
	 */
	public function email_reset_token($username)
	{
		$user = self::find_by_username($username);
		if($user && isset($user->token)) {
			$mail = new PHPMailer();
			$mail->isSMTP();
			$mail->isHTML(TRUE);
			$mail->addAddress($this->email, "Reset Password");
			$mail->CharSet    = 'UTF-8';
			$mail->Host       = SMTP;
			$mail->SMTPSecure = TLS;
			$mail->Port       = PORT;
			$mail->SMTPAuth   = TRUE;
			$mail->Username   = EMAILUSER;
			$mail->Password   = EMAILPASS;
			$mail->FromName   = DOMAIN;
			$mail->From       = EMAILUSER;
			$mail->Subject    = "Reset Password Request";
			$content          = "
				<p>اخیرا شما درخواست بازیافت پسوردتان را از ما نموده اید؟</p>
				<p>اگر شما این درخواست را نکردید هول نکنید, هیچ اقدامی لازم نیست انجام دهید. پسورد شما بدون کلیک کردن به لینک بالا قابل تغییر نخواهد بود.</p>
				<p>از لینک زیر برای عوض کردن پسورد خود استفاده کنید:</p>
			";

			$mail->Body = email($user->full_name(), DOMAIN, "http://www.parsclick.net/reset-password?token={$user->token}", $content);

			//return send_email($this->email, "Reset Password Request", email($user->full_name(), DOMAIN, "http://www.parsclick.net/reset-password?token={$user->token}", $content));
			return $mail->send();
		} else {
			return FALSE;
		}
	}

	/**
	 * This method will email the member's username to the member's email address on file.
	 *
	 * @param $email string is taken from the member by typing in the input field
	 * @return bool TRUE if email is sent and FALSE if not
	 * @throws \Exception
	 * @throws \phpMailerException
	 */
	public function email_username($email)
	{
		$user = self::find_by_email($email);
		if($user && isset($user->token)) {
			$mail = new PHPMailer();
			$mail->isSMTP();
			$mail->isHTML(TRUE);
			$mail->addAddress($this->email, "Forgot Username");
			$mail->CharSet    = 'UTF-8';
			$mail->Host       = SMTP;
			$mail->SMTPSecure = TLS;
			$mail->Port       = PORT;
			$mail->SMTPAuth   = TRUE;
			$mail->Username   = EMAILUSER;
			$mail->Password   = EMAILPASS;
			$mail->FromName   = DOMAIN;
			$mail->From       = EMAILUSER;
			$mail->Subject    = "Username Reminder Request";
			$content          = "
				<p>اسم کاربری را فراموش کردید؟</p>
				<p>لطفا به خاطر بسپارید که اسم کاربری را در جایی امن نگه داری کنید و این ایمیل را پاک کنید. این ایمیل را از سطل زباله ایمیل هم پاک کنید.</p>
				<p>اسم کاربری شما هست:</p>
			";

			$mail->Body = email($user->full_name(), DOMAIN, $user->username, $content);

			//return send_email($this->email, "Username Reminder Request", email($user->full_name(), DOMAIN, $user->username, $content));
			return $mail->send();
		} else {
			return FALSE;
		}
	}

	/**
	 * This method will send a confirmation email to the recently registered members.
	 *
	 * @param $username
	 * @return bool TRUE if email is sent and FALSE if not
	 * @throws \Exception
	 * @throws \phpmailerException
	 */
	public function email_confirmation_details($username)
	{
		$user = self::find_by_username($username);
		if($user && isset($user->token)) {
			$mail = new PHPMailer();
			$mail->isSMTP();
			$mail->isHTML(TRUE);
			$mail->addAddress($this->email, "Welcome to Parsclick, Confirm Your Email");
			$mail->CharSet    = 'UTF-8';
			$mail->Host       = SMTP;
			$mail->SMTPSecure = TLS;
			$mail->Port       = PORT;
			$mail->SMTPAuth   = TRUE;
			$mail->Username   = EMAILUSER;
			$mail->Password   = EMAILPASS;
			$mail->FromName   = DOMAIN;
			$mail->From       = EMAILUSER;
			$mail->Subject    = "به پارس کلیک خوش آمدید";
			$content          = "
				<p>خوب یک خوش آمد و استقبال گرم را از طرف بچه های پارس کلیک بپذیرید و ممنونیم که ما را انتخاب کردید.</p>
				<ul>
					<li>خیلی ضرورت دارد که اطلاعات شما بروز باشد مخصوصا تنها پل ارتباطی بین ما  و شما که ایمیلتان است.</li>
					<li>به محض ورود به سیستم شما قادر به تغییر اطلاعات شخصی خود هستید. </li>
					<li>از ایمیل آدرس شما برای بازیافت پسورد در موقعیت احتمالی گم کردن و فراموشی پسورد استفاده می شود. </li>
					<li>اگر سوالی در مورد دروس و مقالات داشتید در قسمت نظرات مربوط به هر کدام بنویسید.</li>
					<li>اگر سوالی کلی داشتید با ما در قسمت تماس با مای سایت تماس بگیرید. قبل از هر سوال بد نیست یک سری به قسمت سوالات شما بزنید چون بیشتر جواب سوالات مشترک شما آنجاست.</li>
				</ul>
				<p>لطفا روی لینک زیر جهت تایید ایمیل خود استفاده کنید:</p>
			";

			$mail->Body = email($user->full_name(), DOMAIN, "http://www.parsclick.net/confirm-email?token={$user->token}", $content);
			//return send_email($this->email, "به پارس کلیک خوش آمدید", email($user->full_name(), DOMAIN, "http://www.parsclick.net/confirm-email?token={$user->token}", $content));
			return $mail->send();
		} else {
			return FALSE;
		}
	}

} // END of CLASS