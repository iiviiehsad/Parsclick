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
	if(!empty($message)) {
		$output = "<div class='alert alert-success alert-dismissible' role='alert'>";
		$output .= "<button type='button' class='close' data-dismiss='alert'>";
		$output .= "<span aria-hidden='true'>&times;</span>";
		$output .= "<span class='sr-only'></span>";
		$output .= "</button>";
		$output .= "<i class='fa fa-check-circle-o fa-fw fa-lg'></i> ";
		$output .= "<strong>" . htmlentities($message) . "</strong>";
		$output .= "</div>";
		return $output;
	} elseif(!empty($errors)) {
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
	if(!is_numeric($value)) {
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
	return !in_array($value, $set);
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
			'0wnd.net',
			'0wnd.org',
			'10minutemail.co.za',
			'10minutemail.com',
			'123-m.com',
			'1fsdfdsfsdf.tk',
			'1pad.de',
			'20minutemail.com',
			'21cn.com',
			'2fdgdfgdfgdf.tk',
			'2prong.com',
			'30minutemail.com',
			'33mail.com',
			'3trtretgfrfe.tk',
			'4gfdsgfdgfd.tk',
			'4warding.com',
			'5ghgfhfghfgh.tk',
			'6hjgjhgkilkj.tk',
			'6paq.com',
			'7tags.com',
			'9ox.net',
			'a-bc.net',
			'agedmail.com',
			'ama-trade.de',
			'amilegit.com',
			'amiri.net',
			'amiriindustries.com',
			'anonmails.de',
			'anonymbox.com',
			'antichef.com',
			'antichef.net',
			'antireg.ru',
			'antispam.de',
			'antispammail.de',
			'armyspy.com',
			'artman-conception.com',
			'azmeil.tk',
			'baxomale.ht.cx',
			'beefmilk.com',
			'bigstring.com',
			'binkmail.com',
			'bio-muesli.net',
			'bobmail.info',
			'bodhi.lawlita.com',
			'bofthew.com',
			'bootybay.de',
			'boun.cr',
			'bouncr.com',
			'breakthru.com',
			'brefmail.com',
			'bsnow.net',
			'bspamfree.org',
			'bugmenot.com',
			'bund.us',
			'burstmail.info',
			'buymoreplays.com',
			'byom.de',
			'c2.hu',
			'card.zp.ua',
			'casualdx.com',
			'cek.pm',
			'centermail.com',
			'centermail.net',
			'chammy.info',
			'childsavetrust.org',
			'chogmail.com',
			'choicemail1.com',
			'clixser.com',
			'cmail.net',
			'cmail.org',
			'coldemail.info',
			'cool.fr.nf',
			'courriel.fr.nf',
			'courrieltemporaire.com',
			'crapmail.org',
			'cust.in',
			'cuvox.de',
			'd3p.dk',
			'dacoolest.com',
			'dandikmail.com',
			'dayrep.com',
			'dcemail.com',
			'deadaddress.com',
			'deadspam.com',
			'delikkt.de',
			'despam.it',
			'despammed.com',
			'devnullmail.com',
			'dfgh.net',
			'digitalsanctuary.com',
			'dingbone.com',
			'disposableaddress.com',
			'disposableemailaddresses.com',
			'disposableinbox.com',
			'dispose.it',
			'dispostable.com',
			'dodgeit.com',
			'dodgit.com',
			'donemail.ru',
			'dontreg.com',
			'dontsendmespam.de',
			'drdrb.net',
			'dump-email.info',
			'dumpandjunk.com',
			'dumpyemail.com',
			'e-mail.com',
			'e-mail.org',
			'e4ward.com',
			'easytrashmail.com',
			'einmalmail.de',
			'einrot.com',
			'eintagsmail.de',
			'emailgo.de',
			'emailias.com',
			'emaillime.com',
			'emailsensei.com',
			'emailtemporanea.com',
			'emailtemporanea.net',
			'emailtemporar.ro',
			'emailtemporario.com.br',
			'emailthe.net',
			'emailtmp.com',
			'emailwarden.com',
			'emailx.at.hm',
			'emailxfer.com',
			'emeil.in',
			'emeil.ir',
			'emz.net',
			'ero-tube.org',
			'evopo.com',
			'explodemail.com',
			'express.net.ua',
			'eyepaste.com',
			'fakeinbox.com',
			'fakeinformation.com',
			'fansworldwide.de',
			'fantasymail.de',
			'fightallspam.com',
			'filzmail.com',
			'fivemail.de',
			'fleckens.hu',
			'frapmail.com',
			'friendlymail.co.uk',
			'fuckingduh.com',
			'fudgerub.com',
			'fyii.de',
			'garliclife.com',
			'gehensiemirnichtaufdensack.de',
			'get2mail.fr',
			'getairmail.com',
			'getmails.eu',
			'getonemail.com',
			'giantmail.de',
			'girlsundertheinfluence.com',
			'gishpuppy.com',
			'gmial.com',
			'goemailgo.com',
			'gotmail.net',
			'gotmail.org',
			'gotti.otherinbox.com',
			'great-host.in',
			'greensloth.com',
			'grr.la',
			'gsrv.co.uk',
			'guerillamail.biz',
			'guerillamail.com',
			'guerrillamail.biz',
			'guerrillamail.com',
			'guerrillamail.de',
			'guerrillamail.info',
			'guerrillamail.net',
			'guerrillamail.org',
			'guerrillamailblock.com',
			'gustr.com',
			'harakirimail.com',
			'hat-geld.de',
			'hatespam.org',
			'herp.in',
			'hidemail.de',
			'hidzz.com',
			'hmamail.com',
			'hopemail.biz',
			'ieh-mail.de',
			'ikbenspamvrij.nl',
			'imails.info',
			'inbax.tk',
			'inbox.si',
			'inboxalias.com',
			'inboxclean.com',
			'inboxclean.org',
			'infocom.zp.ua',
			'instant-mail.de',
			'ip6.li',
			'irish2me.com',
			'iwi.net',
			'jetable.com',
			'jetable.fr.nf',
			'jetable.net',
			'jetable.org',
			'jnxjn.com',
			'jourrapide.com',
			'jsrsolutions.com',
			'kasmail.com',
			'kaspop.com',
			'killmail.com',
			'killmail.net',
			'klassmaster.com',
			'klzlk.com',
			'koszmail.pl',
			'kurzepost.de',
			'lawlita.com',
			'letthemeatspam.com',
			'lhsdv.com',
			'lifebyfood.com',
			'link2mail.net',
			'litedrop.com',
			'lol.ovpn.to',
			'lolfreak.net',
			'lookugly.com',
			'lortemail.dk',
			'lr78.com',
			'lroid.com',
			'lukop.dk',
			'm21.cc',
			'mail-filter.com',
			'mail-temporaire.fr',
			'mail.by',
			'mail.mezimages.net',
			'mail.zp.ua',
			'mail1a.de',
			'mail21.cc',
			'mail2rss.org',
			'mail333.com',
			'mailbidon.com',
			'mailbiz.biz',
			'mailblocks.com',
			'mailbucket.org',
			'mailcat.biz',
			'mailcatch.com',
			'mailde.de',
			'mailde.info',
			'maildrop.cc',
			'maileimer.de',
			'mailexpire.com',
			'mailfa.tk',
			'mailforspam.com',
			'mailfreeonline.com',
			'mailguard.me',
			'mailin8r.com',
			'mailinater.com',
			'mailinator.com',
			'mailinator.net',
			'mailinator.org',
			'mailinator2.com',
			'mailincubator.com',
			'mailismagic.com',
			'mailme.lv',
			'mailme24.com',
			'mailmetrash.com',
			'mailmoat.com',
			'mailms.com',
			'mailnesia.com',
			'mailnull.com',
			'mailorg.org',
			'mailpick.biz',
			'mailrock.biz',
			'mailscrap.com',
			'mailshell.com',
			'mailsiphon.com',
			'mailtemp.info',
			'mailtome.de',
			'mailtothis.com',
			'mailtrash.net',
			'mailtv.net',
			'mailtv.tv',
			'mailzilla.com',
			'makemetheking.com',
			'manybrain.com',
			'mbx.cc',
			'mega.zik.dj',
			'meinspamschutz.de',
			'meltmail.com',
			'messagebeamer.de',
			'mezimages.net',
			'ministry-of-silly-walks.de',
			'mintemail.com',
			'misterpinball.de',
			'moncourrier.fr.nf',
			'monemail.fr.nf',
			'monmail.fr.nf',
			'monumentmail.com',
			'mt2009.com',
			'mt2014.com',
			'mycard.net.ua',
			'mycleaninbox.net',
			'mymail-in.net',
			'mypacks.net',
			'mypartyclip.de',
			'myphantomemail.com',
			'mysamp.de',
			'mytempemail.com',
			'mytempmail.com',
			'mytrashmail.com',
			'nabuma.com',
			'neomailbox.com',
			'nepwk.com',
			'nervmich.net',
			'nervtmich.net',
			'netmails.com',
			'netmails.net',
			'neverbox.com',
			'nice-4u.com',
			'nincsmail.hu',
			'nnh.com',
			'no-spam.ws',
			'noblepioneer.com',
			'nomail.pw',
			'nomail.xl.cx',
			'nomail2me.com',
			'nomorespamemails.com',
			'nospam.ze.tc',
			'nospam4.us',
			'nospamfor.us',
			'nospammail.net',
			'notmailinator.com',
			'nowhere.org',
			'nowmymail.com',
			'nurfuerspam.de',
			'nus.edu.sg',
			'objectmail.com',
			'obobbo.com',
			'odnorazovoe.ru',
			'oneoffemail.com',
			'onewaymail.com',
			'onlatedotcom.info',
			'online.ms',
			'opayq.com',
			'ordinaryamerican.net',
			'otherinbox.com',
			'ovpn.to',
			'owlpic.com',
			'pancakemail.com',
			'pcusers.otherinbox.com',
			'pjjkp.com',
			'plexolan.de',
			'poczta.onet.pl',
			'politikerclub.de',
			'poofy.org',
			'pookmail.com',
			'privacy.net',
			'privatdemail.net',
			'proxymail.eu',
			'prtnx.com',
			'putthisinyourspamdatabase.com',
			'putthisinyourspamdatabase.com',
			'qq.com',
			'quickinbox.com',
			'rcpt.at',
			'reallymymail.com',
			'realtyalerts.ca',
			'recode.me',
			'recursor.net',
			'reliable-mail.com',
			'rhyta.com',
			'rmqkr.net',
			'royal.net',
			'rtrtr.com',
			's0ny.net',
			'safe-mail.net',
			'safersignup.de',
			'safetymail.info',
			'safetypost.de',
			'saynotospams.com',
			'schafmail.de',
			'schrott-email.de',
			'secretemail.de',
			'secure-mail.biz',
			'senseless-entertainment.com',
			'services391.com',
			'sharklasers.com',
			'shieldemail.com',
			'shiftmail.com',
			'shitmail.me',
			'shitware.nl',
			'shmeriously.com',
			'shortmail.net',
			'sibmail.com',
			'sinnlos-mail.de',
			'slapsfromlastnight.com',
			'slaskpost.se',
			'smashmail.de',
			'smellfear.com',
			'snakemail.com',
			'sneakemail.com',
			'sneakmail.de',
			'snkmail.com',
			'sofimail.com',
			'solvemail.info',
			'sogetthis.com',
			'soodonims.com',
			'spam4.me',
			'spamail.de',
			'spamarrest.com',
			'spambob.net',
			'spambog.ru',
			'spambox.us',
			'spamcannon.com',
			'spamcannon.net',
			'spamcon.org',
			'spamcorptastic.com',
			'spamcowboy.com',
			'spamcowboy.net',
			'spamcowboy.org',
			'spamday.com',
			'spamex.com',
			'spamfree.eu',
			'spamfree24.com',
			'spamfree24.de',
			'spamfree24.org',
			'spamgoes.in',
			'spamgourmet.com',
			'spamgourmet.net',
			'spamgourmet.org',
			'spamherelots.com',
			'spamherelots.com',
			'spamhereplease.com',
			'spamhereplease.com',
			'spamhole.com',
			'spamify.com',
			'spaml.de',
			'spammotel.com',
			'spamobox.com',
			'spamslicer.com',
			'spamspot.com',
			'spamthis.co.uk',
			'spamtroll.net',
			'speed.1s.fr',
			'spoofmail.de',
			'stuffmail.de',
			'super-auswahl.de',
			'supergreatmail.com',
			'supermailer.jp',
			'superrito.com',
			'superstachel.de',
			'suremail.info',
			'talkinator.com',
			'teewars.org',
			'teleworm.com',
			'teleworm.us',
			'temp-mail.org',
			'temp-mail.ru',
			'tempe-mail.com',
			'tempemail.co.za',
			'tempemail.com',
			'tempemail.net',
			'tempemail.net',
			'tempinbox.co.uk',
			'tempinbox.com',
			'tempmail.eu',
			'tempmaildemo.com',
			'tempmailer.com',
			'tempmailer.de',
			'tempomail.fr',
			'temporaryemail.net',
			'temporaryforwarding.com',
			'temporaryinbox.com',
			'temporarymailaddress.com',
			'tempthe.net',
			'thankyou2010.com',
			'thc.st',
			'thelimestones.com',
			'thisisnotmyrealemail.com',
			'thismail.net',
			'throwawayemailaddress.com',
			'tilien.com',
			'tittbit.in',
			'tizi.com',
			'tmailinator.com',
			'toomail.biz',
			'topranklist.de',
			'tradermail.info',
			'trash-mail.at',
			'trash-mail.com',
			'trash-mail.de',
			'trash2009.com',
			'trashdevil.com',
			'trashemail.de',
			'trashmail.at',
			'trashmail.com',
			'trashmail.de',
			'trashmail.me',
			'trashmail.net',
			'trashmail.org',
			'trashymail.com',
			'trialmail.de',
			'trillianpro.com',
			'twinmail.de',
			'tyldd.com',
			'uggsrock.com',
			'umail.net',
			'uroid.com',
			'us.af',
			'venompen.com',
			'veryrealemail.com',
			'viditag.com',
			'viralplays.com',
			'vpn.st',
			'vsimcard.com',
			'vubby.com',
			'wasteland.rfc822.org',
			'webemail.me',
			'weg-werf-email.de',
			'wegwerf-emails.de',
			'wegwerfadresse.de',
			'wegwerfemail.com',
			'wegwerfemail.de',
			'wegwerfmail.de',
			'wegwerfmail.info',
			'wegwerfmail.net',
			'wegwerfmail.org',
			'wh4f.org',
			'whyspam.me',
			'willhackforfood.biz',
			'willselfdestruct.com',
			'winemaven.info',
			'wronghead.com',
			'www.e4ward.com',
			'www.mailinator.com',
			'wwwnew.eu',
			'x.ip6.li',
			'xagloo.com',
			'xemaps.com',
			'xents.com',
			'xmaily.com',
			'xoxy.net',
			'yep.it',
			'yogamaven.com',
			'yopmail.com',
			'yopmail.fr',
			'yopmail.net',
			'yourdomain.com',
			'yuurok.com',
			'z1p.biz',
			'za.com',
			'zehnminuten.de',
			'zehnminutenmail.de',
			'zippymail.info',
			'zoemail.net',
			'zomg.info'
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
		if(!empty($subject->name)) {
			$output .= htmlentities(ucwords($subject->name));
		} else {
			$output .= htmlentities("(موضوع اسم ندارد)");
		}
		$output .= "</a>";
		if(!$subject->visible) {
			$output .= "&nbsp;<i class='text-danger fa fa-eye-slash'></i>";
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
			$output .= ">";
			if(!empty($article->name)) {
				$output .= htmlentities(ucwords($article->name));
			} else {
				$output .= htmlentities("(مقاله اسم ندارد)");
			}
			$output .= "</a>";
			if(!$article->visible) {
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
		if(!empty($subject->name)) {
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
			$output .= ">";
			if(!empty($article->name)) {
				$output .= htmlentities(ucwords($article->name));
			} else {
				$output .= htmlentities("(مقاله اسم ندارد)");
			}
			$output .= "</a>";
			if(!$article->visible) //if visibility is FALSE
			{
				$output .= " <i class='text-danger fa fa-eye-slash'></i>";
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
		$output .= "<li class='list-group-item'>";
		$output .= "<span class='badge'>" . Article::count_articles_for_subject($subject->id, TRUE) . "</span>";
		$output .= "<a href='member-articles?subject=";
		$output .= urlencode($subject->id) . "'";
		if($subject_array && $subject->id == $subject_array->id) {
			$output .= " style='font-size:25px;' ";
		}
		$output .= ">";
		if(!empty($subject->name)) {
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
					$output .= ">";
					if(!empty($article->name)) {
						$output .= htmlentities(ucwords($article->name));
					} else {
						$output .= htmlentities("(مقاله اسم ندارد!)");
					}
					if($article->find_new_articles()) {
						$output .= "&nbsp;<kbd>تازه</kbd>";
					}
					$output .= "</a></li>";
				}
				$output .= "</ul>";
			}
		}
		$output .= "</li>";
	}
	$output .= "</ull>";
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
		if(!empty($category->name)) {
			$output .= htmlentities(ucwords($category->name));
		} else {
			$output .= htmlentities("(موضوع اسم ندارد)");
		}
		$output .= "</a>";
		if(!$category->visible) {
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
			if(Comment::count_comments_for_course($course->id) > 0) {
				$output .= "data-toggle='tooltip' data-placement='left' title='";
				$output .= Comment::count_comments_for_course($course->id) . " دیدگاه";
				$output .= "'";
			}
			$output .= ">";
			if(!empty($course->name)) {
				$output .= htmlentities(ucwords($course->name));
			} else {
				$output .= htmlentities("(درس اسم ندارد)");
			}
			$output .= "</a>";
			if(!$course->visible) {
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
		if(!empty($category->name)) {
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
			if(Comment::count_comments_for_course($course->id) > 0) {
				$output .= "data-toggle='tooltip' data-placement='left' title='";
				$output .= Comment::count_comments_for_course($course->id) . " دیدگاه";
				$output .= "'";
			}
			$output .= ">";
			if(!empty($course->name)) {
				$output .= htmlentities(ucwords($course->name));
			} else {
				$output .= htmlentities("(no course title)");
			}
			$output .= "</a>";
			if(!$course->visible) //if visibility is FALSE
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
		if(!empty($category->name)) {
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
					if(Comment::count_comments_for_course($course->id) > 0) {
						$output .= "data-toggle='tooltip' data-placement='left' title='";
						$output .= Comment::count_comments_for_course($course->id) . " دیدگاه";
						$output .= "'";
					}
					$output .= ">";
					if(!empty($course->name)) {
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
		if(!empty($category->name)) {
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
					if(Comment::count_comments_for_course($course->id) > 0) {
						$output .= "data-toggle='tooltip' data-placement='left' title='";
						$output .= Comment::count_comments_for_course($course->id) . " دیدگاه";
						$output .= "'";
					}
					$output .= ">";
					if(!empty($course->name)) {
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
		if(!empty($category->name)) {
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
			if(!empty($course->name)) {
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
		$output .= "<li>";
		$output .= "<h3>";
		if(!empty($subject->name)) {
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
			if(!empty($article->name)) {
				$output .= htmlentities(ucwords($article->name));
			} else {
				$output .= htmlentities("مقاله اسم ندارد");
			}
			if($article->find_new_articles()) {
				$output .= "&nbsp;<kbd>تازه</kbd>";
			}
			$output .= "</a></li>";
		}
		$output .= "</ul></li>";
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
	} elseif(($filename == "member_list.php") || ($filename == "edit_member.php") || ($filename == "new_member.php")) {
		echo "<script>$(\"a:contains('لیست اعضا')\").parent().addClass('active');</script>";
	} elseif(($filename == "admin_list.php") || ($filename == "author_list.php") || ($filename == "new_admin.php") ||
	         ($filename == "new_author.php") || ($filename == "edit_admin.php") || ($filename == "edit_author.php")
	) {
		echo "<script>$(\"a:contains('لیست کارکنان')\").parent().addClass('active');</script>";
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
