<?php
$files=array();
foreach($slides as $slide) $files[]=$slide->src;
$id='banner'.uniqid();
YiiHelper::csjs($id, '$("#'.$id.'").bgImageTween('.json_encode($files).', 3000, 2000);');
?>
<div id="<?=$id?>" style="width:<?=D::cms('slider_banner_width')?>px;height:<?=D::cms('slider_banner_height')?>px"></div>



 
