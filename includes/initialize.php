<?php
// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected
// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
defined('DS') ? NULL : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? NULL : define("SITE_ROOT", dirname(dirname(__FILE__)));

// TODO: Change this after getting a domain if needed and possibly adding http:// before it
//defined('DOMAIN') ? NULL : define('DOMAIN', $_SERVER['HTTP_HOST']);
defined('DOMAIN') ? NULL : define('DOMAIN', 'www.parsclick.net');

defined('LIB_PATH') ? NULL : define('LIB_PATH', SITE_ROOT . DS . 'includes');
defined('PUB_PATH') ? NULL : define('PUB_PATH', SITE_ROOT . DS . 'public_html');

defined('ARTICLE_ALLOWABLE_TAGS') ? NULL : define(
	'ARTICLE_ALLOWABLE_TAGS',
	"<h1><h2><h3><h4><h5><h6><strong><em><i><p><code><pre><mark><span><ul><ol><li><dl><dt><dd><a><img><iframe><video><audio>"
);

// Email Details
defined('ADMIN_EMAIL') ? NULL : define('ADMIN_EMAIL', "info@parsclick.net");
// TODO: Change based on email configuration
defined('SMTP') ? NULL : define('SMTP', "n1plcpnl0045.prod.ams1.secureserver.net");
defined('PORT') ? NULL : define('PORT', 587);
defined('EMAILUSER') ? NULL : define('EMAILUSER', "do-not-reply@parsclick.net");
defined('EMAILPASS') ? NULL : define('EMAILPASS', "1365@1986Ha");
defined('TLS') ? NULL : define('TLS', "tls");

// Stripe keys
// TODO: Change based on stripe live keys
defined('SECRETKEY') ? NULL : define('SECRETKEY', "sk_live_4VsTFfNoXVuTpc4P0X3oZO3E");
defined('PUBLICKEY') ? NULL : define('PUBLICKEY', "pk_live_4VsTSyMBU0owS4GoclFSt1vk");

// TODO: Change based on YouTube API key
// YouTube API key: AIzaSyBHTFWKKWvYfxs9rP0fEgLlPo8K2V1MsoM
defined('YOUTUBEAPI') ? NULL : define('YOUTUBEAPI', "AIzaSyBHTFWKKWvYfxs9rP0fEgLlPo8K2V1MsoM");
defined('MAXRESULTS') ? NULL : define('MAXRESULTS', "50");

// TODO: Change based on Google reCaptcha API key
// Register API keys at https://www.google.com/recaptcha/admin
defined('RECAPTCHASITEKEY') ? NULL : define('RECAPTCHASITEKEY', "6Leb2fYSAAAAAFp7bkpkNmvuvaOA9phcJN7LoQ7J");
defined('RECAPTCHASECRETKEY') ? NULL : define('RECAPTCHASECRETKEY', "6Leb2fYSAAAAABU1lHHAc0PcRLRKcs9StJxFXQPE");

// YouTube and Udemy
defined('YOUTUBE') ? NULL : define('YOUTUBE', "https://www.youtube.com/user/PersianComputers/");
defined('UDEMY') ? NULL : define('UDEMY', "https://www.udemy.com/u/amirhassanazimi/");

// load config file first
require_once(LIB_PATH . DS . 'config.php');

// load basic functions next so that everything after can use them
require_once(LIB_PATH . DS . 'functions.php');

// load core objects
require_once(LIB_PATH . DS . 'session.php');
require_once(LIB_PATH . DS . 'database.php');
require_once(LIB_PATH . DS . 'database_object.php');
require_once(LIB_PATH . DS . 'pagination.php');
require_once(LIB_PATH . DS . 'vendor' . DS . 'autoload.php');

// load database-related classes
require_once(LIB_PATH . DS . 'member.php');
require_once(LIB_PATH . DS . 'admin.php');
require_once(LIB_PATH . DS . 'author.php');
require_once(LIB_PATH . DS . 'subject.php');
require_once(LIB_PATH . DS . 'article.php');
require_once(LIB_PATH . DS . 'category.php');
require_once(LIB_PATH . DS . 'course.php');
require_once(LIB_PATH . DS . 'file.php');
require_once(LIB_PATH . DS . 'playlist.php');
require_once(LIB_PATH . DS . 'comment.php');
require_once(LIB_PATH . DS . 'article_comment.php');
require_once(LIB_PATH . DS . 'failed_logins.php');
