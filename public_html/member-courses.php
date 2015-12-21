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
					<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo htmlentities(datetime_to_text($current_course->created_at)); ?>
				</h4>
				<h4>
					<?php if(isset($author)) {
						echo $author->full_name();
					} ?>
				</h4>
				<a class="btn btn-primary pull-right" href="member-comments?category=<?php echo urlencode($current_course->category_id); ?>&course=<?php echo urlencode($current_course->id); ?>" data-toggle="tooltip" data-placement="bottom" title="سوالات و نظرات"><i class="fa fa-comments fa-lg"></i>
					انجمن<?php echo "<span class='label label-danger label-as-badge'>" . Comment::count_comments_for_course($current_course->id) . "</span>"; ?>
				</a>
				&nbsp;
				<!-- ------------------------------------------------------------------------------------------------- -->
				<?php $playlist_set = Playlist::courses_playlist_for_member($current_course->id, $member->id); ?>
				<?php if(!$playlist_set) { ?>
					<form action="add-to-playlist" method="POST" class="addtoplaylist">
						<input type="hidden" name="course" value="<?php echo $current_course->id; ?>">
						<button id="btn" type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="left" title="اضافه به لیست پخش">
							<i class="fa fa-plus-circle"></i> اضافه به لیست
						</button>
					</form>
				<?php } else { ?>
					&nbsp;
					<form action="remove-from-playlist" method="POST" class="removefromplaylist">
						<input type="hidden" name="playlist" value="<?php echo $playlist_set->id; ?>">
						<button id="btn" type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="حذف از لیست پخش">
							<i class="fa fa-minus-circle"></i> حذف از لیست
						</button>
					</form>
					&nbsp;
				<?php } ?>
				<br/><br/>
				<p><?php echo nl2br(htmlentities($current_course->content)); ?></p>
				<!-- ------------------------------------------------------------------------------------------------- -->
				<?php if(empty($current_course->file_link) && File::num_files_for_course($current_course->id) == 0) {
					echo "<h4 class='text-danger'>این درس فایل تمرینی ندارد.</h4>";
				} ?>
				<?php if(!empty($current_course->file_link)) { ?>
					<a class="btn btn-primary" href="<?php echo htmlentities($current_course->file_link); ?>" target="_blank" data-toggle="tooltip" data-placement="left" title="دانلود کنید">
						<i class="fa fa-files-o fa-lg"></i>&nbsp; دانلود فایل های تمرینی
					</a>
				<?php } ?>
				<!-- ------------------------------------------------------------------------------------------------- -->
				<?php if(File::num_files_for_course($current_course->id) > 0) { ?>
					<?php $files = File::find_files_for_course($current_course->id); ?>
					<?php foreach($files as $file) { ?>
						<a class="btn btn-primary btn-small" href="<?php echo urlencode($file->file_path()); ?>">
							<?php echo htmlentities($file->description); ?>
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
						if($json['pageInfo']['totalResults'] > 0) { ?>
							<article class="videos">
								<div class="table-responsive">
									<table class="table table-condensed table-hover">
										<thead>
										<tr>
											<h3><i class="fa fa-video-camera fa-lg"></i> ویدیوهای این درس</h3>
										</tr>
										</thead>
										<tbody>
										<?php foreach($json['items'] as $item): ?>
											<tr>
												<td>
													<a class="youtube" href="https://www.youtube.com/embed/<?php echo $item['snippet']['resourceId']['videoId']; // hl=fa-ir&theme=light&showinfo=0&autoplay=1 ?>"
													   title="Click to play">
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
									<a class="btn btn-primary btn-block" href="?category=<?php echo $current_category->id; ?>&course=<?php echo $current_course->id; ?>&nextPageToken=<?php echo $json["nextPageToken"]; ?>">
										<span class="arial">&laquo;</span> صفحه بعدی
									</a>
								<?php }
								if(isset($json["prevPageToken"])) { ?>
									<a class="btn btn-primary btn-block" href="?category=<?php echo $current_category->id; ?>&course=<?php echo $current_course->id; ?>&prevPageToken=<?php echo $json["prevPageToken"]; ?>">
										صفحه قبلی <span class="arial">&raquo;</span>
									</a>
								<?php } ?>
							</div>
						<?php } ?>
					<?php } else { ?>
						<div class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i>
							پلی لیست پیدا نشد، آدرس اینترنتی چیزی را بر نمی گرداند یا سِرور شلوغ است! لطفا بعدا بر گردید... 
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
					<span class="input-group-addon"><span class="arial glyphicon glyphicon-search"></span></span>
					<input type="text" name="q" class="form-control" size="30" maxlength="50" data-toggle="tooltip" data-placement="top" title="جستجو کنید و اینتر بزنید" placeholder="جستجوی درس"/>
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