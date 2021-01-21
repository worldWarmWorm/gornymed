<?php
/**
 * Поведение сортировки для модели \CActiveRecord
 * 
 */
namespace ext\D\sort\behaviors;

use AttributeHelper as A;
use ext\D\sort\models\SortCategory;
use ext\D\sort\models\SortData;

class SortBehavior extends \CBehavior
{
	/**
	 * Scope. Сортировка.
	 * @param string $category имя категории сортировки
	 * @param int|null $key ключ категории сортировки.
	 * @param boolean $revers обратная сортировка. По умолчанию FALSE (прямая сортировка).
	 * @param string $tableNameAlias псевдоним таблицы модели для которой в запросе происходит сортировка. 
	 * По умолчанию "t".
	 * @param string $attributeId имя атрибута id модели. По умолчанию "id".
	 */
	public function scopeSort($category, $key=null, $revers=false, $tableNameAlias='t', $attributeId='id')
	{
		$criteria=$this->owner->getDbCriteria();
		
		$columnId=\ARHelper::dbQC($attributeId);
		$tableName=\ARHelper::dbQT($tableNameAlias);
		$tnSortCategory=\ARHelper::dbQT(SortCategory::model()->tableName());
		$tnSortData=\ARHelper::dbQT(SortData::model()->tableName());
		
		$conditionCategoryKey="{$tnSortCategory}.`key`" . ($key ? "=:sortCategoryKey" : ' IS NULL'); 
		$criteria->join.=" LEFT JOIN {$tnSortCategory} ON ({$tnSortCategory}.`name`=:sortCategoryName AND {$conditionCategoryKey})";
		$criteria->join.=" LEFT JOIN {$tnSortData} ON ({$tnSortData}.`category_id`={$tnSortCategory}.`id` AND {$tnSortData}.`model_id`={$tableName}.{$columnId})";
		$criteria->params[':sortCategoryName']=$category;
		
		if($key) 
			$criteria->params[':sortCategoryKey']=$key;
		
		$criteria->order.=($criteria->order ? ', ' : '') . "{$tnSortData}.`order_number` " . ($revers ? 'DESC' : 'ASC');
		
		return $this->owner;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \CBehavior::attach()
	 */
	public function attach($owner) 
	{
		parent::attach($owner);
		
		$this->createTableSortCategory();
		$this->createTableSortData();
	}
	
	/**
	 * Создание таблицы категорий сортировки
	 */
	protected function createTableSortCategory()
	{
		if(\ARHelper::getTable('sort_category'))
			return true;
		
		$sql='
CREATE TABLE `sort_category` (
	`id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`name` VARCHAR(32) NOT NULL,
	`key` INT(11),
	UNIQUE (`name`, `key`),
	INDEX (`name`)
)';
		\Yii::app()->db->createCommand($sql)->execute();
	}
	
	/**
	 * Создание таблицы данных сортировки
	 */
	protected function createTableSortData()
	{
		if(\ARHelper::getTable('sort_data')) 
			return true;
		
		$sql='
CREATE TABLE `sort_data` (
	`id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`category_id` INT(11) NOT NULL,
	`model_id` INT(11) NOT NULL,
	`order_number` INT(11) NOT NULL,
	UNIQUE (`category_id`, `model_id`),
	KEY(`category_id`),
	FOREIGN KEY `category_id` (`category_id`) REFERENCES `sort_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
)';
		
		\Yii::app()->db->createCommand($sql)->execute();
	}
} 