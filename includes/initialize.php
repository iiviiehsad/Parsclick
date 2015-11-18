<?php
// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected
// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : define("SITE_ROOT", dirname(dirname(__FILE__)));
//	define('SITE_ROOT', DS . 'Users' . DS . 'hasan_azimi0' . DS . 'Sites' . DS . 'OnlineLibrarySystem');
// TODO: Change this after getting a domain if needed and possibly adding http:// before it
defined('DOMAIN') ? null : define('DOMAIN', $_SERVER['HTTP_HOST']);

defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT . DS . 'includes');

// Email Details
// TODO: Change based on email configuration
defined('SMTP') ? null : define('SMTP', "smtp.gmail.com");
defined('PORT') ? null : define('PORT', 587);
defined('EMAILUSER') ? null : define('EMAILUSER', "hasan0azimi@gmail.com");
defined('EMAILPASS') ? null : define('EMAILPASS', "1365*1986HA");
defined('TLS') ? null : define('TLS', "tls");

// Stripe keys
// TODO: Change based on stripe live keys
defined('SECRETKEY') ? null : define('SECRETKEY', "sk_live_4VsTFfNoXVuTpc4P0X3oZO3E");
defined('PUBLICKEY') ? null : define('PUBLICKEY', "pk_live_4VsTSyMBU0owS4GoclFSt1vk");

// TODO: Change based on YouTube API key
// YouTube API key: AIzaSyBHTFWKKWvYfxs9rP0fEgLlPo8K2V1MsoM
defined('YOUTUBEAPI') ? null : define('YOUTUBEAPI', "AIzaSyBHTFWKKWvYfxs9rP0fEgLlPo8K2V1MsoM");
defined('MAXRESULTS') ? null : define('MAXRESULTS', "50");

// TODO: Change based on Google reCaptcha API key
// Register API keys at https://www.google.com/recaptcha/admin
defined('RECAPTCHASITEKEY') ? null : define('RECAPTCHASITEKEY', "6Leb2fYSAAAAAFp7bkpkNmvuvaOA9phcJN7LoQ7J");
defined('RECAPTCHASECRETKEY') ? null : define('RECAPTCHASECRETKEY', "6Leb2fYSAAAAABU1lHHAc0PcRLRKcs9StJxFXQPE");

// YouTube and Udemy
defined('YOUTUBE') ? null : define('YOUTUBE', "https://www.youtube.com/user/PersianComputers/");
defined('UDEMY') ? null : define('UDEMY', "https://www.udemy.com/u/amirhassanazimi/");

// load config file first
require_once(LIB_PATH . DS . 'config.php');

// load basic functions next so that everything after can use them
require_once(LIB_PATH . DS . 'functions.php');

// load core objects
require_once(LIB_PATH . DS . 'session.php');
require_once(LIB_PATH . DS . 'database.php');
require_once(LIB_PATH . DS . 'database_object.php');
require_once(LIB_PATH . DS . 'pagination.php');

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
require_once(LIB_PATH . DS . 'failed_logins.php');
require_once(LIB_PATH . DS . 'PHPMailer' . DS . 'class.phpmailer.php');
require_once(LIB_PATH . DS . 'PHPMailer' . DS . 'class.smtp.php');
require_once(LIB_PATH . DS . 'PHPMailer' . DS . 'phpmailer.lang-en.php');