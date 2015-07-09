<?php
require_once("../../includes/initialize.php");
$session->confirm_admin_logged_in();
if(empty($_GET["category"])) {
	$session->message("شناسه دسته پیدا نشد!");
	redirect_to("admin_courses.php");
}
$category = Category::find_by_id($_GET["category"], false);
$pages_set = Course::num_courses_for_category($category->id);
if($pages_set > 0) { // if there is any course for the category
	$session->message("قادر به حذف دسته با درس نیستسم. ابتدا درس ها را حذف کنید.");
	redirect_to("admin_courses.php?category={$category->id}");
}
$result = $category->delete();
if($result) { // Success
	$session->message("دسته {$category->name} حذف شد.");
	redirect_to("admin_courses.php");
} else { // Failure
	$session->message("دسته حذف نشد!");
	redirect_to("admin_courses.php?category={$category->id}");
}
if(isset($database)) {
	$database->close_connection();
}
?>