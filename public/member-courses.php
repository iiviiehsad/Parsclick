<?php require_once("../includes/initialize.php");
$filename = basename(__FILE__);
$title    = "پارس کلیک - دروس";
$session->confirm_logged_in();
$member = Member::find_by_id($session->id);
$member->check_status();
find_selected_course(TRUE);
if(isset($current_course->author_id)) {
	$author = Author::find_by_id($current_course->author_id);
}
?>
<?php include_layout_template("header.php"); ?>
<?php include("_/components/php/member_nav.php"); ?>
<?php echo output_message($message); ?>
	<section class="main col-sm-12 col-md-8 col-lg-8">
		<article>
			<?php if($current_category && $current_course) { ?>
				<h1><?php echo htmlentities($current_course->name); ?></h1>
				<h4>
					<?php if(isset($author)) {
						if(empty($author->photo)) { ?>
							<i class="fa fa-user fa-lg"></i>
						<?php } else { ?>
							<img style="width:50px;height:50px;" class="img-responsive img-rounded" alt="Profile Picture" src="data:image/jpeg;base64,<?php echo base64_encode($author->photo); ?>"/>
						<?php }
						echo $author->full_name();
					} ?>
				</h4>
				<a class="btn btn-primary pull-right" href="member-comments?course=<?php echo urldecode($current_course->id); ?>"><i class="fa fa-comments fa-lg"></i>
					نظرات <?php echo "<span class='badge'>" . Comment::count_comments_for_course($current_course->id) . "</span>"; ?>
				</a>
				&nbsp;
				<!-- ------------------------------------------------------------------------------------------------- -->
				<?php if(!Playlist::num_courses_for_playlist($current_course->id)) { ?>
					<form action="add-to-playlist" method="POST" class="addtoplaylist">
						<input type="hidden" name="course" value="<?php echo $current_course->id; ?>">
						<button id="btn" type="submit" class="btn btn-info">
							<i class="fa fa-plus-circle"></i> اضافه به لیست
						</button>
					</form>
				<?php } else { ?>
					<?php $playlist_set = Playlist::find_playlist_for_course($current_course->id); ?>
					&nbsp;
					<form action="remove-from-playlist" method="POST" class="removefromplaylist">
						<input type="hidden" name="course" value="<?php echo array_shift($playlist_set)->id; ?>">
						<button id="btn" type="submit" class="btn btn-danger">
							<i class="fa fa-minus-circle"></i> حذف از لیست
						</button>
					</form>
					&nbsp;
				<?php } //else num_courses_for_playlist?>

				<p><?php echo nl2br(htmlentities($current_course->content)); ?></p>
				<!-- ------------------------------------------------------------------------------------------------- -->
				<h4>
					<i class="fa fa-file"></i> فایل های تمرینی:
					<?php if(empty($current_course->file_link) && File::num_files_for_course($current_course->id) == 0) {
						echo "<span class='text-danger'>این درس فایل تمرینی ندارد.</span>";
					} ?>
				</h4>
				<?php if(!empty($current_course->file_link)) { ?>
					<a class="btn btn-primary btn-small" href="<?php echo htmlentities($current_course->file_link); ?>" target="_blank" title="لینک فایل تمرینی">
						لینک خارجی
					</a>
				<?php } ?>
				<!-- ------------------------------------------------------------------------------------------------- -->
				<?php if(File::num_files_for_course($current_course->id) > 0) { ?>
					<?php $files = File::find_files_for_course($current_course->id); ?>
					<?php foreach($files as $file) { ?>
						<a class="btn btn-primary btn-small arial" href="../<?php echo urldecode($file->file_path()); ?>">
							<?php echo htmlentities($file->name); ?>
						</a>
					<?php } //foreach file ?>
				<?php } //num_files_for_course ?>
				<!-- ------------------------------------------------------------------------------------------------- -->
				<?php if(isset($current_course->youtubePlaylist)) {
					$googleapi  = "https://www.googleapis.com/youtube/v3/playlistItems";
					$playListID = $current_course->youtubePlaylist;
					if(!isset($_GET['nextPageToken']) || !isset($_GET['prevPageToken'])) {
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
					if($file_headers[0] != 'HTTP/1.0 404 Not Found') {
						//get the playlist from JSON file
						$content = file_get_contents($url);
						// decode the JSON file
						$json = json_decode($content, TRUE);
						//var_dump($json);
						if($json['pageInfo']['totalResults'] > 0) {
							?>
							<hr/>
							<article class="videos">
								<div class="table-responsive">
									<table class="table table-condensed table-hover">
										<thead>
										<tr>
											<h3><i class="fa fa-video-camera"></i> ویدیوهای این درس</h3>
										</tr>
										</thead>
										<tbody>
										<?php foreach($json['items'] as $item): ?>
											<tr>
												<td>
													<!--<a class="visited" href="https://www.youtube.com/embed/--><?php //echo $item['snippet']['resourceId']['videoId']; ?><!--?hl=fa-ir&theme=light&showinfo=0&autoplay=1"-->
													<!--   title="Click to play" target="_blank" onclick="videoPlayer(this); return false;">-->
													<?php //echo $item['snippet']['title']; ?>
													<!--</a>-->
													<a class="visited" href="http://www.youtube.com/watch?v=<?php echo $item['snippet']['resourceId']['videoId']; ?>" title="Click to play">
														<?php echo $item['snippet']['title']; ?>
													</a>
												</td>
											</tr>
										<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</article>
							<div class="clearfix center">
								<?php
								if(isset($json["nextPageToken"])) { ?>
									<a class="btn btn-primary" href="?category=<?php echo $current_category->id; ?>&course=<?php echo $current_course->id; ?>&nextPageToken=<?php echo $json["nextPageToken"]; ?>">
										<span class="arial">&lt;</span> صفحه بعدی
									</a>
								<?php }
								if(isset($json["prevPageToken"])) { ?>
									<a class="btn btn-primary" href="?category=<?php echo $current_category->id; ?>&course=<?php echo $current_course->id; ?>&prevPageToken=<?php echo $json["prevPageToken"]; ?>">
										صفحه قبلی <span class="arial">&gt;</span>
									</a>
								<?php } ?>
							</div>
						<?php } ?>
					<?php } else { ?>
						<div class='alert'><i class='fa fa-exclamation-triangle'></i>
							ویدئویی در این پلی لیست پیدا نشد و آدرس اینترنتی چیزی را بر نمی گرداند!
						</div>
					<?php } ?>
				<?php } ?>
			<?php } else { ?>
				<?php include_once("_/components/php/member_course_info.php"); ?>
			<?php } ?>
		</article>
	</section>
	<section class="sidebar col-sm-12 col-md-4 col-lg-4">
		<aside>
			<form class="form-inline" action="member-course-search" method="GET">
				<div class="input-group">
					<span class="input-group-addon"><span class="edit glyphicon glyphicon-search"></span></span>
					<input type="search" name="q" class="form-control" size="30" maxlength="50" placeholder="جستجوی درس"/>
				</div>
			</form>
			<h2>موضوعات و دروس</h2>
			<?php echo member_courses($current_category, $current_course); ?>
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
<?php include_layout_template("footer.php"); ?>