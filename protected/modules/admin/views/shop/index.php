<?php $this->pageTitle = D::cms('shop_title').' - '. $this->appName; ?>
<?php
    $this->breadcrumbs=array(
    	D::cms('shop_title', 'Каталог') => array('shop/index')
    );
?>
<div class="left">
  <h1><?=D::cms('shop_title', 'Каталог')?></h1>
</div>
<div class="right">
  <?php echo CHtml::link('Очистить кеш картинок  <i class="glyphicon glyphicon-warning-sign"></i>',
  	array('shop/clearImageCache'),
  	array('class'=>' btn btn-danger', 'title'=>'Обновить все картинки на сайте до первоначального вида')); ?>
  <?php echo CHtml::link('Настройки <i class="glyphicon glyphicon-cog"></i>', array('shopSettings/index'), array('class'=>'btn btn-warning')); ?>
</div>
<div class="clr"></div>

<?php $this->renderPartial('_categories', compact('categories')); ?>
<?php $this->renderPartial('_products', compact('products')); ?>
