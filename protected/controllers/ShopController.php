<?php

class ShopController extends Controller
{
	/**
	 * (non-PHPdoc)
	 * @see AdminController::filters()
	 */
	public function filters()
	{
		return CMap::mergeArray(parent::filters(), array(
			array('DModuleFilter', 'name'=>'shop'),
		));
	}
	
    public function actionIndex()
    {
    	$this->seoTags(array(
			'meta_h1'=>D::shop('meta_h1', $this->getHomeTitle()),
			'meta_title'=>D::shop('meta_title', $this->getHomeTitle()),
			'meta_key'=>D::shop('meta_key'),
			'meta_desc'=>D::shop('meta_desc')
		));
        
        $dataProvider=Product::model()->cardColumns()->defaultOrder()->getDataProvider(
        	array('limit'=>15),
            array('pageSize' => 15, 'pageVar'=>'p')
        );
        
       	$this->breadcrumbs->add($this->getHomeTitle());
        $this->render('shop', compact('dataProvider'));
    }

    public function actionCategory($id)
    {
        $category = $this->loadModel('Category', $id);
        
        $model = new Product('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Product']))
        	$model->attributes=$_GET['Product'];
        
        if(\Yii::app()->request->isAjaxRequest) {
        	$this->renderPartial('_products_listview', compact('model'), false, true);
        }
        else {
        	$this->prepareSeo($category->title);
	        $this->seoTags($category);
	        ContentDecorator::decorate($category, 'description');
	
	        $this->breadcrumbs->add($this->getHomeTitle(), '/shop');
	        $this->breadcrumbs->addByNestedSet($category, '/shop/category');
	        $this->breadcrumbs->add($category->title);
	        
	        $this->render('category', compact('model', 'category') );
        }
    }

    /**
     * Action show a product page
     *
     * @param $id
     */
    public function actionProduct($id)
    {
        $product = Product::model()->findByPk($id);

        $this->prepareSeo($product->meta_title?:$product->title);
        $this->seoTags($product);

        if (!$product)
            throw new CHttpException(404, Yii::t('shop','product_not_found'));

        $this->breadcrumbs->add($this->getHomeTitle(), '/shop');
        $this->breadcrumbs->addByNestedSet($product->category, '/shop/category');
        $this->breadcrumbs->add($product->category->title, array('/shop/category', 'id'=>$product->category->id));
        $this->breadcrumbs->add($product->title);
        
        $this->render('product', compact('product'));
    }
    
    public function getHomeTitle()
    {
    	return D::cms('shop_title',Yii::t('shop','shop_title'));
    }
}
