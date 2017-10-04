<?php

# TODO: Rename this file to initialize.php
# TODO: Replace the credentials below to your own

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
defined('SMTP') ? NULL : define('SMTP', 'YOUR_EMAIL_SERVER_SMTP');
defined('PORT') ? NULL : define('PORT', 465);
defined('EMAILUSER') ? NULL : define('EMAILUSER', 'noreply@parsclick.net');
defined('EMAILPASS') ? NULL : define('EMAILPASS', 'EMAIL_PASSWORD');
defined('TLS') ? NULL : define('TLS', 'ssl');
/*
|----------------------------------------------------------------
| Stripe public and private Keys
|----------------------------------------------------------------
*/
defined('SECRETKEY') ? NULL : define('SECRETKEY', 'STRIPE_SECRET_KEY');
defined('PUBLICKEY') ? NULL : define('PUBLICKEY', 'STRIPE_PUBLIC_KEY');
/*
|----------------------------------------------------------------
| YouTube API key: AIzaSyBHTFWKKWvYfxs9rP0fEgLlPo8K2V1MsoM
|----------------------------------------------------------------
*/
defined('GOOGLEAPI') ? NULL : define('GOOGLEAPI', 'https://www.googleapis.com/youtube/v3/playlistItems');
defined('YOUTUBEAPI') ? NULL : define('YOUTUBEAPI', 'GOOGLE_API_KEY');
defined('MAXRESULTS') ? NULL : define('MAXRESULTS', '50');
/*
|----------------------------------------------------------------
| Register @Google ReCaptcha API keys at https://www.google.com/recaptcha/admin
|----------------------------------------------------------------
*/
defined('RECAPTCHASITEKEY') ? NULL : define('RECAPTCHASITEKEY', 'RECAPTCHA_KEY');
defined('RECAPTCHASECRETKEY') ? NULL : define('RECAPTCHASECRETKEY', 'RECAPTCHA_SECRET_KEY');
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
require_once LIB_PATH . DS . 'vendor' . DS . 'autoload.php';
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
