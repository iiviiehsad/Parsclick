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
		if($this->status == 0) {
			redirect_to("author_freezed.php");
		} elseif($this->status == 2) {
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

	/**
	 * This method will send a confirmation email to the recently registered members.
	 * @param $username
	 * @return bool TRUE if email is sent and FALSE if not
	 * @throws \Exception
	 * @throws \phpmailerException
	 */
	public function email_confirmation_details($username) {
		$user      = self::find_by_username($username);
		$site_root = DOMAIN;
		if($user && isset($user->token)) {
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->IsHTML(TRUE);
			$mail->AddAddress($this->email, "Welcome to Parsclick, Confirm Your Email");
			$mail->CharSet    = 'UTF-8';
			$mail->Host       = SMTP;
			$mail->SMTPSecure = TLS;
			$mail->Port       = PORT;
			$mail->SMTPAuth   = TRUE;
			$mail->Username   = EMAILUSER;
			$mail->Password   = EMAILPASS;
			$mail->FromName   = "do-not-reply@parsclick.net";
			$mail->From       = EMAILUSER;
			$mail->Subject    = "به پارس کلیک خوش آمدید";
			$mail->Body       = <<<EMAILBODY
			<body style="background-color:#F5F5DC;direction:rtl;font-family:Tahoma;">
				<h1>جناب {$this->full_name()}،</h1>
				<br/><br/>
				<h3>عضویت شما به عنوان نویسنده ساخته شد و قبل از اینکه از سیستم استفاده کنید از لینک زیر برای تایید کردن ایمیل خود استفاده کنید:<br/><br/>
				<p style="direction:ltr;margin:auto;padding:5px;">
					http://{$site_root}/admin/author_confirm_email.php?token={$user->token}
				</p>
				</h3>
				<p>خوب یک خوش آمد و استقبال گرم را از طرف پارس کلیک بپذیرید و ممنونیم که ما را انتخاب کردید.<br />
				باعث افتخار ماست که دعوت ما به عنوان نویسنده را می پذیرید و مقاله می نویسید</p><br/>
				<h3 style="color:red;">یارآوری مهم:</h3>
				<ul>
				<li>خیلی ضرورت دارد که اطلاعات شما بروز باشد مخصوصا تنها پل ارتباطی بین ما  و شما که ایمیلتان است. </li>
				<li>به محض ورود به سیستم شما قادر به تغییر اطلاعات شخصی خود هستید. </li>
				<li>از ایمیل آدرس شما برای بازیافت پسورد در موقعیت احتمالی گم کردن و فراموشی پسورد استفاده می شود. </li>
				<li>اگر سوالی در مورد دروس و مقالات داشتید در قسمت نظرات مربوط به هر کدام بنویسید.</li>
				<li>اگر سوالی کلی داشتید با ما در قسمت تماس با مای سایت تماس بگیرید.</li>
				<li>شما به عنوان نویسنده فعلا قادر به نوشتن مقاله هستید و درسی نسازید تا اینکه مدیر سایت به شما خبر دهد.
				 چرا که ساخت درس حساب یوتیوب، و پلی لیست یا لیست پخش می خواهد، بعلاوه ی آنها فایل های تمرینی هم بهتد است تهیه کنید.
				  بنابراین فعلا فقط مقاله نویسی کنید. اگر دسترسی به یوتیوب برای شما آسان است و کانال یوتیوب دارید و
				  مهمتر از همه اینکه دقیقا می توانید ازسیستم استفاده کنید پس می توانید درس هم بسازید.</li>
				<li>در آخر اینکه حتما این ویدئو را تماشا کنید که همه چیز در مورد نویسندگی برای پارس کلیک را توضیح داده است: <a href="http://youtu.be/G0TY36VCODc" target="_blank">http://youtu.be/G0TY36VCODc</a></li>
				</ul>
				<br/><br/><br/>
				<hr/>
				<p>با تشکر نویسنده عزیز <br/>
				پارس کلیک <br/>
				http://{$site_root}</p>
			</body>
EMAILBODY;
			return $mail->Send();
		} else {
			return FALSE;
		}
	}
}

?>