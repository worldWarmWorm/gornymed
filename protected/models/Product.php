<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property integer $category_id
 * @property string $code
 * @property string $title
 * @property string $description
 * @property integer $price
 * @property boolean $notexist
 * @property boolean $new
 * @property integer $ordering
 * @property CUploadedFile $mainImg
 * @property CUploadedFile $moreImg
 * @property string $path Get path for images directory
 *
 * @property string|boolean ext
 */
class Product extends DActiveRecord
{
    public $property;

    protected $mainImg;
    protected $moreImg;

    protected $sizes = array(
        'full'=>array(
            'suffix'=>'',
            'size'=>900,
            'masterSize'=>4
        ),
        'big'=>array(
            'suffix'=>'_b',
            'size'=>320,
            'masterSize'=>4
        ),
        'small'=>array(
            'suffix'=>'_s',
            'size'=>200,
            'crop'=>1
        ),
        'tmb'=>array(
            'suffix'=>'_tmb',
            'size'=>45,
            'crop'=>1
        )
    );

    protected $exts = array('jpg', 'jpeg', 'png', 'gif');

    public function behaviors()
    {
        return array(
            'aliasBehavior'=>array('class'=>'DAliasBehavior'),
    		'metaBehavior'=>array('class'=>'MetadataBehavior'),
        	'sortBehavior'=>['class'=>'\ext\D\sort\behaviors\SortBehavior']
        );
    }
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'product';
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return parent::rules(array(
            array('category_id, title', 'required'),
            array('price', 'numerical', 'numberPattern'=>'/^[\d\s]+([.,][\d\s]+)?$/', 'message'=>'Число должно целым, либо в формате X.XX'),
            array('category_id, ordering', 'numerical', 'integerOnly'=>true),
            array('title, link_title', 'length', 'max'=>255),
            array('alt_title', 'length', 'max'=>500),
            array('mainImg', 'file', 'allowEmpty'=>true, 'types'=>'jpg, jpeg, gif, png'),
            array('notexist, sale, new, hit, in_carousel', 'boolean'),
            array('description, moreImg, price, code, popular', 'safe')
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return parent::relations(array(
            'related'=>array(self::HAS_MANY, 'Related', 'product_id'),
            'category'=>array(self::BELONGS_TO, 'Category', 'category_id'),
            'productAttributes'=>array(self::HAS_MANY, 'EavValue', 'id_product'),
            'relatedCategories'=>array(self::HAS_MANY, 'RelatedCategory', 'product_id'),
            'reviews'=>array(self::HAS_MANY, 'ProductReview', 'product_id')
        ));
    }

    public function scopes()
    {
        return array(
            'lastRecord'=>array(
                'order'=>'id DESC',
                'limit'=>1,
            ),
        	'cardColumns'=>array('select'=>'`t`.`id`, `t`.`category_id`, title, code, price, sale, new, hit, alt_title, link_title, notexist'),
        	'defaultOrder'=>array('order'=>'IF(ordering>0,0,1), ordering ASC, created')
        );
    }
    
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return parent::attributeLabels(array(
            'id' => 'ID',
            'category_id' => 'Категория',
            'title' => 'Название',
            'code'=>'Артикул',
            'description' => 'Описание',
            'price' => 'Цена',
            'ordering'=> 'Порядок',
            'property'=>'Свойство',
            'mainImg' => 'Главное фото',
            'moreImg' => 'Дополнительные фото',
            'notexist'=>'Нет в наличии',
            'sale'=>'Акция',
            'new'=>'Новинка',
            'hit'=>'Хит',
            'popular'=>'Популярный товар',
            'alt_title'=>'(ALT) для главного фото',
            'link_title'=>'(TITLE) для главной ссылки',
            'in_carousel'=>'Отображать на главной'
        ));
    }

