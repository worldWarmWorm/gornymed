<?php
/**
 * Правило маршрутизации для моделей с поведением DAliasBehavior
 * 
 */
use AttributeHelper as A;

class DAliasRule extends CBaseUrlRule
{
	/**
	 * @var array массив конфигурации. 
	 * array(
	 * 	className=>array(
	 * 		'url'=>основная ссылка, 
	 * 		'replaceUrl'=>ссылка на которую замениться основная ссылка при формировании,
	 * 		если не указана, будет использована основная ссылка. 
	 * 		'attributeId'=>attributeId,
	 * 		'attributeAlias'=>attributeAlias,
	 * 		'module'=>имя модуля, если задан, будет проверяться подключен модуль или нет.
	 * )) 
	 * attributeId по умолчанию "id",
	 * attributeAlias по умолчанию "alias",
	 */
	public $config=array(
		'Category'=>array('url'=>'shop/category', 'module'=>'shop'),
		'Product'=>array('url'=>'shop/product', 'module'=>'shop'),
		'Event'=>array('url'=>'site/event', 'replaceUrl'=>'news'),
		'Sale'=>array('url'=>'sale/view', 'replaceUrl'=>'sale', 'module'=>'sale'),
		'Blog'=>array('url'=>'site/blog'),
		'Page'=>array('url'=>'site/page')
	);
	
	/**
	 * (non-PHPdoc)
	 * @see CBaseUrlRule::createUrl()
	 */
	public function createUrl($manager, $route, $params, $ampersand)
	{
		foreach($this->config as $className=>$cfg) {
	  		if ($route == $cfg['url']) {
	  			if($module=A::get($cfg, 'module')) {
	  				if(!\Yii::app()->d->isActive($module)) return false;
	  			}
	   			$id=$params['id'];
	   			$alias=$this->_getAliasById($className, $id);
	   			
	   			unset($params['id']);
				$url=empty($alias) ? sprintf(A::get($cfg, 'replaceUrl', $cfg['url']).'/%d', $id) : sprintf('%s', $alias);
	
	    		if(!empty($params)) 
	    			$url.='?' . $manager->createPathInfo($params, '=', $ampersand);
	    		
	    		return $url;
	  		}
		}

		return false;
	}

	/**
	 * (non-PHPdoc)
	 * @see CBaseUrlRule::parseUrl()
	 */
	public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
	{
		foreach($this->config as $className=>$cfg) {
			if ($id=$this->_getIdByAlias($className, $pathInfo)) {
				$_GET['id']=$id;
				return $cfg['url'];
			}
		}
		
		return false;
	}
	
	/**
	 * Получение имени атрибута id модели
	 * @param string $className имя класса модели
	 * @return string
	 */
	private function _getAttributeId($className)
	{
		return A::get(A::get($this->config, $className, array()), 'attributeId', 'id');
	}
	
	/**
	 * Получение имени атрибута алиаса модели
	 * @param string $className имя класса модели
	 * @return string
	 */
	private function _getAttributeAlias($className)
	{
		return A::get(A::get($this->config, $className, array()), 'attributeAlias', 'alias');
	}
	
	/**
	 * Получение id модели по алиасу 
	 * @param string $className имя класса модели
	 * @param string $alias алиас модели.
	 * @return integer
	 */
	private function _getIdByAlias($className, $alias)
	{
		return (int)\Yii::app()->db->createCommand()
			->select($this->_getAttributeId($className))
			->from($className::model()->tableName())
			->where($this->_getAttributeAlias($className).'=:alias', array(':alias'=>$alias))
			->queryScalar();
	}

	/**
	 * Получение алиаса модели по id
	 * @param string $className имя класса модели
	 * @param integer $id id модели.
	 */
	private function _getAliasById($className, $id)
	{
		return \Yii::app()->db->createCommand()
			->select($this->_getAttributeAlias($className))
			->from($className::model()->tableName())
			->where($this->_getAttributeId($className).'=:id', array(':id'=>$id))
			->queryScalar();
	}
}
