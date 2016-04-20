<?php require_once('../includes/initialize.php');
if($session->is_logged_in()) redirect_to('member-articles?' . $_SERVER['QUERY_STRING']);
$filename = basename(__FILE__);
find_selected_article(TRUE);
$newest_article = Article::find_newest_article();
$errors         = '';
$body           = '';
if(isset($current_article->author_id)) {
	$author = Author::find_by_id($current_article->author_id);
}
$title = isset($current_article) ? 'پارس کلیک - ' . $current_article->name : 'پارس کلیک - ' . $newest_article->name;
?>
<?php include_layout_template('header.php'); ?>
<?php include_layout_template('nav.php'); ?>
<?php echo output_message($message, $errors); ?>
<section class="main col-sm-12 col-md-8 col-lg-8">
	<article id="member_article">
		<?php if($current_subject && $current_article): ?>
			<?php include_layout_template('article-info.php'); ?>
		<?php else: ?>
			<?php include_layout_template('recent-article.php'); ?>
		<?php endif; ?>
	</article>
</section>
<section class="sidebar col-sm-12 col-md-4 col-lg-4">
	<aside class="members_menu">
		<?php include_layout_template('aside-share.php'); ?>
		<h2>موضوعات و مقالات</h2>
		<?php echo articles($current_subject, $current_article, TRUE); ?>
		<h2>وبلاگ پارس کلیک</h2>
		<p>برای اخبار، کوپن های یودمی، خبر از درس های آینده، پادکست، صحبت از نویسندگان، صحبت از مقالات خوب، لینک دانلود
		   ویدیو و خیلی چیزهای دیگه به وبلاگ پارس کلیک سر بزنید.</p>
		<ul class="list-group">
			<li class="list-group-item">
				<a href="http://blog.parsclick.net/" target="_blank">وبلاگ پارس کلیک</a>
				<span class="label label-as-badge label-danger pull-left">اخبار</span>
			</li>
		</ul>
		<?php include_layout_template('aside-read.php'); ?>
		<?php include_layout_template('aside-ad.php'); ?>

		<div class="panel-group" id="accordion">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
							Collapsible Group Item #1
						</a>
					</h4>
				</div>
				<div id="collapseOne" class="panel-collapse collapse in">
					<div class="panel-body">
						Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon
						officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3
						wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et.
						Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan
						excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt
						you probably haven't heard of them accusamus labore sustainable VHS.
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
							Collapsible Group Item #2
						</a>
					</h4>
				</div>
				<div id="collapseTwo" class="panel-collapse collapse">
					<div class="panel-body">
						Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon
						officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3
						wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et.
						Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan
						excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt
						you probably haven't heard of them accusamus labore sustainable VHS.
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
							Collapsible Group Item #3
						</a>
					</h4>
				</div>
				<div id="collapseThree" class="panel-collapse collapse">
					<div class="panel-body">
						Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon
						officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3
						wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et.
						Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan
						excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt
						you probably haven't heard of them accusamus labore sustainable VHS.
					</div>
				</div>
			</div>
		</div>

	</aside>
</section>
<?php include_layout_template('footer.php'); ?>
