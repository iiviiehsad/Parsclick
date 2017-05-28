<?php
require_once '../includes/initialize.php';
require_once '../includes/vendor/autoload.php';
$title  = 'پارس کلیک - کمک به ما';
$errors = '';
\Stripe\Stripe::setApiKey(SECRETKEY);
if (isset($_POST['stripeToken'])) {
	try {
		\Stripe\Charge::create([
			'amount'      => 1000,
			'currency'    => 'gbp',
			'source'      => $_POST['stripeToken'],
			'description' => 'کمک به پارس کلیک',
		]);
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
			<h3>نویسندگی</h3>
			<p>دنبال چند نویسنده برای سایت و صفحه فیسبوک میگردم. کسانی که میتوانند اخبار تکنولوژی و کامپیوتر رو پیدا
				کنند، یا
				ترجمه کنند، یا مطالب قشنگی رو برای این صفحه آپلود کنند یک چیزی مثل صفحه دانستنی های کامپیوتر یا مطالب
				کامپیوتر. مقالات شما با اسم شما برای صفحات مقالات توسط دوستان دیده خواهند شد،</p>
			<h3>تبلیغات</h3>
			<p>از دوستانی که دوست دارند به ما و هموطنان کمک کنند و اول از کانال یوتیوب ما و بعد از صفحه فیسبوک ما تبلیغ
				کنند و همه رو آگاه کنند، دعوت میکنم به ما ایمیل بدهند. ما این دوستان را به عنوان تبلیغ کننده در فیسبوک
				ثبت نام خواهیم کرد.</p>
			<h3>کمک مالی</h3>
			<p class="lead">
				بدون کمک مالی کار ما متوقف میشه. این رو جدی عرض می کنم. بخار داشته باشید و به آینده ی خودتون کمک کنید.
				اگه پارس کلیک ادامه پیدا کنه، خودتون میافتید جلو وگرنه تجربه رو وقت میخره.
				انتخاب با شماست: پارس کلیک یا سالها هزینه و وقت؟ خودانی!
			</p>
		</article>
		<?php include_layout_template('aside-ad.php'); ?>
		<?php include_layout_template('aside-share.php'); ?>
	</section><!-- main -->
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<?php include_layout_template('aside-help.php'); ?>
		<?php include_layout_template('aside-ad.php'); ?>
	</section><!-- sidebar -->
<?php include_layout_template('footer.php'); ?>