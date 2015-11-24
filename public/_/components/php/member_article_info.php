<h2 xmlns="http://www.w3.org/1999/html">به قسمت مقالات خوش آمدید.</h2>
<p class="lead">مقالاتی اعم از...</p>
<br/>
<section class="row">
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 wow fadeInRight" data-wow-duration="1s">
		<i class="center fa fa-newspaper-o fa-fw fa-3x" style="color: #56b98a;"></i>
		<p class="center lead">اخبار</p>
	</div>
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
		<i class="center fa fa-keyboard-o fa-fw fa-3x" style="color: #b973ac;"></i>
		<p class="center lead">نظرات شخصی</p>
	</div>
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".5s">
		<i class="center fa fa-sitemap fa-fw fa-3x" style="color: #5e60b9;"></i>
		<p class="center lead">بحث های داغ</p>
	</div>
</section>
<br/>
<section class="row">
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 wow fadeInRight" data-wow-duration="1s" data-wow-delay=".75s">
		<i class="center fa fa-desktop fa-fw fa-3x" style="color: #b97741;"></i>
		<p class="center lead">آموزش</p>
	</div>
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 wow fadeInUp" data-wow-duration="1s" data-wow-delay="1s">
		<i class="center fa fa-user-secret fa-fw fa-3x" style="color: #8fb945;"></i>
		<p class="center lead">ترفند</p>
	</div>
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1.25s">
		<i class="center fa fa-question fa-fw fa-3x" style="color: #cb4d65;"></i>
		<p class="center lead">جواب های سوالات</p>
	</div>
</section>
<br/>
<section>
	<h4>تعداد نویسندگان: <span class="badge arial"><?php echo Author::count_all(); ?></span></h4>
	<?php $authors = Author::find_all(); ?>
	<ol>
		<?php foreach($authors as $author) {
			echo "<li> {$author->full_name()} </li>";
		 } ?>
	</ol>
	<h4>شما هم می تونید اینجا مقاله بنویسید، چطوری؟</h4>
	<p>اگر مقالاتی در آرشیو خود دارید و نویسنده ی خوبی هستید با ما تماس بگیرید. شما به عنوان نویسنده ثبت نام خواهید شد و
	   مقالات خود را اینجا به اسم خود قرار خواهید داد. خوبی این قضیه این هست که این مقالات همیشه اینجا هستند و به این
	   کتابخانه کمک می کنند که غنی تر شود.</p>
</section>