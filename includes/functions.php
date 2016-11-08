<?php

/**
 * Custom __autoload for finding php classes
 *
 * @param $class_name
 */
function __autoload($class_name)
{
	$path = LIB_PATH . DS . $class_name . '.php';
	if ( ! file_exists($path)) {
		die("The file {$class_name}.php could not be found!");
	}
	require_once $path;
}

/**
 * @param null $location
 */
function redirect_to($location = NULL)
{
	if ($location) {
		header('Location: ' . $location);
		exit;
	}
}

/**
 * @param string $message
 * @param string $errors
 * @return string
 */
function output_message($message = '', $errors = '')
{
	if ( ! empty($message)) return bootstrap_alert($message, 'info');
	if ( ! empty($errors)) return bootstrap_alert($errors, 'danger');

	return '';
}

/**
 * @param $message
 * @param $kind
 * @return string
 */
function bootstrap_alert($message = '', $kind = 'info')
{
	$output = "<div class=\"alert alert-{$kind} alert-dismissible\" role=\"alert\">";
	$output .= '<button type="button" class="close" data-dismiss="alert">';
	$output .= '<span aria-hidden="true">&times;</span>';
	$output .= '<span class="sr-only"></span>';
	$output .= '</button>';
	$output .= '<i class="fa fa-info-circle fa-fw fa-lg"></i>';
	$output .= '<strong>' . htmlentities($message) . '</strong>';
	$output .= '</div>';

	return $output;
}

/**
 * Replaces the associate layout for footer
 * or header inside includes folder
 *
 * @param string $template
 * @return mixed
 */
function include_layout_template($template = '')
{
	return include LIB_PATH . DS . 'layouts' . DS . $template;
}

/**
 * is the marked string and the date you need to pas in
 * which first removes the marked zeros, then removes any
 * remaining marks.
 *
 * @param string $marked_string
 * @return mixed
 */
function strip_zeros_from_date($marked_string = '')
{
	if (strpos($marked_string, '۰')) {
		return str_replace('*', '', str_replace('*۰', '', $marked_string));
	}

	return str_replace('*', '', str_replace('*0', '', $marked_string));
}

/**
 * @param string $datetime
 * @param string $format
 * @return mixed
 */
function datetime_to_text($datetime = '', $format = '*%B *%d, %Y at *%I:%M %p')
{
	$unixdatetime = strtotime($datetime);

	return strip_zeros_from_date(strftime($format, $unixdatetime));
}

/**
 * Converts Georgian to Shamsi date and time
 *
 * @param string $datetime
 * @param string $format
 * @return mixed
 */
function datetime_to_shamsi($datetime = '', $format = '*%d *%B، %Y ساعت *%H:%M')
{
	return strip_zeros_from_date(Miladr\Jalali\jDate::forge($datetime)->format($format));
}

/**
 * Calculate time left for creating new content
 *
 * @param null $date
 * @param string $interval
 * @return bool
 */
function time_left($date, $interval = '+6 months')
{
	if (is_numeric($date)) {
		$future = (new DateTime($interval))->getTimestamp();

		return $future - time() + $date;
	}
	$future = (new DateTime($interval))->getTimestamp();

	return $future - time() + strtotime($date);
}

/**
 * Finds newest date given dates as integer arrays
 *
 * @param array $dates
 * @return mixed
 */
function find_newest_date($dates = [])
{
	$dates = array_map(function($date) {
		return strtotime($date);
	}, $dates);

	return max($dates);
}

/**
 * @param $date
 * @return bool
 * @internal param $dates
 * @internal param $date
 */
function idle($date)
{
    return time() > time_left($date);
}

/**
 * @param $string
 * @return mixed
 */
function convert($string = '')
{
	$eastern = ['۰', '١', '٢', '٣', '۴', '۵', '۶', '۷', '۸', '۹'];
	$western = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

	return str_replace($western, $eastern, $string);
}

/**
 * @param $size
 * @return string
 */
function check_size($size = 0)
{
	if ($size > 1024000) return round($size / 1024000) . ' مگابایت';
	if ($size > 1024) return round($size / 1024) . ' کیلوبایت';

	return $size . ' بایت';
}

/**
 * @param        $string
 * @param        $length
 * @param string $dots
 * @return string
 */
