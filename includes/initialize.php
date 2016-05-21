<?php
defined('DS') ? NULL : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? NULL : define('SITE_ROOT', dirname(__DIR__));
/**
 * defined('DOMAIN') ? NULL : define('DOMAIN', $_SERVER['HTTP_HOST']);
 */
defined('DOMAIN') ? NULL : define('DOMAIN', 'www.parsclick.net');
/**
 * @Paths for public and private
 */
defined('LIB_PATH') ? NULL : define('LIB_PATH', SITE_ROOT . DS . 'includes');
defined('PUB_PATH') ? NULL : define('PUB_PATH', SITE_ROOT . DS . 'public_html');
/**
 * Allowable @HTML tags to use in articles
 */
defined('ARTICLE_ALLOWABLE_TAGS') ? NULL : define('ARTICLE_ALLOWABLE_TAGS', '<h3><h4><h5><h6><strong><em><i><p><code><pre><mark><span><ul><ol><li><dl><dt><dd><a><img><iframe><video><audio>');
/**
 * @Email Details
 */
defined('ADMIN_EMAIL') ? NULL : define('ADMIN_EMAIL', 'info@parsclick.net');
defined('SMTP') ? NULL : define('SMTP', 'n1plcpnl0045.prod.ams1.secureserver.net');
defined('PORT') ? NULL : define('PORT', 587);
defined('EMAILUSER') ? NULL : define('EMAILUSER', 'do-not-reply@parsclick.net');
defined('EMAILPASS') ? NULL : define('EMAILPASS', '1365@1986Ha');
defined('TLS') ? NULL : define('TLS', 'tls');
/**
 * @Stripe Keys
 */
defined('SECRETKEY') ? NULL : define('SECRETKEY', 'sk_live_4VsTFfNoXVuTpc4P0X3oZO3E');
defined('PUBLICKEY') ? NULL : define('PUBLICKEY', 'pk_live_4VsTSyMBU0owS4GoclFSt1vk');
/**
 * @YouTube API key: AIzaSyBHTFWKKWvYfxs9rP0fEgLlPo8K2V1MsoM
 */
defined('GOOGLEAPI') ? NULL : define('GOOGLEAPI', 'https://www.googleapis.com/youtube/v3/playlistItems');
defined('YOUTUBEAPI') ? NULL : define('YOUTUBEAPI', 'AIzaSyBHTFWKKWvYfxs9rP0fEgLlPo8K2V1MsoM');
defined('MAXRESULTS') ? NULL : define('MAXRESULTS', '50');
/**
 * Register @Google @ReCaptcha API keys at https://www.google.com/recaptcha/admin
 */
defined('RECAPTCHASITEKEY') ? NULL : define('RECAPTCHASITEKEY', '6Leb2fYSAAAAAFp7bkpkNmvuvaOA9phcJN7LoQ7J');
defined('RECAPTCHASECRETKEY') ? NULL : define('RECAPTCHASECRETKEY', '6Leb2fYSAAAAABU1lHHAc0PcRLRKcs9StJxFXQPE');
/**
 * @YouTube and @Udemy links
 */
defined('YOUTUBE') ? NULL : define('YOUTUBE', 'https://www.youtube.com/user/PersianComputers/');
defined('UDEMY') ? NULL : define('UDEMY', 'https://www.udemy.com/u/amirhassanazimi/');
/**
 * To load the core objects
 *
 * @param array $files
 * @return array
 */
function init($files = [])
{
	foreach($files as $file) {
		require_once(LIB_PATH . DS . $file);
	}

	return $files;
}

/**
 * Load libraries using the @init function
 */
init([
	'vendor' . DS . 'autoload.php',
	'config.php',
	'Session.php',
	'functions.php',
	'Database.php',
	'MySQLDatabase.php',
	'DatabaseObject.php',
	'Pagination.php',
	'Member.php',
	'Admin.php',
	'Author.php',
	'Subject.php',
	'Article.php',
	'Category.php',
	'Course.php',
	'File.php',
	'Playlist.php',
	'Comment.php',
	'ArticleComment.php',
	'FailedLogins.php',
]);
//
$session = new Session();
$message = $session->message();
//
$database = new MySQLDatabase();
$db       =& $database;
