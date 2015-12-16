<?php
require_once("../../includes/initialize.php");
$filename = basename(__FILE__);
$session->confirm_admin_logged_in();
find_selected_article();
$errors = "";
if(!$current_article || !$current_subject) {
	redirect_to("author_articles.php");
}
if(isset($_POST['submit'])) {
	$article           = Article::find_by_id($current_article->id, FALSE);
	$article->name     = ucfirst($_POST["article_name"]);
	$article->position = (int)$_POST["position"];
	$article->visible  = (int)$_POST["visible"];
	$article->content  = $_POST["content"];
	$result            = $article->save();
	if($result) {
		$session->message("مقاله بروزرسانی شد.");
		redirect_to("admin_articles.php?subject=" . $current_subject->id . "&article=" . $current_article->id);
	} else {
		$errors = "مقاله بروزرسانی نشد!";
		//redirect_to("admin_articles.php?subject=" . $current_subject->id . "&article=" . $current_article->id);
	}
} else {
}
include_layout_template("admin_header.php");
include("../_/components/php/admin_nav.php");
echo output_message($message, $errors);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<h2><i class="fa fa-pencil-square"></i> ویرایش مقاله</h2>

			<form class="form-horizontal" action="edit_article.php?subject=<?php echo urlencode($current_subject->id); ?>&article=<?php echo urlencode($current_article->id) ?>" method="post" role="form">
				<fieldset>
					<legend><?php echo htmlentities(ucfirst($current_article->name)); ?></legend>
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="article_name">اسم مقاله</label>
						<div class="controls">
							<input class="col-xs-12 col-sm-8 col-md-8 col-lg-8" type="text" name="article_name" id="article_name" autofocus placeholder="اسم مقاله" value="<?php echo htmlentities($current_article->name); ?>"/>
						</div>
					</section>
					<!--position-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="position">محل</label>
						<div class="controls">
							<select class="form-control col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" name="position" id="position">
								<option value="" disabled> انتخاب کنید</option>
								<?php
								$page_set = Article::num_articles_for_subject($current_article->subject_id, FALSE);
								for($count = 1; $count <= $page_set; $count++) {
									echo "<option value='{$count}'";
									if($current_article->position == $count) {
										echo " selected";
									}
									echo ">{$count}</option>";
								}
								?>
							</select>
						</div>
					</section>
					<!--visible-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="visible">نشر شد</label>
						<div class="controls radio-disabled">
							<label class="radio-inline" for="inlineRadioNo">
								<input type="radio" name="visible" id="inlineRadioNo" value="0"
									<?php if($current_article->visible == 0) echo "checked";
									?> > خیر
							</label>
							<label class="radio-inline" for="inlineRadioYes">
								<input type="radio" name="visible" id="inlineRadioYes" value="1"
									<?php if($current_article->visible == 1) echo "checked";
									?> > بله
							</label>
						</div>
					</section>
					<!--content-->
					<section class="row">
						<label class="col-xs-12 col-sm-4 col-md-4 col-lg-4 control-label" for="content">مطالب</label>
						<div class="controls">
							<textarea class="col-xs-12 col-sm-8 col-md-8 col-lg-8 edit" name="content" id="content" rows="50" required placeholder="مطالب"><?php echo(htmlentities($current_article->content)); ?></textarea>
						</div>
					</section>
					<!--buttons-->
					<section class="row">
						<div class="controls col-sm-offset-4 col-md-offset-4 col-lg-offset-4">
							<a class="btn btn-danger" href="admin_articles.php?subject=<?php echo urlencode($current_subject->id) ?>&article=<?php echo urlencode($current_article->id) ?>">لغو</a>
							<a class="btn btn-info" href="delete_article.php?subject=<?php echo urlencode($current_subject->id); ?>&article=<?php echo urlencode($current_article->id); ?>" onclick="return confirm('مطمئن هستید؟')">
								حذف
							</a>
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
			<?php echo admin_articles($current_subject, $current_article); ?>
		</aside>
	</section>
<?php include_layout_template("admin_footer.php"); ?>