<?php

/**
 * This is the model class for table "article".
 *
 * The followings are the available columns in table 'article':
 * @property integer $id
 * @property string $title
 * @property string $short
 * @property string $link
 * @property string $preview
 * @property integer $sort
 */
class Article extends DActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'article';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return parent::rules(array(
            array('title, short, link', 'required'),
            array('sort', 'numerical', 'integerOnly'=>true),
            array('title, short, link, preview', 'length', 'max'=>255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, short, link, preview, sort', 'safe'),
            array('id, title, short, link, preview, sort', 'safe', 'on'=>'search'),
        ));
	}

    public function behaviors()
    {
        return array(
            'imageBehavior'=>array(
                'class'=>'\ext\D\image\components\behaviors\ImageBehavior',
                'attribute'=>'preview',
                'attributeLabel'=>\Yii::t('sale', 'attribute.label.preview'),
//                'attributeEnable'=>'enable_preview',
                'attributeEnableLabel'=>\Yii::t('sale', 'attribute.label.previewEnable'),
                'tmbWidth'=>D::cms('sale_preview_width', 86),
                'tmbHeight'=>D::cms('sale_preview_height', 100),
            ),
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return parent::attributeLabels(array(
            'id' => 'ID',
            'title' => 'Заголовок',
            'short' => 'Краткое описание',
            'link' => 'Ссылка',
            'preview' => 'Изображение',
            'sort' => 'Сортировка',
        ));
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
		$criteria->compare('short',$this->short,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('preview',$this->preview,true);
		$criteria->compare('sort',$this->sort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Article the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
