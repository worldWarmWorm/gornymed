<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
use YiiHelper as Y;
use AttributeHelper as A;

class Controller extends DController
{
	/**
	 * @see CController::$layout
	 * @var string
	 */
	public $layout='//layouts/other';

	/**
	 * @var string текст заголовока контента страницы для H1.
	 */
	public $contentTitle='';
	
	/**
	 * @var \ext\D\breadcrumbs\components\Breadcrumb компонент хлебных крошек;
	 */
	public $breadcrumbs;

    /**
     * дополнительные аттрибуты/свойства контроллера.
     * @var array 
     */
    private $_attributes = array();

    /**
     * (non-PHPdoc)
     * @see CComponent::__get()
     */
    public function __get($name) 
    {
    	try {
    		return parent::__get($name);
    	}
    	catch(CException $e) {	
    	}
    	
    	return $this->isAllowAttribute($name) ? $this->_attributes[$name] : null;
    }
    
    /**
     * (non-PHPdoc)
     * @see CComponent::__set()
     */
    public function __set($name, $value)
    {
    	if(isset($this->_attributes[$name]) || $this->isAllowAttribute($name)) {
    		$this->_attributes[$name] = $value;
    		return;
    	}
    	try {
    		parent::__set($name, $value);
    	}
    	catch(CException $e) {
    		throw $e;
    	}

    	return;
    }
    
    /**
     * Разрешенные аттрибуты/свойства контроллера.
     * Аттрибут/свойство необходимо задавать как пару (attribute=>default).
     * @return array массив разрешенных аттрибутов вида (attribute=>default)
     */
    public static function allowAttributes()
    {
    	return array(
    		'menu'=>array(),
    		'meta_key'=>'',
    		'meta_desc'=>''
    	);
    }
    
    /**
     * Проверяет, является ли переданный аттрибут разрешенным.
     * @param string $attribute имя проверяемого атрибута.
     * @return boolean
     */
    public function isAllowAttribute($name)
    {
    	if(array_key_exists($name, $this->_attributes)) return true; 
   		else {
   			$allowAttributes = method_exists($this, 'allowAttributes') ? $this::allowAttributes() : array();
   			foreach(class_parents($this) as $parent) {
   				if(method_exists($parent, 'allowAttributes')) {
   					$allowAttributes = CMap::mergeArray($parent::allowAttributes(), $allowAttributes);
   				}
   			}    		
   			if(in_array($name, array_keys($allowAttributes))) {
    			$this->_attributes[$name] = $allowAttributes[$name];
    			return true;
   			}
   		}

    	return false;
    } 

    /**
     * Main init method for cms
     * @return void
     */
    public function init()
    {
        // Set site name
        $sitename   = Yii::app()->settings->get('cms_settings', 'sitename');
        $adminEmail = Yii::app()->settings->get('cms_settings', 'email');
        $menu_limit = Yii::app()->settings->get('cms_settings', 'menu_limit');
        $hide_news  = Yii::app()->settings->get('cms_settings', 'hide_news');

        if ($sitename)
            Yii::app()->name = $sitename;
        
        if ($adminEmail)
            Yii::app()->params['adminEmail'] = $adminEmail;

        if ($menu_limit)
            Yii::app()->params['menu_limit'] = $menu_limit;

        if ($hide_news)
            Yii::app()->params['hide_news'] = $hide_news;

        $this->meta_key  = Yii::app()->settings->get('cms_settings', 'meta_key');
        $this->meta_desc = Yii::app()->settings->get('cms_settings', 'meta_desc');

        $this->menu = CmsMenu::getInstance()->siteMenu();
        
        $this->breadcrumbs=new \ext\D\breadcrumbs\components\Breadcrumb();
    }

    /**
     * Set title of page
     * @param null|mixed $page_title
     * @return void
     */
    protected function prepareSeo($page_title = null)
    {   
        $meta_title = Yii::app()->settings->get('cms_settings', 'meta_title');
        if (empty($meta_title))
            $meta_title = Yii::app()->name;

        if ($page_title === null)
            $this->contentTitle = $this->pageTitle = $meta_title;
        else {
            $this->pageTitle = $page_title;
            $this->contentTitle = $page_title;
        }
    }

    /**
     * 
     * @param mixed $metadata может быть передана либо модель с поведение MetadataBehavior, 
     * либо модель Metadata, 
     * либо массив, в котором все ключи являются не обязательными.  
     * 	array(
     * 		'meta_h1'=>заголовок H1, 
     * 		'meta_title'=>заголовок браузера,
     * 		'meta_key'=>ключевые слова,
     *  	'meta_desc'=>описание
     * ) 
     */
    public function seoTags($metadata)
    {
    	if(is_array($metadata)) {
    		$this->contentTitle=A::get($metadata, 'meta_h1', $this->contentTitle);
    		$this->pageTitle=A::get($metadata, 'meta_title', $this->pageTitle);
    		$this->meta_key=A::get($metadata, 'meta_key', $this->meta_key);
    		$this->meta_desc=A::get($metadata, 'meta_desc', $this->meta_desc);
    	}
    	elseif($metadata instanceof CModel) {
    		if(!($metadata instanceof Metadata) && $metadata->hasRelated('meta')) 
    			$metadata=$metadata->getRelated('meta');
    		 
        	if($metadata instanceof Metadata) {
        		$this->meta_key=$metadata->getKey()?:$this->meta_key;
        		$this->meta_desc=$metadata->getDesc()?:$this->meta_desc;
        		$this->pageTitle=$metadata->getTitle()?:$this->pageTitle;
        		$this->contentTitle=$metadata->getH1()?:$this->contentTitle;
            }
    	}
    }

    /**
     * Get template base url
     */
    public function getTemplate()
    {
        return Yii::app()->theme->baseUrl;
    }

    /**
     * Is index page
     * @return boolean
     */
    public function isIndex()
    {
        return $this->id == 'site' && $this->action->id == 'index';
    }
}
