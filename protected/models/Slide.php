<?php

/**
 * This is the model class for table "slide".
 *
 * The followings are the available columns in table 'slide':
 * @property integer $id
 * @property string $title
 * @property string $link
 * @property string $filename
 * @property integer $ordering
 *
 * @property CUploadedFile $file
 */
class Slide extends CActiveRecord
{
	const TYPE_CAROUSEL=1;
	const TYPE_SLIDER=2;
	const TYPE_BANNER=3;

    public $file;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Slide the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'slide';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('type', 'in', 'range'=>$this->getAllTypes()),
			array('title, link', 'length', 'max'=>255),
            array('file', 'file', 'allowEmpty'=>true)
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Заголовок',
			'type' => 'Тип',
			'file' => 'Изображение',
            'link' => 'Ссылка',
			'ordering' => 'Ordering',
		);
	}

	public function getTypeNames()
	{
		return array(
			self::TYPE_SLIDER=>'slider', 
			self::TYPE_CAROUSEL=>'carousel',
			self::TYPE_BANNER=>'banner'
		);
	}
	
	public function getTypeLabels()
	{
		return array(
			self::TYPE_SLIDER=>'Слайд-шоу', 
			self::TYPE_CAROUSEL=>'Карусель',
			self::TYPE_BANNER=>'Баннер'
		);
	}

	public function getTypes()
	{
		$types=array(); 
		foreach($this->getTypeNames() as $type=>$name) {
			if((int)D::cms("slider_{$name}_active")) 
				$types[$type]=$this->getTypeLabels()[$type];
		}
		return $types;
	}

	public function getAllTypes()
	{
		return array(
			self::TYPE_SLIDER, 
			self::TYPE_CAROUSEL,
			self::TYPE_BANNER
		);
	}

    public function getSrc()
    {
        $file = Yii::getPathOfAlias('webroot.images.carousel').DS.$this->filename;
        if (is_file($file)) {
            return '/images/carousel/'.$this->filename;
        }
        return false;
    }

    protected function beforeValidate()
    {
        $this->file = CUploadedFile::getInstance($this, 'file');
        return true;
    }

    protected function beforeSave()
    {
        if ($this->file instanceof CUploadedFile) {
            $path = Yii::getPathOfAlias('webroot.images.carousel');

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $name = coreHelper::generateHash().'.'.$this->file->extensionName;
            $this->file->saveAs($path .DS. $name);

            $image = Yii::app()->image->load($path.DS.$name);
            
            $prefix = 'slider_'.$this->getTypeNames()[$this->type];
            $w = D::cms($prefix.'_width');
           	$h = D::cms($prefix.'_height');
           	
           	if($image->width > $w || $image->height > $h) {
	           	$p=$w / $h;
	           	$pImage=$image->width / $image->height;
	           	// если $pImage > $p значит высота изображения в пропорции будет меньше нужной, 
	           	// масштабируем по высоте, иначе по ширине 
           		$master=($pImage > $p) ? Image::HEIGHT : Image::WIDTH;
                $image->resize($w, $h, $master)
					->crop($w, $h)
	                ->save();
            }

            $this->filename = $name;
        }
        return true;
    }
}
