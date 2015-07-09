<?php require_once("../includes/initialize.php"); ?>
<?php require_once("../includes/Stripe/vendor/autoload.php") ?>
<?php $filename = basename(__FILE__); ?>
<?php $title = "پارس کلیک - کمک به ما"; ?>
<?php
$errors = "";
\Stripe\Stripe::setApiKey(SECRETKEY);
if(isset($_POST['stripeToken'])) {
	try {
		\Stripe\Charge::create(array(
			                       "amount"      => 1000,
			                       "currency"    => "gbp",
			                       "source"      => $_POST['stripeToken'],
			                       "description" => "کمک به پارس کلیک"
		                       ));
	} catch(\Stripe\Error\Card $e) {
		$errors = $e->getMessage();
	}
	$session->message("خیلی متشکریم از کمک شما.");
	redirect_to("help.php");
}
?>
<?php include_layout_template("header.php"); ?>
<?php include "_/components/php/nav.php"; ?>
<?php echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2>کمک به ما</h2>
			<p>بهترین کمکی که میتونید به شخص بنده بکنید این هست که ما دنبال وب هاستی هستم که حداقل یک ترا بایت به ما فضا
			   بده که ما بتونیم ویدیو هامون رو اونجا آپلود کنیم. وب هاستی که داخل ایران نباشه، سرعتش خوب باشه و CPU و
			   RAM و Bandwidth کافی برای کاربران این وب سایت فراهم کند. اگر صاحب وب هاست هستید که به خودتون تعلق داره با
			   ما تماس بگیرید تا ما تحقیق کنیم ببینیم که چطور می تونیم به وب هاست شما اعتماد کنیم.</p>

			<h3>کی میتونه برای ما مطلب بسازه؟</h3>
			<p>دنبال چند Editor برای صفحه فیسبوک میگردم. کسانی که میتوانند اخبار تکنولوژی و کامپیوتر رو پیدا کنند، یا
			   ترجمه کنند، یا مطالب قشنگی رو برای این صفحه آپلود کنند یک چیزی مثل صفحه دانستنی های کامپیوتر یا مطالب
			   کامپیوتر. کسانی که اینکاره هستند به ما پیام بدهدند اینجا و من از مطالبی که خواهید گذاشت خواهم فهمید که
			   اینکاره هستند یا خیر. آنهایی که اینکاره هستند لطفا پیغام دهند!</p>

			<h3>کی میتونه برای ما تبلیغ کنه؟</h3>
			<p>از دوستانی که دوست دارند به ما و هموطنان کمک کنند و اول از کانال یوتیوب ما و بعد از صفحه فیسبوک ما تبلیغ
			   کنند و همه رو آگاه کنند، دعوت میکنم به ما ایمیل بدهند. ما این دوستان را به عنوان تبلیغ کننده در فیسبوک
			   ثبت نام خواهیم کرد.</p>
		</article>
		<?php include("_/components/php/aside-share.php"); ?>
	</section><!-- main -->
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<?php include "_/components/php/aside-help.php"; ?>
	</section><!-- sidebar -->
<?php include_layout_template("footer.php"); ?>