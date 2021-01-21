<?php

/**
 * This is the model class for table "sale".
 *
 * The followings are the available columns in table 'sale':
 * @property integer $id
 * @property string $title
 * @property string $active
 * @property string $preview
 * @property string $enable_preview
 * @property string $preview_text
 * @property string $text
 */
use YiiHelper as Y;

class Sale extends DActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sale';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CModel::behaviors()
	 */
	public function behaviors()
	{
		return array(
			'aliasBehavior'=>array('class'=>'DAliasBehavior'),
			'metaBehavior'=>array('class'=>'MetadataBehavior'),
			'imageBehavior'=>array(
				'class'=>'\ext\D\image\components\behaviors\ImageBehavior',
				'attribute'=>'preview',
				'attributeLabel'=>\Yii::t('sale', 'attribute.label.preview'),
				'attributeEnable'=>'enable_preview',
				'attributeEnableLabel'=>\Yii::t('sale', 'attribute.label.previewEnable'),
				'tmbWidth'=>D::cms('sale_preview_width', 320),
				'tmbHeight'=>D::cms('sale_preview_height', 240),
			),
			'activeBehavior'=>array(
				'class'=>'DActiveBehavior',
				'attributeLabel'=>\Yii::t('sale', 'attribute.label.active')
			)
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see CActiveRecord::scopes()
	 */
	public function scopes()
	{
		return parent::scopes(array(
			'previewColumns'=>array('select'=>'id, title, IF(enable_preview=1, preview, NULL) as preview, preview_text'),
			'detailColumns'=>array('select'=>'id, title, text')
		));
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return parent::rules(array(
			array('title', 'required'),
			array('title', 'length', 'max'=>255),
			array('id, title, enable_preview, preview_text, text, create_time', 'safe'),
			array('id, title, preview_text, text', 'safe', 'on'=>'search'),
		));
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return parent::attributeLabels(array(
			'id' => \Yii::t('sale', 'attribute.label.id'),
			'title' => \Yii::t('sale', 'attribute.label.title'),
			'preview_text' => \Yii::t('sale', 'attribute.label.preview_text'),
			'text' => \Yii::t('sale', 'attribute.label.text'),
			'create_time'=>\Yii::t('sale', 'attribute.label.create_time')
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
		$criteria->compare('text',$this->text,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Получить дату создания
	 * @return string отформатированная дата создания
	 */
	protected function getDate()
	{
		return Yii::app()->params['month']
			? Y::formatDateVsRusMonth($this->create_time)
			: Y::formatDate($this->created, 'dd.MM.yyyy');
	}
}
