<?php //namespace Parsclick;

class Author extends DatabaseObject
{
	protected static $table_name = "authors";
	protected static $db_fields  = [
		'id',
		'username',
		'password',
		'first_name',
		'last_name',
		'email',
		'parsclickmail',
		'status',
		'photo',
		'token'
	];
	public           $id;
	public           $username;
	public           $password;
	public           $first_name;
	public           $last_name;
	public           $email;
	public           $parsclickmail;
	public           $status;
	public           $photo;
	public           $token;

	/**
	 * Finds active authors
	 *
	 * @return array
	 */
	public static function find_active_authors()
	{
		return self::find_by_sql("SELECT * FROM " . static::$table_name . " WHERE status = 1");
	}
	
	/**
	 * This function checks the status to see if is TRUE or FALSE
	 */
	public function check_status()
	{
		if($this->status == 0) {
			redirect_to("author_freezed.php");
		} elseif($this->status == 2) {
			redirect_to("deactivated.php");
		}
	}

	/**
	 * @return bool TRUE if photo is deleted and FALSE if not
	 */
	public function remove_photo()
	{
		global $database;
		$sql = "UPDATE " . self::$table_name . " SET ";
		$sql .= " photo = NULL ";
		$sql .= " WHERE id=" . $database->escape_value($this->id);
		$database->query($sql);

		return ($database->affected_rows() == 1) ? TRUE : FALSE;
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
		$user      = self::find_by_username($username);
		$site_root = DOMAIN;
		if($user && isset($user->token)) {
			$mail = new PHPMailer();
			$mail->isSMTP();
			$mail->isHTML(TRUE);
			$mail->addAddress($user->email, "Welcome to Parsclick, Confirm Your Email");
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
			$content          = "
				<p>ثبت نام شما به عنوان نویسنده مورد قبول مدیر واقع شد.</p>
				<p>خوب یک خوش آمد و استقبال گرم را از طرف پارس کلیک بپذیرید و ممنونیم که ما را انتخاب کردید.</p>
				<p>باعث افتخار ماست که دعوت ما به عنوان نویسنده را می پذیرید و مقاله می نویسید.</p>
				<h3>یارآوری مهم:</h3>
				<ul>
					<li>به محض کلیک کردن لینک، شما به قسمت نویسندگان وارد خواهید شد. اولین و مهمترین کاری که باید انجام بدهید این است که به قسمت ویرایش حساب کاربری روید و پسوردتان را تغییر دهید. </li>
					<li>خیلی ضرورت دارد که اطلاعات شما بروز باشد مخصوصا تنها پل ارتباطی بین ما  و شما که ایمیلتان است. </li>
					<li>به محض ورود به سیستم شما قادر به تغییر اطلاعات شخصی خود هستید. </li>
					<li>از ایمیل آدرس شما برای بازیافت پسورد در موقعیت احتمالی گم کردن و فراموشی پسورد استفاده می شود. </li>
					<li>اولین مقاله شما باعث محکم شدن جای شما در پارس کلیک یا باعث سقوط شماست. پس در اولین مقاله دقت کنید.</li>
					<li>شما به عنوان نویسنده فعلا قادر به نوشتن مقاله هستید و درسی نسازید تا اینکه مدیر سایت به شما خبر دهد. چرا که ساخت درس حساب یوتیوب، و پلی لیست یا لیست پخش می خواهد، بعلاوه ی آنها فایل های تمرینی باید تهیه کنید. بنابراین فعلا فقط مقاله نویسی کنید. اگر دسترسی به یوتیوب برای شما آسان است و کانال یوتیوب دارید و مهمتر از همه اینکه دقیقا می توانید ازسیستم استفاده کنید پس می توانید درس هم بسازید.</li>
					<li>در آخر اینکه حتما این ویدئو را تماشا کنید که همه چیز در مورد نویسندگی برای پارس کلیک را توضیح داده است: <a href='https://www.youtube.com/embed/G0TY36VCODc?modestbranding=1&rel=0&showinfo=0&controls=0&hl=fa-ir' target='_blank'>https://www.youtube.com/embed/G0TY36VCODc?showinfo=0</a></li>
				</ul>
				<br/>
				<h3>عضویت شما به عنوان نویسنده ساخته شد و قبل از اینکه از سیستم استفاده کنید از لینک زیر برای تایید کردن ایمیل خود استفاده کنید:</h3>
			";
			$mail->Body       = email($user->full_name(), DOMAIN, "http://{$site_root}/admin/author_confirm_email.php?token={$user->token}", $content);

			return $mail->send();
			//return send_email($user->email, "به پارس کلیک خوش آمدید", email($user->full_name(), DOMAIN, "http://{$site_root}/admin/author_confirm_email.php?token={$user->token}", $content));
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

} // END of CLASS