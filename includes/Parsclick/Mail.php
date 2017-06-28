<?php

class Mail
{
	public    $data;
	public    $content;
	public    $to       = [];
	public    $subject  = 'پارس کلیک';
	public    $fullName = 'کاربر گرامی';
	protected $mailer;
	protected $charset  = 'UTF-8';
	protected $from     = EMAILUSER;
	protected $host     = SMTP;
	protected $secure   = TLS;
	protected $port     = PORT;
	protected $smtpAuth = TRUE;
	protected $username = EMAILUSER;
	protected $password = EMAILPASS;
	protected $fromName = DOMAIN;

	/**
	 * Mail constructor.
	 */
	public function __construct()
	{
		$this->mailer = new PHPMailer();
	}

	/**
	 * @param $email
	 * @param $subject
	 * @param $message
	 * @return bool
	 */
	public static function send_email($email, $subject, $message)
	{
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'From: Parsclick <do-not-reply@parsclick.net>' . "\r\n";
		// $headers .= 'To: Amir <infoe@parsclick.net>, Hassan <infoe@parsclick.net>' . "\r\n";
		// $headers .= 'Cc: info@parsclick.net' . "\r\n";
		// $headers .= 'Bcc: info@parsclick.net' . "\r\n";
		return mail($email, $subject, $message, $headers);
	}

	/**
	 * @param array $emails
	 * @param string $data
	 * @param string $content
	 * @param string $subject
	 * @param string $fullName
	 * @return bool
	 * @throws \phpmailerException
	 */
	public function sendEmailTo($emails = [], $data = '', $content = '', $subject = 'ایمیل از سایت پارس کلیک', $fullName = 'کاربر گرامی')
	{
		$this->to       = $emails;
		$this->fullName = $fullName;
		$this->subject  = $subject;
		$this->data     = $data;
		$this->content  = $content;

		return $this->deliver();
	}

	/**
	 * @return bool
	 * @throws \phpmailerException
	 */
	public function deliver()
	{
		$this->mailer->isSMTP();
		$this->mailer->isHTML(TRUE);
		foreach ($this->to as $address) {
			$this->mailer->addBCC($address, $this->subject);
		}
		$this->mailer->CharSet    = $this->charset;
		$this->mailer->Host       = $this->host;
		$this->mailer->SMTPSecure = $this->secure;
		$this->mailer->Port       = $this->port;
		$this->mailer->SMTPAuth   = $this->smtpAuth;
		$this->mailer->Username   = $this->username;
		$this->mailer->Password   = $this->password;
		$this->mailer->FromName   = $this->fromName;
		$this->mailer->From       = $this->from;
		$this->mailer->Subject    = $this->subject;
		$this->mailer->Body       = $this->template();

		// return self::send_email($this->to, $this->subject, $this->template());
		return $this->mailer->send();
	}

