<?php
global $current_category;
global $current_course;
if(isset($current_course->youtubePlaylist)):
	$url = set_prev_next_page($current_course->youtubePlaylist);
	// get the playlist from JSON file
	$content = @file_get_contents($url);
	// decode the JSON file
	$json = json_decode($content, TRUE);
	//var_dump($json);
	if($content && $json['pageInfo']['totalResults'] > 0): ?>
		<article class="videos">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						<span class="label label-as-badge label-success">
							<i class="fa fa-video-camera"></i> ویدیوهای این درس
						</span>
					</h3>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-condensed table-hover">
							<tbody>
								<div class="embed-responsive embed-responsive-16by9">
									<iframe width="640" height="360" src="https://www.youtube.com/embed/?list=<?php echo $current_course->youtubePlaylist; ?>&hl=fa-ir" frameborder="0" allowfullscreen></iframe>
								</div>
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
				</div>
				<div class="panel-footer">
					<div class="clearfix center">
						<?php if(isset($json['nextPageToken'])): ?>
							<a class="btn btn-primary btn-block" href="?category=<?php echo $current_category->id; ?>&course=<?php echo $current_course->id; ?>&nextPageToken=<?php echo $json['nextPageToken']; ?>">
								<span class="arial">&laquo;</span> صفحه بعدی
							</a>
						<?php endif; ?>
						<?php if(isset($json['prevPageToken'])): ?>
							<a class="btn btn-primary btn-block" href="?category=<?php echo $current_category->id; ?>&course=<?php echo $current_course->id; ?>&prevPageToken=<?php echo $json['prevPageToken']; ?>">
								صفحه قبلی <span class="arial">&raquo;</span>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</article>
	<?php else: ?>
		<div class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i>
			پلی لیست پیدا نشد، آدرس اینترنتی چیزی را بر نمی گرداند یا سِرور شلوغ است! لطفا بعدا بر گردید... 
		</div>
	<?php endif; ?>
<?php endif; ?>