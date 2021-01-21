<?php

/**
* Класс генерации карты сайта.
*/
namespace ext\sitemap;

class SitemapGenerator extends Sitemap //Наследуемся от карты сайта.
{
	//Точка функция которая генерирует отдельно страницы, категории, продукты
	public function generateSitemap() {
		$this->setPath('');
		$this->addItem('', '1');
		$this->generateXml('site/event', 'sitemap', 'Event', '0.8');
		$this->generatePageXml('sitemap');
		$this->generateCategoryXml('sitemap');
		$this->generateXml('shop/product', 'sitemap', 'Product', '0.5');

		$this->createSitemapIndex('http://'. $_SERVER['HTTP_HOST'] .'/', 'Today');

	}

	//Добавление meta данных.
	private function addMetaItem($product_url, $priority='0.5', $changefreq=false, $lastmod=false) {
		if(empty($priority)) $priority = '0.5';
		$this->addItem($product_url, $priority, $changefreq, $lastmod);
	}


	private function generatePageXml($xmlName) {
		$this->setFilename($xmlName);
		$model = \Page::model()->findAll();
		foreach($model as $model_item) {
			$url = \Yii::app()->createUrl('site/page', array('id'=>$model_item->id));
			if(!empty($model_item->meta)){
				$priority = $model_item->meta->priority;
			}
			if(empty($priority)){
				$priority = '0.8';
			}
			$changefreq = $model_item->meta->changefreq;
			$lastmod = $model_item->meta->lastmod;
			$this->addMetaItem($url, $priority, $changefreq, $lastmod);
		}
	}

	private function generateCategoryXml($xmlName) {
		$this->setFilename($xmlName);
		$model = \Category::model()->findAll();
		$priority = '0.8';
		foreach($model as $model_item) {
			if(!empty($model_item->meta)){
				$priority = $model_item->meta->priority;
				$changefreq = $model_item->meta->changefreq;
				$lastmod = $model_item->meta->lastmod;
				$url = \Yii::app()->createUrl('shop/category', array('id'=>$model_item->id));
				if(empty($priority)){
					if($model_item->level==1) $priority = '0.8';
					if($model_item->level==2) $priority = '0.6';
					if($model_item->level==3) $priority = '0.5';
					if($model_item->level==4) $priority = '0.4';
					if($model_item->level==5) $priority = '0.3';
				}	
			}

			$this->addMetaItem($url, $priority, $changefreq, $lastmod);
		}
	}

	//Универсальная функция генерации по модели, если у модели есть мета бехивер.
	private function generateXml($modelUrl, $xmlName, $model, $p) {
		$this->setFilename($xmlName);
		$model = $model::model()->findAll();
		foreach($model as $model_item) {
			$url = \Yii::app()->createUrl($modelUrl, array('id'=>$model_item->id));
			if(!empty($model_item->meta)){
				$priority = $model_item->meta->priority;
			}
			if(empty($priority)) {
				$priority = $p;
			}
			$changefreq = $model_item->meta->changefreq;
			$lastmod = $model_item->meta->lastmod;
			$this->addMetaItem($url, $priority, $changefreq, $lastmod);
		}
	}
}