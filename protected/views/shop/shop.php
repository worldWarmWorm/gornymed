<?if(D::role('admin')) CmsHtml::editPricePlugin();?>
<h1><?=D::shop('meta_h1', D::cms('shop_title',Yii::t('shop','shop_title')))?></h1>

<div id="product-list-module">
	<?php 
		$this->widget('zii.widgets.CListView', array(
		    'dataProvider'=>$dataProvider,
		    'itemView'=>'_products', 
		    'sorterHeader'=>'Сортировка:',
		    'itemsTagName'=>'div',
		    'emptyText' => 'Нет товаров для отображения.',
		    'itemsCssClass'=>'product-list-custom',
		    'sortableAttributes'=>array(
		        'title',
		        'price',
		    ),
		    'id'=>'ajaxListView',
            'template'=>'{sorter}{items}{pager}<div class="sort-hidden">{sorter}</div>',
		    'ajaxUpdate'=>true,
		));
	?>	
</div>