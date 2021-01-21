<?php
namespace ext\D\sort\models;

class SortData extends \DActiveRecord
{
	/**
	 * (non-PHPdoc)
	 * @see \CActiveRecord::tableName()
	 */
	public function tableName()
	{
		return 'sort_data';
	}

	/**
	 * Сохранение данных сортировки
	 * @param int $categoryId id категории
	 * @param array $data отсортированный массив идентификаторов моделей.
	 * Если значение массива не является числом, сортировка сохранена не будет.
	 * @return boolean
	 */
	public function saveData($categoryId, $data)
	{
		if(!$categoryId) return false;
		
		if(!empty($data)) {
			$values=[];
			foreach($data as $n=>$id) {
				if(!is_numeric($id)) return false;
				$values[]="({$categoryId}, {$id}, ".($n+1).')';
			}
				
			$sql='REPLACE '.\ARHelper::dbQT($this->tableName()).' (`category_id`, `model_id`, `order_number`) VALUES ';
			$sql.=implode(',', $values);
				
			\ARHelper::db()->createCommand($sql)->execute();
		}
	}
}