	/**
	 * @return string
	 */
	public function template()
	{
		return <<<EMAILBODY
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml" style="direction: rtl !important;">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width"/>
</head>
<body style="direction: rtl; unicode-bidi: embed; width: 100% !important; min-width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; color: #222; font-family: yekan, Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; text-align: right; line-height: 19px; font-size: 14px; margin: 0; padding: 0 10px;">
<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: right; height: 100%; width: 100%; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;">
	<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
		<td align="center" valign="top" style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: center; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;">
			<center style="width: 100%; min-width: 580px;">
				<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; background: #000; padding: 0px;" bgcolor="#000000">
					<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
						<td align="center" style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: center; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" valign="top">
							<center style="width: 100%; min-width: 580px;">
								<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: inherit; width: 580px; margin: 0 auto; padding: 0;">
									<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
										<td class="wrapper last" style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; position: relative; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 10px 0px 0px;" align="right" valign="top">
											<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 580px; margin: 0 auto; padding: 0;">
												<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
													<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; min-width: 0px; width: 50%; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 10px 10px 0px;" align="right" valign="top">
														<img src="http://www.parsclick.net/images/misc/logo.png" style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; width: auto; max-width: 100%; float: left; clear: both; display: block;" align="left"/>
													</td>
													<td style="text-align: left; vertical-align: middle; word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; min-width: 0px; width: 50%; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px;" align="left" valign="middle">
														<span style="color: #FFF; font-weight: bold; font-size: 11px;"><a href="http://{$this->fromName}/" style="color: #2BA6CB; text-decoration: none;">Parscick.net</a></span>
													</td>
													<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; visibility: hidden; width: 0px; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="right" valign="top"></td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</center>
						</td>
					</tr>
				</table>
				<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: inherit; width: 580px; margin: 0 auto; padding: 0;">
					<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
						<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="right" valign="top">
							<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: block; padding: 0px;">
								<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
									<td class="wrapper last" style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; position: relative; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 10px 0px 0px;" align="right" valign="top">
										<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 580px; margin: 0 auto; padding: 0;">
											<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
												<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px;" align="right" valign="top">
													<h1 style="color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; text-align: right; line-height: 1.3; word-break: normal; font-size: 25px; margin: 0; padding: 0;" align="right">
														{$this->fullName} </h1><br/>
													<p style="color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; text-align: right; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="right">
														{$this->content}
													</p></td>
												<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; visibility: hidden; width: 0px; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="right" valign="top"></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: block; padding: 0px;">
								<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
									<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; position: relative; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 10px 0px 20px;" align="right" valign="top">
										<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: center; width: 580px; margin: 0 auto; padding: 0;">
											<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
												<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; background: #ECF8FF; margin: 0; padding: 10px; border: 1px solid #B9E5FF;" align="right" bgcolor="#ECF8FF" valign="top">
													<p style="color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; text-align: center; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="right">
														{$this->data}
													</p>
												</td>
												<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; visibility: hidden; width: 0px; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="right" valign="top"></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: block; padding: 0px;">
								<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
									<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; position: relative; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; background: #EBEBEB; margin: 0; padding: 10px 20px 0px 0px;" align="right" bgcolor="#ebebeb" valign="top">
										<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 280px; margin: 0 auto; padding: 0;">
											<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
												<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px 10px;" align="right" valign="top">
													<h5 style="color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; text-align: right; line-height: 1.3; word-break: normal; font-size: 18px; margin: 0; padding: 0 0 10px;" align="right">
														تماس با ما:</h5>
													<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; overflow: hidden; padding: 0;">
														<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
															<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: center; color: #FFF; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: initial !important; font-size: 14px; box-sizing: border-box; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; display: block; width: auto !important; background: #3B5998; margin: 0; padding: 5px 0; border: 1px solid #2D4473;" align="center" bgcolor="#3b5998" valign="top">
																<a href="https://www.facebook.com/persiantc/" style="color: #FFF; text-decoration: none; font-weight: normal; font-family: Tahoma, Helvetica, Arial, sans-serif; font-size: 12px; display: block; height: 100%; width: 100%;">Facebook</a>
															</td>
														</tr>
													</table>
													<br/>
													<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; overflow: hidden; padding: 0;">
														<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
															<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: center; color: #FFF; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: initial !important; font-size: 14px; box-sizing: border-box; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; display: block; width: auto !important; background: #00ACEE; margin: 0; padding: 5px 0; border: 1px solid #0087BB;" align="center" bgcolor="#00acee" valign="top">
																<a href="https://twitter.com/AmirHassanAzimi" style="color: #FFF; text-decoration: none; font-weight: normal; font-family: Tahoma, Helvetica, Arial, sans-serif; font-size: 12px; display: block; height: 100%; width: 100%;">Twitter</a>
															</td>
														</tr>
													</table>
													<br/>
													<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; overflow: hidden; padding: 0;">
														<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
															<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: center; color: #FFF; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: initial !important; font-size: 14px; box-sizing: border-box; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; display: block; width: auto !important; background: #DB4A39; margin: 0; padding: 5px 0; border: 1px solid #C00;" align="center" bgcolor="#DB4A39" valign="top">
																<a href="https://plus.google.com/+PersianComputers" style="color: #FFF; text-decoration: none; font-weight: normal; font-family: Tahoma, Helvetica, Arial, sans-serif; font-size: 12px; display: block; height: 100%; width: 100%;">Google+</a>
															</td>
														</tr>
													</table>
												</td>
												<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; visibility: hidden; width: 0px; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="right" valign="top"></td>
											</tr>
										</table>
									</td>
									<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none;
									border-collapse: collapse !important; vertical-align: top; text-align: right; position: relative;
									color: #222222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height:
									19px; font-size: 14px; background: #ebebeb; margin: 0; padding: 10px 0px 0px;" align="right"
									bgcolor="#ebebeb" valign="top">
									<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 280px; margin: 0 auto; padding: 0;">
										<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
											<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px;" align="right" valign="top">
												<h5 style="color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; text-align: right; line-height: 1.3; word-break: normal; font-size: 18px; margin: 0; padding: 0 0 10px;" align="right">
													اطلاعات بیشتر:</h5>
												<p style="color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; text-align: right; line-height: 19px; font-size: 14px; margin: 0 0 14px; padding: 0;" align="right">
													کانال تلگرام:
													<a href="https://telegram.me/pars_click" style="color: #2BA6CB; text-decoration: none;">pars_click</a>
												</p>
												<p style="color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; text-align: right; line-height: 19px; font-size: 14px; margin: 0 0 14px; padding: 0;" align="right">
													ایمیل: <a href="mailto:parsclickmail@gmail.com" style="color: #2BA6CB; text-decoration: none;">parsclickmail@gmail.com</a>
												</p>
												<hr/>
												<p style="color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; text-align: right; line-height: 19px; font-size: 12px; margin: 0 0 10px; padding: 0;" align="right">
													لطفا به این ایمیل جواب ندهید. این ایمیل صرفا جهت اطلاع رسانی است.</p>
											</td>
											<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; visibility: hidden; width: 0px; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="right" valign="top"></td>
										</tr>
									</table>
									</td>
								</tr>
							</table>
							<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: block; padding: 0px;">
								<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
									<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; position: relative; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 10px 0px 0px;" align="right" valign="top">
										<table style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 580px; margin: 0 auto; padding: 0;">
											<tr style="vertical-align: top; text-align: left; padding: 0;" align="left">
												<td align="center" style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px;" valign="top">
													<center style="width: 100%; min-width: 580px;">
														<p style="text-align: center; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="center">
															<a href="http://{$this->fromName}/" style="color: #2BA6CB; text-decoration: none;">پارس
															                                                                              کلیک</a> |
															<a href="http://{$this->fromName}/privacypolicy" style="color: #2BA6CB; text-decoration: none;">شرایط
															                                                                                           و
															                                                                                           ضوابط</a>
															                                                                                       |
															<a href="http://{$this->fromName}/login" style="color: #2BA6CB; text-decoration: none;">ورود به
															                                                                                   سیستم</a>
														</p>
													</center>
												</td>
												<td style="word-break: break-word; -webkit-hyphens: none; -moz-hyphens: none; hyphens: none; border-collapse: collapse !important; vertical-align: top; text-align: right; visibility: hidden; width: 0px; color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="right" valign="top"></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</center>
		</td>
	</tr>
</table>
</body>
</html>
EMAILBODY;
	}
}