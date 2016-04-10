<?php global $current_subject; ?>
<h2><i class="fa fa-newspaper-o"></i> مقالات در این موضوع </h2>
<h2>
	<?php
	if(Article::count_recent_articles_for_subject($current_subject->id, FALSE) > 0) {
		echo "&nbsp;&nbsp;";
		echo "<small><span class='label label-as-badge label-info'>" . convert(Article::count_recent_articles_for_subject($current_subject->id, FALSE)) . " مقاله جدید</span></small>";
	}
	if(Article::count_invisible_articles_for_subject($current_subject->id) > 0) {
		echo "&nbsp;&nbsp;";
		echo "<small><span class='label label-as-badge label-danger'>" . convert(Article::count_invisible_articles_for_subject($current_subject->id)) . " مقاله مخفی</span></small>";
	}
	?>
</h2>
<ul>
	<?php
	$subject_articles = Article::find_articles_for_subject($current_subject->id, FALSE);
	foreach($subject_articles as $article):
		echo '<li class="list-group-item-text">';
		echo '<a href="?subject=' . $current_subject->id . '&article=' . urlencode($article->id) . '"';
		if($article->comments()):
			echo 'data-toggle="tooltip" data-placement="left" title="';
			echo convert(count($article->comments())) . ' دیدگاه';
			echo '"';
		endif;
		echo '>';
		echo htmlentities(ucwords($article->name));
		echo '</a>';
		if($article->recent()) {
			echo "&nbsp;<kbd>تازه</kbd>";
		}
		if( ! $article->visible) {
			echo '&nbsp;<kbd>مخفی</kbd>';
		}
		echo '</li>';
	endforeach; ?>
</ul>