function truncate($string, $length, $dots = '... ... ...')
{
	return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
}

/**
 * Checks to see if environment is local
 *
 * @return bool
 */
function is_local()
{
	$ip1  = '::1';
	$ip2  = '127.0.0.1';
	$host = 'localhost';

	return $_SERVER['REMOTE_ADDR'] == $ip1 || $_SERVER['REMOTE_ADDR'] == $ip2 || $_SERVER['HTTP_HOST'] == $host;
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
function ip_info($ip = NULL, $purpose = 'location', $deep_detect = TRUE)
{
	$output = NULL;
	if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
		$ip = $_SERVER['REMOTE_ADDR'];
		if ($deep_detect) {
			if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
		}
	}
	$purpose    = str_replace(array('name', "\n", "\t", ' ', '-', '_'), NULL, strtolower(trim($purpose)));
	$support    = array('country', 'countrycode', 'state', 'region', 'city', 'location', 'address');
	$continents = array(
		'AF' => 'Africa',
		'AN' => 'Antarctica',
		'AS' => 'Asia',
		'EU' => 'Europe',
		'OC' => 'Australia (Oceania)',
		'NA' => 'North America',
		'SA' => 'South America'
	);
	if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support, FALSE)) {
		$ipdat = @json_decode(file_get_contents('http://www.geoplugin.net/json.gp?ip=' . $ip));
		if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
			switch ($purpose) {
				case 'location':
					$output = array(
						'city'           => @$ipdat->geoplugin_city,
						'state'          => @$ipdat->geoplugin_regionName,
						'country'        => @$ipdat->geoplugin_countryName,
						'country_code'   => @$ipdat->geoplugin_countryCode,
						'continent'      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
						'continent_code' => @$ipdat->geoplugin_continentCode
					);
					break;
				case 'address':
					$address = array($ipdat->geoplugin_countryName);
					if (@strlen($ipdat->geoplugin_regionName) >= 1) {
						$address[] = $ipdat->geoplugin_regionName;
					}
					if (@strlen($ipdat->geoplugin_city) >= 1) {
						$address[] = $ipdat->geoplugin_city;
					}
					$output = implode(', ', array_reverse($address));
					break;
				case 'city':
					$output = @$ipdat->geoplugin_city;
					break;
				case 'state':
					$output = @$ipdat->geoplugin_regionName;
					break;
				case 'region':
					$output = @$ipdat->geoplugin_regionName;
					break;
				case 'country':
					$output = @$ipdat->geoplugin_countryName;
					break;
				case 'countrycode':
					$output = @$ipdat->geoplugin_countryCode;
					break;
			}
		}
	}

	return $output;
}

/**
 * Parses XML feeds
 *
 * @param $xml
 * @return \SimpleXMLElement
 */
function parse_rss_feed($xml)
{
	return simplexml_load_file($xml, NULL, LIBXML_NOCDATA);
}

/******************************************************************************************************/
/*                                    SECURITY FUNCTIONS                                              */
/******************************************************************************************************/

/**
 * @return bool
 */
