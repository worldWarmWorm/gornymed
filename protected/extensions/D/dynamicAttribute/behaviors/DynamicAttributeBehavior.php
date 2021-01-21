<?php
/**
 * Dynamic attribute behavior
 * PHP >=5.4
 *
 * Модель должна быть наследуемой от \DActiveRecord
 */
namespace ext\D\dynamicAttribute\behaviors;

use \AttributeHelper as A;

class DynamicAttributeBehavior extends \CBehavior
{
	/**
	 * Dynamic attribute 
	 * @var string
	 */
	public $attribute = 'extend_props';
	
	/**
	 * @var string название атрибута
	 */
	public $attributeLabel='Properties';

	/**
	 * Разрешены пустые записи или нет.
	 * @var boolean
	 */
	public $allowEmpty = false;
	
	/**
	 * Опция безопасного получения значения. 
	 * Если значение будет битым, то возвращается пустой массив.
	 * @var boolean
	 */
	public $safeGet = true;

	/**
  	 * (non-PHPDoc)
  	 * @see \CBehavior::events();
	 */
	public function events() 
	{
		return [
			'onBeforeSave'=>'beforeSave'
		];
	}
	
	/**
	 * (non-PHPDoc)
	 * @see CBehavior::attach($owner)
	 */
	public function attach($owner)
	{
		parent::attach($owner);

		if(!\Yii::app()->db->getSchema()->getTable($this->owner->tableName())->getColumn($this->attribute)) {
			\Yii::app()->db->createCommand()->addColumn(
				$this->owner->tableName(),
				$this->attribute,
				'TEXT'
			);
		}
	}
	
	/**
	 * (non-PHPDoc)
	 * @see CModel::rules()
	 */
	public function rules()
	{
		return [
			[$this->attribute, 'safe']
		];
	}

	/**
	 * (non-PHPDoc)
	 * @see CModel::attributeLabels()
	 */
	public function attributeLabels()
	{
		return [
			$this->attribute=>$this->attributeLabel
		];
	}

	/**
	 * Before save
	 * @return boolean
	 */
	public function beforeSave()
	{ 
		return $this->set($this->owner->{$this->attribute});		
	}
	
	/**
	 * Get attribute
	 * @return mixed
	 */
	public function get()
	{
		try {
			$value=$this->owner->{$this->attribute};
			if(is_array($value)) $data=$value;
			else $data=unserialize($value);
			return is_array($data) ? $data : array();
		}
		catch(\Exception $e) {
			if($this->safeGet) return array();
			else throw $e;
		}
	}
	
	/**
	 * Set attribute
	 * @param array $value
	 */
	public function set($value)
	{	
		if(is_array($value)) {
			if(!$this->allowEmpty) {
				foreach($value as $idx=>$data) {
					if(empty($data) || !implode('', array_values($data))) unset($value[$idx]);
					elseif(!isset($data['active'])) $value[$idx]['active']=0;
				}
			}
		}
		elseif($this->safeGet) 
			$value = array();

		$this->owner->{$this->attribute} = serialize($value);

		return true;
	}
	
	/**
	 * Get only actived
	 * @param boolean $preserveKeys сохранять ключи или нет.
	 * @return array
	 */
	public function getActive($preserveKeys=false)
	{
		$actived = array();
		 
		foreach($this->get() as $index=>$data) {
			if(A::get($data, 'active')) {
				if($preserveKeys) $actived[$index] = $data;
				else $actived[] = $data;
			}
		}
		
		return $actived;
	}
}
