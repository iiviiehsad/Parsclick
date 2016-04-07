<?php require_once('../../includes/initialize.php');
$filename = basename(__FILE__);
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
$author->check_status();
find_selected_article();
if( ! $current_subject) {
	redirect_to('author_articles.php');
}
$errors = '';
if(isset($_POST['submit'])) {
	$author              = Author::find_by_id($session->id);
	$article             = new Article();
	$article->id         = (int) '';
	$article->subject_id = $current_subject->id; //$_GET['subject'];
	$article->author_id  = $author->id;
	$article->name       = $_POST['article_name'];
	$article->position   = (int) $_POST['position'];
	if($author->id == 1) {
		$article->visible = (int) $_POST['visible'];
	} else {
		$article->visible = 0;
	}
	$article->content    = $_POST['content'];
	$article->created_at = strftime("%Y-%m-%d %H:%M:%S", time());
	$result              = $article->create();
	if($result) { // Success
		send_email('info@parsclick.net', 'New Article!', 'New Article Added');
		$session->message('مقاله ساخته شد.');
		redirect_to('author_articles.php');
	} else { // Failure
		$errors = 'مقاله شاخته نشد!';
	}
}
include_layout_template('admin_header.php');
include_layout_template('author_nav.php');
echo output_message($message, $errors); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-newspaper-o"></i> مقاله جدید</h2>
			<form class="form-horizontal" action="new_article.php?subject=<?php echo urlencode($current_subject->id); ?>" method="POST" role="form">
				<fieldset id="login">
					<legend><?php echo ucfirst($current_subject->name); ?></legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="article_name">اسم مقاله</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="article_name" id="article_name" autofocus placeholder="اسم مقاله" value="" required/>
						</div>
					</section>
					<!--position-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="position">محل</label>
						<div class="controls">
							<select class="form-control col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" name="position" id="position">
								<?php $page_count = Article::num_articles_for_subject($current_subject->id, FALSE);
								echo '<option selected value=' . ++$page_count . '>' . $page_count . '</option>';
								?>
							</select>
						</div>
					</section>
					<!--visible-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="visible">نشر شد</label>
						<div class="controls radio-disabled">
							<label class="radio-inline" for="inlineRadioNo">
								<input type="radio" name="visible" id="inlineRadioNo" <?php echo $author->id == 1 ? ' value="0" ' : ' disabled'; ?> >
								خیر
							</label>
							<label class="radio-inline" for="inlineRadioNo">
								<input type="radio" name="visible" id="inlineRadioYes" <?php echo $author->id == 1 ? ' value="1" ' : ' disabled'; ?> >
								بله
							</label>
						</div>
					</section>
					<!--content-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="content">مطالب</label>
						<div class="controls">
							<textarea class="col-xs-12 col-sm-8 col-md-8 col-lg-8" name="content" id="content" rows="30" required></textarea>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<div class="controls col-sm-offset-4 col-md-offset-4 col-lg-offset-4">
							<a class="btn btn-danger" href="author_articles.php">لغو</a>
							<button class="btn btn-success" name="submit" id="submit" type="submit">
								بساز
							</button>
						</div>
					</section>
				</fieldset>
			</form>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<h2>موضوعات و مقالات</h2>
			<?php echo author_articles($current_subject, $current_article); ?>
		</aside>
	</section>
<?php include_layout_template('admin_footer.php'); ?>