    public function getCategories()
    {
        $cats_list = Category::model()->findAll(array('order'=>'root, lft'));;
        if (isset(Yii::app()->params['subcategories'])) {
            $cats_list = CmsCore::prepareTreeSelect($cats_list);
        }
        $categories = CHtml::listData($cats_list, 'id', 'title');
        return $categories;
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->with=array('productAttributes');
        $price_from = Yii::app()->getRequest()->getQuery('price_from');
        $price_to = Yii::app()->getRequest()->getQuery('price_to');
        $ftitle = Yii::app()->getRequest()->getQuery('f_title');
        $cat_id = Yii::app()->getRequest()->getQuery('id');
        $data_json = Yii::app()->getRequest()->getQuery('data');
        if(isset($data_json)){
            $attr_filter = json_decode($data_json);
            if(count($attr_filter)>0){
                $counter = 0;
                foreach ($attr_filter as $key => $attr) {
                    if($attr->value=="none") continue;
                    $counter++;
                    $criteria->addCondition('productAttributes.value = "'.$attr->value.'" and productAttributes.id_attrs = "'.$attr->name.'"', 'OR');
                }
                if($counter!=0){
                    $criteria->group = 'id_product';
                    $criteria->having = 'count(id_product)='.$counter;  
                }

            }
        }
        //Фильтрация цены
        if(isset($price_from) && isset($price_to)){
            #$criteria->addCondition('price >= '.$price_from.' AND price <= '.$price_to, 'AND');
            $criteria->addBetweenCondition('price', $price_from, $price_to );
        }
        elseif(isset($price_from)){
            $criteria->addCondition('price >= '.$price_to, 'AND');
           # $criteria->params = array('price_from'=>$price_from); 
        }
        elseif(isset($price_from)){
            $criteria->addCondition('price >= '.$price_to, 'AND');
        }
        $criteria->addSearchCondition('title', $ftitle, true, 'AND');

        if(isset($ftitle)){
            $criteria->addSearchCondition('title', $ftitle, true, 'AND');  
        }
        $additional = RelatedCategory::model()->findAll(array(
            'index'=>'product_id', 
            'select'=>'product_id', 
            'condition'=>'category_id=:id', 
            'params'=>array(':id'=>$cat_id)
        ));

        $category = Category::model()->findByPK($cat_id);

        $inIDs = array_keys($category->descendants(2)->findAll(array('index'=>'id', 'select'=>'id')));
        $inIDs[] = $cat_id;

        if($additional) {
            $criteria->addCondition(new CDbExpression('category_id IN ('.implode(',',array_values($inIDs)).') OR `t`.`id` IN ('.implode(',',array_keys($additional)).')'));
        }
        else {
            $criteria->addInCondition('category_id', $inIDs);
        }

        // if($additional) {
        //     $criteria->addCondition(new CDbExpression('category_id='.(int)$cat_id.' OR `t`.`id` IN ('.implode(',',array_keys($additional)).')'));
        // }
        // else {
        //     $criteria->compare('category_id',$cat_id);
        // }

        $criteria->compare('id',$this->id);
        $criteria->together = true;

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize' => 12,
                'pageVar'=>'p'
            ),
            'sort'=>array(
                'sortVar'=>'s', 
                'descTag'=>'d',
                'defaultOrder'=>'IF(ordering>0,0,1), ordering ASC, created',
            ),
        ));
    }

    protected function beforeValidate()
    {
        $this->mainImg = CUploadedFile::getInstance($this, 'mainImg');
        $this->moreImg = CUploadedFile::getInstances($this, 'moreImg');
        $this->price=str_replace(',','.', str_replace(' ', '', $this->price));

        return true;
    }
    
    protected function beforeSave() 
    {
    	if($this->isNewRecord) {
    		$this->ordering=0;
    		$this->created=new \CDbExpression('NOW()');
    	}
    	
    	return parent::beforeSave();
    }

    protected function afterSave()
    {
        parent::afterSave();
        
        if ($this->mainImg instanceof CUploadedFile) {
            $this->removeMainImage($this->id);
            $this->createMainImages();
        }

        if (count($this->moreImg)) {
            $this->createMoreImages();
        }
      
        return true;
    }

    protected function afterDelete()
    {
        parent::afterDelete();
        
        if(Yii::app()->params['attributes']){
            foreach($this->productAttributes as $model)
                $model->delete();
        }
        
        
        $this->removeMainImage();
        return true;
    }

    protected function afterFind()
    {
        parent::afterFind();
        
        $image = $this->id .'_s.' .$this->ext;

        if (is_file(Yii::getPathOfAlias('webroot.images.product') .DS. $image)) {
            $this->mainImg = '/images/product/'. $image;
        } else {
            $this->mainImg = '/images/shop/product_no_image.png';
        }

        return true;
    }

    public function removeMainImage($id = null)
    {
        $id       = $id ? $id : $this->id;
        if (!$this->id) {
            $this->id = $id;
        }

        $path     = $this->path;
        $suffixes = array('', '_b', '_s', '_tmb');
        $ext = $this->ext;

        foreach($suffixes as $s) {
            $file = $path. DS . $id . $s .'.'. $ext;

            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function clearImageCache()
    {
        $suffixes = array('_b', '_s', '_tmb');
        $path     = $this->path;
        $files    = scandir($path);

        foreach($files as $file) {
            foreach($suffixes as $s) {
                if (strpos($file, $s) !== false)
                    unlink($path .DS. $file);
            }
        }
    }

    public function getMoreImages()
    {
        if ($this->moreImg == null) {
            $this->moreImg = CImage::model()->findAll('model=? AND item_id=?', array(
                strtolower(get_class($this)),
                $this->id
            ));
        }

        return $this->moreImg;
    }

    public function getMainImg($admin = false)
    {
        return $this->checkSize('small', $admin);
    }

    public function getBigMainImg($admin = false)
    {
        return $this->checkSize('big', $admin);
    }

    public function getTmbImg($admin = false)
    {
        return $this->checkSize('tmb', $admin);
    }

    public function getFullImg($bool = false, $withTime = true)
    {
        $image = $this->id .'.' .$this->ext;
        if (!$withTime){
            if (is_file($this->path .DS. $image)){
            return $this->id .'.' .$this->ext;
            }
            else{
                return false;
            }
        }
        if (is_file($this->path .DS. $image))
            return $bool ? true : '/images/product/' .$image .'?'.filemtime($this->path .DS. $image);
        else
            return $bool ? false : '/images/shop/product_no_image_b.png';
    }

    private function createMainImages()
    {
        $path     = $this->path;
        $ext      = strtolower($this->mainImg->extensionName);
        $name     = $this->id. '.' .$ext;

        $this->mainImg->saveAs($path .DS. $name);

        $this->checkSize('full', true, true, true);
        $this->checkSize('big', true, true);
        $this->checkSize('small', true, true);
        $this->checkSize('tmb', true, true);
    }

    private function createMoreImages()
    {
        $params = array('max'=>100, 'master_side'=>4);

        if ($cropTop = Yii::app()->settings->get('shop_settings', 'cropTop')) {
            $params['crop'] = true;
            $params['cropt_top'] = $cropTop;
        }

        $upload = new UploadHelper;
        $upload->add($this->moreImg, $this);
        $upload->runUpload($params);
    }

    protected function getPath()
    {
        return Yii::getPathOfAlias('webroot.images.product');
    }

    protected function getExt($name = null)
    {
        if (!$name) {
            $name = $this->id;
        }

        foreach($this->exts as $ext) {
            if (is_file($this->path .DS. $name .'.'. $ext)) {
                return $ext;
            }
        }

        return false;
    }

    /**
     * Return images link
     * @param string $sizeName Full name of size type
     * @param bool $admin
     * @param bool $createOnly
     * @param bool $force
     * @return string|bool
     * @throws CException
     */

    private function checkSize($sizeName, $admin, $createOnly = false, $force = false)
    {
        if (!isset($this->sizes[$sizeName])) {
            throw new CException('Size type not found');
        }

        $path    = $this->path;
        $params  = $this->sizes[$sizeName];
        $ext     = $this->ext;

        $fullImg = $this->id .'.'. $ext;
        $image   = $this->id . $params['suffix'] .'.'. $ext;

        if ((!is_file($path .DS. $image) || $force) && is_file($path .DS. $fullImg) && exif_imagetype($path .DS. $fullImg)) {
            $img = Yii::app()->image->load($path .DS. $fullImg);

            if (isset($params['masterSize'])) {
                $masterSize = $params['masterSize'];
            } else {
                $masterSize = $img->width > $img->height ? Image::HEIGHT : Image::WIDTH;
            }

            if ($img->width > $params['size']) {
                $img->resize($params['size'], $params['size'], $masterSize);

                $cropTop = Yii::app()->settings->get('shop_settings', 'cropTop');

                if (isset($params['crop']) && $cropTop) {
                    $img->crop($params['size'], $params['size'], $cropTop);
                }
            }

            $img->save($path .DS. $image);
        }

        if ($createOnly)
            return;

        if (is_file($path .DS. $image)) {
            return '/images/product/'. $image . '?'. filemtime($path .DS. $image);
        }

        return $admin ? false : '/images/shop/product_no_image'. $params['suffix'] .'.png';
    }

    private function urlToPath($url) {
        $path = $_SERVER['DOCUMENT_ROOT'].mb_strcut($url, 0, mb_strpos($url, '?'));
        if(file_exists($path))
            return $path;
        else
            return false;
    }

    public function getWidth($image) {
        if(is_file($this->urlToPath($image))) {
            $imageObject = Yii::app()->image->load($this->urlToPath($image));
            return $imageObject->width;
        }
    }

    public function getHeight($image) {
        if(is_file($this->urlToPath($image))) {
            $imageObject = Yii::app()->image->load($this->urlToPath($image));
            return $imageObject->height;
        }
    }

    public function createUrl()
    {
        Yii::import('ext.Transliteration.Transliteration');

        $url = strtolower(Transliteration::text($this->title));
        $url = preg_replace(array('/[^a-z0-9_-]+/ui', '/-{2,}/ui'), '-', $url);
        $url = preg_replace(array('/^[^\w\d]+/ui', '/[^\w\d]+$/'), '', $url);

        return trim($url);
    }

}
