
<div id="images-gallery" class="fotogallery_page">
<?
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$imageProvider,
    'itemsTagName'=>'ul',
    'itemView'=>'_image',   // refers to the partial view named '_post'
    'pager'=>array(
      'maxButtonCount'=>'5',
      'header'=>'',
    ),
    'itemsCssClass'=>'fotogallery_inner_box',
    'template'=>'{items}{pager}',
    'htmlOptions'=>array('class'=>'fotogallery_page')
));
?>
</div>

<script type="text/javascript">
$(function(){
    $("#images-gallery a").fancybox({
        'autoDimensions':  false,
        'autoScale'     :  false,
        'transitionIn'  : 'elastic',
    'transitionOut' : 'elastic',
        'titlePosition' : 'over',
        helpers: {
            overlay: {
              locked: false
            }
        }
    });
});
</script>
