<?php
/* @var SliderController $this */
$this->pageTitle = 'Слайдер - '. $this->appName;
?>

<?php

$breadcrumbs = array();

$breadcrumbs['Слайдер'] = array('slider/index');

$this->breadcrumbs = $breadcrumbs;
?>

<h1>Слайдер</h1>

<p>
	<a id="create_slide" href="<?php echo $this->createUrl('slider/create') ?>">Новый слайд</a>
	<?php
	$types=Slide::model()->getTypes();
	if(count($types) > 1) echo CHtml::dropDownList('t', Yii::app()->request->getParam('t'), array_intersect_key(Slide::model()->getTypeLabels(),$types), array(
		'style'=>'float:right',
		'empty'=>'Все',
		'onchange'=>'window.location=window.location.href.replace(/[?&]t=[^&]/,"")+(+$(this).val()?"?t="+$(this).val():"");'
	));
	?>
</p>

<ul class="sliders-list" id="sliders-list">
    <?php foreach($slides as $slide): ?>
    <li id="item-<?php echo $slide->id; ?>">
        <a href="<?php echo $this->createUrl('slider/update', array('id'=>$slide->id)); ?>" title="Редактировать"><?php echo $slide->title; ?></a>
        <a class="remove btn btn-danger btn-xs" href="<?php echo $this->createUrl('slider/remove', array('id'=>$slide->id)); ?>">Удалить</a>
        <a href="<?php echo $this->createUrl('slider/update', array('id'=>$slide->id)); ?>" class="glyphicon glyphicon-pencil pull-right" style="margin: 3px 15px;" title="Редактировать"></a>
        <div class="clr"></div>
    </li>
    <?php endforeach; ?>
</ul>

<script type="text/javascript">
    $(function() {
        $('#sliders-list').sortable({
            //items: "li:not(.ui-state-disabled)",
            placeholder: "ui-state-highlight",
            axis: "y",
            helper: "original",
            stop: function(event, ui) {
                var order = $(this).sortable('serialize');
                $.post('<?php echo $this->createUrl('slider/order'); ?>', order);
            }
        });

        /*$('#create_slide').click(function(e) {
            e.preventDefault();
            var t = this;

            $.get($(t).attr('href'), function(result) {
                console.log(result)
            }, 'html');
        });*/
    });
</script>

<style type="text/css">
    .sliders-list .ui-state-highlight {
        height: 17px;
        background: #eee;
        border: solid 1px #ddd;
    }
</style>
