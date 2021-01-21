<?php
class MetadataBehavior extends \CBehavior
{
	public $attributeTitle='title';
	
	public $meta_h1;
	public $meta_title;
	public $meta_key;
	public $meta_desc;
	public $a_title;

	public $priority;
	public $changefreq;
	public $lastmod;
	
	public function events()
	{
		return array(
			'onAfterFind'=>'afterFind',
			'onAfterSave'=>'afterSave',
			'onAfterDelete'=>'afterDelete'
		);
	}
	
	public function rules()
	{
		return array(
			array('meta_h1, meta_title, a_title', 'length', 'max'=>255),
            array('a_title', 'match', 'pattern'=>'/^[^"]+$/', 'message'=>'Не допускается использование символа двойной кавычки (").'),
			array('meta_h1, meta_title, meta_key, meta_desc, priority, changefreq, lastmod, a_title', 'safe'),
			array('priority', 'numerical', 'min' => 0, 'max' => 1)
			
		);
	}
	
	public function relations()
	{
		return array(
			'meta'=>array(CActiveRecord::BELONGS_TO, 'Metadata', array('id'=>'owner_id'),
                'together'=>true,
                'condition'=>'owner_name = :ownerName',
                'params'=>array(':ownerName'=>strtolower(get_class($this->owner)))
            )
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'meta_title'=>'META: Заголовок',
			'meta_key'=>'META: Ключевые слова',
			'meta_desc'=>'META: Описание',
			'meta_h1'=>'H1',
			'a_title'=>'SEO &lt;A title="" ...&gt;',
			'priority'=>'Приоритетность URL относительно других URL на Вашем сайте',
			'changefreq'=>'Вероятная частота изменения этой страницы',
			'lastmod'=>'Дата последнего изменения файла',
		);
	}
		
	public function getPriority()
	{
		$meta=$this->owner->getRelated('meta');	
		return $meta->priority;
	}

	public function getChangefreq()
	{
		$meta=$this->owner->getRelated('meta');	
		return $meta->changefreq;
	}

	public function getLastmod()
	{
		$meta=$this->owner->getRelated('meta');	
		return $meta->lastmod;
	}

	public function getMetaH1()
	{
		$meta=$this->owner->getRelated('meta'); 
		return ($meta && $meta->meta_h1) ? $meta->meta_h1 : $this->owner->{$this->attributeTitle};
	}
	
	public function getMetaATitle()
	{
		$meta=$this->owner->getRelated('meta');
		return ($meta && $meta->a_title) ? $meta->a_title : $this->owner->{$this->attributeTitle};
	}
	
	public function afterFind()
	{
		if($meta=$this->owner->getRelated('meta')) {
			$this->meta_h1=$meta->meta_h1;
			$this->meta_title=$meta->meta_title;
			$this->meta_key=$meta->meta_key;
			$this->meta_desc=$meta->meta_desc;
			$this->a_title=$meta->a_title;

			$this->priority = $meta->priority;
			$this->changefreq = $meta->changefreq;
			$this->lastmod = $meta->lastmod;
		}
		
		return true;		
	}
	
	public function afterSave()
	{
		$meta=$this->owner->getRelated('meta');
		if (!$meta) {
			$this->owner->meta = new Metadata();
			$this->owner->meta->owner_name = $this->_getOwnerName();
			$this->owner->meta->owner_id   = $this->owner->id;
		}
		
		$this->owner->meta->meta_h1 = $this->meta_h1; 
		$this->owner->meta->meta_title = $this->meta_title; 
		$this->owner->meta->meta_key = $this->meta_key; 
		$this->owner->meta->meta_desc = $this->meta_desc; 
		$this->owner->meta->a_title = $this->a_title; 
			
		$this->owner->meta->priority = $this->priority;
		$this->owner->meta->changefreq = $this->changefreq;
		$this->owner->meta->lastmod = date('Y-m-d', time());

		$this->owner->meta->save();
		
		return true;
	}
	
	public function afterDelete()
	{
		$model=Metadata::model()->find('owner_name=:ownerName AND owner_id=:ownerID', array(
			':ownerName'=>$this->_getOwnerName(),
			':ownerID'=>$this->owner->id
		));
		
		if($model) $model->delete();
		
		return true;
	}
	
	private function _getOwnerName()
	{
		return strtolower(get_class($this->owner));
	}
}