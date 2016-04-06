<?php
require_once("../../includes/initialize.php");
$session->confirm_admin_logged_in();
$filename = basename(__FILE__);
find_selected_course();
include_layout_template("admin_header.php");
include("../_/components/php/admin_nav.php");
echo output_message($message);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php if($current_category && $current_course): ?>
				<h2><i class="fa fa-film fa-lg"></i> تنظیم درس</h2>
				<h4>
					<i class="fa fa-calendar arial"></i>&nbsp;&nbsp;<?php echo htmlentities(datetime_to_text($current_course->created_at)); ?>
				</h4>
				<h4>
					<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo datetime_to_shamsi($current_course->created_at); ?>
				</h4>
				<dl class="dl-horizontal">
					<dt>اسم درس:</dt>
					<dd><?php echo htmlentities(ucwords($current_course->name)); ?></dd>
					<dt>محل:</dt>
					<dd><?php echo $current_course->position; ?></dd>
					<dt>نمایان:</dt>
					<dd><?php echo $current_course->visible == 1 ? '<span class="text-success">بله</span>' : '<span class="text-danger">خیر</span>'; ?></dd>
					<dt>نویسنده:</dt>
					<dd><?php echo isset($current_course->author_id) ? htmlentities(Author::find_by_id($current_course->author_id)
					                                                                      ->full_name()) : '-'; ?></dd>
					<?php if( ! empty($current_course->content)): ?>
						<dt>توضیحات:</dt>
						<dd>
							<small><?php echo nl2br(strip_tags($current_course->content, '<strong><em><p><code><pre><mark><kbd><ul><ol><li><img><a>')); ?></small>
						</dd>
					<?php endif; ?>
					<dt>لینک ها:</dt>
					<dd>
						<!-----------------------------------------------EDIT------------------------------------------>
						<a class="btn btn-primary btn-small arial" href="edit_course.php?category=<?php echo urlencode($current_category->id); ?>&course=<?php echo urlencode($current_course->id); ?>" data-toggle="tooltip" title="ویرایش درس">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
						<!---------------------------------------------FILE LINK--------------------------------------->
						<?php if( ! empty($current_course->file_link)): ?>
							<a class="btn btn-primary btn-small arial" href="<?php echo htmlentities($current_course->file_link); ?>" target="_blank" data-toggle="tooltip" title="لینک فایل تمرینی">
								<span class="glyphicon glyphicon-file"></span>
							</a>
						<?php endif; ?>
					</dd>
					<dt>فایل های تمرینی:</dt>
					<dd>
						<?php if(File::num_files_for_course($current_course->id) != 0): ?>
							<?php $files = File::find_files_for_course($current_course->id); ?>
							<?php foreach($files as $file): ?>
								<div class="btn-group">
									<a class="btn btn-primary btn-small" href="../files/<?php echo urlencode($file->name); ?>">
										<?php echo htmlentities($file->name); ?>
									</a>
									<a class="btn btn-danger btn-small" href="delete_file.php?id=<?php echo urlencode($file->id); ?>" onclick="return confirm('آیا مطمئن به حذف این فایل هستید؟')">
										<i class="fa fa-trash fa-lg"></i>
									</a>
								</div>
							<?php endforeach; ?>
						<?php else: ?>
							فایل تمرینی نداریم
						<?php endif; ?>
					</dd>
				</dl>
				<!--------------------------------------------VIDEOS--------------------------------------------------->
				<?php
				if(isset($current_course->youtubePlaylist)):
					$googleapi = "https://www.googleapis.com/youtube/v3/playlistItems";
					$playListID = $current_course->youtubePlaylist;
					if( ! isset($_GET['nextPageToken']) || ! isset($_GET['prevPageToken'])) {
						$url = "{$googleapi}?part=snippet&hl=fa&maxResults=" . MAXRESULTS . "&playlistId={$playListID}&key=" . YOUTUBEAPI;
					}
					if(isset($_GET['nextPageToken'])) {
						$url = "{$googleapi}?part=snippet&hl=fa&maxResults=" . MAXRESULTS . "&playlistId={$playListID}&key=" . YOUTUBEAPI . "&pageToken=" . $_GET['nextPageToken'];
					}
					if(isset($_GET['prevPageToken'])) {
						$url = "{$googleapi}?part=snippet&hl=fa&maxResults=" . MAXRESULTS . "&playlistId={$playListID}&key=" . YOUTUBEAPI . "&pageToken=" . $_GET['prevPageToken'];
					}
					// check to see if the url exists
					$file_headers = get_headers($url);
					if($file_headers[0] != 'HTTP/1.0 404 Not Found'):
						//get the playlist from JSON file
						$content = file_get_contents($url);
						// decode the JSON file
						$json = json_decode($content, TRUE);
						//var_dump($json);
						if($json['pageInfo']['totalResults'] > 0): ?>
							<h3><i class="fa fa-video-camera fa-lg"></i> ویدیوهای این درس</h3>
							<div class="table-responsive">
								<table class="table table-condensed table-hover">
									<tbody>
										<?php foreach($json['items'] as $item): ?>
											<tr>
												<td>
													<a class="youtube visited" href="https://www.youtube.com/embed/<?php echo $item['snippet']['resourceId']['videoId']; // hl=fa-ir&theme=light&showinfo=0&autoplay=1 ?>"
													   title="پخش کنید">
														<?php echo $item['snippet']['title']; ?>
													</a>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
							<div class="clearfix center">
								<?php
								if(isset($json["prevPageToken"])): ?>
									<a class="btn btn-primary" href="?category=<?php echo $current_category->id; ?>&course=<?php echo $current_course->id; ?>&prevPageToken=<?php echo $json["prevPageToken"]; ?>">
										&nbsp;<span class="arial">&lt;</span>&nbsp;صفحه قبلی&nbsp;
									</a>
								<?php endif;
								if(isset($json["nextPageToken"])): ?>
									<a class="btn btn-primary" href="?category=<?php echo $current_category->id; ?>&course=<?php echo $current_course->id; ?>&nextPageToken=<?php echo $json["nextPageToken"]; ?>">
										&nbsp;صفحه بعدی&nbsp;<span class="arial">&gt;</span>&nbsp;
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					<?php else: ?>
						<div class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i>
							پلی لیست پیدا نشد، آدرس اینترنتی چیزی را بر نمی گرداند یا سِرور شلوغ است! لطفا بعدا بر گردید... 
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<!--------------------------------------------COMMENTS--------------------------------------------------->
				<?php // Pagination
				$page       = ! empty($_GET["page"]) ? (int)$_GET["page"] : 1;
				$pagination = new pagination($page, 20, Comment::count_comments_for_course($current_course->id));
				$comments   = Comment::find_comments($current_course->id, 20, $pagination->offset());
				?>
				<hr><?php echo output_message($message); ?>
				<article id="comments">
					<h3>
						<i class="fa fa-comments-o fa-2x"></i>
						<?php if( ! empty($comments)): ?>
							<span class="label label-as-badge label-info"><?php echo convert(count($current_course->comments())); ?>
								نظر</span>
						<?php else: ?>
							<span class="label label-as-badge label-danger">نظری نیست</span>
						<?php endif; ?>
					</h3>
					<?php foreach($comments as $comment): ?>
						<section class="media">
							<?php $_member = Member::find_by_id($comment->member_id); ?>
							<img class="img-circle pull-right" width="50" style="padding-right:0;" src="http://gravatar.com/avatar/<?php echo md5($_member->email); ?>?s=50&d=<?php echo '//' . DOMAIN . '/images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $_member->email; ?>">
							<div class="media-body arial">
								<span class="label label-as-badge label-success yekan"><?php echo htmlentities($_member->full_name()); ?></span>
								<span class="label label-as-badge label-info"><?php echo htmlentities(datetime_to_text($comment->created)); ?></span>
								<a class="label label-as-badge label-danger" href="admin_delete_comment.php?id=<?php echo urlencode($comment->id); ?>">
									<i class="fa fa-times"></i>
								</a>
								<p><?php echo nl2br(strip_tags($comment->body, ARTICLE_ALLOWABLE_TAGS)); ?></p>
							</div>
						</section>
					<?php endforeach; ?>
					<?php echo paginate($pagination, $page, "admin_courses.php", "category={$current_category->id}", "course={$current_course->id}" . get_prev_next_token() . "#comments"); ?>
				</article>
			<?php elseif($current_category): ?>
				<h2><i class="fa fa-list-alt"></i>&nbsp;تنظیم موضوع</h2>
				<dl class="dl-horizontal">
					<dt>اسم موضوع:</dt>
					<dd><?php echo htmlentities(ucwords($current_category->name)); ?></dd>
					<dt>محل:</dt>
					<dd><?php echo $current_category->position; ?></dd>
					<dt>نمایان:</dt>
					<dd><?php echo $current_category->visible == 1 ? 'بله' : 'خیر'; ?></dd>
					<dt>&nbsp;</dt>
					<dd>
						<a title="ویرایش" class="btn btn-primary btn-small" href="edit_category.php?category=<?php echo urlencode($current_category->id); ?>" data-toggle="tooltip">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
					</dd>
				</dl>
				<hr/>
				<div>
					<h2><i class="fa fa-film"></i>&nbsp;درس ها در این موضوع</h2>
					<ul>
						<?php
						$category_courses = Course::find_courses_for_category($current_category->id, FALSE);
						foreach($category_courses as $course):
							echo "<li class='list-group-item-text'>";
							$safe_course_id = urlencode($course->id);
							echo "<a href='admin_courses.php?category=" . $current_category->id . "&course={$safe_course_id}'";
							if($course->comments()):
								echo "data-toggle='tooltip' data-placement='left' title='";
								echo count($course->comments()) . " دیدگاه";
								echo "'";
							endif;
							echo ">";
							echo htmlentities(ucwords($course->name));
							echo "</a>";
							echo "</li>";
						endforeach; ?>
					</ul>
				</div>

			<?php else: ?>
				<h2>لطفا یک درس یا یک موضوع انتخاب کنید.</h2>
			<?php endif; ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<h2>موضوعات و دروس</h2>
			<a class="btn btn-success pull-left arial" href="new_category.php" data-toggle="tooltip" title="موضوع جدید اضافه کنید"><span class="glyphicon glyphicon-plus"></span></a>
			<?php echo admin_courses($current_category, $current_course); ?>
		</aside>
	</section>
	<!-- Video / Generic Modal -->
	<div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<!-- content dynamically inserted -->
				</div>
			</div>
		</div>
	</div>
<?php include_layout_template("admin_footer.php"); ?>