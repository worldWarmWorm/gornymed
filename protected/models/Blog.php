<?php

/**
 * This is the model class for table "blog".
 *
 * The followings are the available columns in table 'blog':
 * @property integer $id
 * @property string $alias
 * @property string $title
 * @property integer $ordering
 */
class Blog extends DActiveRecord
{
    /**
     * (non-PHPdoc)
     * @see CModel::behaviors()
     */
    public function behaviors()
    {
    	$behaviors = array(
    		'aliasBehavior'=>array('class'=>'DAliasBehavior'),
    	);

    	if(D::yd()->isActive('treemenu')) {
    		$behaviors['activeMenuBehavior']=array(
    			'class'=>'\menu\components\behaviors\ActiveMenuBehavior'
    		);
    	}

    	return $behaviors;
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'blog';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return parent::rules(array(
			array('alias, title', 'required'),
			array('ordering', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('id, alias, title, ordering', 'safe', 'on'=>'search')
		));
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return parent::relations(array(
            'posts'=>array(self::HAS_MANY, 'Page', 'blog_id')
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
			'ordering' => 'Порядок'
		));
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('ordering',$this->ordering);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    protected function beforeValidate()
    {
        $this->alias = trim($this->alias);
        return true;
    }

    protected function afterSave()
    {
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

        return true;
    }

    protected function afterDelete()
    {
    	if(D::yd()->isActive('treemenu')) {
	    	if($this->asa('activeMenuBehavior')) $this->activeMenuBehavior->afterDelete();
	    }
	    else {
	        CmsMenu::getInstance()->removeItem($this);
	    }

        return true;
    }
}
