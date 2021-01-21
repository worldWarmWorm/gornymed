<?php
namespace ext\D\dynamicAttribute\widgets;

use \AttributeHelper as A;

class DynamicAttributeWidget extends \CWidget
{
	/**
	 * @var \ext\D\dynamicAttribute\behaviors\DynamicAttributeBehavior поведение динамического атрибута
	 */
	public $behavior;
	
	/**
	 * @var string имя атрибута
	 */
	public $attribute;
	
	/**
	 * Массив заголовков данных
	 * "active" - is RESERVED KEY!
	 * array(key=>title)
	 * @var array
	 */
	public $header = array();
	
	/**
	 * Список ключей, которые будут только для чтения.
	 * Игнорируется ключ "active".
	 * array(key)
	 * @var array
	 */
	public $readOnly = array();
	
	/**
	 * Данные по умолчанию
	 * Индексы элементов должны совпадать индексами массива загловков.
	 * array(array(key=>value)) 
	 * @var array
	 */
	public $default = array();

	/**
	 * @var boolean значение активности по умолчанию. 
	 * Будет перезаписано, если установлено в DynamicAttributeWidget::$default.
	 */
	public $defaultActive=false;

	/**
	 * @var boolean скрыть колонку активности
	 */
	public $hideActive = false;
	
	/**
	 * Не отображать кнопку добавления
	 * @var boolean
	 */
	public $hideAddButton = false;

	/**
	 * @var boolean не отображать кнопку удаления
	 */
	public $hideDeleteButton=false;
	
	public function init()
	{
		\AssetHelper::publish(array(
			'path' => __DIR__ . DS . 'assets',
			'js' => array('js/DynamicAttributesWidget.js', 'js/dynamic_attributes_widget.js'),
			'css' => 'css/default.css'
		));
		
		\Yii::app()->clientScript->registerScript(
			'dynamicAttributesWidget' . $this->attribute, 
			'DynamicAttributesWidget.init("'.$this->attribute.'");', 
			\CClientScript::POS_END
		);
	}
	
	public function run()
	{
		$this->render('default');
	}
	
	/**
	 * Get row data by index.
	 * @param integer $index row index
	 * @return array
	 */
	public function getRowData($index) 
	{
		return A::get($this->behavior->owner->{$this->$attribute}, $index, A::get($this->default, $index, null));		
	}
	
	/**
	 * Generate row HTML code. 
	 * @param integer $index row index. Если передано значение NULl, 
	 * генерится код шаблона для новых элементов.
	 * @param array $data row data. 
	 * @return string html code
	 */
	public function generateRow($index, $data=array()) {
		$name = \YiiHelper::slash2_($this->behavior->owner) . "[{$this->attribute}]";
	
		$isTemplate = is_null($index);
		if(is_null($index)) $index = '{{daw-index}}';
		
		$html = '<tr>';
		if($this->hideActive) {
			$html .= \CHtml::hiddenField($name . "[{$index}][active]", A::get($data, 'active', $this->defaultActive), array(
				'disabled'=>$isTemplate
			));			
		}
		else {
			$html .= '<td align="center">';
			$html .= \CHtml::checkBox($name . "[{$index}][active]", A::get($data, 'active', $this->defaultActive), array(
				'class'=>'daw-inpt-active',
				'title'=>'Отображать на сайте',
				'value'=>1,
				'disabled'=>$isTemplate,
				'onclick'=>in_array('active', $this->readOnly) ? 'return false;' : ''
			));
			$html .= '</td>';
		}
	
		foreach($this->header as $key=>$title) {
			$value = is_null($index) ? '' : A::q(A::get($data, $key, ''));
			$html .= '<td>';
			
			if(in_array($key, $this->readOnly)) {
				$html .= \CHtml::hiddenField($name . "[{$index}][{$key}]", $value, array('class'=>'daw-inpt', 'readonly'=>true, 'disabled'=>$isTemplate));
				$html .= $value;
			}
			else {
				$html .= \CHtml::textField($name . "[{$index}][{$key}]", $value, array('class'=>'daw-inpt form-control', 'maxlength'=>255,'disabled'=>$isTemplate));
			}
			$html .= '</td>';
		}
	
	    if(!$this->hideDeleteButton)
			$html .= '<td align="center"><button class="btn btn-danger btn-sm daw-btn-remove">Удалить</button></td>';

		$html .= '</tr>';
	
		return $html;
	}	
}
