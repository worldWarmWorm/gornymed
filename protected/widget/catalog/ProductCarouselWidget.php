<?php

class ProductCarouselWidget extends CWidget
{
	public function run()
	{
		if(!D::cms('shop_enable_carousel')) return false;
		
		$dataProvider=Product::model()
			->cardColumns()
			->scopeSort('product_carousel')
			->getDataProvider(['condition'=>'in_carousel=1'], false);
		
		if(!$dataProvider->totalItemCount) return false;

		Yii::app()->clientScript->registerScriptFile('/js/jquery.bxslider.js');
		
		$this->render('product_carousel', compact('dataProvider'));
	}
}