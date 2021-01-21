<?php

/**
 * DishCMS API
 */
class DApi extends \CComponent
{
	/**
	 * @var string имя поля с секретным ключом
	 */
	const SKEY = '#securityKey#';
	
	/**
	 * Список модулей array(module=>boolean)
	 * Имя модуля=>Влючен/Выключен 
	 * @var array
	 */
	public $modules = array();
	
	/**
	 * @var string имя файла лога миграций. Файл будет записан в директорию application.runtime
	 */
	public $migrateLogFile='db_migrations.log';
	
	/**
	 * @var string значение секретного ключа
	 */
	private $_securityKey=null; 
	
	/**
	 * Список модулей array(module=>boolean)
	 * @see self::$modules
	 * @var array
	 */
	private $_modules=array();
		
	/**
	 * Массив конфигурации для компонента \DCart\components\DCart
	 */
	public $configDCart = array(
		'class' => '\DCart\components\DCart',
		'attributeImage' => 'tmbImg',
		'cartAttributes' => array(),
		'attributes' => array('code')
	);
	
	/**
	 * Список уже загруженных модулей
	 * @var array array(module=>true)
	 */
	private static $_loaded=array();
	
	/**
	 *  Инициализация
	 */
	public function init()
	{
		$this->_installDb();

		$this->_modules=$this->modules;
		if(is_array($this->modules) && !empty($this->modules)) {
			foreach($this->modules as $name=>$active) {
				if($name==self::SKEY) {
					$this->_securityKey = $active;
				}
				elseif(!isset(self::$_loaded[$name])) {
					$methodRegister = '_register' . ucfirst(strtolower($name));
					if(($active === true) && method_exists($this, $methodRegister)) {
						$this->$methodRegister();
						self::$_loaded[$name]=true;
					}
				}
			}
		}	
	}
	
	/**
	 * Проверить активен модуль или нет.
	 * @param string $module имя модуля.
	 * @return boolean
	 */
	public function isActive($module)
	{
		return isset($this->modules[$module]) && ($this->modules[$module] === true);
	}
	
	/**
	 * Проверить активность всех переданных модулей или нет.
	 * @param array $modules список модулей
	 * @param boolean $or если установлен в TRUE, проверка будет "один из"
	 * @return boolean возвращает TRUE, только если все переданные модули активны или, 
	 * если параметр $or установлен в TRUE "один из".
	 */
	public function isActives($modules, $or=false)
	{
		if(is_string($modules)) $modules=array($modules);
		if(!is_array($modules)) return false;
		
		$actived=false;
		foreach($modules as $module) {
			if($or) $actived |= $this->isActive($module);
			else $actived &= $this->isActive($module);
		}
		
		return $actived;
	}
	
	/**
	 * Получить значение секретного ключа
	 * @return string
	 */
	public function getSecurityKey()
	{
		return $this->_securityKey;
	}
	
	/**
	 * Получить список модулей
	 * @return array
	 */
	public function getModules()
	{
		return is_array($this->_modules) ? $this->_modules : array();
	}
	
	/**
	 * Получить список активных модулей
	 * @return array
	 */
	public function getActived()
	{
		return array_filter(
			array_keys($this->getModules()), 
			function($name){
				return $this->isActive($name) && ($name!=self::SKEY);
			}
		);
	}
	
	/**
	 * Регистрация модуля "Обратная связь"
	 */
	private function _registerFeedback()
	{
		\Yii::setPathOfAlias('feedback', 'application.modules.feedback');
		\Yii::app()->setModules(array('feedback'=>array('class'=>'application.modules.feedback.FeedbackModule')));
		$module = \Yii::app()->getModule('feedback');
		\Yii::app()->urlManager->addRules($module->urlRules, false);
	}
	
	/**
	 * Регистрация модуля "Вопрос-ответ"
	 */
	private function _registerQuestion()
	{
		// добавление пункта меню
		$this->_addMenuItem(array(
			'title'=>'Вопрос-Ответ',
			'options'=>'{"model":"question"}'
		));
	}
	
	/**
	 * Регистрация модуля "Слайдер"
	 */
	private function _registerSlider()
	{
		// добавление пункта меню
		$this->_addMenuItem(array(
			'title'=>'Слайдер',
			'options'=>'{"model":"slider"}'
		), true);
	}
	
	/**
	 * Регистрация модуля "Фотогалерея"
	 */
	private function _registerGallery()
	{
		// добавление пункта меню
		$this->_addMenuItem(array(
			'title'=>'Фотогалерея',
			'options'=>'{"model":"gallery"}'
		));
	}
	
	/**
	 * Регистрация модуля "Многоуровневое меню"
	 */
	private function _registerTreemenu()
	{ 
		\Yii::setPathOfAlias('menu', 'application.modules.menu');
		\Yii::app()->setModules(array('menu'=>array('class'=>'application.modules.menu.MenuModule')));
		$module = \Yii::app()->getModule('menu');
		$model = \menu\models\Menu::model(); 
		$model->install(true);
		
		if($model->exists('hidden>0 AND ordering<0 AND id<>1')) {
			$query = 'UPDATE ' . $model->tableName() . ' SET system=1 WHERE hidden>0 AND ordering<0 AND id<>1';
			\Yii::app()->db->createCommand($query)->execute();
		}
	}
	
