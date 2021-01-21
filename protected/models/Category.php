<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $ordering
 */
class Category extends DActiveRecord
{
    public function behaviors()
    {
        return array(
            'NestedSetBehavior'=>array(
                'class'=>'ext.yiiext.behaviors.trees.NestedSetBehavior',
                'leftAttribute'=>'lft',
                'rightAttribute'=>'rgt',
                'levelAttribute'=>'level',
                'hasManyRoots'=>true
            ),
        	'aliasBehavior'=>array('class'=>'DAliasBehavior'),
        	'metaBehavior'=>array('class'=>'MetadataBehavior')
        );
    }
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return parent::rules(array(
			array('title', 'required'),
			//array('ordering', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('description, parent_id', 'safe'),
			array('id, title, description, ordering', 'safe', 'on'=>'search'),
		));
	}
    
	public function relations()
	{
		return parent::relations(array(
            'tovars'=>array(self::HAS_MANY, 'Product', 'category_id'),
			'images'=>array(self::HAS_MANY, 'CImage', 'item_id',
					'condition'=>'model = :model',
					'params'=>array(':model' => strtolower(get_class($this))),
					'order'=>'images.ordering'
			),
			'mainImg'=>array(self::HAS_ONE, 'CImage', 'item_id',
				'condition'=>'model = :model',
				'params'=>array(':model' => strtolower(get_class($this))),
				'order'=>'mainImg.ordering'
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
			'title' => 'Название',
			'description' => 'Описание',
			'ordering' => 'Порядок',
            'parent_id'=>'Родитель'
		));
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('ordering',$this->ordering);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getMaxPrice(){
		$price = Yii::app()->db->createCommand('SELECT max(price) from product where category_id='.Yii::app()->request->getParam('id'))->queryRow();
		return (int)$price['max(price)'];
	}
	
	public function getMinPrice(){
		$price = Yii::app()->db->createCommand('SELECT min(price) from product where category_id='.Yii::app()->request->getParam('id'))->queryRow();
		return (int)$price['min(price)'];
	}
}
