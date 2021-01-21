<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rick
 * Date: 05.03.12
 * Time: 11:49
 * To change this template use File | Settings | File Templates.
 */
class GalleryController extends Controller
{
	public function filters()
	{
		return CMap::mergeArray(parent::filters(), array(
			array('DModuleFilter', 'name'=>'gallery')
		));
	}
	
    public function actionIndex()
    {
        $this->prepareSeo($this->getGalleryHomeTitle());
    	$albums = Gallery::model()->findAll(array('order'=>'ordering ASC'));
    	
    	$this->breadcrumbs->add($this->getGalleryHomeTitle());
    	
    	$this->render('index', compact('albums'));
    }

    public function actionAlbum($id)
    {
        $album = Gallery::model()->findByPk((int)$id);
        $this->prepareSeo('Альбом - '.$album->title);
    	$photos = GalleryImg::model()->findAll(array('condition'=>'gallery_id=' . (int)$id, 'order'=>'image_order'));
    	
    	$this->breadcrumbs->add($this->getGalleryHomeTitle(), '/gallery');
    	$this->breadcrumbs->add($album->title);
    	
    	$this->render('album', compact('photos', 'album'));
    }
    
    public function getGalleryHomeTitle()
    {
    	return D::cms('gallery_title',Yii::t('gallery','gallery_title'));
    }
}
