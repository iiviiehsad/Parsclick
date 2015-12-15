<?php
/**
 * @param $class_name String will get the class name for each PHP class and finds the file name associate to it
 */
function __autoload($class_name)
{
	$class_name = strtolower($class_name);
	$path       = LIB_PATH . DS . $class_name . "php";
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
	if(! empty($message)) {
		$output = "<div class='alert alert-success alert-dismissible' role='alert'>";
		$output .= "<button type='button' class='close' data-dismiss='alert'>";
		$output .= "<span aria-hidden='true'>&times;</span>";
		$output .= "<span class='sr-only'></span>";
		$output .= "</button>";
		$output .= "<i class='fa fa-check-circle-o fa-fw fa-lg'></i> ";
		$output .= "<strong>" . htmlentities($message) . "</strong>";
		$output .= "</div>";
		return $output;
	} elseif(! empty($errors)) {
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
 * @param $size integer parameter getting the size as bytes
 * @return string format for size
 */
function check_size($size)
{
	if($size > 1024000) {
		return round($size / 1024000) . " MB";
	} elseif($size > 1024) {
		return round($size / 1024) . " KB";
	} else {
		return $size . " bytes";
	}
}

/**
 * @param        $string string text to truncate
 * @param        $length integer length to truncate from the string
 * @param string $dots   string default (...) to show immediately after the string
 * @return string from 0 character to length and ... after it
 */
function truncate($string, $length, $dots = " (برای ادامه کلیک کنید ...) ")
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

/** validate value is a number
 * @param       $value   string so use is_numeric instead of is_int
 * @param array $options : max, min
 * @return bool has_number($items_to_order, ['min' => 1, 'max' => 5])
 */
function has_number($value, $options = [])
{
	if(! is_numeric($value)) {
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
 * @param $id         integer to compare
 * @param $session_id integer to compare
 * @return bool return TRUE if two values are identical
 */
function check_ownership($id, $session_id)
{
	if($id === $session_id) {
		return TRUE;
	} else {
		return FALSE;
	}
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

function is_temp_mail($mail)
{
	$mail_domains_ko = [
			'0815.ru',
			'0815.ru0clickemail.com',
			'0815.ry',
			'0815.su',
			'0845.ru',
			'0clickemail.com',
			'0-mail.com',
			'0wnd.net',
			'0wnd.org',
			'10mail.com',
			'10mail.org',
			'10minut.com.pl',
			'10minute-email.com',
			'10minutemail.cf',
			'10minutemail.co.uk',
			'10minutemail.co.za',
			'10minutemail.com',
			'10minutemail.de',
			'10minutemail.ga',
			'10minutemail.gq',
			'10minutemail.ml',
			'10minutemail.net',
			'10minutesmail.com',
			'10x9.com',
			'123-m.com',
			'126.com',
			'12houremail.com',
			'12minutemail.com',
			'12minutemail.net',
			'139.com',
			'163.com',
			'1ce.us',
			'1chuan.com',
			'1fsdfdsfsdf.tk',
			'1mail.ml',
			'1pad.de',
			'1zhuan.com',
			'20mail.it',
			'20email.eu',
			'20minutemail.com',
			'21cn.com',
			'24hourmail.com',
			'2fdgdfgdfgdf.tk',
			'2prong.com',
			'30minutemail.com',
			'33mail.com',
			'3d-painting.com',
			'3mail.ga',
			'3trtretgfrfe.tk',
			'420blaze.it',
			'4gfdsgfdgfd.tk',
			'4mail.cf',
			'4mail.ga',
			'4warding.com',
			'4warding.net',
			'4warding.org',
			'5ghgfhfghfgh.tk',
			'5mail.cf',
			'5mail.ga',
			'60minutemail.com',
			'675hosting.com',
			'675hosting.net',
			'675hosting.org',
			'6hjgjhgkilkj.tk',
			'6ip.us',
			'6mail.cf',
			'6mail.ga',
			'6mail.ml',
			'6paq.com',
			'6url.com',
			'75hosting.com',
			'75hosting.net',
			'75hosting.org',
			'7days-printing.com',
			'7mail.ga',
			'7mail.ml',
			'7tags.com',
			'8127ep.com',
			'8chan.co',
			'8mail.cf',
			'8mail.ga',
			'8mail.ml',
			'99experts.com',
			'9mail.cf',
			'9ox.net',
			'a.mailcker.com',
			'a.vztc.com',
			'a45.in',
			'a-bc.net',
			'abyssmail.com',
			'afrobacon.com',
			'ag.us.to',
			'agedmail.com',
			'ajaxapp.net',
			'akapost.com',
			'akerd.com',
			'aktiefmail.nl',
			'alivance.com',
			'amail4.me',
			'ama-trade.de',
			'ama-trans.de',
			'amilegit.com',
			'amiri.net',
			'amiriindustries.com',
			'anappthat.com',
			'ano-mail.net',
			'anonbox.net',
			'anon-mail.de',
			'anonmails.de',
			'anonymail.dk',
			'anonymbox.com',
			'anonymousmail.org',
			'anonymousspeech.com',
			'antichef.com',
			'antichef.net',
			'antireg.com',
			'antireg.ru',
			'antispam.de',
			'antispam24.de',
			'antispammail.de',
			'armyspy.com',
			'artman-conception.com',
			'asdasd.nl',
			'asdasd.ru',
			'atvclub.msk.ru',
			'auti.st',
			'avpa.nl',
			'azmeil.tk',
			'b2cmail.de',
			'baxomale.ht.cx',
			'beddly.com',
			'beefmilk.com',
			'big1.us',
			'bigprofessor.so',
			'bigstring.com',
			'binkmail.com',
			'bio-muesli.info',
			'bio-muesli.net',
			'blackmarket.to',
			'bladesmail.net',
			'bloatbox.com',
			'blogmyway.org',
			'blogos.com',
			'bluebottle.com',
			'bobmail.info',
			'bodhi.lawlita.com',
			'bofthew.com',
			'bootybay.de',
			'boun.cr',
			'bouncr.com',
			'boxformail.in',
			'boximail.com',
			'br.mintemail.com',
			'brainonfire.net',
			'breakthru.com',
			'brefmail.com',
			'brennendesreich.de',
			'broadbandninja.com',
			'bsnow.net',
			'bspamfree.org',
			'bu.mintemail.com',
			'buffemail.com',
			'bugmenever.com',
			'bugmenot.com',
			'bumpymail.com',
			'bund.us',
			'bundes-li.ga',
			'burnthespam.info',
			'burstmail.info',
			'buymoreplays.com',
			'buyusedlibrarybooks.org',
			'byom.de',
			'c2.hu',
			'cachedot.net',
			'cam4you.cc',
			'card.zp.ua',
			'casualdx.com',
			'cc.liamria',
			'cek.pm',
			'cellurl.com',
			'centermail.com',
			'centermail.net',
			'chammy.info',
			'cheatmail.de',
			'childsavetrust.org',
			'chogmail.com',
			'choicemail1.com',
			'chong-mail.com',
			'chong-mail.net',
			'chong-mail.org',
			'clixser.com',
			'clrmail.com',
			'cmail.com',
			'cmail.net',
			'cmail.org',
			'cock.li',
			'coieo.com',
			'coldemail.info',
			'consumerriot.com',
			'cool.fr.nf',
			'correo.blogos.net',
			'cosmorph.com',
			'courriel.fr.nf',
			'courrieltemporaire.com',
			'crapmail.org',
			'crazymailing.com',
			'cubiclink.com',
			'cumallover.me',
			'curryworld.de',
			'cust.in',
			'cuvox.de',
			'd3p.dk',
			'dacoolest.com',
			'dandikmail.com',
			'dayrep.com',
			'dbunker.com',
			'dcemail.com',
			'deadaddress.com',
			'deadchildren.org',
			'deadfake.cf',
			'deadfake.ga',
			'deadfake.ml',
			'deadfake.tk',
			'deadspam.com',
			'deagot.com',
			'dealja.com',
			'delikkt.de',
			'despam.it',
			'despammed.com',
			'devnullmail.com',
			'dfgh.net',
			'dharmatel.net',
			'dicksinhisan.us',
			'dicksinmyan.us',
			'digitalsanctuary.com',
			'dingbone.com',
			'discard.cf',
			'discard.email',
			'discard.ga',
			'discard.gq',
			'discard.ml',
			'discard.tk',
			'discardmail.com',
			'discardmail.de',
			'disposable.cf',
			'disposable.ga',
			'disposable.ml',
			'disposableaddress.com',
			'disposable-email.ml',
			'disposableemailaddresses.com',
			'disposableinbox.com',
			'dispose.it',
			'disposeamail.com',
			'disposemail.com',
			'dispostable.com',
			'divermail.com',
			'dm.w3internet.co.uk',
			'dm.w3internet.co.ukexample.com',
			'docmail.com',
			'dodgeit.com',
			'dodgit.com',
			'dodgit.org',
			'doiea.com',
			'domozmail.com',
			'donemail.ru',
			'dontreg.com',
			'dontsendmespam.de',
			'dotman.de',
			'dotmsg.com',
			'drdrb.com',
			'drdrb.net',
			'dropcake.de',
			'droplister.com',
			'dropmail.me',
			'dudmail.com',
			'dumpandjunk.com',
			'dump-email.info',
			'dumpmail.de',
			'dumpyemail.com',
			'duskmail.com',
			'e4ward.com',
			'easytrashmail.com',
			'edv.to',
			'ee1.pl',
			'ee2.pl',
			'eelmail.com',
			'einmalmail.de',
			'einrot.com',
			'einrot.de',
			'eintagsmail.de',
			'e-mail.com',
			'email.net',
			'e-mail.org',
			'email60.com',
			'emailage.cf',
			'emailage.ga',
			'emailage.gq',
			'emailage.ml',
			'emailage.tk',
			'emaildienst.de',
			'email-fake.cf',
			'email-fake.ga',
			'email-fake.gq',
			'email-fake.ml',
			'email-fake.tk',
			'emailgo.de',
			'emailias.com',
			'emailigo.de',
			'emailinfive.com',
			'emaillime.com',
			'emailmiser.com',
			'emails.ga',
			'emailsensei.com',
			'emailspam.cf',
			'emailspam.ga',
			'emailspam.gq',
			'emailspam.ml',
			'emailspam.tk',
			'emailtemporanea.com',
			'emailtemporanea.net',
			'emailtemporar.ro',
			'emailtemporario.com.br',
			'emailthe.net',
			'emailtmp.com',
			'emailto.de',
			'emailwarden.com',
			'emailx.at.hm',
			'emailxfer.com',
			'emailz.cf',
			'emailz.ga',
			'emailz.gq',
			'emailz.ml',
			'emall.ir',
			'emeil.in',
			'emeil.ir',
			'emkei.cf',
			'emkei.ga',
			'emkei.gq',
			'emkei.ml',
			'emkei.tk',
			'emz.net',
			'enterto.com',
			'ephemail.net',
			'e-postkasten.com',
			'e-postkasten.de',
			'e-postkasten.eu',
			'e-postkasten.info',
			'ero-tube.org',
			'etranquil.com',
			'etranquil.net',
			'etranquil.org',
			'evopo.com',
			'example.com',
			'explodemail.com',
			'express.net.ua',
			'eyepaste.com',
			'facebook-email.cf',
			'facebook-email.ga',
			'facebook-email.ml',
			'facebookmail.gq',
			'facebookmail.ml',
			'faecesmail.me',
			'fakedemail.com',
			'fakeinbox.cf',
			'fakeinbox.com',
			'fakeinbox.ga',
			'fakeinbox.ml',
			'fakeinbox.tk',
			'fakeinformation.com',
			'fake-mail.cf',
			'fakemail.fr',
			'fake-mail.ga',
			'fake-mail.ml',
			'fakemailgenerator.com',
			'fakemailz.com',
			'fammix.com',
			'fansworldwide.de',
			'fantasymail.de',
			'fastacura.com',
			'fastchevy.com',
			'fastchrysler.com',
			'fastermail.com',
			'fastkawasaki.com',
			'fastmail.fm',
			'fastmazda.com',
			'fastmitsubishi.com',
			'fastnissan.com',
			'fastsubaru.com',
			'fastsuzuki.com',
			'fasttoyota.com',
			'fastyamaha.com',
			'fatflap.com',
			'fdfdsfds.com',
			'fightallspam.com',
			'film-blog.biz',
			'filzmail.com',
			'fivemail.de',
			'fixmail.tk',
			'fizmail.com',
			'fleckens.hu',
			'flurred.com',
			'flyspam.com',
			'fly-ts.de',
			'footard.com',
			'forgetmail.com',
			'fornow.eu',
			'fr33mail.info',
			'frapmail.com',
			'freecoolemail.com',
			'free-email.cf',
			'free-email.ga',
			'freeletter.me',
			'freemail.ms',
			'freemails.cf',
			'freemails.ga',
			'freemails.ml',
			'freundin.ru',
			'friendlymail.co.uk',
			'front14.org',
			'fuckingduh.com',
			'fuckmail.me',
			'fudgerub.com',
			'fux0ringduh.com',
			'fyii.de',
			'garbagemail.org',
			'garliclife.com',
			'garrifulio.mailexpire.com',
			'gawab.com',
			'gehensiemirnichtaufdensack.de',
			'gelitik.in',
			'geschent.biz',
			'get1mail.com',
			'get2mail.fr',
			'getairmail.cf',
			'getairmail.com',
			'getairmail.ga',
			'getairmail.gq',
			'getairmail.ml',
			'getairmail.tk',
			'get-mail.cf',
			'get-mail.ga',
			'get-mail.ml',
			'get-mail.tk',
			'getmails.eu',
			'getonemail.com',
			'getonemail.net',
			'ghosttexter.de',
			'giantmail.de',
			'girlsundertheinfluence.com',
			'gishpuppy.com',
			'gmal.com',
			'gmial.com',
			'gmx.com',
			'goat.si',
			'goemailgo.com',
			'gomail.in',
			'gorillaswithdirtyarmpits.com',
			'gotmail.com',
			'gotmail.net',
			'gotmail.org',
			'gotti.otherinbox.com',
			'gowikibooks.com',
			'gowikicampus.com',
			'gowikicars.com',
			'gowikifilms.com',
			'gowikigames.com',
			'gowikimusic.com',
			'gowikinetwork.com',
			'gowikitravel.com',
			'gowikitv.com',
			'grandmamail.com',
			'grandmasmail.com',
			'great-host.in',
			'greensloth.com',
			'grr.la',
			'gsrv.co.uk',
			'guerillamail.biz',
			'guerillamail.com',
			'guerillamail.net',
			'guerillamail.org',
			'guerillamailblock.com',
			'guerrillamail.biz',
			'guerrillamail.com',
			'guerrillamail.de',
			'guerrillamail.info',
			'guerrillamail.net',
			'guerrillamail.org',
			'guerrillamailblock.com',
			'gustr.com',
			'h.mintemail.com',
			'h8s.org',
			'hacccc.com',
			'haltospam.com',
			'harakirimail.com',
			'hartbot.de',
			'hatespam.org',
			'hat-geld.de',
			'herp.in',
			'hidemail.de',
			'hidzz.com',
			'hmamail.com',
			'hochsitze.com',
			'hooohush.ai',
			'hopemail.biz',
			'horsefucker.org',
			'hotmai.com',
			'hot-mail.cf',
			'hot-mail.ga',
			'hot-mail.gq',
			'hot-mail.ml',
			'hot-mail.tk',
			'hotmial.com',
			'hotpop.com',
			'huajiachem.cn',
			'hulapla.de',
			'humaility.com',
			'hush.ai',
			'hush.com',
			'hushmail.com',
			'hushmail.me',
			'i2pmail.org',
			'ieatspam.eu',
			'ieatspam.info',
			'ieh-mail.de',
			'ignoremail.com',
			'ihateyoualot.info',
			'iheartspam.org',
			'ikbenspamvrij.nl',
			'imails.info',
			'imgof.com',
			'imgv.de',
			'imstations.com',
			'inbax.tk',
			'inbox.si',
			'inbox2.info',
			'inboxalias.com',
			'inboxclean.com',
			'inboxclean.org',
			'inboxdesign.me',
			'inboxed.im',
			'inboxed.pw',
			'inboxstore.me',
			'incognitomail.com',
			'incognitomail.net',
			'incognitomail.org',
			'infocom.zp.ua',
			'insorg-mail.info',
			'instantemailaddress.com',
			'instant-mail.de',
			'iozak.com',
			'ip6.li',
			'ipoo.org',
			'irish2me.com',
			'iroid.com',
			'is.af',
			'iwantmyname.com',
			'iwi.net',
			'jetable.com',
			'jetable.fr.nf',
			'jetable.net',
			'jetable.org',
			'jnxjn.com',
			'jourrapide.com',
			'jsrsolutions.com',
			'junk.to',
			'junk1e.com',
			'junkmail.ga',
			'junkmail.gq',
			'k2-herbal-incenses.com',
			'kasmail.com',
			'kaspop.com',
			'keepmymail.com',
			'killmail.com',
			'killmail.net',
			'kir.ch.tc',
			'klassmaster.com',
			'klassmaster.net',
			'kloap.com',
			'klzlk.com',
			'kmhow.com',
			'kostenlosemailadresse.de',
			'koszmail.pl',
			'kulturbetrieb.info',
			'kurzepost.de',
			'l33r.eu',
			'lackmail.net',
			'lags.us',
			'landmail.co',
			'lastmail.co',
			'lavabit.com',
			'lawlita.com',
			'letthemeatspam.com',
			'lhsdv.com',
			'lifebyfood.com',
			'link2mail.net',
			'linuxmail.so',
			'litedrop.com',
			'llogin.ru',
			'loadby.us',
			'login-email.cf',
			'login-email.ga',
			'login-email.ml',
			'login-email.tk',
			'lol.com',
			'lol.ovpn.to',
			'lolfreak.net',
			'lookugly.com',
			'lopl.co.cc',
			'lortemail.dk',
			'losemymail.com',
			'lovebitco.in',
			'lovemeleaveme.com',
			'loves.dicksinhisan.us',
			'loves.dicksinmyan.us',
			'lr7.us',
			'lr78.com',
			'lroid.com',
			'luckymail.org',
			'lukop.dk',
			'luv2.us',
			'm21.cc',
			'm4ilweb.info',
			'ma1l.bij.pl',
			'maboard.com',
			'mac.hush.com',
			'mail.by',
			'mail.me',
			'mail.mezimages.net',
			'mail.ru',
			'mail.zp.ua',
			'mail114.net',
			'mail1a.de',
			'mail21.cc',
			'mail2rss.org',
			'mail2world.com',
			'mail333.com',
			'mail4trash.com',
			'mailbidon.com',
			'mailbiz.biz',
			'mailblocks.com',
			'mailbucket.org',
			'mailcat.biz',
			'mailcatch.com',
			'mailde.de',
			'mailde.info',
			'maildrop.cc',
			'maildrop.cf',
			'maildrop.ga',
			'maildrop.gq',
			'maildrop.ml',
			'maildu.de',
			'maileater.com',
			'mailed.in',
			'maileimer.de',
			'mailexpire.com',
			'mailfa.tk',
			'mail-filter.com',
			'mailforspam.com',
			'mailfree.ga',
			'mailfree.gq',
			'mailfree.ml',
			'mailfreeonline.com',
			'mailguard.me',
			'mailhazard.com',
			'mailhazard.us',
			'mailhz.me',
			'mailimate.com',
			'mailin8r.com',
			'mailinater.com',
			'mailinator.com',
			'mailinator.gq',
			'mailinator.net',
			'mailinator.org',
			'mailinator.us',
			'mailinator2.com',
			'mailincubator.com',
			'mailismagic.com',
			'mailita.tk',
			'mailjunk.cf',
			'mailjunk.ga',
			'mailjunk.gq',
			'mailjunk.ml',
			'mailjunk.tk',
			'mailme.gq',
			'mailme.ir',
			'mailme.lv',
			'mailme24.com',
			'mailmetrash.com',
			'mailmoat.com',
			'mailms.com',
			'mailnator.com',
			'mailnesia.com',
			'mailnull.com',
			'mailorg.org',
			'mailpick.biz',
			'mailquack.com',
			'mailrock.biz',
			'mailsac.com',
			'mailscrap.com',
			'mailseal.de',
			'mailshell.com',
			'mailsiphon.com',
			'mailslapping.com',
			'mailslite.com',
			'mailtemp.info',
			'mail-temporaire.fr',
			'mailtome.de',
			'mailtothis.com',
			'mailtrash.net',
			'mailtv.net',
			'mailtv.tv',
			'mailwithyou.com',
			'mailzilla.com',
			'mailzilla.org',
			'makemetheking.com',
			'malahov.de',
			'manifestgenerator.com',
			'manybrain.com',
			'mbx.cc',
			'mega.zik.dj',
			'meinspamschutz.de',
			'meltmail.com',
			'messagebeamer.de',
			'mezimages.net',
			'mierdamail.com',
			'migmail.pl',
			'migumail.com',
			'ministry-of-silly-walks.de',
			'mintemail.com',
			'misterpinball.de',
			'mjukglass.nu',
			'mmmmail.com',
			'moakt.com',
			'mobi.web.id',
			'mobileninja.co.uk',
			'moburl.com',
			'moncourrier.fr.nf',
			'monemail.fr.nf',
			'monmail.fr.nf',
			'monumentmail.com',
			'ms9.mailslite.com',
			'msa.minsmail.com',
			'msb.minsmail.com',
			'msg.mailslite.com',
			'mt2009.com',
			'mt2014.com',
			'mt2015.com',
			'muchomail.com',
			'mx0.wwwnew.eu',
			'my10minutemail.com',
			'mycard.net.ua',
			'mycleaninbox.net',
			'myemailboxy.com',
			'mymail-in.net',
			'mynetstore.de',
			'mypacks.net',
			'mypartyclip.de',
			'myphantomemail.com',
			'mysamp.de',
			'myspaceinc.com',
			'myspaceinc.net',
			'myspaceinc.org',
			'myspacepimpedup.com',
			'myspamless.com',
			'mytempemail.com',
			'mytempmail.com',
			'mythrashmail.net',
			'mytrashmail.com',
			'nabuma.com',
			'national.shitposting.agency',
			'naver.com',
			'neomailbox.com',
			'nepwk.com',
			'nervmich.net',
			'nervtmich.net',
			'netmails.com',
			'netmails.net',
			'netzidiot.de',
			'neverbox.com',
			'nevermail.de',
			'nice-4u.com',
			'nigge.rs',
			'nincsmail.hu',
			'nmail.cf',
			'nnh.com',
			'noblepioneer.com',
			'nobugmail.com',
			'nobulk.com',
			'nobuma.com',
			'noclickemail.com',
			'nogmailspam.info',
			'nomail.pw',
			'nomail.xl.cx',
			'nomail2me.com',
			'nomorespamemails.com',
			'nonspam.eu',
			'nonspammer.de',
			'noref.in',
			'nospam.wins.com.br',
			'no-spam.ws',
			'nospam.ze.tc',
			'nospam4.us',
			'nospamfor.us',
			'nospammail.net',
			'nospamthanks.info',
			'notmailinator.com',
			'notsharingmy.info',
			'nowhere.org',
			'nowmymail.com',
			'ntlhelp.net',
			'nullbox.info',
			'nurfuerspam.de',
			'nus.edu.sg',
			'nwldx.com',
			'o2.co.uk',
			'o2.pl',
			'objectmail.com',
			'obobbo.com',
			'odaymail.com',
			'odnorazovoe.ru',
			'ohaaa.de',
			'omail.pro',
			'oneoffemail.com',
			'oneoffmail.com',
			'onewaymail.com',
			'onlatedotcom.info',
			'online.ms',
			'oopi.org',
			'opayq.com',
			'ordinaryamerican.net',
			'otherinbox.com',
			'ourklips.com',
			'outlawspam.com',
			'ovpn.to',
			'owlpic.com',
			'pancakemail.com',
			'paplease.com',
			'pcusers.otherinbox.com',
			'pepbot.com',
			'pfui.ru',
			'phentermine-mortgages-texas-holdem.biz',
			'pimpedupmyspace.com',
			'pjjkp.com',
			'plexolan.de',
			'poczta.onet.pl',
			'politikerclub.de',
			'poofy.org',
			'pookmail.com',
			'postonline.me',
			'powered.name',
			'privacy.net',
			'privatdemail.net',
			'privy-mail.com',
			'privymail.de',
			'privy-mail.de',
			'proxymail.eu',
			'prtnx.com',
			'prtz.eu',
			'punkass.com',
			'put2.net',
			'putthisinyourspamdatabase.com',
			'pwrby.com',
			'qasti.com',
			'qisdo.com',
			'qisoa.com',
			'qoika.com',
			'qq.com',
			'quickinbox.com',
			'quickmail.nl',
			'rcpt.at',
			'rcs.gaggle.net',
			'reallymymail.com',
			'realtyalerts.ca',
			'receiveee.com',
			'recode.me',
			'recursor.net',
			'recyclemail.dk',
			'redchan.it',
			'regbypass.com',
			'regbypass.comsafe-mail.net',
			'rejectmail.com',
			'reliable-mail.com',
			'remail.cf',
			'remail.ga',
			'rhyta.com',
			'rklips.com',
			'rmqkr.net',
			'royal.net',
			'rppkn.com',
			'rtrtr.com',
			's0ny.net',
			'safe-mail.net',
			'safersignup.de',
			'safetymail.info',
			'safetypost.de',
			'sandelf.de',
			'saynotospams.com',
			'scatmail.com',
			'schafmail.de',
			'schmeissweg.tk',
			'schrott-email.de',
			'secmail.pw',
			'secretemail.de',
			'secure-mail.biz',
			'secure-mail.cc',
			'selfdestructingmail.com',
			'selfdestructingmail.org',
			'sendspamhere.com',
			'senseless-entertainment.com',
			'server.ms',
			'services391.com',
			'sharklasers.com',
			'shieldedmail.com',
			'shieldemail.com',
			'shiftmail.com',
			'shitmail.me',
			'shitmail.org',
			'shitware.nl',
			'shmeriously.com',
			'shortmail.net',
			'shut.name',
			'shut.ws',
			'sibmail.com',
			'sify.com',
			'sina.cn',
			'sina.com',
			'sinnlos-mail.de',
			'siteposter.net',
			'skeefmail.com',
			'sky-ts.de',
			'slapsfromlastnight.com',
			'slaskpost.se',
			'slave-auctions.net',
			'slopsbox.com',
			'slushmail.com',
			'smaakt.naar.gravel',
			'smapfree24.com',
			'smapfree24.de',
			'smapfree24.eu',
			'smapfree24.info',
			'smapfree24.org',
			'smashmail.de',
			'smellfear.com',
			'snakemail.com',
			'sneakemail.com',
			'sneakmail.de',
			'snkmail.com',
			'sofimail.com',
			'sofortmail.de',
			'sofort-mail.de',
			'sogetthis.com',
			'sohu.com',
			'solvemail.info',
			'soodomail.com',
			'soodonims.com',
			'spam.la',
			'spam.su',
			'spam4.me',
			'spamail.de',
			'spamarrest.com',
			'spamavert.com',
			'spam-be-gone.com',
			'spambob.com',
			'spambob.net',
			'spambob.org',
			'spambog.com',
			'spambog.de',
			'spambog.net',
			'spambog.ru',
			'spambooger.com',
			'spambox.info',
			'spambox.irishspringrealty.com',
			'spambox.org',
			'spambox.us',
			'spamcannon.com',
			'spamcannon.net',
			'spamcero.com',
			'spamcon.org',
			'spamcorptastic.com',
			'spamcowboy.com',
			'spamcowboy.net',
			'spamcowboy.org',
			'spamday.com',
			'spamdecoy.net',
			'spamex.com',
			'spamfighter.cf',
			'spamfighter.ga',
			'spamfighter.gq',
			'spamfighter.ml',
			'spamfighter.tk',
			'spamfree.eu',
			'spamfree24.com',
			'spamfree24.de',
			'spamfree24.eu',
			'spamfree24.info',
			'spamfree24.net',
			'spamfree24.org',
			'spamgoes.in',
			'spamgourmet.com',
			'spamgourmet.net',
			'spamgourmet.org',
			'spamherelots.com',
			'spamhereplease.com',
			'spamhole.com',
			'spamify.com',
			'spaminator.de',
			'spamkill.info',
			'spaml.com',
			'spaml.de',
			'spammotel.com',
			'spamobox.com',
			'spamoff.de',
			'spamsalad.in',
			'spamslicer.com',
			'spamspot.com',
			'spamstack.net',
			'spamthis.co.uk',
			'spamthisplease.com',
			'spamtrail.com',
			'spamtroll.net',
			'speed.1s.fr',
			'spoofmail.de',
			'squizzy.de',
			'sry.li',
			'ssoia.com',
			'startkeys.com',
			'stinkefinger.net',
			'stop-my-spam.cf',
			'stop-my-spam.com',
			'stop-my-spam.ga',
			'stop-my-spam.ml',
			'stop-my-spam.tk',
			'stuffmail.de',
			'suioe.com',
			'super-auswahl.de',
			'supergreatmail.com',
			'supermailer.jp',
			'superplatyna.com',
			'superrito.com',
			'superstachel.de',
			'suremail.info',
			'sweetxxx.de',
			'swift10minutemail.com',
			'tafmail.com',
			'tagyourself.com',
			'talkinator.com',
			'tapchicuoihoi.com',
			'techemail.com',
			'techgroup.me',
			'teewars.org',
			'teleworm.com',
			'teleworm.us',
			'temp.emeraldwebmail.com',
			'tempail.com',
			'tempalias.com',
			'tempemail.biz',
			'tempemail.co.za',
			'tempemail.com',
			'tempe-mail.com',
			'tempemail.net',
			'tempimbox.com',
			'tempinbox.co.uk',
			'tempinbox.com',
			'tempmail.eu',
			'tempmail.it',
			'temp-mail.org',
			'temp-mail.ru',
			'tempmail2.com',
			'tempmaildemo.com',
			'tempmailer.com',
			'tempmailer.de',
			'tempomail.fr',
			'temporarily.de',
			'temporarioemail.com.br',
			'temporaryemail.net',
			'temporaryemail.us',
			'temporaryforwarding.com',
			'temporaryinbox.com',
			'temporarymailaddress.com',
			'tempthe.net',
			'tempymail.com',
			'tfwno.gf',
			'thanksnospam.info',
			'thankyou2010.com',
			'thc.st',
			'thecloudindex.com',
			'thelimestones.com',
			'thisisnotmyrealemail.com',
			'thismail.net',
			'thrma.com',
			'throam.com',
			'throwawayemailaddress.com',
			'throwawaymail.com',
			'tijdelijkmailadres.nl',
			'tilien.com',
			'tittbit.in',
			'tizi.com',
			'tmail.com',
			'tmailinator.com',
			'toiea.com',
			'tokem.co',
			'toomail.biz',
			'topcoolemail.com',
			'topfreeemail.com',
			'topranklist.de',
			'tormail.net',
			'tormail.org',
			'tradermail.info',
			'trash2009.com',
			'trash2010.com',
			'trash2011.com',
			'trash-amil.com',
			'trashcanmail.com',
			'trashdevil.com',
			'trashdevil.de',
			'trashemail.de',
			'trashinbox.com',
			'trashmail.at',
			'trash-mail.at',
			'trash-mail.cf',
			'trashmail.com',
			'trash-mail.com',
			'trashmail.de',
			'trash-mail.de',
			'trash-mail.ga',
			'trash-mail.gq',
			'trashmail.me',
			'trash-mail.ml',
			'trashmail.net',
			'trashmail.org',
			'trash-mail.tk',
			'trashmail.ws',
			'trashmailer.com',
			'trashymail.com',
			'trashymail.net',
			'trayna.com',
			'trbvm.com',
			'trialmail.de',
			'trickmail.net',
			'trillianpro.com',
			'tryalert.com',
			'turual.com',
			'twinmail.de',
			'tyldd.com',
			'ubismail.net',
			'uggsrock.com',
			'umail.net',
			'upliftnow.com',
			'uplipht.com',
			'uroid.com',
			'us.af',
			'uyhip.com',
			'valemail.net',
			'venompen.com',
			'verticalscope.com',
			'veryrealemail.com',
			'veryrealmail.com',
			'vidchart.com',
			'viditag.com',
			'viewcastmedia.com',
			'viewcastmedia.net',
			'viewcastmedia.org',
			'vipmail.name',
			'vipmail.pw',
			'viralplays.com',
			'vistomail.com',
			'vomoto.com',
			'vpn.st',
			'vsimcard.com',
			'vubby.com',
			'vztc.com',
			'walala.org',
			'walkmail.net',
			'wants.dicksinhisan.us',
			'wants.dicksinmyan.us',
			'wasteland.rfc822.org',
			'watchfull.net',
			'watch-harry-potter.com',
			'webemail.me',
			'webm4il.info',
			'webuser.in',
			'wegwerfadresse.de',
			'wegwerfemail.com',
			'wegwerfemail.de',
			'wegwerf-email.de',
			'weg-werf-email.de',
			'wegwerfemail.net',
			'wegwerf-email.net',
			'wegwerfemail.org',
			'wegwerf-email-addressen.de',
			'wegwerfemailadresse.com',
			'wegwerf-email-adressen.de',
			'wegwerf-emails.de',
			'wegwerfmail.de',
			'wegwerfmail.info',
			'wegwerfmail.net',
			'wegwerfmail.org',
			'wegwerpmailadres.nl',
			'wegwrfmail.de',
			'wegwrfmail.net',
			'wegwrfmail.org',
			'wetrainbayarea.com',
			'wetrainbayarea.org',
			'wh4f.org',
			'whatiaas.com',
			'whatpaas.com',
			'whatsaas.com',
			'whopy.com',
			'whyspam.me',
			'wickmail.net',
			'wilemail.com',
			'willhackforfood.biz',
			'willselfdestruct.com',
			'winemaven.info',
			'wmail.cf',
			'wolfsmail.tk',
			'writeme.us',
			'wronghead.com',
			'wuzup.net',
			'wuzupmail.net',
			'www.e4ward.com',
			'www.gishpuppy.com',
			'www.mailinator.com',
			'wwwnew.eu',
			'x.ip6.li',
			'xagloo.co',
			'xagloo.com',
			'xemaps.com',
			'xents.com',
			'xmail.com',
			'xmaily.com',
			'xoxox.cc',
			'xoxy.net',
			'xxtreamcam.com',
			'xyzfree.net',
			'yandex.com',
			'yanet.me',
			'yapped.net',
			'yeah.net',
			'yep.it',
			'yogamaven.com',
			'yomail.info',
			'yopmail.com',
			'yopmail.fr',
			'yopmail.gq',
			'yopmail.net',
			'youmail.ga',
			'youmailr.com',
			'yourdomain.com',
			'you-spam.com',
			'ypmail.webarnak.fr.eu.org',
			'yuurok.com',
			'yxzx.net',
			'z1p.biz',
			'za.com',
			'zebins.com',
			'zebins.eu',
			'zehnminuten.de',
			'zehnminutenmail.de',
			'zetmail.com',
			'zippymail.info',
			'zoaxe.com',
			'zoemail.com',
			'zoemail.net',
			'zoemail.org',
			'zomg.info',
	];
	foreach($mail_domains_ko as $ko_mail) {
		list(, $mail_domain) = explode('@', $mail);
		if(strcasecmp($mail_domain, $ko_mail) == 0) {
			return TRUE;
		}
	}
	return FALSE;
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
		$content   = "{$timestamp} | {$action}: {$message}" . PHP_EOL;
		fwrite($handle, $content);
		fclose($handle);
		if($new) {
			chmod($logfile, 0777);
		}
	} else {
		echo "Could not open log file for writing";
	}
}

/**
 * Function for super admins to show the subjects and articles
 * @param $subject_array array gets the subject ID form URL and return it as an array
 * @param $article_array array gets the article ID form URL and return it as an array
 * @return string subjects as an HTML ordered list along with articles as an HTML unordered list
 */
function admin_articles($subject_array, $article_array)
{
	$output      = "<ol>";
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
		if(! empty($subject->name)) {
			$output .= htmlentities(ucwords($subject->name));
		} else {
			$output .= htmlentities("(موضوع اسم ندارد)");
		}
		$output .= "</a>";
		if(! $subject->visible) {
			$output .= "&nbsp;<i class='text-danger fa fa-eye-slash fa-lg'></i>";
		} else {
			$output .= "&nbsp;<i class='text-success fa fa-eye'></i>";
		}
		$output .= "</div>";
		$article_set = Article::find_articles_for_subject($subject->id, FALSE);
		$output .= "<ul>";
		foreach($article_set as $article) {
			$output .= "<li>";
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
			if(! empty($article->name)) {
				$output .= htmlentities(ucwords($article->name));
			} else {
				$output .= htmlentities("(مقاله اسم ندارد)");
			}
			$output .= "</a>";
			if(! $article->visible) {
				$output .= "&nbsp;<i class='text-danger fa fa-eye-slash'></i>";
			}
			$output .= "</li>";
		}
		$output .= "</ul></li>";
	}
	$output .= "</ol>";
	return $output;
}

/**
 * Function for authors to show the subjects and articles
 * @param $subject_array array gets the subject ID form URL and return it as an array
 * @param $article_array array gets the article ID form URL and return it as an array
 * @return string subjects as an HTML ordered list along with articles as an HTML unordered list
 */
function author_articles($subject_array, $article_array)
{
	$output      = "<ol>";
	$subject_set = Subject::find_all(TRUE);
	foreach($subject_set as $subject):
		$output .= "<li>";
		$output .= "<div class='lead'>";
		$output .= "<a href='author_articles.php?subject=";
		$output .= urlencode($subject->id) . "'";
		if($subject_array && $subject->id == $subject_array->id) {
			$output .= " class='selected'";
		}
		$output .= ">";
		if(! empty($subject->name)) {
			$output .= htmlentities(ucwords($subject->name));
		} else {
			$output .= htmlentities("(موضوع اسم ندارد)");
		}
		$output .= "</a>";
		$output .= "</div>";
		$article_set = Article::find_articles_for_subject($subject->id, FALSE);
		$output .= "<ul>";
		foreach($article_set as $article):
			$output .= "<li>";
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
			if(! empty($article->name)) {
				$output .= htmlentities(ucwords($article->name));
			} else {
				$output .= htmlentities("(مقاله اسم ندارد)");
			}
			$output .= "</a>";
			if(! $article->visible) {
				$output .= " <i class='text-danger fa fa-eye-slash fa-lg'></i>";
			}
			$output .= "</li>";
		endforeach;
		$output .= "</ul></li>";
	endforeach;
	$output .= "</ol>";
	return $output;
}

/**
 * Function for members to show the subjects and articles. The difference between this function with administrators
 * functions are instead of all articles to be open for every subjects, the members actually have to click on subjects
 * in order for articles to be open underneath subjects and this happens once for every subject.
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
				$output .= " style='font-size:25px;' ";
			}
			$output .= ">";
			if(! empty($subject->name)) {
				$output .= htmlentities(ucwords($subject->name));
			} else {
				$output .= htmlentities("(موضوع اسم ندارد!)");
			}
			$output .= "</a>";
			if($subject_array && $article_array) {
				if($subject_array->id == $subject->id || $article_array->subject_id == $subject->id) {
					$article_set = Article::find_articles_for_subject($subject->id, TRUE);
					$output .= "<ul>";
					foreach($article_set as $article) {
						$output .= "<li>";
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
						if(! empty($article->name)) {
							$output .= htmlentities(ucwords($article->name));
						} else {
							$output .= htmlentities("(مقاله اسم ندارد!)");
						}
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
 * @param $category_array array gets the subject ID form URL and return it as an array
 * @param $course_array   array gets the article ID form URL and return it as an array
 * @return string categories as an HTML ordered list along with courses as an HTML unordered list
 */
function admin_courses($category_array, $course_array)
{
	$output       = "<ol>";
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
		if(! empty($category->name)) {
			$output .= htmlentities(ucwords($category->name));
		} else {
			$output .= htmlentities("(موضوع اسم ندارد)");
		}
		$output .= "</a>";
		if(! $category->visible) {
			$output .= "&nbsp;<i class='text-danger fa fa-eye-slash'></i>";
		} else {
			$output .= "&nbsp;<i class='text-success fa fa-eye'></i>";
		}
		$output .= "</div>";
		$course_set = Course::find_courses_for_category($category->id, FALSE);
		$output .= "<ul>";
		foreach($course_set as $course) {
			$output .= "<li>";
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
			if(! empty($course->name)) {
				$output .= htmlentities(ucwords($course->name));
			} else {
				$output .= htmlentities("(درس اسم ندارد)");
			}
			$output .= "</a>";
			if(! $course->visible) {
				$output .= "&nbsp;<i class='text-danger fa fa-eye-slash'></i>";
			}
			$output .= "</li>";
		}
		$output .= "</ul></li>";
	}
	$output .= "</ol>";
	return $output;
}

/**
 * Function for authors to show the categories and courses
 * @param $category_array array gets the category ID form URL and return it as an array
 * @param $course_array   array gets the course ID form URL and return it as an array
 * @return string categories as an HTML ordered list along with courses as an HTML unordered list
 */
function author_courses($category_array, $course_array)
{
	$output       = "<ol>";
	$category_set = Category::find_all(TRUE);
	foreach($category_set as $category):
		$output .= "<li>";
		$output .= "<div class='lead'>";
		$output .= "<a href='author_courses.php?category=";
		$output .= urlencode($category->id) . "'";
		if($category_array && $category->id == $category_array->id) {
			$output .= " class='selected'";
		}
		$output .= ">";
		if(! empty($category->name)) {
			$output .= htmlentities(ucwords($category->name));
		} else {
			$output .= htmlentities("(no category title)");
		}
		$output .= "</a>";
		$output .= "</div>";
		$course_set = Course::find_courses_for_category($category->id, FALSE);
		$output .= "<ul>";
		foreach($course_set as $course):
			$output .= "<li>";
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
			if(! empty($course->name)) {
				$output .= htmlentities(ucwords($course->name));
			} else {
				$output .= htmlentities("(no course title)");
			}
			$output .= "</a>";
			if(! $course->visible) //if visibility is FALSE
			{
				$output .= "&nbsp;<i class='text-danger fa fa-eye-slash'></i>";
			}
			$output .= "</li>";
		endforeach;
		$output .= "</ul></li>";
	endforeach;
	$output .= "</ol>";
	return $output;
}

/**
 * Function for members to show the categories and courses. The difference between this function with administrators
 * functions are instead of all courses to be open for every categories, the members actually have to click on
 * categories in order for courses to be open underneath categories and this happens once for every category.
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
			$output .= " style='font-size:25px;' ";
		}
		$output .= ">";
		if(! empty($category->name)) {
			$output .= htmlentities(ucwords($category->name));
		} else {
			$output .= htmlentities("(موضوع اسم ندارد!)");
		}
		$output .= "</a>";
		if($category_array && $course_array) {
			if($category_array->id == $category->id || $course_array->category_id == $category->id) {
				$course_set = Course::find_courses_for_category($category->id);
				$output .= "<ul>";
				foreach($course_set as $course) {
					$output .= "<li>";
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
					if(! empty($course->name)) {
						$output .= htmlentities(ucwords($course->name));
					} else {
						$output .= htmlentities("(درس اسم ندارد!)");
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
 * Function for members to show the categories and courses. The difference between this function with administrators
 * functions are instead of all courses to be open for every categories, the members actually have to click on
 * categories in order for courses to be open underneath categories and this happens once for every category.
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
			$output .= " style='font-size:25px;' ";
		}
		$output .= ">";
		if(! empty($category->name)) {
			$output .= htmlentities(ucwords($category->name));
		} else {
			$output .= htmlentities("(موضوع اسم ندارد!)");
		}
		$output .= "</a>";
		if($category_array && $course_array) {
			if($category_array->id == $category->id || $course_array->category_id == $category->id) {
				$course_set = Course::find_courses_for_category($category->id);
				$output .= "<ul>";
				foreach($course_set as $course) {
					$output .= "<li>";
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
					if(! empty($course->name)) {
						$output .= htmlentities(ucwords($course->name));
					} else {
						$output .= htmlentities("(درس اسم ندارد!)");
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
 * @return string categories as an HTML ordered list along with courses as an HTML unordered list
 */
function public_courses()
{
	$output       = "<ol class='list-unstyled'>";
	$category_set = Category::find_all(TRUE);
	foreach($category_set as $category) {
		$output .= "<li>";
		$output .= "<h3>";
		if(! empty($category->name)) {
			$output .= htmlentities(ucwords($category->name));
		} else {
			$output .= htmlentities("موضوع اسم ندارد");
		}
		$output .= "</h3>";
		$course_set = Course::find_courses_for_category($category->id, TRUE);
		$output .= "<ul>";
		foreach($course_set as $course) {
			$output .= "<li>";
			$output .= "<a target='_blank' data-toggle='tooltip' data-placement='left' title='برو به یوتیوب' href='https://www.youtube.com/playlist?list=";
			$output .= $course->youtubePlaylist;
			$output .= "'>";
			if(! empty($course->name)) {
				$output .= htmlentities(ucwords($course->name));
			} else {
				$output .= htmlentities("درس اسم ندارد");
			}
			$output .= "</a></li>";
		}
		$output .= "</ul></li>";
	}
	$output .= "</ol>";
	return $output;
}

/**
 * Function for public to show the subjects and articles
 * @return string subject as an HTML ordered list along with courses as an HTML unordered list
 */
function public_articles()
{
	$output      = "<ol class='list-unstyled'>";
	$subject_set = Subject::find_all(TRUE);
	foreach($subject_set as $subject) {
		if(Article::num_articles_for_subject($subject->id)) {
			$output .= "<li>";
			$output .= "<h3>";
			if(! empty($subject->name)) {
				$output .= htmlentities(ucwords($subject->name));
			} else {
				$output .= htmlentities("موضوع اسم ندارد");
			}
			$output .= "</h3>";
			$article_set = Article::find_articles_for_subject($subject->id, TRUE);
			$output .= "<ul>";
			foreach($article_set as $article) {
				$output .= "<li>";
				$output .= "<a data-toggle='tooltip' data-placement='left' title='وارد شوید' href='login'>";
				if(! empty($article->name)) {
					$output .= htmlentities(ucwords($article->name));
				} else {
					$output .= htmlentities("مقاله اسم ندارد");
				}
				if($article->recent()) {
					$output .= "&nbsp;<kbd>تازه</kbd>";
				}
				$output .= "</a></li>";
			}
			$output .= "</ul></li>";
		}
	}
	$output .= "</ol>";
	return $output;
}

/**
 * Finds all courses for categories
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
 * This function adds the active class by jQuery for the navbar by checking the file name.
 * There is <?php $filename = basename(__FILE__); ?> on top of every PHP file which finds the file name and based on
 * that name jQuery adds the active class for the particular menu.
 */
function active()
{
	global $filename;
	if(($filename == "index.php") || ($filename == "member.php") || ($filename == "admin.php") ||
	   ($filename == "author.php")
	) {
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
	} elseif(($filename == "login.php") || ($filename == "register.php") || ($filename == "forgot.php") ||
	         ($filename == "reset-password.php") || ($filename == "forgot-username.php")
	) {
		echo "<script>$(\"a:contains('ورود')\").parent().addClass('active');</script>";
	} elseif(($filename == "admin_courses.php") || ($filename == "admin_articles.php") ||
	         ($filename == "new_subject.php") || ($filename == "author_articles.php") ||
	         ($filename == "author_courses.php") || ($filename == "new_courses.php") ||
	         ($filename == "edit_courses.php") || ($filename == "new_article.php") || ($filename == "edit_article.php") ||
	         ($filename == "author_edit_article.php") || ($filename == "new_course.php") ||
	         ($filename == "author_edit_course.php") || ($filename == "author_add_video.php") ||
	         ($filename == "author_edit_video_description.php") || ($filename == "edit_video_description.php") ||
	         ($filename == "admin_comments.php") || ($filename == "edit_course.php") || ($filename == "courses.php") ||
	         ($filename == "articles.php") || ($filename == "member-courses.php") || ($filename == "member-articles.php")
	) {
		echo "<script>$(\"a:contains('محتوی')\").parent().addClass('active');</script>";
		if(($filename == "courses.php") || ($filename == "member-courses.php") || ($filename == "admin_courses.php") ||
		   ($filename == "author_courses.php")
		) {
			echo "<script>$(\"a:contains('دروس')\").parent().addClass('active');</script>";
		} elseif(($filename == "articles.php") || ($filename == "admin_articles.php") ||
		         ($filename == "author_articles.php") || ($filename == "member-articles.php")
		) {
			echo "<script>$(\"a:contains('مقالات')\").parent().addClass('active');</script>";
		}
	} elseif(($filename == "member-profile.php") || ($filename == "member-edit-profile.php") ||
	         ($filename == "author_profile.php") || ($filename == "author_edit_profile.php")
	) {
		echo "<script>$(\"a:contains('حساب کاربری')\").parent().addClass('active');</script>";
	} elseif($filename == "member-playlist.php") {
		echo "<script>$(\"a:contains('لیست پخش')\").parent().addClass('active');</script>";
	} elseif(($filename == "member_list.php") || ($filename == "edit_member.php") || ($filename == "new_member.php") ||
	         ($filename == "email_to_members.php")
	) {
		echo "<script>$(\"a:contains('اعضا')\").parent().addClass('active');</script>";
		if($filename == "email_to_members.php") {
			echo "<script>$(\"a:contains(' ایمیل به عضوها')\").parent().addClass('active');</script>";
		} elseif($filename == "member_list.php") {
			echo "<script>$(\"a:contains(' لیست عضوها')\").parent().addClass('active');</script>";
		}
	} elseif(($filename == "admin_list.php") || ($filename == "author_list.php") || ($filename == "new_admin.php") ||
	         ($filename == "new_author.php") || ($filename == "edit_admin.php") || ($filename == "edit_author.php") ||
	         ($filename == "email_to_authors.php")
	) {
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
