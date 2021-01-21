<?php

/**
 * This is the model class for table "gallery".
 *
 * The followings are the available columns in table 'gallery':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $preview_id
 */
class Gallery extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gallery';
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
			#array('preview_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>500),
			array('preview_id', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, description, preview_id', 'safe'),
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

	public function getAlbumLink($album_id){
		return Gallery::model()->findByPk((int)$album_id);
	}

	public function getImageCount(){
		$count = count(GalleryImg::model()->findAll(array('condition'=>'gallery_id='.(int)$this->id)));
		return $count;
	}	

	public function getAlbumPreview() {
		if(strlen($this->preview_id)){
			if(is_file('images/gallery/' . $this->preview_id)){
				return '/images/gallery/' . $this->preview_id;
			}
		}
		
		$img = GalleryImg::model()->find(array('condition'=>'gallery_id='.(int)$this->id));
		if(count($img)){
			return '/images/gallery/tmb_' . $img->image;
		}
		else{
			return '/images/no_photo.png';
		}
	}

	public static function isTmbExist( $preview_id ) {
		$criteria=new CDbCriteria;
		$criteria->compare('preview_id',$preview_id, true);
		$cod = Gallery::model()->find($criteria);
		if(count($cod)){
			return true;
		} else {
			false;
		}
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Заголовок альбома',
			'description' => 'Описание альбома',
			'preview_id' => 'Preview',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('preview_id',$this->preview_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Gallery the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
