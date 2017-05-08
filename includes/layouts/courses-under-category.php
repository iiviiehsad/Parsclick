<?php global $current_category; ?>
<h4>درس ها در این موضوع</h4>
<h2>
	<?php
	if (Course::count_recent_course_for_category($current_category->id, FALSE) > 0) {
		echo '&nbsp;&nbsp;';
		echo '<small><span class="label label-as-badge label-info">' .
			convert(Course::count_recent_course_for_category($current_category->id, FALSE)) .
			' درس جدید</span></small>';
	}
	if (Course::count_invisible_courses_for_category($current_category->id) > 0) {
		echo '&nbsp;&nbsp;';
		echo '<small><span class="label label-as-badge label-danger">' .
			convert(Course::count_invisible_courses_for_category($current_category->id)) .
			' درس مخفی</span></small>';
	}
	?>
</h2>
<ul>
	<?php
	$category_courses = Course::find_courses_for_category($current_category->id, FALSE);
	foreach ($category_courses as $course):
		echo '<li class="list-group-item-text">';
		echo '<a href="?category=' . $current_category->id . '&course=' . urlencode($course->id) . '"';
		if ($course->comments()):
			echo 'data-toggle="tooltip" data-placement="left" title="';
			echo convert(count($course->comments())) . ' دیدگاه';
			echo '"';
		endif;
		echo '>';
		echo htmlentities(ucwords($course->name));
		echo '</a>';
		if ($course->recent()) {
			echo '&nbsp;<kbd>تازه</kbd>';
		}
		if ( ! $course->visible) {
			echo '&nbsp;<kbd>مخفی</kbd>';
		}
		echo '</li>';
	endforeach; ?>
</ul>