	private function _registerShop()
	{
		\Yii::setPathOfAlias('DCart', 'application.modules.DCart');
		\Yii::setPathOfAlias('DOrder', 'application.modules.DOrder');
		\Yii::app()->setModules(array(
			'DCart'=>array('class'=>'application.modules.DCart.DCartModule'),
			'DOrder'=>array(
				'class'=>'application.modules.DOrder.DOrderModule',
				'tableName' => 'dorder',
            	'frontendControllerAlias' => 'order'
			)
		));
		foreach(array('DCart','DOrder') as $name) {
			$module = \Yii::app()->getModule($name);
			\Yii::app()->urlManager->addRules($module->getUrlRules(), false);
		}
		
		Yii::app()->setComponent('cart', $this->configDCart);
		
		// добавление пунктов меню
		$this->_addMenuItem(array(
			'title'=>'Каталог',
			'options'=>'{"model":"shop"}'
		));
		
		$attributes=array('title'=>'Отзывы на товар', 'options'=>'{"model":"review"}');
		if((int)D::cms('shop_enable_reviews', 1) === 1) $this->_addMenuItem($attributes, true);
		else $this->_removeMenuItem($attributes);
		
		$attributes=array('title'=>'Аттрибуты товара', 'options'=>'{"model":"attributes"}');
		\Yii::app()->params['attributes']=((int)D::cms('shop_enable_attributes') === 1);
		if(\Yii::app()->params['attributes']) $this->_addMenuItem($attributes, true);
		else $this->_removeMenuItem($attributes);
	}
	
	/**
	 * Регистрация модуля Акции
	 */
	private function _registerSale()
	{
		$this->_addMenuItem(array(
			'title'=>'Акции',
			'options'=>'{"model":"sale"}'
		));
	}
	
	/**
	 * Добавление пункта меню
	 * @param string $attributes аттрибуты. Если не задан аттрибут "ordering",
	 * для ($isSystem==false) устанавливается максимальное значение плюс один(+1),
	 * для ($isSystem==true) устанавливается максимальное отрицательное значение минус один(-1).
	 */
	private function _addMenuItem($attributes, $isSystem=false)
	{
		// добавление пункта меню
		$options = $attributes['options']; 
		if(!\Menu::model()->exists('options=:options', array(':options'=>$options))) {
			$menu = new Menu();
			$menu->attributes = $attributes;
			
			if(!isset($attributes['ordering'])) {
				$max = (int)\Yii::app()->db->createCommand()
					->select('ordering')
					->from(Menu::model()->tableName())
					->order('ordering '.($isSystem?'ASC':'DESC'))
					->queryScalar();
				
				if($isSystem && $max > -1) $max = 0;
				$menu->ordering = (int)$max + ($isSystem ? -1 : 1);
			}
			
			if($isSystem) {
				$menu->hidden = 1;
				$menu->system = 1;
			}
			
			return $menu->save();
		}
		
		return false;
	}
	
	private function _removeMenuItem($attributes)
	{
		if($item=\Menu::model()->findByAttributes(array('options'=>$attributes['options']))) {
			$item->delete();
		}
	}

	/**
	 * Инсталляция базы данных и выполение миграций
	 * @throws CException
	 */
	private function _installDb()
	{
        if (!Yii::app()->db->schema->getTable('settings')) {
            $db_schema = Yii::getPathOfAlias('application.data').DS.'install.sql';

            if (!is_file($db_schema)) {
                throw new CException('Not tables');
            }

            Yii::app()->db->createCommand(file_get_contents($db_schema))->execute();
        }
		
        // накатываем миграции
        $countMigrations=count(scandir(Yii::getPathOfAlias('application.migrations')))-2;
       	$countDbMigrations=Yii::app()->db->schema->getTable('tbl_migration') 
			? (Yii::app()->db->createCommand('SELECT COUNT(*) FROM `tbl_migration`')->queryScalar() - 1)
        	: 0;
        if($countMigrations != $countDbMigrations) {
       		Yii::import('system.cli.commands.MigrateCommand');
       		Yii::import('system.console.CConsoleCommandRunner');
       		$consoleCommandRunner=new CConsoleCommandRunner();
       		$consoleCommandRunner->commands=array(
       			'migrate'=>array(
					'class'=>'system.cli.commands.MigrateCommand',
					'migrationPath'=>'application.migrations',
					'migrationTable'=>'tbl_migration',
					'connectionID'=>'db',
       				'interactive'=>false
			));
       		ob_start();
       		$consoleCommandRunner->run(array('yiic.php', 'migrate'));
       		$migrateLog=ob_get_clean();
       		file_put_contents(Yii::getPathOfAlias('application.runtime').DS.$this->migrateLogFile, $migrateLog, FILE_APPEND);
        }
	}
}