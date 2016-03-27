<?php

/**
 * @param $class_name String will get the class name for each PHP class and finds the file name associate to it
 */
function __autoload($class_name)
{
	$class_name = strtolower($class_name);
	$path       = LIB_PATH . DS . $class_name . ".php";
	if(file_exists($path)) {
		require_once($path);
	} else {
		die("The file {$class_name}.php could not be found!");
	}
}

/**
 * @param null $location is by default NULL which will redirect the article to a particular location
 */
function redirect_to($location = NULL)
{
	if($location != NULL) {
		header("Location: " . $location);
		exit;
	}
}

/**
 * @param string $message string shows the messages
 * @param string $errors  string shows the errors
 * @return string
 */
function output_message($message = "", $errors = "")
{
	if( ! empty($message)) {
		$output = "<div class='alert alert-success alert-dismissible' role='alert'>";
		$output .= "<button type='button' class='close' data-dismiss='alert'>";
		$output .= "<span aria-hidden='true'>&times;</span>";
		$output .= "<span class='sr-only'></span>";
		$output .= "</button>";
		$output .= "<i class='fa fa-check-circle-o fa-fw fa-lg'></i> ";
		$output .= "<strong>" . htmlentities($message) . "</strong>";
		$output .= "</div>";

		return $output;
	} elseif( ! empty($errors)) {
		$output = "<div class='animated flash alert alert-danger alert-dismissible' role='alert'>";
		$output .= "<button type='button' class='close' data-dismiss='alert'>";
		$output .= "<span aria-hidden='true'>&times;</span>";
		$output .= "<span class='sr-only'></span>";
		$output .= "</button>";
		$output .= "<i class='fa fa-times-circle-o fa-fw fa-lg'></i> ";
		$output .= "<strong>" . htmlentities($errors) . "</strong>";
		$output .= "</div>";

		return $output;
	} else {
		return "";
	}
}

/**
 * @param string $template will replace the associate layout for footer or header inside includes folder
 */
function include_layout_template($template = "")
{
	include(LIB_PATH . DS . 'layouts' . DS . $template);
}

/**
 * @param string $marked_string is the marked string and the date you need to pas in which first removes the marked
 *                              zeros, then removes any remaining marks.
 * @return mixed the clean date output
 */
function strip_zeros_from_date($marked_string = "")
{
	$no_zeros       = str_replace('*0', '', $marked_string);
	$cleaned_string = str_replace('*', '', $no_zeros);

	return $cleaned_string;
}

/**
 * @param string $datetime will get the date and time as a simple text
 * @return string ready format to insert into MySQL
 */
function datetime_to_text($datetime = "")
{
	$unixdatetime = strtotime($datetime);

	return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
}

/**
 * @param $string string gets the number as western
 * @return mixed string number as eastern
 */
function convert($string)
{
	$eastern = ['۰', '١', '٢', '٣', '۴', '۵', '۶', '۷', '۸', '۹'];
	$western = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

	return str_replace($western, $eastern, $string);
}

/**
 * @param $size integer parameter getting the size as bytes
 * @return string format for size
 */
function check_size($size)
{
	if($size > 1024000) {
		return round($size / 1024000) . " مگابایت";
	} elseif($size > 1024) {
		return round($size / 1024) . " کیلوبایت";
	} else {
		return $size . " بایت";
	}
}

/**
 * @param        $string string text to truncate
 * @param        $length integer length to truncate from the string
 * @param string $dots   string default (...) to show immediately after the string
 * @return string from 0 character to length and ... after it
 */
function truncate($string, $length, $dots = "... ... ...")
{
	return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
}

/**
 * echo ip_info("173.252.110.27", "Country");
 * echo ip_info("173.252.110.27", "Country Code");
 * echo ip_info("173.252.110.27", "State");
 * echo ip_info("173.252.110.27", "City");
 * echo ip_info("173.252.110.27", "Address");
 * ------------------------------------------
 * echo ip_info("Visitor", "Country");
 * echo ip_info("Visitor", "Country Code");
 * echo ip_info("Visitor", "State");
 * echo ip_info("Visitor", "City");
 * echo ip_info("Visitor", "Address");
 *
 * @param null   $ip          gets the IP address
 * @param string $purpose     gets Country, Country Code, State, City or Address
 * @param bool   $deep_detect TRUE is user is using proxy and FALSE otherwise
 * @return array|null|string of location details
 */
