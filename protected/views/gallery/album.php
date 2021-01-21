<h1><?=$album->title?></h1>
<div class="fotogallery_page">
	<ul class="fotogallery_inner_box">
		<?php foreach($photos as $photo): ?>
			<li>
				<div class="foto_wrap">
					<a  rel="images" href="<?=$photo->img?>" class="image-full" title="<?=$photo->description?>">
						<img src="<?=$photo->MainTmb?>" alt="<?=$photo->description?>">
					</a>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
<div class="content">
	<?=$album->description?>
</div>

