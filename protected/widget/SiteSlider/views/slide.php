<div class="slides">
	<ul id="slider">
		<?foreach($slides as $slide): $image=CHtml::image($slide->src, $slide->title, array('title'=>$slide->title));?>
			<li><?=$slide->link ? CHtml::link($image, $slide->link) : $image?></li>
 		<?endforeach?>
	</ul>
</div>