function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
{
	$output = NULL;
	if(filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
		$ip = $_SERVER["REMOTE_ADDR"];
		if($deep_detect) {
			if(filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			if(filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
		}
	}
	$purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
	$support    = array("country", "countrycode", "state", "region", "city", "location", "address");
	$continents = array(
		"AF" => "Africa",
		"AN" => "Antarctica",
		"AS" => "Asia",
		"EU" => "Europe",
		"OC" => "Australia (Oceania)",
		"NA" => "North America",
		"SA" => "South America"
	);
	if(filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
		$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
		if(@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
			switch($purpose) {
				case "location":
					$output = array(
						"city"           => @$ipdat->geoplugin_city,
						"state"          => @$ipdat->geoplugin_regionName,
						"country"        => @$ipdat->geoplugin_countryName,
						"country_code"   => @$ipdat->geoplugin_countryCode,
						"continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
						"continent_code" => @$ipdat->geoplugin_continentCode
					);
					break;
				case "address":
					$address = array($ipdat->geoplugin_countryName);
					if(@strlen($ipdat->geoplugin_regionName) >= 1) {
						$address[] = $ipdat->geoplugin_regionName;
					}
					if(@strlen($ipdat->geoplugin_city) >= 1) {
						$address[] = $ipdat->geoplugin_city;
					}
					$output = implode(", ", array_reverse($address));
					break;
				case "city":
					$output = @$ipdat->geoplugin_city;
					break;
				case "state":
					$output = @$ipdat->geoplugin_regionName;
					break;
				case "region":
					$output = @$ipdat->geoplugin_regionName;
					break;
				case "country":
					$output = @$ipdat->geoplugin_countryName;
					break;
				case "countrycode":
					$output = @$ipdat->geoplugin_countryCode;
					break;
			}
		}
	}

	return $output;
}

/**
 * @param $email
 * @param $subject string gets the subject
 * @param $message string gets the message
 * @return bool TRUE if mail is sent and FALSE otherwise
 */
function send_email($email, $subject, $message)
{
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'From: Parsclick <do-not-reply@parsclick.net>' . "\r\n";

	//$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
	//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
	//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";

	return mail($email, $subject, $message, $headers);
}

/**
 * Sends responsive emails
 *
 * @param string $full_name
 * @param string $site_root
 * @param string $highlight
 * @param string $content
 * @return string
 */
function email($full_name = "", $site_root = DOMAIN, $highlight = "", $content = "")
{
	$output = <<<EMAILBODY
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
														<span style="color: #FFF; font-weight: bold; font-size: 11px;"><a href="http://{$site_root}/" style="color: #2BA6CB; text-decoration: none;">Parscick.net</a></span>
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
														{$full_name} </h1><br/>
													<p style="color: #222; font-family: Tahoma, Helvetica, Arial, sans-serif; font-weight: normal; text-align: right; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="right">
														{$content}
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
														{$highlight}
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
																<a href="https://plus.google.com/+PersianComputers" style="color: #FFF; text-decoration: none; font-weight: normal; font-family: Tahoma, Helvetica, Arial, sans-serif; font-size: 12px; display: block; height: 100%; width: 100%;">Google
																                                                                                                                                                                                                                                    +</a>
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
															<a href="http://{$site_root}/" style="color: #2BA6CB; text-decoration: none;">پارس
															                                                                              کلیک</a> |
															<a href="http://{$site_root}/privacypolicy" style="color: #2BA6CB; text-decoration: none;">شرایط
															                                                                                           و
															                                                                                           ضوابط</a>
															                                                                                       |
															<a href="http://{$site_root}/login" style="color: #2BA6CB; text-decoration: none;">ورود به
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

	return $output;
}

/******************************************************************************************************/
/*                                    SECURITY FUNCTIONS                                              */
/******************************************************************************************************/
/**
 * @return bool TRUE if request is GET and FALSE otherwise
 */
function request_is_get()
{
	return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * @return bool TRUE if request is POST and FALSE otherwise
 */
function request_is_post()
{
	return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * validate value has presence
 *
 * @param $value        string uses trim() so empty spaces don't count
 *                      use === to avoid false positives
 *                      empty() would consider "0" to be empty
 * @return bool true or false
 */
function has_presence($value)
{
	$trimmed_value = trim($value);

	return isset($trimmed_value) && $trimmed_value !== "";
}

/**
 * @param       $value   string validate value has string length
 * @param array $options leading and trailing spaces will count
 * @return bool options: exact, max, min
 *                       has_length($first_name, ['exact' => 20])
 *                       has_length($first_name, ['min' => 5, 'max' => 100])
 */
function has_length($value, $options = [])
{
	if(isset($options['max']) && (strlen($value) > (int)$options['max'])) {
		return FALSE;
	}
	if(isset($options['min']) && (strlen($value) < (int)$options['min'])) {
		return FALSE;
	}
	if(isset($options['exact']) && (strlen($value) != (int)$options['exact'])) {
		return FALSE;
	}

	return TRUE;
}

/**
 * Example:
 * has_format_matching('1234', '/\d{4}/') is true
 * has_format_matching('12345', '/\d{4}/') is also true
 * has_format_matching('12345', '/\A\d{4}\Z/') is false
 *
 * @param        $value string has a format matching
 * @param string $regex regular expression
 *                      Be sure to use anchor expressions to match start and end of string.
 *                      (Use \A and \Z, not ^ and $ which allow line returns.)
 * @return int
 */
function has_format_matching($value, $regex = '//')
{
	return preg_match($regex, $value);
}

/**
 * validate value is a number
 *
 * @param       $value   string so use is_numeric instead of is_int
 * @param array $options : max, min
 * @return bool has_number($items_to_order, ['min' => 1, 'max' => 5])
 */
function has_number($value, $options = [])
{
	if( ! is_numeric($value)) {
		return FALSE;
	}
	if(isset($options['max']) && ($value > (int)$options['max'])) {
		return FALSE;
	}
	if(isset($options['min']) && ($value < (int)$options['min'])) {
		return FALSE;
	}

	return TRUE;
}

/**
 * validate value is included in a set
 *
 * @param       $value
 * @param array $set
 * @return bool
 */
function has_inclusion_in($value, $set = [])
{
	return in_array($value, $set);
}

/**
 * validate value is excluded from a set
 *
 * @param       $value
 * @param array $set
 * @return bool
 */
function has_exclusion_from($value, $set = [])
{
	return ! in_array($value, $set);
}

/**
 * This function will simply check if the parameters given are identical or not
 *
 * @param $id         integer to compare
 * @param $session_id integer to compare
 * @return bool return TRUE if two values are identical
 */
function check_ownership($id, $session_id)
{
	return $id === $session_id;
}

/**
 * @param $file string gets the file name
 * @return mixed of file extensions
 */
function file_extension($file)
{
	$path_parts = pathinfo($file);

	return $path_parts['extension'];
}

/**
 * @param $file string gets the file name
 * @return bool TRUE if file contains PHP in it and FALSE otherwise
 */
function file_contains_php($file)
{
	$contents = file_get_contents($file);
	$position = strpos($contents, '<?php');

	return $position !== FALSE;
}

/**
 * @param $error_integer integer gets the file error number
 * @return mixed of errors descriptions
 */
function file_upload_error($error_integer)
{
	$upload_errors = [
		// http://php.net/manual/en/features.file-upload.errors.php
		UPLOAD_ERR_OK         => "خطایی نیست.",
		UPLOAD_ERR_INI_SIZE   => "فایل بزرگتر از تنظیمات پی اچ پی است!",
		UPLOAD_ERR_FORM_SIZE  => "اندازه فایل بزرگ است!",
		UPLOAD_ERR_PARTIAL    => "فایل نصفه آپلود شد!",
		UPLOAD_ERR_NO_FILE    => "هیچ فایلی انتخاب نشد!",
		UPLOAD_ERR_NO_TMP_DIR => "پوشه موقت موجود نیست!",
		UPLOAD_ERR_CANT_WRITE => "نمیشه روی دیسک نوشت!",
		UPLOAD_ERR_EXTENSION  => "آپلود فایل بخاطر نوع آن متوقف شد!"
	];

	return $upload_errors[$error_integer];
}

/**
 * Check if it's disposable email address
 *
 * @param $mail string gets user email
 * @return bool TRUE if it is disposable and FALSE if not
 */
function is_temp_mail($mail)
{
	$mail_domains_ko = file('https://gist.githubusercontent.com/hassanazimi/d6e49469258d7d06f9f4/raw/disposable_email_addresses');
	foreach($mail_domains_ko as $ko_mail) {
		list(, $mail_domain) = explode('@', $mail);
		if(strcasecmp($mail_domain, trim($ko_mail)) == 0) {
			return TRUE;
		}
	}

	return FALSE;
}

/**
 * @param $output1
 * @param $output2
 * @return string HTML
 */
function warning($output1, $output2)
{
	return "
<!DOCTYPE html>
<html>
<head>
	<title>پارس کلیک - Parsclick</title>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1.0'/>
	<link rel='shortcut icon' type='image/png' href=''/images/favicon.png'/>
	<link rel='stylesheet' href='/_/css/all.css' media='screen'/>
	<style>
		body { background-color : beige; }
		.error-template { padding : 40px 15px; text-align : center; }
	</style>
</head>
<body>
	<div class='container'>
		<div class='row'>
			<section class='col col-md-12'>
				<div class='error-template'>
					<h1>!Error</h1>
					<h2>{$output1}</h2>
					<h3>{$output2}</h3>
				</div>
			</section>
		</div>
	</div>
</body>
</html>
";
}

/******************************************************************************************************/
/*                                       MEMBER'S FUNCTIONS                                           */
/******************************************************************************************************/
/**
 * @param        $action  string represents the login or logout action for each user
 * @param string $message represent the message for every user
 */
function log_action($action, $message = "")
{
	$logfile = SITE_ROOT . DS . 'logs' . DS . 'log.txt';
	$new     = file_exists($logfile) ? FALSE : TRUE;
	if($handle = fopen($logfile, 'a')) { //appends
		$timestamp = datetime_to_text(strftime("%Y-%m-%d %H:%M:%S", time()));
		//		$country   = ip_info("Visitor", "Country");
		//		$content   = "{$timestamp} | {$country} | {$action}: {$message}" . PHP_EOL;
		$content = "{$timestamp} | {$action}: {$message}" . PHP_EOL;
		fwrite($handle, $content);
		fclose($handle);
		if($new) {
			chmod($logfile, 0777);
		}
	} else {
		echo "فایل ثبت قابل نوشتن نیست!";
	}
}

/**
 * Function for super admins to show the subjects and articles
 *
 * @param $subject_array array gets the subject ID form URL and return it as an array
 * @param $article_array array gets the article ID form URL and return it as an array
 * @return string subjects as an HTML ordered list along with articles as an HTML unordered list
 */
function admin_articles($subject_array, $article_array)
{
	$output      = "<ol type='persian'>";
	$subject_set = Subject::find_all(FALSE);
	foreach($subject_set as $subject) {
		$output .= "<li>";
		$output .= "<div class='lead'>";
		$output .= "<a href='admin_articles.php?subject=";
		$output .= urlencode($subject->id) . "'";
		if($subject_array && $subject->id == $subject_array->id) {
			$output .= " class='selected'";
		}
		$output .= ">";
		$output = ! empty($subject->name) ? $output . $subject->name : $output . '-';
		$output .= "</a>";
		if( ! $subject->visible) {
			$output .= "&nbsp;<i class='text-danger fa fa-eye-slash fa-lg'></i>";
		}
		$output .= "</div>";
		$article_set = Article::find_articles_for_subject($subject->id, FALSE);
		$output .= "<ol style='margin-right:20px;'>";
		foreach($article_set as $article) {
			$output .= "<li value='" . $article->position . "'>";
			$output .= "<a href='admin_articles.php?subject=";
			$output .= urlencode($subject->id) . "&article=";
			$output .= $article->id . "'";
			if($article_array && $article->id == $article_array->id) {
				$output .= " class='selected'";
			}
			if($article->comments()) {
				$output .= "data-toggle='tooltip' data-placement='left' title='";
				$output .= count($article->comments()) . " دیدگاه";
				$output .= "'";
			}
			$output .= ">";
			if( ! $article->visible) {
				$output = ! empty($article->name) ? $output . ('<del>' . $article->name . '</del>') : $output . '-';
			} else {
				$output = ! empty($article->name) ? $output . $article->name : $output . '-';
			}
			$output .= "</a>";
			if($article->recent()) {
				$output .= "&nbsp;<kbd>تازه</kbd>";
			}
			$output .= "</li>";
		}
		$output .= "</ol></li>";
	}
	$output .= "</ol>";

	return $output;
}

/**
 * Function for authors to show the subjects and articles
 *
 * @param $subject_array array gets the subject ID form URL and return it as an array
 * @param $article_array array gets the article ID form URL and return it as an array
 * @return string subjects as an HTML ordered list along with articles as an HTML unordered list
 */
function author_articles($subject_array, $article_array)
{
	$output      = "<ul class='list-group'>";
	$subject_set = Subject::find_all(TRUE);
	foreach($subject_set as $subject) {
		$output .= "<li class='list-group-item'>";
		$output .= "<div class='lead'>";
		$output .= "<a href='author_articles.php?subject=";
		$output .= urlencode($subject->id) . "'";
		if($subject_array && $subject->id == $subject_array->id) {
			$output .= " class='selected'";
		}
		$output .= ">";
		$output = ! empty($subject->name) ? $output . $subject->name : $output . '-';
		$output .= "</a>";
		$output .= "</div>";
		$article_set = Article::find_articles_for_subject($subject->id, FALSE);
		$output .= "<ul class='list-unstyled'>";
		foreach($article_set as $article) {
			$output .= "<li>- ";
			$output .= "<a href='author_articles.php?subject=";
			$output .= urlencode($subject->id) . "&article=";
			$output .= $article->id . "'";
			if($article_array && $article->id == $article_array->id) {
				$output .= " class='selected'";
			}
			if($article->comments()) {
				$output .= "data-toggle='tooltip' data-placement='left' title='";
				$output .= count($article->comments()) . " دیدگاه";
				$output .= "'";
			}
			$output .= ">";
			$output = ! empty($article->name) ? $output . $article->name : $output . '-';
			$output .= "</a>";
			if( ! $article->visible) {
				$output .= " <i class='text-danger fa fa-eye-slash fa-lg'></i>";
			}
			if($article->recent()) {
				$output .= "&nbsp;<kbd>تازه</kbd>";
			}
			$output .= "</li>";
		}
		$output .= "</ul></li>";
	}
	$output .= "</ul>";

	return $output;
}

/**
 * Function for members to show the subjects and articles. The difference between this function with administrators
 * functions are instead of all articles to be open for every subjects, the members actually have to click on subjects
 * in order for articles to be open underneath subjects and this happens once for every subject.
 *
 * @param $subject_array array gets the subject ID form URL and return it as an array
 * @param $article_array array gets the article ID form URL and return it as an array
 * @return string subjects as an HTML ordered list along with articles as an HTML unordered list
 */
function member_articles($subject_array, $article_array)
{
	$output      = "<ul class='list-group'>";
	$subject_set = Subject::find_all(TRUE);
	foreach($subject_set as $subject) {
		if(Article::num_articles_for_subject($subject->id)) {
			$output .= "<li class='list-group-item'>";
			$output .= "<span class='badge'>" . Article::count_articles_for_subject($subject->id, TRUE) . "</span>";
			$output .= "<a href='member-articles?subject=";
			$output .= urlencode($subject->id) . "'";
			if($subject_array && $subject->id == $subject_array->id) {
				$output .= " class='lead selected' ";
			}
			$output .= ">";
			$output = ! empty($subject->name) ? $output . $subject->name : $output . '-';
			$output .= "</a>";
			if(Article::count_recent_articles_for_subject($subject->id, TRUE) > 0) {
				$output .= "&nbsp;&nbsp;";
				$output .= "<small><span class='label label-as-badge label-info'>" . convert(Article::count_recent_articles_for_subject($subject->id, TRUE)) . " مقاله جدید</span></small>";
			}
			if($subject_array && $article_array) {
				if($subject_array->id == $subject->id || $article_array->subject_id == $subject->id) {
					$article_set = Article::find_articles_for_subject($subject->id, TRUE);
					$output .= "<ul class='list-unstyled'>";
					foreach($article_set as $article) {
						$output .= "<li>- ";
						$output .= "<a href='member-articles?subject=";
						$output .= urlencode($subject->id) . "&article=";
						$output .= urlencode($article->id) . "'";
						if($article_array && $article->id == $article_array->id) {
							$output .= " class='selected'";
						}
						if($article->comments()) {
							$output .= "data-toggle='tooltip' data-placement='left' title='";
							$output .= count($article->comments()) . " دیدگاه";
							$output .= "'";
						}
						$output .= ">";
						$output = ! empty($article->name) ? $output . $article->name : $output . '-';
						if($article->recent()) {
							$output .= "&nbsp;<kbd>تازه</kbd>";
						}
						$output .= "</a></li>";
					}
					$output .= "</ul>";
				}
			}
			$output .= "</li>";
		}
	}
	$output .= "</ul>";

	return $output;
}

/**
 * Finds all articles for subjects
 *
 * @param bool $public is a condition to select the first article (the default one) for every subject upon clicking on
 *                     subjects and by default is equals to FALSE.
 */
function find_selected_article($public = FALSE)
{
	global $current_subject;
	global $current_article;
	if(isset($_GET["subject"]) && isset($_GET["article"])) {
		$current_subject = Subject::find_by_id($_GET["subject"], $public);
		$current_article = Article::find_by_id($_GET["article"], $public);
	} elseif(isset($_GET["subject"])) {
		$current_subject = Subject::find_by_id($_GET["subject"], $public);
		if($current_subject && $public) {
			$current_article = Article::find_default_article_for_subject($current_subject->id);
		} else {
			$current_article = NULL;
		}
	} elseif(isset($_GET["article"])) {
		$current_article = Article::find_by_id($_GET["article"], $public);
		$current_subject = NULL;
	} else {
		$current_subject = NULL;
		$current_article = NULL;
	}
}

/**
 * Function for super admins to show the categories and courses
 *
 * @param $category_array array gets the subject ID form URL and return it as an array
 * @param $course_array   array gets the article ID form URL and return it as an array
 * @return string categories as an HTML ordered list along with courses as an HTML unordered list
 */
function admin_courses($category_array, $course_array)
{
	$output       = "<ol type='persian'>";
	$category_set = Category::find_all(FALSE);
	foreach($category_set as $category) {
		$output .= "<li>";
		$output .= "<div class='lead'>";
		$output .= "<a href='admin_courses.php?category=";
		$output .= urlencode($category->id) . "'";
		if($category_array && $category->id == $category_array->id) {
			$output .= " class='selected'";
		}
		$output .= ">";
		$output = ! empty($category->name) ? $output . $category->name : $output . '-';
		$output .= "</a>";
		if( ! $category->visible) {
			$output .= "&nbsp;<i class='text-danger fa fa-eye-slash'></i>";
		} else {
			$output .= "&nbsp;<i class='text-success fa fa-eye'></i>";
		}
		$output .= "</div>";
		$course_set = Course::find_courses_for_category($category->id, FALSE);
		$output .= "<ol style='margin-right:20px;'>";
		foreach($course_set as $course) {
			$output .= "<li value='" . $course->position . "'>";
			$output .= "<a href='admin_courses.php?category=";
			$output .= urlencode($category->id) . "&course=";
			$output .= $course->id . "'";
			if($course_array && $course->id == $course_array->id) {
				$output .= " class='selected'";
			}
			if($course->comments()) {
				$output .= "data-toggle='tooltip' data-placement='left' title='";
				$output .= count($course->comments()) . " دیدگاه";
				$output .= "'";
			}
			$output .= ">";
			if( ! $course->visible) {
				$output = ! empty($course->name) ? $output . ('<del>' . $course->name . '</del>') : $output . '-';
			} else {
				$output = ! empty($course->name) ? $output . $course->name : $output . '-';
			}
			$output .= "</a>";
			if($course->recent()) {
				$output .= "&nbsp;<kbd>تازه</kbd>";
			}
			$output .= "</li>";
		}
		$output .= "</ol></li>";
	}
	$output .= "</ol>";

	return $output;
}

/**
 * Function for authors to show the categories and courses
 *
 * @param $category_array array gets the category ID form URL and return it as an array
 * @param $course_array   array gets the course ID form URL and return it as an array
 * @return string categories as an HTML ordered list along with courses as an HTML unordered list
 */
function author_courses($category_array, $course_array)
{
	$output       = "<ul class='list-group'>";
	$category_set = Category::find_all(TRUE);
	foreach($category_set as $category):
		$output .= "<li class='list-group-item'>";
		$output .= "<div class='lead'>";
		$output .= "<a href='author_courses.php?category=";
		$output .= urlencode($category->id) . "'";
		if($category_array && $category->id == $category_array->id) {
			$output .= " class='selected'";
		}
		$output .= ">";
		$output = ! empty($category->name) ? $output . $category->name : $output . '-';
		$output .= "</a>";
		$output .= "</div>";
		$course_set = Course::find_courses_for_category($category->id, FALSE);
		$output .= "<ul class='list-unstyled'>";
		foreach($course_set as $course):
			$output .= "<li>- ";
			$output .= "<a href='author_courses.php?category=";
			$output .= urlencode($category->id) . "&course=";
			$output .= $course->id . "'";
			if($course_array && $course->id == $course_array->id) {
				$output .= " class='selected'";
			}
			if($course->comments()) {
				$output .= "data-toggle='tooltip' data-placement='left' title='";
				$output .= count($course->comments()) . " دیدگاه";
				$output .= "'";
			}
			$output .= ">";
			$output = ! empty($course->name) ? $output . $course->name : $output . '-';
			$output .= "</a>";
			if( ! $course->visible) {
				$output .= "&nbsp;<i class='text-danger fa fa-eye-slash fa-lg'></i>";
			}
			if($course->recent()) {
				$output .= "&nbsp;<kbd>تازه</kbd>";
			}
			$output .= "</li>";
		endforeach;
		$output .= "</ul></li>";
	endforeach;
	$output .= "</ul>";

	return $output;
}

/**
 * Function for members to show the categories and courses. The difference between this function with administrators
 * functions are instead of all courses to be open for every categories, the members actually have to click on
 * categories in order for courses to be open underneath categories and this happens once for every category.
 *
 * @param $category_array array gets the category ID form URL and return it as an array
 * @param $course_array   array gets the course ID form URL and return it as an array
 * @return string categories as an HTML ordered list along with courses as an HTML unordered list
 */
function member_courses($category_array, $course_array)
{
	$output       = "<ul class='list-group'>";
	$category_set = Category::find_all(TRUE);
	foreach($category_set as $category) {
		$output .= "<li class='list-group-item'>";
		$output .= "<span class='badge'>" . Course::count_courses_for_category($category->id, TRUE) . "</span>";
		$output .= "<a href='member-courses?category=";
		$output .= urlencode($category->id) . "'";
		if($category_array && $category->id == $category_array->id) {
			$output .= " class='lead' ";
		}
		$output .= ">";
		$output = ! empty($category->name) ? $output . $category->name : $output . '-';
		$output .= "</a>";
		if(Course::count_recent_course_for_category($category->id, TRUE) > 0) {
			$output .= "&nbsp;&nbsp;";
			$output .= "<small><span class='label label-as-badge label-info'>" . convert(Course::count_recent_course_for_category($category->id, TRUE)) . " درس جدید</span></small>";
		}
		if($category_array && $course_array) {
			if($category_array->id == $category->id || $course_array->category_id == $category->id) {
				$course_set = Course::find_courses_for_category($category->id);
				$output .= "<ul class='list-unstyled'>";
				foreach($course_set as $course) {
					$output .= "<li>- ";
					$output .= "<a href='member-courses?category=";
					$output .= urlencode($category->id) . "&course=";
					$output .= urlencode($course->id) . "'";
					if($course_array && $course->id == $course_array->id) {
						$output .= " class='selected'";
					}
					if($course->comments()) {
						$output .= "data-toggle='tooltip' data-placement='left' title='";
						$output .= count($course->comments()) . " دیدگاه";
						$output .= "'";
					}
					$output .= ">";
					$output = ! empty($course->name) ? $output . $course->name : $output . '-';
					$output .= "</a>";
					if($course->recent()) {
						$output .= "&nbsp;<kbd>تازه</kbd>";
					}
					$output .= "</li>";
				}
				$output .= "</ul>";
			}
		}
		$output .= "</li>";
	}
	$output .= "</ul>";

	return $output;
}

/**
 * Function for members to show the categories and courses. The difference between this function with administrators
 * functions are instead of all courses to be open for every categories, the members actually have to click on
 * categories in order for courses to be open underneath categories and this happens once for every category.
 *
 * @param $category_array array gets the category ID form URL and return it as an array
 * @param $course_array   array gets the course ID form URL and return it as an array
 * @return string categories as an HTML ordered list along with courses as an HTML unordered list
 */
function member_comments_for_course($category_array, $course_array)
{
	$output       = "<ul class='list-group'>";
	$category_set = Category::find_all(TRUE);
	foreach($category_set as $category) {
		$output .= "<li class='list-group-item'>";
		$output .= "<span class='badge'>" . Course::count_courses_for_category($category->id, TRUE) . "</span>";
		$output .= "<a href='member-comments?category=";
		$output .= urlencode($category->id) . "'";
		if($category_array && $category->id == $category_array->id) {
			$output .= " class='lead' ";
		}
		$output .= ">";
		$output = ! empty($category->name) ? $output . $category->name : $output . '-';
		$output .= "</a>";
		if(Course::count_recent_course_for_category($category->id, TRUE) > 0) {
			$output .= "&nbsp;&nbsp;";
			$output .= "<small><span class='label label-as-badge'>" . convert(Course::count_recent_course_for_category($category->id, TRUE)) . " درس جدید</span></small>";
		}
		if($category_array && $course_array) {
			if($category_array->id == $category->id || $course_array->category_id == $category->id) {
				$course_set = Course::find_courses_for_category($category->id);
				$output .= "<ul class='list-unstyled'>";
				foreach($course_set as $course) {
					$output .= "<li>- ";
					$output .= "<a href='member-comments?category=";
					$output .= urlencode($category->id) . "&course=";
					$output .= urlencode($course->id) . "'";
					if($course_array && $course->id == $course_array->id) {
						$output .= " class='selected'";
					}
					if($course->comments()) {
						$output .= "data-toggle='tooltip' data-placement='left' title='";
						$output .= count($course->comments()) . " دیدگاه";
						$output .= "'";
					}
					$output .= ">";
					$output = ! empty($course->name) ? $output . $course->name : $output . '-';
					if($course->recent()) {
						$output .= "&nbsp;<kbd>تازه</kbd>";
					}
					$output .= "</a></li>";
				}
				$output .= "</ul>";
			}
		}
		$output .= "</li>";
	}
	$output .= "</ul>";

	return $output;
}

/**
 * Function for public to show the categories and courses
 *
 * @return string categories as an HTML ordered list along with courses as an HTML unordered list
 */
function public_courses()
{
	$output       = "<ol class='list-unstyled'>";
	$category_set = Category::find_all(TRUE);
	foreach($category_set as $category) {
		$output .= "<li>";
		$output .= "<h3>";
		$output = ! empty($category->name) ? $output . $category->name : $output . '-';
		$output .= "</h3>";
		$course_set = Course::find_courses_for_category($category->id, TRUE);
		$output .= "<ul>";
		foreach($course_set as $course) {
			$output .= "<li>";
			$output .= "<a target='_blank' data-toggle='tooltip' data-placement='left' title='برو به یوتیوب' href='https://www.youtube.com/playlist?list=";
			$output .= $course->youtubePlaylist;
			$output .= "'>";
			$output = ! empty($course->name) ? $output . $course->name : $output . '-';
			$output .= "</a>";
			if($course->recent()) {
				$output .= "&nbsp;<span class='lead'><kbd>تازه</kbd></span>";
			}
			$output .= "</li>";
		}
		$output .= "</ul></li>";
	}
	$output .= "</ol>";

	return $output;
}

/**
 * Function for public to show the subjects and articles
 *
 * @param $subject_array
 * @param $article_array
 * @return string subject as an HTML ordered list along with courses as an HTML unordered list
 */
function public_articles($subject_array, $article_array)
{
	$output      = "<ul class='list-group'>";
	$subject_set = Subject::find_all(TRUE);
	foreach($subject_set as $subject) {
		if(Article::num_articles_for_subject($subject->id)) {
			$output .= "<li class='list-group-item'>";
			$output .= "<span class='badge'>" . Article::count_articles_for_subject($subject->id, TRUE) . "</span>";
			$output .= "<a href='/articles?subject=" . urlencode($subject->id) . "'";
			if($subject_array && $subject->id == $subject_array->id) {
				$output .= " class='lead selected' ";
			}
			$output .= ">";
			$output = ! empty($subject->name) ? $output . $subject->name : $output . '-';
			$output .= "</a>";
			if(Article::count_recent_articles_for_subject($subject->id, TRUE) > 0) {
				$output .= "&nbsp;&nbsp;";
				$output .= "<span class='label label-as-badge label-primary'>" . convert(Article::count_recent_articles_for_subject($subject->id, TRUE)) . " مقاله جدید</span>";
			}
			if($subject_array && $article_array) {
				if($subject_array->id == $subject->id || $article_array->subject_id == $subject->id) {
					$article_set = Article::find_articles_for_subject($subject->id, TRUE);
					$output .= "<ul class='list-unstyled'>";
					foreach($article_set as $article) {
						$output .= "<li>- ";
						$output .= "<a href='/articles?subject=" . urlencode($subject->id) . "&article=" . urlencode($article->id) . "'";
						if($article_array && $article->id == $article_array->id) {
							$output .= " class='selected'";
						}
						$output .= ">";
						$output = ! empty($article->name) ? $output . $article->name : $output . '-';
						if($article->recent()) {
							$output .= "&nbsp;<kbd>تازه</kbd>";
						}
						$output .= "</a></li>";
					}
					$output .= "</ul>";
				}
			}
			$output .= "</li>";
		}
	}
	$output .= "</ul>";

	return $output;
}

/**
 * Finds all courses for categories
 *
 * @param bool $public is a condition to select the first course (the default one) for every category upon clicking on
 *                     categories and by default is equals to FALSE.
 */
function find_selected_course($public = FALSE)
{
	global $current_category;
	global $current_course;
	if(isset($_GET["category"]) && isset($_GET["course"])) {
		$current_category = Category::find_by_id($_GET["category"], $public);
		$current_course   = Course::find_by_id($_GET["course"], $public);
	} elseif(isset($_GET["category"])) {
		$current_category = Category::find_by_id($_GET["category"], $public);
		if($current_category && $public) {
			$current_course = Course::find_default_course_for_category($current_category->id);
		} else {
			$current_course = NULL;
		}
	} elseif(isset($_GET["course"])) {
		$current_course   = Course::find_by_id($_GET["course"], $public);
		$current_category = NULL;
	} else {
		$current_category = NULL;
		$current_course   = NULL;
	}
}

/**
 * Adds Bootstrap pagination under pages which need pagination*
 *
 * @param        $pagination
 * @param        $page
 * @param string $main_url
 * @param string $url1
 * @param string $url2
 * @return string
 */
function paginate($pagination, $page, $main_url = '', $url1 = '', $url2 = '')
{
	$output = '';
	if($pagination->total_page() > 1) {
		$output .= '<nav class="clearfix center">';
		$output .= '<ul class="pagination">';
		if($pagination->has_previous_page()) {
			$output .= '<li>';
			$output .= '<a href="' . $main_url . '?page=' . urlencode($pagination->previous_page());
			if( ! empty($url1)) {
				$output .= '&' . $url1;
			}
			if( ! empty($url2)) {
				$output .= '&' . $url2;
			}
			$output .= '" aria-label="Previous">';
			$output .= '<span aria-hidden="true"> &lt;&lt; </span>';
			$output .= '</a>';
			$output .= '</li>';
		}
		for($i = 1; $i < $pagination->total_page() + 1; $i++) {
			if($i == $page) {
				$output .= '<li class="active">';
				$output .= '<span>' . convert($i) . '</span>';
				$output .= '</li>';
			} else {
				$output .= '<li>';
				$output .= '<a href="' . $main_url . '?page=' . urlencode($i);
				if( ! empty($url1)) {
					$output .= '&' . $url1;
				}
				if( ! empty($url2)) {
					$output .= '&' . $url2;
				}
				$output .= '">' . convert($i) . '</a>';
				$output .= '</li>';
			}
		}
		if($pagination->has_next_page()) {
			$output .= '<li>';
			$output .= '<a href="' . $main_url . '?page=' . urlencode($pagination->next_page());
			if( ! empty($url1)) {
				$output .= '&' . $url1;
			}
			if( ! empty($url2)) {
				$output .= '&' . $url2;
			}
			$output .= '" aria-label="Next">';
			$output .= '<span aria-hidden="true">&gt;&gt;</span>';
			$output .= '</a>';
			$output .= '</li>';
		}
		$output .= '</ul>';
		$output .= '</nav>';
	}

	return $output;
}

/**
 * This function adds the active class by jQuery for the navbar by checking the file name.
 * There is <?php $filename = basename(__FILE__); ?> on top of every PHP file which finds the file name and based on
 * that name jQuery adds the active class for the particular menu.
 */
function active()
{
	global $filename;
	if(($filename == "index.php") || ($filename == "member.php") || ($filename == "admin.php") || ($filename == "author.php")) {
		echo "<script>$(\"a:contains('خانه')\").parent().addClass('active');</script>";
	} elseif($filename == "authors.php") {
		echo "<script>$(\"a:contains('نویسندگان')\").parent().addClass('active');</script>";
	} elseif($filename == "about.php") {
		echo "<script>$(\"a:contains('درباره ما')\").parent().addClass('active');</script>";
	} elseif($filename == "faq.php") {
		echo "<script>$(\"a:contains('سوالات شما')\").parent().addClass('active');</script>";
		include('_/components/php/smoothscrolling.php');
	} elseif($filename == "help.php") {
		echo "<script>$(\"a:contains('کمک به ما')\").parent().addClass('active');</script>";
	} elseif(($filename == "login.php") || ($filename == "register.php") || ($filename == "forgot.php") || ($filename == "reset-password.php") || ($filename == "forgot-username.php")) {
		echo "<script>$(\"a:contains('ورود')\").parent().addClass('active');</script>";
	} elseif(($filename == "admin_courses.php") || ($filename == "admin_articles.php") || ($filename == "new_subject.php") || ($filename == "author_articles.php") || ($filename == "author_courses.php") || ($filename == "new_courses.php") || ($filename == "edit_courses.php") || ($filename == "new_article.php") || ($filename == "edit_article.php") || ($filename == "author_edit_article.php") || ($filename == "new_course.php") || ($filename == "author_edit_course.php") || ($filename == "author_add_video.php") || ($filename == "author_edit_video_description.php") || ($filename == "edit_video_description.php") || ($filename == "admin_comments.php") || ($filename == "edit_course.php") || ($filename == "member-courses.php") || ($filename == "member-articles.php")) {
		echo "<script>$(\"a:contains('محتوی')\").parent().addClass('active');</script>";
		if(($filename == "member-courses.php") || ($filename == "admin_courses.php") || ($filename == "author_courses.php")) {
			echo "<script>$(\"a:contains('دروس')\").parent().addClass('active');</script>";
		} elseif(($filename == "admin_articles.php") || ($filename == "author_articles.php") || ($filename == "member-articles.php")) {
			echo "<script>$(\"a:contains('مقالات')\").parent().addClass('active');</script>";
		}
	} elseif($filename == "articles.php") {
		echo "<script>$(\"a:contains('مقالات')\").parent().addClass('active');</script>";
	} elseif($filename == "courses.php") {
		echo "<script>$(\"a:contains('دروس')\").parent().addClass('active');</script>";
	} elseif(($filename == "member-profile.php") || ($filename == "member-edit-profile.php") || ($filename == "author_profile.php") || ($filename == "author_edit_profile.php")) {
		echo "<script>$(\"a:contains('حساب کاربری')\").parent().addClass('active');</script>";
	} elseif($filename == "member-playlist.php") {
		echo "<script>$(\"a:contains('لیست پخش')\").parent().addClass('active');</script>";
	} elseif($filename == "member-comments.php") {
		echo "<script>$(\"a:contains('انجمن')\").parent().addClass('active');</script>";
	} elseif(($filename == "member_list.php") || ($filename == "edit_member.php") || ($filename == "new_member.php") || ($filename == "email_to_members.php")) {
		echo "<script>$(\"a:contains('اعضا')\").parent().addClass('active');</script>";
		if($filename == "email_to_members.php") {
			echo "<script>$(\"a:contains(' ایمیل به عضوها')\").parent().addClass('active');</script>";
		} elseif($filename == "member_list.php") {
			echo "<script>$(\"a:contains(' لیست عضوها')\").parent().addClass('active');</script>";
		}
	} elseif(($filename == "admin_list.php") || ($filename == "author_list.php") || ($filename == "new_admin.php") || ($filename == "new_author.php") || ($filename == "edit_admin.php") || ($filename == "edit_author.php") || ($filename == "email_to_authors.php")) {
		echo "<script>$(\"a:contains('کارکنان')\").parent().addClass('active');</script>";
		if($filename == "admin_list.php") {
			echo "<script>$(\"a:contains('لیست مدیران')\").parent().addClass('active');</script>";
		} elseif($filename == "author_list.php") {
			echo "<script>$(\"a:contains('لیست نویسندگان')\").parent().addClass('active');</script>";
		} elseif($filename == "email_to_authors.php") {
			echo "<script>$(\"a:contains('ایمیل به نویسندگان')\").parent().addClass('active');</script>";
		}
	} elseif(($filename == "contact.php")) {
		echo "<script>$(\"a:contains('تماس با ما')\").parent().addClass('active');</script>";
		include('notice.php');
	}
}

/******************************************************************************************************/
/*                                       COOKIE FUNCTIONS                                             */
/******************************************************************************************************/
/**
 * @param $salt   string gets the salt to add to the @param $string
 * @param $string $string string gets the text
 * @return string encrypts the string
 */
function encrypt_string($salt, $string)
{
	// Configuration (must match decryption)
	$cipher_type = MCRYPT_RIJNDAEL_256;
	$cipher_mode = MCRYPT_MODE_CBC;
	// Using initialization vector adds more security
	$iv_size          = mcrypt_get_iv_size($cipher_type, $cipher_mode);
	$iv               = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$encrypted_string = mcrypt_encrypt($cipher_type, $salt, $string, $cipher_mode, $iv);
	// Return initialization vector + encrypted string
	// We'll need the $iv when decoding.
	return $iv . $encrypted_string;
}

/**
 * @param $salt           string gets the salt to add to the @param $string
 * @param $iv_with_string string initialization vector
 * @return string decrypts the string
 */
function decrypt_string($salt, $iv_with_string)
{
	// Configuration (must match encryption)
	$cipher_type = MCRYPT_RIJNDAEL_256;
	$cipher_mode = MCRYPT_MODE_CBC;
	// Extract the initialization vector from the encrypted string.
	// The $iv comes before encrypted string and has fixed size.
	$iv_size          = mcrypt_get_iv_size($cipher_type, $cipher_mode);
	$iv               = substr($iv_with_string, 0, $iv_size);
	$encrypted_string = substr($iv_with_string, $iv_size);
	$string           = mcrypt_decrypt($cipher_type, $salt, $encrypted_string, $cipher_mode, $iv);

	return $string;
}

/**
 * @param $salt   string gets the salt to add to the @param $string
 * @param $string string gets the text
 * @return string encode after encryption to ensure encrypted characters are savable
 */
function encrypt_string_and_encode($salt, $string)
{
	return base64_encode(encrypt_string($salt, $string));
}

/**
 * @param $salt   string gets the salt to add it to the @param $string
 * @param $string string gets the text
 * @return string and decodes before decryption
 */
function decrypt_string_and_decode($salt, $string)
{
	return decrypt_string($salt, base64_decode($string));
}

/**
 * @param $string string gets the cookie or any text
 * @return string signs cookie or any string by applying hashing algorithm and salting
 */
function sign_string($string)
{
	// Using $salt makes it hard to guess how $checksum is generated
	// Caution: changing salt will invalidate all signed strings
	$salt     = "Simple salt";
	$checksum = sha1($string . $salt); // Any hash algorithm would work
	// return the string with the checksum at the end
	return $string . '--' . $checksum;
}

/**
 * @param $signed_string string gets the cookie or any signed string signed by @function sign_string
 * @return bool TRUE if new signed string equals to the signed string and FALSE if otherwise
 */
function signed_string_is_valid($signed_string)
{
	$array = explode('--', $signed_string);
	if(count($array) != 2) {
		// string is malformed or not signed
		return FALSE;
	}
	// Sign the string portion again. Should create same
	// checksum and therefore the same signed string.
	$new_signed_string = sign_string($array[0]);
	if($new_signed_string == $signed_string) {
		return TRUE;
	} else {
		return FALSE;
	}
}

