<?php
namespace ext\D\sort\widgets;

use \YiiHelper as Y;

class Sortable extends \CWidget
{
	/**
	 * @var string имя категории сортировки
	 */
	public $category;
	
	/**
	 * @var string ссылка на действие сохранения
	 */
	public $actionUrl;
	
	/**
	 * @var string выражение выборки (jQuery) родительского элемента.
	 */
	public $selector;
	
	/**
	 * @var string id виджета. 
	 * При использовании на одной странице нескольких сортировок задавайте каждому виджету свое значение.
	 */
	public $id='ext_d_sort_widget_sortable';
	
	/**
	 * @var int ключ категории сортировки.
	 */
	public $key=null;
	
	/**
	 * @var string имя атрибута сортировки, в котором будут хранится id модели.
	 */
	public $optionId='data-sort-id';
	
	/**
	 * (non-PHPdoc)
	 * @see \CWidget::init()
	 */
	public function init()
	{
		parent::init();
		
		\AssetHelper::publish([
			'path'=>dirname(__FILE__) . Y::DS . 'assets',
			'js'=>'js/Sortable.js'
		]);
		
		Y::cs()->registerCoreScript('jquery.ui');
		Y::csjs(
			$this->id, 
			'Ext_D_sort_widgets_Sortable.init({
				category: "'.$this->category.'",
				key: '.($this->key ?: 'null').',
				selector: "'.$this->selector.'",
				actionUrl: "'.$this->actionUrl.'", 
				optionId: "'.$this->optionId.'"
			});',  
			\CClientScript::POS_READY
		);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \CWidget::run()
	 */
	public function run()
	{	
	}
}