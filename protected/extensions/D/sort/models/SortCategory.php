<?php
namespace ext\D\sort\models;

class SortCategory extends \DActiveRecord
{
	/**
	 * (non-PHPdoc)
	 * @see \CActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'sort_category';
	}
	
	/**
	 * Scope.
	 * @param unknown $name
	 * @param string $key
	 * @return \ext\D\sort\models\SortCategory
	 */
	public function category($name, $key=null)
	{
		$criteria=$this->getDbCriteria();
		$criteria->addColumnCondition(['`t`.`name`'=>$name, '`t`.`key`'=>$key]);
		
		return $this;
	}
}