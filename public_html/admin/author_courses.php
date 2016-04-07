<?php require_once('../../includes/initialize.php');
$session->confirm_author_logged_in();
$author = Author::find_by_id($session->id);
$author->check_status();
$filename = basename(__FILE__);
find_selected_course();
include_layout_template('admin_header.php');
$file_max_file_size = File::$max_file_size; // 32MB
$errors             = '';
if(isset($_POST['submit_file'])) {
	$file              = new File();
	$file->id          = (int) '';
	$file->course_id   = (int) $current_course->id;
	$file->description = $_POST['description'];
	$file->attach_file($_FILES['single_file']);
	if($file->save()) {
		$session->message("فایل {$file->description} با موفقیت آپلود شد.");
		redirect_to('author_courses.php?category=' . urlencode($current_category->id) . '&course=' . urlencode($current_course->id));
	} else {
		$errors = join(' ', $file->errors);
	}
}
include('../_/components/php/author_nav.php');
echo output_message($message, $errors);
?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php
			if($current_category && $current_course): ?>
				<h1>
					<?php echo $current_course->visible == 1 ? '<i class="fa fa-eye"></i>' : '<i class="text-danger fa fa-eye-slash"></i>'; ?>
					<?php echo htmlentities(ucwords($current_course->name)); ?>
				</h1>
				<h4>
					<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo htmlentities(datetime_to_text($current_course->created_at)); ?>
				</h4>
				<h4>
					<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo datetime_to_shamsi($current_course->created_at); ?>
				</h4>
				<h4 class="text-success">
					<?php echo isset($current_course->author_id) ? htmlentities(Author::find_by_id($current_course->author_id)
					                                                                  ->full_name()) : ''; ?>
				</h4>
				<?php if(check_ownership($current_course->author_id, $session->id)): ?>
					<a class="btn btn-small btn-primary" href="author_edit_course.php?category=<?php echo urlencode($current_category->id); ?>&course=<?php echo urlencode($current_course->id); ?>" title="ویرایش">
						ویرایش
					</a>
				<?php endif; ?>
				<!-- -------------------------------------------FILE LINK--------------------------------------------- -->
				<?php if( ! empty($current_course->file_link)): ?>
					<a class="btn btn-primary btn-small" href="<?php echo htmlentities($current_course->file_link); ?>" target="_blank" title="لینک فایل تمرینی">
						لینک فایل تمرینی
					</a>
				<?php endif; ?>
				<!-- -------------------------------------------FILES------------------------------------------------- -->
				<?php if(File::num_files_for_course($current_course->id) > 0): ?>
					<?php $files = File::find_files_for_course($current_course->id); ?>
					<?php foreach($files as $file): ?>
						<?php if(check_ownership($current_course->author_id, $session->id)): ?>
							<div class="btn-group">
								<a class="btn btn-primary btn-small" href="../files/<?php echo urlencode($file->name); ?>">
									<?php echo htmlentities($file->description); ?>
								</a>
								<a class="btn btn-danger btn-small" href="author_delete_file.php?id=<?php echo urlencode($file->id); ?>" onclick="return confirm('آیا مطمئن هستید که می خواهید این فایل حذف شود؟')">
									<i class="fa fa-trash fa-lg"></i>
								</a>
							</div>
						<?php else: ?>
							<a class="btn btn-small btn-success" href="../<?php echo urlencode($file->name); ?>">
								<?php echo htmlentities($file->description); ?>
							</a>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<!-- --------------------------------------------Content---------------------------------------------- -->
				<?php if( ! empty($current_course->content)): ?>
					<h5>توضیحات:</h5>
					<p><?php echo nl2br(strip_tags($current_course->content, '<strong><em><p><code><pre><mark><kbd><ul><ol><li><img><a>')); ?></p>
				<?php endif; ?>
				<!-- --------------------------------Check to see if there is any file-------------------------------- -->
				<?php if(File::num_files_for_course($current_course->id) == 0): ?>
					<?php if(check_ownership($current_course->author_id, $session->id)): ?>
						<div class="alert alert-info">
							<h3><span class="label label-as-badge label-info"><i class="fa fa-upload fa-lg"></i> آپلود فایل تمرینی زیپ</span>
								<small><?php echo check_size($file_max_file_size); ?></small>
							</h3>
							<form enctype="multipart/form-data" action="author_courses.php?category=<?php echo urlencode($current_category->id); ?>&course=<?php echo urlencode($current_course->id); ?>" method="POST" class="form-horizontal fileForm" role="form">
								<label style="cursor:pointer;" class="control-label btn btn-small btn-primary" for="single_file">
									برای انتخاب فایل اینجا را کلیک کنید
								</label>
								<div class="controls">
									<input name="MAX_FILE_SIZE" value="<?php echo $file_max_file_size; ?>" type="hidden"/>
									<input type="file" name="single_file" class="form-control" id="single_file" accept="application/zip"/>
								</div>
								<section class="row">
									<div class="input-group col-xs-11 col-sm-11 col-md-11 col-lg-11">
										<input type="text" name="description" class="form-control input-small" placeholder="اسم فایل " maxlength="255" required/>
										<span class="input-group-btn">
											<button class="btn btn-primary  btn-small" type="submit" name="submit_file" id="fileSubmit" data-loading-text="<i class='fa fa-spinner fa-pulse'></i> Loading...">
												آپلود
											</button>
										</span>
									</div>
								</section>
							</form>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<!-- ------------------------------------------VIDEOS------------------------------------------------- -->
				<?php
				if(isset($current_course->youtubePlaylist)):
					$googleapi = 'https://www.googleapis.com/youtube/v3/playlistItems';
					$playListID = $current_course->youtubePlaylist;
					if( ! isset($_GET['nextPageToken']) || ! isset($_GET['prevPageToken'])) {
						$url = "{$googleapi}?part=snippet&hl=fa&maxResults=" . MAXRESULTS . "&playlistId={$playListID}&key=" . YOUTUBEAPI;
					}
					if(isset($_GET['nextPageToken'])) {
						$url = "{$googleapi}?part=snippet&hl=fa&maxResults=" . MAXRESULTS . "&playlistId={$playListID}&key=" . YOUTUBEAPI . '&pageToken=' . $_GET['nextPageToken'];
					}
					if(isset($_GET['prevPageToken'])) {
						$url = "{$googleapi}?part=snippet&hl=fa&maxResults=" . MAXRESULTS . "&playlistId={$playListID}&key=" . YOUTUBEAPI . '&pageToken=' . $_GET['prevPageToken'];
					}
					$file_headers = get_headers($url);
					if($file_headers[0] != 'HTTP/1.0 404 Not Found'):
						//get the playlist from JSON file
						$content = file_get_contents($url);
						// decode the JSON file
						$json = json_decode($content, TRUE);
						//var_dump($json);
						if($json['pageInfo']['totalResults'] > 0): ?>
							<div class="alert alert-success">
								<h3>
									<span class="label label-as-badge label-success">
										<i class="fa fa-video-camera fa-lg"></i>
										ویدیوهای این درس
									</span>
								</h3>
								<div class="table-responsive">
									<table class="table table-condensed table-hover">
										<tbody>
											<?php foreach($json['items'] as $item): ?>
												<tr>
													<td>
														<a class="youtube visited" href="https://www.youtube.com/embed/<?php echo $item['snippet']['resourceId']['videoId']; // hl=fa-ir&theme=light&showinfo=0&autoplay=1 ?>"
														   title="Click to play">
															<?php echo $item['snippet']['title']; ?>
														</a>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
								<div class="clearfix center">
									<?php if(isset($json['prevPageToken'])): ?>
										<a class="btn btn-primary" href="?category=<?php echo $current_category->id; ?>&course=<?php echo $current_course->id; ?>&prevPageToken=<?php echo $json["prevPageToken"]; ?>">
											<span class="arial">&lt;</span> صفحه قبلی
										</a>
									<?php endif;
									if(isset($json['nextPageToken'])): ?>
										<a class="btn btn-primary" href="?category=<?php echo $current_category->id; ?>&course=<?php echo $current_course->id; ?>&nextPageToken=<?php echo $json["nextPageToken"]; ?>">
											صفحه بعدی <span class="arial">&gt;</span>
										</a>
									<?php endif; ?>
								</div>
							</div>
						<?php else: ?>
							<div class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i>
								پلی لیست پیدا نشد، آدرس اینترنتی چیزی را بر نمی گرداند یا سِرور شلوغ است! لطفا بعدا بر گردید... 
							</div>
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
				<!--------------------------------------------COMMENTS--------------------------------------------------->
				<?php // Pagination
				$page       = ! empty($_GET['page']) ? (int) $_GET['page'] : 1;
				$pagination = new pagination($page, 20, Comment::count_comments_for_course($current_course->id));
				$comments   = Comment::find_comments($current_course->id, 20, $pagination->offset());
				?>
				<div class="alert alert-danger">
					<article id="comments">
						<h3>
							<?php if( ! empty($comments)): ?>
								<span class="label label-as-badge label-danger"><i class="fa fa-comment fa-lg"></i>
									<?php echo convert(count($current_course->comments())); ?>
									دیدگاه</span>
							<?php else: ?>
								<span class="label label-as-badge">دیدگاهی نیست</span>
							<?php endif; ?>
						</h3>
						<div class="table-responsive">
							<table class="table table-condensed table-hover">
								<tbody>
									<?php foreach($comments as $comment): ?>
										<tr>
											<td>
												<section class="media">
													<?php $_member = Member::find_by_id($comment->member_id); ?>
													<img class="img-circle pull-right" width="50" style="padding-right:0;" src="http://gravatar.com/avatar/<?php echo md5($_member->email); ?>?s=50&d=<?php echo '//' . DOMAIN . '/images/misc/default-gravatar-pic.png'; ?>" alt="<?php echo $_member->email; ?>">
													<div class="media-body arial">
														<span class="label label-as-badge label-success yekan"><?php echo htmlentities($_member->full_name()); ?></span>
														<span class="label label-as-badge label-info"><?php echo htmlentities(datetime_to_text($comment->created)); ?></span>
														<p><?php echo nl2br(strip_tags($comment->body, ARTICLE_ALLOWABLE_TAGS)); ?></p>
													</div>
												</section>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
						<?php echo paginate($pagination, $page, 'author_courses.php', "category={$current_category->id}", "course={$current_course->id}" . get_prev_next_token() . '#comments'); ?>
					</article>
				</div>
			<?php elseif($current_category): ?>
				<?php if( ! $current_category->visible) redirect_to("author_courses.php"); ?>
				<div class="panel panel-danger">
					<div class="panel-heading">
						<h2 class="panel-title">
							<a class="arial btn btn-success btn-small" href="new_course.php?category=<?php echo urlencode($current_category->id); ?>" data-toggle="tooltip" title="درس جدید">
								<i class="fa fa-plus fa-lg"></i>
							</a>
							<?php echo htmlentities(ucwords($current_category->name)); ?>
						</h2>
					</div>
					<div class="panel-body">
						<h4><i class="fa fa-film"></i> درس ها در این موضوع:</h4>
						<ul>
							<?php
							$category_courses = Course::find_courses_for_category($current_category->id, FALSE);
							foreach($category_courses as $course):
								echo "<li class='list-group-item-text'>";
								echo '<a href="author_courses.php?category=' . urlencode($current_category->id) . '&course=' . urlencode($course->id) . '"';
								if($course->comments()):
									echo 'data-toggle="tooltip" data-placement="left" title="';
									echo count($course->comments()) . ' دیدگاه';
									echo '"';
								endif;
								echo '>';
								echo htmlentities(ucwords($course->name));
								echo '</a>';
								echo '</li>';
							endforeach; ?>
						</ul>
					</div>
				</div>
			<?php else: ?>
				<article>
					<h2>لطفا یک موضوع یا درس انتخاب کنید.</h2>
					<p class="lead text-danger"><i class="fa fa-info-circle"></i> نکات مهم:</p>
					<ul>
						<li><p>به عنوان یک نویسنده شما مسئول ساختن، بروزرساندن، و پاک کردن درس های خود هستید.</p></li>
						<li><p>درس هایی که توسط شما نوشته می شوند، توسط مدیران ویرایش و تنظیم خواهند شد.</p></li>
						<li><p>در کنار اسم هر درس، آیکانی به شکل چشم وجود دارد که نشاندهنده ی این است که درس نشر شده یا
						       نشده است. درس هایی که توسط شما ساخته می شوند، تا زمان تنظیم و ویرایش آنها توسط مدیران
						       قابل
						       دیدن نمی باشند.</p></li>
						<li><p>لطفا سعی بر پاک کردن درس هایی که از قبل توسط مدیران تنظیم شده اند ننمائید مگر اینکه مایل
						       به
						       بروزرساندن آنها هستید.</p></li>
						<li><p>پاک کردن درسی بدون دلیل باعث معلق شدن عضویت شما به عنوان نویسنده خواهد شد.</p></li>
						<li><p>هنگام اضافه کردن درس شناسه لیست پخش یوتیوب فراموشتان نشود.</p></li>
						<li><p>اگر کانال یوتیوب دارید، لطفا به
								<a href="https://developers.google.com/youtube/v3/getting-started" target="_blank" title="YouTube Data API Overview">
									این آدرس</a> روید و مدیر وبسایت را از
								<a href="https://developers.google.com/youtube/v3/getting-started">YouTube API Key</a>
						       با خبر کنید. این کلید به کاربران اجازه می دهد که با سرعت بیشتری به لیست پخش یوتیوب شما
						       دسترسی پیدا کنند.</p></li>
					</ul>
				</article>
			<?php endif; ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<h2>موضوعات و دروس</h2>
			<?php echo author_courses($current_category, $current_course); ?>
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
<?php include_layout_template('admin_footer.php'); ?>