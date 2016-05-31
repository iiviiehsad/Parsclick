<!DOCTYPE html>
<html lang="fa">
<head>
	<title><?php global $title; echo isset($title) ? $title : 'پارس کلیک - سینمای برنامه نویسان'; ?></title>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="description" content="سینمای برنامه نویسان آموزش رایگان ویدیویی توسعه وب"/>
	<meta name="keywords" content="برنامه نویسی, وب, جاوا, روبی, پایتون, پی اچ پی, سی اس اس, اچ تی ام ال, جاوااسکریپت, گیت"/>
	<meta name="copyright" content="parsclick.net">
	<meta name="author" content="Amir Hassan Azimi"/>
	<meta name="application-name" content="Parsclick"/>
	<meta name="language" content="fa-IR"/>
	<meta name="geo.region" content="IR"/>
	<meta name="geo.position" content="32.427908;53.688046"/>
	<meta name="ICBM" content="32.427908, 53.688046"/>
	<meta name="image" content="<?php echo is_local() ? '' : '/'; ?>images/misc/logo.png"/>
	<!--Facebook Tags-->
	<meta property="og:url" content="<?php echo DOMAIN . $_SERVER['REQUEST_URI']; ?>"/>
	<meta property="og:title" content="<?php echo isset($title) ? $title : 'پارس کلیک - سینمای برنامه نویسان'; ?>"/>
	<meta property="og:type" content="article"/>
	<meta property="og:image" content="<?php echo DOMAIN . '/images/misc/parsclick-logo.png'; ?>"/>
	<meta property="article:author" content="https://www.facebook.com/persiantc"/>
	<meta property="og:locale" content="fa_IR"/>
	<meta property="og:description" content="سینمای برنامه نویسان آموزش رایگان ویدیویی توسعه وب"/>
	<!--Twitter Tags-->
	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:title" content="<?php echo isset($title) ? $title : 'پارس کلیک - سینمای برنامه نویسان'; ?>"/>
	<meta name="twitter:description" content="سینمای برنامه نویسان آموزش رایگان ویدیویی توسعه وب"/>
	<meta name="twitter:image" content="<?php echo DOMAIN . '/images/misc/parsclick-logo.png'; ?>"/>
	<!--Apple Icons-->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo is_local() ? '' : '/'; ?>images/icons/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo is_local() ? '' : '/'; ?>images/icons/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo is_local() ? '' : '/'; ?>images/icons/apple-touch-icon-114x114.png">
	<!--CSS Styles-->
	<link rel="shortcut icon" type="image/png" href="<?php echo is_local() ? '' : '/'; ?>images/favicon.png">
	<link rel="stylesheet" href="<?php echo is_local() ? '' : '/'; ?>_/css/all.css" media="screen">
</head>
<body>
	<section class="container">
		<div class="content row">