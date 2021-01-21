<?php

/**
 * This is the model class for table "page".
 *
 * The followings are the available columns in table 'page':
 * @property integer $id
 * @property integer $parent_id
 * @property integer $blog_id
 * @property string $alias
 * @property string $title
 * @property string $intro
 * @property string $text
 * @property string $created
 * @property string $modified
 *
 * @property Metadata $meta[]
 */
class Page extends DActiveRecord
{
    public $image;
    public $file;

	/**
	 * Returns the static model of the specified AR class.
     * @param mixed
	 * @return Page the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * (non-PHPdoc)
     * @see CModel::behaviors()
     */
    public function behaviors()
    {
    	$behaviors = array(
    		'aliasBehavior'=>array('class'=>'DAliasBehavior'),
			'metaBehavior'=>array('class'=>'MetadataBehavior'),
			// 'loadFileBhavior'=>[
			// 	'class'=>'\common\ext\file\behaviors\FileBehavior',
			// 	'attribute'=>'load_file',
			// 	'attributeLabel'=>'Скачать прайл',
			// 	'imageMode'=>false

			// ]
    	);

    	if(D::yd()->isActive('treemenu')) {
    		$behaviors['activeMenuBehavior']=array(
    			'class' => '\menu\components\behaviors\ActiveMenuBehavior',
    		);
    	}

    	return $behaviors;
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'page';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return parent::rules(array(
			array('alias, title, text', 'required'),
            array('blog_id, parent_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
            array('created, modified', 'unsafe')
		));
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return parent::relations(array(
            'blog'=>array(self::BELONGS_TO, 'Blog', 'blog_id'),
			'images'=>array(self::HAS_MANY, 'CImage', 'item_id', 
				'condition'=>'model = :model', 
				'params'=>array(':model' => strtolower(get_class($this))),
				'order'=>'ordering'
			),
			'mainImg'=>array(self::HAS_ONE, 'CImage', 'item_id',
				'condition'=>'model = :model',
				'params'=>array(':model' => strtolower(get_class($this))),
				'order'=>'ordering'
			)
		));
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return parent::attributeLabels(array(
			'id' => 'ID',
			'parent_id' => 'Привязать к странице',
            'blog_id' => 'Блог',
			'title' => 'Заголовок',
			'intro' => 'Вводный текст',
			'text' => 'Текст',
			'created' => 'Создана',
            'modified' => 'Изменена',
		));
	}

	/**
	 * Get image from images relation.
	 * @param integer $index zero-based index of image
	 * @return mixed CImage or NULL.
	 */
	public function getImage($index=0)
	{
		return (count($this->images) > $index) ? $this->images[$index] : null;
	}
	
	/**
	 * Get image URL.
	 * @param integer $index zero-based index of image.
	 * @param string $default default url.
	 * @param integer $alternativeIndex zero-based alternative index of image. 
	 * альтернативный индекс картинки, если картинка по $index не найдена, 
	 * то берется данный индекс.
	 * Значения данного индекса могут быть:
	 * NULL: поиска альтернативной картинки не совершается
	 * -1: поиск ведется по убыванию, до первой найденной картинки, от значения $index.  
	 * @return string
	 */
	public function getImageUrl($index=0, $default='', $alternativeIndex=null) 
	{
		if($image = $this->getImage($index)) { 
			return $image->getUrl();
		} elseif($alternativeIndex == -1) {
			while((--$index >= 0) && ($image = $this->getImage($index))) 
				return $image->getUrl();
		}
		elseif(!is_null($alternativeIndex) && ($image = $this->getImage($alternativeIndex))) { 
			return $image->getUrl();
		}
		
		return $default;
	}

	/**
	 * Get intro
	 * @note This is old function, use HtmlHelper::getIntro().
	 */
    public function getIntro()
    {
        preg_match('%<p[^>]*>(.*)</p>%', $this->text, $array);
        $txt = '<p>'. $array[1]. '</p>';

        ContentDecorator::decorate($this);
        return $txt;
    }

    protected function getDate()
    {
        return Yii::app()->dateFormatter->format('dd.MM.yyyy', $this->created);
    }

    public function isDefault()
    {
        $menuItem = CmsMenu::getInstance()->getItem($this);

        if (!$menuItem)
            return false;

        if (!$menuItem->default)
            return false;

        return true;
    }

    protected function afterFind()
    {
    	parent::afterFind();
        //$format = 'dd.MM.yyyy HH:mm';
        //$this->created  = Yii::app()->dateFormatter->format($format, $this->created);
        //$this->modified = Yii::app()->dateFormatter->format($format, $this->modified);

        return true;
    }

    protected function beforeValidate()
    {
        $this->alias = trim($this->alias);
        $this->image = CUploadedFile::getInstances($this, 'image');
        $this->file  = CUploadedFile::getInstances($this, 'file');

        if ($this->isNewRecord) {
            $this->created = new CDbExpression('NOW()');
        } else {
            $this->modified = new CDbExpression('NOW()');
        }

        return true;
    }

    protected function afterSave()
    {
    	parent::afterSave();
    	
        $upload = new UploadHelper;

        if (count($this->image))
            $upload->add($this->image, $this);
        if (count($this->file))
            $upload->add($this->file, $this, 'file');
        $upload->runUpload();

        if (!$this->blog_id) {
   	    	// Update site menu
    		if(D::yd()->isActive('treemenu')) {
	    		if($this->asa('activeMenuBehavior')) $this->activeMenuBehavior->afterSave();
		    }
	        else {
		        if ($this->isNewRecord)
    	    	    CmsMenu::getInstance()->addItem($this);
	    	    else
    		        CmsMenu::getInstance()->updateItem($this);
	    	}
        }

        return true;
    }

    protected function afterDelete()
    {
    	parent::afterDelete();
    	
        $params = array(
            'model'   => strtolower(get_class($this)),
            'item_id' => $this->id
        );

        $items = array_merge(
            CImage::model()->findAllByAttributes($params),
            File::model()->findAllByAttributes($params)
        );

        foreach($items as $item)
            $item->delete();

    	if(D::yd()->isActive('treemenu')) {
	    	if($this->asa('activeMenuBehavior')) $this->activeMenuBehavior->afterDelete();
	    }
	    else {
	        CmsMenu::getInstance()->removeItem($this);
	    }

        return true;
    }
    
    /**
     * Get data for CActiveForm::dropDownList() and etc.
     *
     * @param array $addNoSelected Добавить элемент "Не выбран" в начало списка.
     * @return array
     */
    public function getListData($addNoSelected=false)
    {
    	$data=$addNoSelected ? array(0=>"-- cамостоятельная страница --") : array();
    
    	$pages=$this->findAll(array('select'=>'id, title','order'=>'title asc'));
    	if($pages)
    	foreach($pages as $page)
    	if(!$this->id || ($this->id != $page->id))
    		$data[$page->id]=$page->title;
    
    	return $data;
    }
    
    public function getItems()
    {
    	return Page::model()->findAll(array('select'=>'id, parent_id, alias, title'));
    }
    
    public function findByAlias($alias)
    {
    	return $this->find('alias like :alias', array(':alias'=>$alias));
    }

}
