<?php
global $current_category;
global $current_course;
global $json;
if($json && $json['pageInfo']['totalResults'] > 0): ?>
	<article class="videos">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
							<span class="label label-as-badge label-success">
								<i class="fa fa-video-camera"></i> <?php echo convert($json['pageInfo']['totalResults']); ?> ویدیو
							</span>
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-condensed table-hover">
						<tbody>
							<div class="embed-responsive embed-responsive-16by9">
								<iframe width="640" height="360"
								        src="https://www.youtube.com/embed/?list=<?php echo $current_course->youtubePlaylist; ?>&hl=fa-ir"
								        frameborder="0" allowfullscreen></iframe>
							</div>
							<?php foreach($json['items'] as $item): ?>
								<tr>
									<td>
										<a class="youtube"
										   href="https://www.youtube.com/embed/<?php echo $item['snippet']['resourceId']['videoId']; // hl=fa-ir&theme=light&showinfo=0&autoplay=1 ?>"
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
				<nav>
					<ul class="pager">
						<?php if(isset($json['prevPageToken'])): ?>
							<li>
								<a href="?category=<?php echo $current_category->id; ?>&course=<?php echo $current_course->id; ?>&prevPageToken=<?php echo $json['prevPageToken']; ?>">
									<span aria-hidden="true">&rarr;</span>&nbsp;صفحه قبلی&nbsp;
								</a>
							</li>
						<?php endif; ?>
						<?php if(isset($json['nextPageToken'])): ?>
							<li>
								<a href="?category=<?php echo $current_category->id; ?>&course=<?php echo $current_course->id; ?>&nextPageToken=<?php echo $json['nextPageToken']; ?>">
									&nbsp;صفحه بعدی&nbsp;<span aria-hidden="true">&larr;</span>
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</nav>
			</div>
		</div>
	</article>
<?php else: ?>
	<div class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i>
		پلی لیست پیدا نشد، آدرس اینترنتی چیزی را بر نمی گرداند یا سِرور شلوغ است! لطفا بعدا بر گردید... 
	</div>
<?php endif; ?>