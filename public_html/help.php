<?php
require_once('../includes/initialize.php');
require_once('../includes/vendor/autoload.php');
$title  = 'پارس کلیک - کمک به ما';
$errors = '';
\Stripe\Stripe::setApiKey(SECRETKEY);
if (isset($_POST['stripeToken'])) {
	try {
		\Stripe\Charge::create(array(
				'amount'      => 1000,
				'currency'    => 'gbp',
				'source'      => $_POST['stripeToken'],
				'description' => 'کمک به پارس کلیک'
		));
	} catch (\Stripe\Error\Card $e) {
		$errors = $e->getMessage();
	}
	$session->message('خیلی متشکریم از کمک شما.');
	redirect_to('help');
}
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('nav.php'); ?>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2>کمک به ما</h2>
			<h3>دنبال نویسنده می گردیم.</h3>
			<p>دنبال چند نویسنده برای سایت و صفحه فیسبوک میگردم. کسانی که میتوانند اخبار تکنولوژی و کامپیوتر رو پیدا کنند، یا
			   ترجمه کنند، یا مطالب قشنگی رو برای این صفحه آپلود کنند یک چیزی مثل صفحه دانستنی های کامپیوتر یا مطالب
			   کامپیوتر. مقالات شما با اسم شما برای صفحات مقالات توسط دوستان دیده خواهند شد،</p>
			<h3>کی میتونه برای ما تبلیغ کنه؟</h3>
			<p>از دوستانی که دوست دارند به ما و هموطنان کمک کنند و اول از کانال یوتیوب ما و بعد از صفحه فیسبوک ما تبلیغ
			   کنند و همه رو آگاه کنند، دعوت میکنم به ما ایمیل بدهند. ما این دوستان را به عنوان تبلیغ کننده در فیسبوک
			   ثبت نام خواهیم کرد.</p>
		</article>
		<?php include_layout_template('aside-ad.php'); ?>
		<?php include_layout_template('aside-share.php'); ?>
	</section><!-- main -->
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<?php include_layout_template('aside-help.php'); ?>
	</section><!-- sidebar -->
<?php include_layout_template('footer.php'); ?>