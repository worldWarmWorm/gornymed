<?php
/**
 * Виджет хлебных крошек
 * 
 */
namespace ext\D\breadcrumbs\widgets;

class Breadcrumbs extends \CWidget
{
	public $breadcrumbs;
	
	public $htmlOptions=array('class'=>'breadcrumbs');
	
	/**
	 * @var string название домашней страницы. 
	 * Если передано значение NULL элемент отображен не будет
	 */
	public $homeTitle='';
	
	public $homeUrl='/';
	
	/**
	 * @var string использовать микроразметку
	 */
	public $useSchema=true;
	
	/**
	 * 
	 * @var array
	 * linkOptions
	 */
	public $homeHtmlOptions=array();
	
	/**
	 * (non-PHPdoc)
	 * @see \CWidget::init()
	 */
	public function init()
	{
		if(!$this->homeTitle && ($this->homeTitle !== null)) { 
			$this->homeTitle=\Yii::t('\ext\D\breadcrumbs\widgets\Breadcrumbs.breadcrumbs', 'homeTitle');
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \CWidget::run()
	 */
	public function run()
	{
		$this->render($this->useSchema ? 'schema_default' : 'default');
	}
}