<?php
namespace ext\D\sort\actions;

use YiiHelper as Y;
use ext\D\sort\models\SortCategory; 
use ext\D\sort\models\SortData;

class SaveAction extends \CAction
{
	/**
	 * @var array список разрешенных категорий. 
	 */
	public $categories=[];
	
	/**
	 * Run action.
	 */
	public function run()
	{
		$categoryName=Y::request()->getParam('category');
		$categoryKey=Y::request()->getParam('key') ?: null;
		
		if(!$categoryName || !in_array($categoryName, $this->categories))
			throw new \CHttpException(400);

		$category=SortCategory::model()->category($categoryName, $categoryKey)->find();
		if(!$category) {
			$category=new SortCategory;
			$category->name=$categoryName;
			$category->key=$categoryKey ?: null;
			$category->save();
		}
		
		SortData::model()->saveData($category->id, Y::request()->getParam('data'));
		
		if(Y::request()->isAjaxRequest) {
			\AjaxHelper::end(true);
		}
	}
}