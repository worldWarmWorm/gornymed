<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rick
 * Date: 28.08.12
 * Time: 11:41
 * To change this template use File | Settings | File Templates.
 */
use YiiHelper as Y;

class SliderController extends AdminController
{
	/**
	 * (non-PHPdoc)
	 * @see AdminController::filters()
	 */
	public function filters()
	{
		return CMap::mergeArray(parent::filters(), array(
			array('DModuleFilter', 'name'=>'slider')
		));
	}
	
    public function actionOrder()
    {
        $orders = Yii::app()->request->getParam('item');

        $items = Slide::model()->findAll(array('order'=>'ordering'));
        $reset = !$orders || !is_array($orders) ? true : false;
        $i = 1;

        foreach($items as $item) {
            if ($item->ordering > 0) {
                $inx = !$reset ? array_search($item->id, $orders) : $i++ ;
                $item->ordering = $inx + 1;
                $item->save();
            }
        }

        Yii::app()->end();
    }

    public function actionIndex()
    {
    	$c=new CDbCriteria();
    	$c->order='ordering';
    	if($type=Yii::app()->request->getParam('t')) {
    		$c->addCondition('type=:type');
    		$c->params=array(':type'=>$type);
    	}
        $slides = Slide::model()->findAll($c);

        $this->render('index', compact('slides'));
    }

    public function actionCreate()
    {
        $model = new Slide();

        if (isset($_POST['Slide'])) {
            $model->attributes = $_POST['Slide'];
            if ($model->save()) {
            	if(isset($_POST['saveout'])) {
					Y::setFlash(Y::FLASH_SYSTEM_SUCCESS, Yii::t('AdminModule.slider', 'success.slideCreatedWithName', ['{name}'=>$model->title]));
					$this->redirect(['index']);
				}
				else {
					$this->redirect(['update', 'id'=>$model->id]);
				}
            }
        }

        $this->render('create', compact('model'));
    }

    public function actionUpdate($id)
    {
        $model = Slide::model()->findByPk($id);

        if (isset($_POST['Slide'])) {
            $model->attributes = $_POST['Slide'];
            if ($model->save()) {
            	if(isset($_POST['saveout'])) {
					Y::setFlash(Y::FLASH_SYSTEM_SUCCESS, Yii::t('AdminModule.slider', 'success.slideUpdatedWithName', ['{name}'=>$model->title]));
					$this->redirect(['index']);
				}
				else {
                	Yii::app()->user->setFlash('slide_update', true);
					$this->refresh();
				}
            }
        }
        $this->render('update', compact('model'));
    }

    public function actionRemove($id)
    {
        $model = Slide::model()->findByPk($id);
        if (!$model)
            throw new CHttpException(404, 'Не найдено!');

        if (!$model->delete())
            throw new CHttpException(500, 'Не удалось удалить');

        $this->redirect(array('slider/index'));
    }
}
