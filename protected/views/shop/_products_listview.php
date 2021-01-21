<?
/** @var Product $model */
 
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$model->with('productAttributes')->cardColumns()->search(),
	'itemView'=>'_products',
	'enableHistory'=>true,
	'sorterHeader'=>'Сортировка:',
	'pagerCssClass'=>'pagination',
	'pager'=>array(
		'class' => 'DLinkPager',
		'maxButtonCount'=>'5',
		'header'=>'',
	),
	'loadingCssClass'=>'loading-content',
	'itemsTagName'=>'div',
	'emptyText' => 'Нет товаров для отображения.',
	'itemsCssClass'=>'product-list-custom',
	'sortableAttributes'=>array(
		'title',
		'price',
	),
	'id'=>'ajaxListView',
	'template'=>'{sorter}{items}{pager}<div class="sort-hidden">{sorter}</div>',
));
?>