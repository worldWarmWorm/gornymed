<?php

/**
 * This is the model class for table "Event".
 *
 * The followings are the available columns in table 'Event':
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $created
 * @property integer $publish
 */
use YiiHelper as Y;

class Event extends DActiveRecord
{
    public $image;
    public $file;
    public $files;

	/**
	 * (non-PHPdoc)
	 * @see CModel::behaviors()
	 */
	public function behaviors()
	{
		return array(
			'aliasBehavior'=>array('class'=>'DAliasBehavior'),
			'metaBehavior'=>array('class'=>'MetadataBehavior')
		);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
        return parent::rules(array(
			array('title, text, created', 'required'),
			array('publish', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
            array('enable_preview', 'boolean'),
            array('intro', 'safe'),
			array('id, title, text, created, publish', 'safe', 'on'=>'search'),
		));
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
        return parent::attributeLabels(array(
			'id' => 'ID',
            'title' => 'Заголовок',
            'intro' => 'Превью новости',
            'text' => 'Текст новости',
            'created' => 'Создана',
            'publish' => 'Активно?',
            'enable_preview' => 'Отображать в модуле',
			'files' => 'Изображение в заголовке'
		));
	}

    protected function getDate()
    {
        return Yii::app()->params['month'] 
        	? Y::formatDateVsRusMonth($this->created) 
        	: Y::formatDate($this->created, 'dd.MM.yyyy');
    }

    public function getPreviewImg(){
        return !empty($this->preview) ? '/images/event/'.$this->preview : false;
    }

    public function getPreviewEnable(){
        return (bool)$this->enable_preview;
    }
    /**
     * Get first paragraph of content
     *
     * @return string
     */
    public function getIntro()
    {
        preg_match('%<p[^>]*>(.*)</p>%', $this->text, $array);
        $txt = '<p>'. $array[1]. '</p>';
        return $txt;
    }
    
    protected function beforeValidate()
    {
        $this->image = CUploadedFile::getInstances($this, 'image');
        $this->file  = CUploadedFile::getInstances($this, 'file');
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
    }

    protected function afterDelete()
    {
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

        return true;
    }
}
