<?php
/*
|----------------------------------------------------------------
| This file is required in every single
|----------------------------------------------------------------
*/
defined('DS') ? NULL : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? NULL : define('SITE_ROOT', dirname(__DIR__));
/*
|----------------------------------------------------------------
| defined('DOMAIN') ? NULL : define('DOMAIN', $_SERVER['HTTP_HOST']);
|----------------------------------------------------------------
*/
defined('DOMAIN') ? NULL : define('DOMAIN', 'www.parsclick.net');
/*
|----------------------------------------------------------------
| Paths to public and private
|----------------------------------------------------------------
*/
defined('LIB_PATH') ? NULL : define('LIB_PATH', SITE_ROOT . DS . 'includes');
defined('PUB_PATH') ? NULL : define('PUB_PATH', SITE_ROOT . DS . 'public_html');
defined('ADMIN_DIR') ? NULL : define('ADMIN_DIR', DS . 'admin' . DS);
/*
|----------------------------------------------------------------
| Admin Details
|----------------------------------------------------------------
*/
defined('ADMIN_MEMBER_ID') ? NULL : define('ADMIN_MEMBER_ID', 100);
defined('ADMIN_EMAIL') ? NULL : define('ADMIN_EMAIL', 'info@parsclick.net');
/*
|----------------------------------------------------------------
| Allowable HTML tags to use in articles
|----------------------------------------------------------------
*/
defined('ARTICLE_ALLOWABLE_TAGS') ? NULL : define('ARTICLE_ALLOWABLE_TAGS', '<h3><h4><h5><h6><blockquote><strong><em><i><p><code><pre><mark><span><ul><ol><li><dl><dt><dd><a><img><iframe><video><audio>');
/*
|----------------------------------------------------------------
| Email details
|----------------------------------------------------------------
*/
defined('SMTP') ? NULL : define('SMTP', 'n1plcpnl0045.prod.ams1.secureserver.net');
defined('PORT') ? NULL : define('PORT', 465);
defined('EMAILUSER') ? NULL : define('EMAILUSER', 'do-not-reply@parsclick.net');
defined('EMAILPASS') ? NULL : define('EMAILPASS', '1365@1986Ha');
defined('TLS') ? NULL : define('TLS', 'ssl');
/*
|----------------------------------------------------------------
| Stripe public and private Keys
|----------------------------------------------------------------
*/
defined('SECRETKEY') ? NULL : define('SECRETKEY', 'sk_live_4VsTFfNoXVuTpc4P0X3oZO3E');
defined('PUBLICKEY') ? NULL : define('PUBLICKEY', 'pk_live_4VsTSyMBU0owS4GoclFSt1vk');
/*
|----------------------------------------------------------------
| YouTube API key: AIzaSyBHTFWKKWvYfxs9rP0fEgLlPo8K2V1MsoM
|----------------------------------------------------------------
*/
defined('GOOGLEAPI') ? NULL : define('GOOGLEAPI', 'https://www.googleapis.com/youtube/v3/playlistItems');
defined('YOUTUBEAPI') ? NULL : define('YOUTUBEAPI', 'AIzaSyBHTFWKKWvYfxs9rP0fEgLlPo8K2V1MsoM');
defined('MAXRESULTS') ? NULL : define('MAXRESULTS', '50');
/*
|----------------------------------------------------------------
| Register @Google ReCaptcha API keys at https://www.google.com/recaptcha/admin
|----------------------------------------------------------------
*/
defined('RECAPTCHASITEKEY') ? NULL : define('RECAPTCHASITEKEY', '6Leb2fYSAAAAAFp7bkpkNmvuvaOA9phcJN7LoQ7J');
defined('RECAPTCHASECRETKEY') ? NULL : define('RECAPTCHASECRETKEY', '6Leb2fYSAAAAABU1lHHAc0PcRLRKcs9StJxFXQPE');
/*
|----------------------------------------------------------------
| YouTube and Udemy links
|----------------------------------------------------------------
*/
defined('YOUTUBE') ? NULL : define('YOUTUBE', 'https://www.youtube.com/user/PersianComputers/');
defined('UDEMY') ? NULL : define('UDEMY', 'https://www.udemy.com/u/amirhassanazimi/');
/*
|----------------------------------------------------------------
| Requires path to the autoload.php file
|----------------------------------------------------------------
*/
require_once(LIB_PATH . DS . 'vendor' . DS . 'autoload.php');
/*
|----------------------------------------------------------------
| Initializing $session and $message to use everywhere
|----------------------------------------------------------------
*/
$session = new Session();
$message = $session->message();
/*
|----------------------------------------------------------------
| Initializing $database and $db to use everywhere
|----------------------------------------------------------------
*/
$database = new MySQLDatabase();
$db       =& $database;
