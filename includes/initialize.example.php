<?php

# TODO: Rename this file to initialize.php
# TODO: Replace the credentials below to your own

/*
|----------------------------------------------------------------
| This file is required in every single
|----------------------------------------------------------------
*/
define('DS', DIRECTORY_SEPARATOR);
define('SITE_ROOT', dirname(__DIR__));
/*
|----------------------------------------------------------------
define('DOMAIN', $_SERVER['HTTP_HOST']);
|----------------------------------------------------------------
*/
define('DOMAIN', 'www.parsclick.net');
/*
|----------------------------------------------------------------
| Paths to public and private
|----------------------------------------------------------------
*/
define('LIB_PATH', SITE_ROOT . DS . 'includes');
define('PUB_PATH', SITE_ROOT . DS . 'public_html');
define('ADMIN_DIR', DS . 'admin' . DS);
/*
|----------------------------------------------------------------
| Admin Details
|----------------------------------------------------------------
*/
define('ADMIN_MEMBER_ID', 100);
define('ADMIN_EMAIL', 'info@parsclick.net');
/*
|----------------------------------------------------------------
| Allowable HTML tags to use in articles
|----------------------------------------------------------------
*/
define('ARTICLE_ALLOWABLE_TAGS', '<h3><h4><h5><h6><blockquote><strong><em><i><p><code><pre><mark><span><ul><ol><li><dl><dt><dd><a><img><iframe><video><audio>');
/*
|----------------------------------------------------------------
| Email details
|----------------------------------------------------------------
*/
define('SMTP', 'YOUR_EMAIL_SERVER_SMTP');
define('PORT', 465);
define('EMAILUSER', 'noreply@parsclick.net');
define('EMAILPASS', 'EMAIL_PASSWORD');
define('TLS', 'ssl');
/*
|----------------------------------------------------------------
| Stripe public and private Keys
|----------------------------------------------------------------
*/
define('SECRETKEY', 'STRIPE_SECRET_KEY');
define('PUBLICKEY', 'STRIPE_PUBLIC_KEY');
/*
|----------------------------------------------------------------
| YouTube API key: AIzaSyBHTFWKKWvYfxs9rP0fEgLlPo8K2V1MsoM
|----------------------------------------------------------------
*/
define('GOOGLEAPI', 'https://www.googleapis.com/youtube/v3/playlistItems');
define('YOUTUBEAPI', 'GOOGLE_API_KEY');
define('MAXRESULTS', '50');
/*
|----------------------------------------------------------------
| Register @Google ReCaptcha API keys at https://www.google.com/recaptcha/admin
|----------------------------------------------------------------
*/
define('RECAPTCHASITEKEY', 'RECAPTCHA_KEY');
define('RECAPTCHASECRETKEY', 'RECAPTCHA_SECRET_KEY');
/*
|----------------------------------------------------------------
| YouTube and Udemy links
|----------------------------------------------------------------
*/
define('YOUTUBE', 'https://www.youtube.com/user/PersianComputers/');
define('UDEMY', 'https://www.udemy.com/u/amirhassanazimi/');
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