function request_is_get()
{
	return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * @return bool
 */
function request_is_post()
{
	return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Allowable parameters to use
 *
 * @param array $allowed_params
 * @return array
 */
function allowed_get_params($allowed_params = [])
{
	$allowed_array = [];
	foreach ($allowed_params as $param) {
		if (isset($_GET[$param])) {
			$allowed_array[$param] = $_GET[$param];
		} else {
			$allowed_array[$param] = NULL;
		}
	}

	return $allowed_array;
}

/**
 * @param $value
 * @return bool
 */
function has_presence($value)
{
	$trimmed_value = trim($value);

	return isset($trimmed_value) && $trimmed_value !== '';
}

/**
 * @param       $value
 * @param array $options
 * @return bool
 */
function has_length($value, $options = [])
{
	if (isset($options['max']) && (strlen($value) > (int) $options['max'])) return FALSE;
	if (isset($options['min']) && (strlen($value) < (int) $options['min'])) return FALSE;
	if (isset($options['exact']) && (strlen($value) != (int) $options['exact'])) return FALSE;

	return TRUE;
}

/**
 * Example:
 * has_format_matching('1234', '/\d{4}/') is true
 * has_format_matching('12345', '/\d{4}/') is also true
 * has_format_matching('12345', '/\A\d{4}\Z/') is false
 *
 * @param        $value
 * @param string $regex
 * @return int
 */
function has_format_matching($value, $regex = '//')
{
	return preg_match($regex, $value);
}

/**
 * @param       $value
 * @param array $options
 * @return bool
 */
function has_number($value, $options = [])
{
	if ( ! is_numeric($value)) return FALSE;
	if (isset($options['max']) && ($value > (int) $options['max'])) return FALSE;
	if (isset($options['min']) && ($value < (int) $options['min'])) return FALSE;

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
	return in_array($value, $set, FALSE);
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
	return ! in_array($value, $set, FALSE);
}

/**
 * @param $id
 * @param $session_id
 * @return bool
 */
function check_ownership($id, $session_id)
{
	return $id === $session_id;
}

/**
 * @param $file
 * @return mixed
 */
function file_extension($file)
{
	$path_parts = pathinfo($file);

	return $path_parts['extension'];
}

/**
 * @param $file
 * @return bool
 */
function file_contains_php($file)
{
	$contents = file_get_contents($file);
	$position = strpos($contents, '<?php');

	return $position !== FALSE;
}

/**
 * @param $error_integer
 * @return mixed
 */
function file_upload_error($error_integer)
{
	$upload_errors = [
		# http://php.net/manual/en/features.file-upload.errors.php
		UPLOAD_ERR_OK         => 'خطایی نیست.',
		UPLOAD_ERR_INI_SIZE   => 'فایل بزرگتر از تنظیمات پی اچ پی است!',
		UPLOAD_ERR_FORM_SIZE  => 'اندازه فایل بزرگ است!',
		UPLOAD_ERR_PARTIAL    => 'فایل نصفه آپلود شد!',
		UPLOAD_ERR_NO_FILE    => 'هیچ فایلی انتخاب نشد!',
		UPLOAD_ERR_NO_TMP_DIR => 'پوشه موقت موجود نیست!',
		UPLOAD_ERR_CANT_WRITE => 'نمیشه روی دیسک نوشت!',
		UPLOAD_ERR_EXTENSION  => 'آپلود فایل بخاطر نوع آن متوقف شد!'
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
	foreach ($mail_domains_ko as $ko_mail) {
		list(, $mail_domain) = explode('@', $mail);
		if (strcasecmp($mail_domain, trim($ko_mail)) == 0) return TRUE;
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
	return <<<HTML
		<!DOCTYPE html>
		<html>
		<head>
			<title>Parsclick - Error</title>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
			<link rel="shortcut icon" type="image/png" href="/images/favicon.png"/>
			<link rel="stylesheet" href="/_/css/all.css" media="screen"/>
			<style>
				html, body { height: 100%; }
				body { background-color : #415B76; display: flex; align-items: center; justify-content: center; text-align: center; }
			</style>
		</head>
		<body>
			<section>
				<h1 class="bright">!Error</h1>
				<h2 class="bright">{$output1}</h2>
				<h3 class="bright">{$output2}</h3>
				<a href="/" class="btn btn-large btn-warning">برگرد به خانه</a>
			</section>
		</body>
		</html>
HTML;
}

/******************************************************************************************************/
/*                                       MEMBER'S FUNCTIONS                                           */
/******************************************************************************************************/

/**
 * @param        $action
 * @param string $message
 */
function log_action($action, $message = '')
{
	$logfile = SITE_ROOT . DS . 'logs' . DS . 'log.txt';
	$new     = file_exists($logfile) ? FALSE : TRUE;
	if ($handle = fopen($logfile, 'a')) { //appends
		$timestamp = datetime_to_text(strftime('%Y-%m-%d %H:%M:%S', time()));
		// $country   = ip_info("Visitor", "Country");
		// $content   = "{$timestamp} | {$country} | {$action}: {$message}" . PHP_EOL;
		$content = "{$timestamp} | {$action}: {$message}" . PHP_EOL;
		fwrite($handle, $content);
		fclose($handle);
		if ($new) chmod($logfile, 0777);
	} else {
		echo 'فایل ثبت قابل نوشتن نیست!';
	}
}

/**
 * @return string
 */
function article_url()
{
	global $session;

	if ($session->is_logged_in()) return 'member-articles';
	if ($session->is_author_logged_in()) return 'author_articles.php';
	if ($session->is_admin_logged_in()) return 'admin_articles.php';

	return 'articles';
}

/**
 * @return string
 */
function course_url()
{
	global $session;

	if ($session->is_logged_in()) {
		switch (basename($_SERVER['PHP_SELF'], '.php')) {
			case 'forum':
			case 'member-forum-search':
				return 'forum';
			default:
				return 'member-courses';
				break;
		}
	}
	if ($session->is_author_logged_in()) return 'author_courses.php';
	if ($session->is_admin_logged_in()) return 'admin_courses.php';
	if ( ! isset($session->id)) return 'anjoman';

	return 'courses';
}

/**
 * @param      $subject_array
 * @param      $article_array
 * @param bool $public
 * @return string
 */
function articles($subject_array, $article_array, $public = FALSE)
{
	$output = '<div id="accordion">';
	$output .= '<ul class="list-group">';
	$subject_set = Subject::find_all($public);
	foreach ($subject_set as $subject) {
		$output .= '<li class="list-group-item">';
		$output .= '<span class="badge">' . convert(Article::count_articles_for_subject($subject->id, $public)) . '</span>';
		if ( ! $public) {
			$output .= '<small><a class="label label-as-badge label-info glyphicon glyphicon-pencil" href="' . article_url() . '?subject=' . urlencode($subject->id) . '"></a></small>&nbsp;';
		}
		$output .= '<a class="accordion-toggle ';
		if ($subject_array && $subject->id == $subject_array->id) {
			$output .= ' lead selected';
		}
		$output .= '" data-toggle="collapse" data-parent="#accordion"';
		$output .= ' href="#' . urlencode($subject->id) . '">';
		$output .= '<strong>';
		$output = ! empty($subject->name) ? $output . $subject->name : $output . '-';
		$output .= '</strong>';
		$output .= '</a>';
		if (Article::count_recent_articles_for_subject($subject->id, $public) > 0) {
			$output .= '<small>&nbsp;<kbd>' . convert(Article::count_recent_articles_for_subject($subject->id, $public)) . ' مقاله جدید</kbd></small>';
		}
		if ( ! $public && Article::count_invisible_articles_for_subject($subject->id) > 0) {
			$output .= '<small>&nbsp;<kbd>' . convert(Article::count_invisible_articles_for_subject($subject->id)) . ' مقاله مخفی</kbd></small>';
		}
		$article_set = Article::find_articles_for_subject($subject->id, $public);
		$output .= '<div id="' . urlencode($subject->id) . '"';
		$output .= ' class="collapse';
		if ($subject_array && $subject->id == $subject_array->id) {
			$output .= ' in';
		}
		$output .= '">';
		$output .= '<div style="margin-top: 1em;">';
		foreach ($article_set as $article) {
			$output .= '<p>';
			$query = http_build_query([
				'subject' => urlencode($subject->id),
				'article' => urlencode($article->id)
			]);
			$output .= '<a href="' . article_url() . '?' . $query . '"';
			if ($article_array && $article->id == $article_array->id) {
				$output .= ' class="selected"';
			}
			if ($article->comments()) {
				$output .= 'data-toggle="tooltip" data-placement="left" title="';
				$output .= convert(count($article->comments())) . ' دیدگاه';
				$output .= '"';
			}
			$output .= '>';
			$output = ! empty($article->name) ? $output . $article->name : $output . '-';
			if ($article->recent()) {
				$output .= '&nbsp;<kbd>تازه</kbd>';
			}
			if ( ! $article->visible) {
				$output .= '&nbsp;<kbd>مخفی</kbd>';
			}
			$output .= '</a></p>';
		}
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</li>';
	}
	$output .= '</ul>';
	$output .= '</div>';

	return $output;
}

/**
 * @param      $category_array
 * @param      $course_array
 * @param bool $public
 * @return string
 */
function courses($category_array, $course_array, $public = FALSE)
{
	$output = '<div id="accordion">';
	$output .= '<ul class="list-group">';
	$category_set = Category::find_all($public);
	foreach ($category_set as $category) {
		$output .= '<li class="list-group-item">';
		$output .= '<span class="badge">' . convert(Course::count_courses_for_category($category->id, $public)) . '</span>';
		if ( ! $public) {
			$output .= '<small><a class="label label-as-badge label-danger glyphicon glyphicon-pencil" href="' . course_url() . '?category=' . urlencode($category->id) . '"></a></small>&nbsp;';
		}
		$output .= '<a class="accordion-toggle ';
		if ($category_array && $category->id == $category_array->id) {
			$output .= ' lead selected';
		}
		$output .= '" data-toggle="collapse" data-parent="#accordion"';
		$output .= ' href="#' . urlencode($category->id) . '">';
		$output .= '<strong>';
		$output = ! empty($category->name) ? $output . $category->name : $output . '-';
		$output .= '</strong>';
		$output .= '</a>';
		if (Course::count_recent_course_for_category($category->id, $public) > 0) {
			$output .= '<small>&nbsp;<kbd>' . convert(Course::count_recent_course_for_category($category->id, $public)) . 'درس جدید</kbd></small>';
		}
		if ( ! $public && Course::count_invisible_courses_for_category($category->id) > 0) {
			$output .= '<small>&nbsp;<kbd>' . convert(Course::count_invisible_courses_for_category($category->id)) . 'درس مخفی</kbd></small>';
		}
		$course_set = Course::find_courses_for_category($category->id, $public);
		$output .= '<div id="' . urlencode($category->id) . '"';
		$output .= ' class="collapse';
		if ($category_array && $category->id == $category_array->id) {
			$output .= ' in';
		}
		$output .= '">';
		$output .= '<div style="margin-top: 1em;">';
		foreach ($course_set as $course) {
			$output .= '<p>';
			$query = http_build_query([
				'category' => urlencode($category->id),
				'course'   => urlencode($course->id)
			]);
			$output .= '<a href="' . course_url() . '?' . $query . '"';
			if ($course_array && $course->id == $course_array->id) {
				$output .= ' class="selected"';
			}
			if ($course->comments()) {
				$output .= 'data-toggle="tooltip" data-placement="left" title="';
				$output .= convert(count($course->comments())) . ' دیدگاه';
				$output .= '"';
			}
			$output .= '>';
			$output = ! empty($course->name) ? $output . $course->name : $output . '-';
			if ($course->recent()) {
				$output .= '&nbsp;<kbd>تازه</kbd>';
			}
			if ( ! $course->visible) {
				$output .= '&nbsp;<kbd>مخفی</kbd>';
			}
			$output .= '</a></p>';
		}
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</li>';
	}
	$output .= '</ul>';
	$output .= '</div>';

	return $output;
}

/**
 * Function for public to show the categories and courses
 *
 * @return string
 */
function public_courses()
{
	$output       = '<ol class="list-unstyled">';
	$category_set = Category::find_all(TRUE);
	foreach ($category_set as $category) {
		$output .= '<li>';
		$output .= '<h2>';
		$output = ! empty($category->name) ? $output . $category->name : $output . '-';
		$output .= '</h2>';
		$course_set = Course::find_courses_for_category($category->id, TRUE);
		$output .= '<div class="list-group">';
		foreach ($course_set as $course) {
			$output .= "<a class='list-group-item' target='_blank' title='برو به یوتیوب' href='https://www.youtube.com/playlist?list={$course->youtubePlaylist}'>";
			$output = ! empty($course->name) ? $output . $course->name : $output . '-';
			if ($course->recent()) {
				$output .= '&nbsp;&nbsp;&nbsp;<kbd>تازه</kbd>';
			}
			$output .= '</a>';
		}
		$output .= '</div>';
		$output .= '</li>';
	}
	$output .= '</ol>';

	return $output;
}

/**
 * Finds all articles for subjects
 *
 * @param bool $public
 */
function find_selected_article($public = FALSE)
{
	global $current_subject;
	global $current_article;
	$get = allowed_get_params(['subject', 'article']);

	if (isset($get['subject'], $get['article'])) {
		$current_subject = Subject::find_by_id($get['subject'], $public);
		$current_article = Article::find_by_id($get['article'], $public);
	} elseif (isset($get['subject'])) {
		$current_article = NULL;
		$current_subject = Subject::find_by_id($get['subject'], $public);
		if ($current_subject && $public) {
			$current_article = Article::find_default_article_for_subject($current_subject->id);
		}
	} elseif (isset($get['article'])) {
		$current_article = Article::find_by_id($get['article'], $public);
		$current_subject = NULL;
	} else {
		$current_subject = NULL;
		$current_article = NULL;
	}
}

/**
 * Finds all courses for categories
 *
 * @param bool $public
 */
function find_selected_course($public = FALSE)
{
	global $current_category;
	global $current_course;
	$get = allowed_get_params(['category', 'course']);

	if (isset($get['category'], $get['course'])) {
		$current_category = Category::find_by_id($get['category'], $public);
		$current_course   = Course::find_by_id($get['course'], $public);
	} elseif (isset($get['category'])) {
		$current_course   = NULL;
		$current_category = Category::find_by_id($get['category'], $public);
		if ($current_category && $public) {
			$current_course = Course::find_default_course_for_category($current_category->id);
		}
	} elseif (isset($get['course'])) {
		$current_course   = Course::find_by_id($get['course'], $public);
		$current_category = NULL;
	} else {
		$current_category = NULL;
		$current_course   = NULL;
	}
}

/**
 * Checks if request already have prev or next page token
 *
 * @return null|string
 */
function get_prev_next_token()
{
	$get = allowed_get_params(['prevPageToken', 'nextPageToken']);

	if (isset($get['prevPageToken'])) {
		return ['prevPageToken' => $get['prevPageToken'] . '#comments'];
	} elseif (isset($get['nextPageToken'])) {
		return ['nextPageToken' => $get['nextPageToken'] . '#comments'];
	} elseif (isset($get['prevPageToken'], $get['nextPageToken'])) {
		return ['prevPageToken=' . $get['prevPageToken'] . 'nextPageToken=' . $get['nextPageToken'] . '#comments'];
	} else {
		return [];
	}
}

/**
 * @param $playlist_id
 * @return null|string
 */
function set_prev_next_page($playlist_id)
{
	$get   = allowed_get_params(['prevPageToken', 'nextPageToken']);
	$query = http_build_query([
		'part'       => 'snippet',
		'hl'         => 'fa',
		'maxResults' => MAXRESULTS,
		'playlistId' => $playlist_id,
		'key'        => YOUTUBEAPI
	]);

	if ( ! isset($get['nextPageToken'], $get['prevPageToken'])) {
		$url = GOOGLEAPI . '?' . $query;
	}
	if (isset($get['nextPageToken'])) {
		$url = GOOGLEAPI . '?' . $query . '&pageToken=' . $get['nextPageToken'];
	}
	if (isset($get['prevPageToken'])) {
		$url = GOOGLEAPI . '?' . $query . '&pageToken=' . $get['prevPageToken'];
	}

	return ! empty($url) ? $url : NULL;
}

/**
 * @param $playlist_id
 * @return mixed
 */
function get_playlist_content($playlist_id = 0)
{
	if (isset($playlist_id)) {
		$url = set_prev_next_page($playlist_id);
		# TODO: $content = @file_get_contents($url);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$content = curl_exec($ch);
		curl_close($ch);

		return json_decode($content, TRUE);
	}

	return NULL;
}

/**
 * Adds Bootstrap pagination under pages which need pagination
 *
 * @param       $pagination
 * @param       $page
 * @param array $urls
 * @return string
 */
function paginate($pagination, $page, $urls = [])
{
	$output   = '';
	$main_url = parse_url($_SERVER['REQUEST_URI'])['path'];
	if ($pagination->total_page() > 1) {
		$output .= '<nav class="clearfix center">';
		$output .= '<ul class="pagination">';
		if ($pagination->has_previous_page()) {
			$output .= '<li>';
			$output .= '<a href="' . $main_url . '?page=' . urlencode($pagination->previous_page());
			if ( ! empty($urls)) {
				$output .= '&';
				$output .= urldecode(http_build_query($urls));
			}
			$output .= '" aria-label="Previous">';
			$output .= '<span aria-hidden="true"> &lt;&lt; </span>';
			$output .= '</a></li>';
		}
		for ($i = 1; $i < $pagination->total_page() + 1; $i++) {
			if ($i == $page) {
				$output .= '<li class="active"><span>' . convert($i) . '</span></li>';
			} else {
				$output .= '<li>';
				$output .= '<a href="' . $main_url . '?page=' . urlencode($i);
				if ( ! empty($urls)) {
					$output .= '&';
					$output .= urldecode(http_build_query($urls));
				}
				$output .= '">' . convert($i) . '</a>';
				$output .= '</li>';
			}
		}
		if ($pagination->has_next_page()) {
			$output .= '<li>';
			$output .= '<a href="' . $main_url . '?page=' . urlencode($pagination->next_page());
			if ( ! empty($urls)) {
				$output .= '&';
				$output .= urldecode(http_build_query($urls));
			}
			$output .= '" aria-label="Next">';
			$output .= '<span aria-hidden="true">&gt;&gt;</span>';
			$output .= '</a></li>';
		}
		$output .= '</ul></nav>';
	}

	return $output;
}

/**
 * @param array  $files
 * @param string $active
 * @return string
 */
function active($files = [], $active = 'active')
{
	$filename = basename($_SERVER['PHP_SELF'], '.php');
	foreach ($files as $file) {
		if ($file == $filename) {
			return $active;
		}
	}
}

/**
 * @param $user
 * @return string
 */
function status($user)
{
	switch ($user->status) {
		case 0:
			return 'warning';
			break;
		case 1:
			return 'success';
			break;
		case 2:
			return 'danger';
			break;
		default:
			return 'primary';
			break;
	}
}

/******************************************************************************************************/
/*                                       COOKIE FUNCTIONS                                             */
/******************************************************************************************************/

/**
 * @param $salt
 * @param $string
 * @return string
 */
function encrypt_string($salt, $string)
{
	# Configuration (must match decryption)
	$cipher_type = MCRYPT_RIJNDAEL_256;
	$cipher_mode = MCRYPT_MODE_CBC;
	# Using initialization vector adds more security
	$iv_size          = mcrypt_get_iv_size($cipher_type, $cipher_mode);
	$iv               = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$encrypted_string = mcrypt_encrypt($cipher_type, $salt, $string, $cipher_mode, $iv);
	# Return initialization vector + encrypted string
	# We'll need the $iv when decoding.
	return $iv . $encrypted_string;
}

/**
 * @param $salt
 * @param $iv_with_string
 * @return string
 */
function decrypt_string($salt, $iv_with_string)
{
	# Configuration (must match encryption)
	$cipher_type = MCRYPT_RIJNDAEL_256;
	$cipher_mode = MCRYPT_MODE_CBC;
	# Extract the initialization vector from the encrypted string.
	# The $iv comes before encrypted string and has fixed size.
	$iv_size          = mcrypt_get_iv_size($cipher_type, $cipher_mode);
	$iv               = substr($iv_with_string, 0, $iv_size);
	$encrypted_string = substr($iv_with_string, $iv_size);
	$string           = mcrypt_decrypt($cipher_type, $salt, $encrypted_string, $cipher_mode, $iv);

	return $string;
}

/**
 * @param $salt
 * @param $string
 * @return string
 */
function encrypt_string_and_encode($salt, $string)
{
	return base64_encode(encrypt_string($salt, $string));
}

/**
 * @param $salt
 * @param $string
 * @return string
 */
function decrypt_string_and_decode($salt, $string)
{
	return decrypt_string($salt, base64_decode($string));
}

/**
 * @param $string
 * @return string
 */
function sign_string($string)
{
	# Using $salt makes it hard to guess how $checksum is generated
	# Caution: changing salt will invalidate all signed strings
	$salt     = 'Simple salt';
	$checksum = sha1($string . $salt); # Any hash algorithm would work
	# return the string with the checksum at the end
	return $string . '--' . $checksum;
}

/**
 * @param $signed_string
 * @return bool
 */
function signed_string_is_valid($signed_string)
{
	$array = explode('--', $signed_string);
	if (count($array) != 2) {
		# string is malformed or not signed
		return FALSE;
	}
	# Sign the string portion again. Should create same
	# checksum and therefore the same signed string.
	$new_signed_string = sign_string($array[0]);

	return $new_signed_string == $signed_string;
}

