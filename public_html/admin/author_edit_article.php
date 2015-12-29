<?php
require_once("../../includes/initialize.php");
$filename = basename(__FILE__);
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
$author->check_status();
find_selected_article();
$errors = "";
if(!$current_article || !$current_subject) {
	redirect_to("author_articles.php");
	// check to see the article belong to this author in order to edit
} elseif(!check_ownership($current_article->author_id, $session->id)) {
	$session->message("شما اجازه تغییر این مقاله را ندارید!");
	redirect_to("author_articles.php");
}
if(isset($_POST['submit'])) {
	$current_article->subject_id = $current_subject->id;
	$current_article->name       = $_POST["article_name"];
	$current_article->content    = $_POST["content"];
	if($author->id == 1) { $current_article->visible = $_POST["visible"]; }
	$result = $current_article->save();
	if($result) { // Success
		$session->message("مقاله بروزرسانی شد.");
		redirect_to("author_articles.php?subject=" . $current_subject->id . "&article=" . $current_article->id);
	} else { // Failure
		$errors = "مقاله بروزرسانی نشد!";
	}
} else {
}
include_layout_template("admin_header.php");
include("../_/components/php/author_nav.php");
echo output_message($message, $errors);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-pencil-square"></i> ویرایش مقاله</h2>

			<form class="form-horizontal" action="author_edit_article.php?subject=<?php echo urlencode($current_subject->id); ?>&article=<?php echo urlencode($current_article->id) ?>" method="post" role="form">
				<fieldset>
					<legend><i class="fa fa-newspaper"></i> <?php echo htmlentities(ucfirst($current_article->name)); ?>
					</legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="article_name">اسم مقاله</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="article_name" id="article_name" maxlength="255" placeholder="Article Name" value="<?php echo htmlentities($current_article->name); ?>"/>
						</div>
					</section>
					<!--position-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="position">محل</label>
						<div class="controls">
							<select class="form-control col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" name="position" id="position" disabled>
								<?php echo "<option value='" . $current_article->position . "' selected>" . $current_article->position . "</option>"; ?>
							</select>
						</div>
					</section>
					<!--visible-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="visible">نشر شد</label>
						<div class="controls radio-disabled">
							<label class="radio-inline" for="inlineRadioNo">
								<input type="radio" name="visible" id="inlineRadioNo" <?php echo $author->id == 1 ? ' value="0" ' : ' disabled '; ?>
										<?php if($current_article->visible == 0) echo "checked"; ?> > خیر
							</label>
							<label class="radio-inline" for="inlineRadioYes">
								<input type="radio" name="visible" id="inlineRadioYes" <?php echo $author->id == 1 ? ' value="1" ' : ' disabled '; ?>
										<?php if($current_article->visible == 1) echo "checked"; ?> > بله
							</label>
						</div>
					</section>
					<!--content-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="content">مطالب</label>
						<div class="controls">
							<textarea class="col-xs-12 col-sm-8 col-md-8 col-lg-8" name="content" id="content" rows="30" required><?php echo(htmlentities($current_article->content)); ?></textarea>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<div class="controls col-sm-offset-4 col-md-offset-4 col-lg-offset-4">
							<a class="btn btn-danger" href="author_articles.php?subject=<?php echo urlencode($current_subject->id); ?>&article=<?php echo urlencode($current_article->id); ?>">لغو</a>
							<?php if($current_article->recent()) { ?>
								<a class="btn btn-default" href="author_delete_article.php?subject=<?php echo urlencode($current_subject->id); ?>&article=<?php echo urlencode($current_article->id); ?>" onclick="return confirm('آیا مطمئن هستید؟\nاگر این مقاله نشر شده و شما بدون دلیل اقدام به حذف آن کنید، ممکن هست باعث معوق شدن حساب نویسندگی شما شود.')">
									حذف
								</a>
							<?php } ?>
							<button class="btn btn-success" name="submit" id="submit" type="submit">
								ویرایش
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
<?php include_layout_template("admin_footer.php"); ?>