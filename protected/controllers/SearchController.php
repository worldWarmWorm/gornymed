<?php

class SearchController extends Controller
{



public function actionAutoComplete() {
     

    if (isset($_GET['q'])) {

    	$query = Yii::app()->request->getQuery('q');
		$criteria = new CDbCriteria();
		$criteria->addSearchCondition('title', $query, true, 'OR');
		$criteria->addSearchCondition('description', $query, true, 'OR');
		$criteria->limit = 10;
         
        $products = Product::model()->findAll($criteria);
         
        $resStr = '';
        foreach ($products as $product) {
            $resStr .= $product->title."\n";
        }
        echo $resStr;
    }
}
	public function actionIndex()
	{
		$query = Yii::app()->request->getQuery('q');
		
		if (mb_strlen($query, 'UTF-8') < 3) {
			$this->prepareSeo('Слишком короткий запрос');
			$this->render('index_empty');
			return;
		}
		
		// поиск по акциям (новостям)
		$criteria = new CDbCriteria();
		$criteria->addSearchCondition('title', $query, true, 'OR');
		$criteria->addSearchCondition('text', $query, true, 'OR');
		$criteria->addSearchCondition('publish', '1', false);
		
		$pagination = new CPagination();
		$pagination->pageSize = 3;
		$eventsDataProvider = new CActiveDataProvider('Event', array(
			'criteria'=>$criteria,
			'pagination' => $pagination
		));
		
		// поиск по страницам
		$criteria = new CDbCriteria();
		$criteria->addSearchCondition('title', $query, true, 'OR');
		$criteria->addSearchCondition('intro', $query, true, 'OR');
		$criteria->addSearchCondition('text', $query, true, 'OR');
		
		$pagination = new CPagination();
		$pagination->pageSize = 3;
		$pagesDataProvider = new CActiveDataProvider('Page', array(
			'criteria'=>$criteria,
			'pagination' => $pagination 
		));
		
		// поиск по продукции
		$criteria = new CDbCriteria();
		$criteria->addSearchCondition('title', $query, true, 'OR');
		$criteria->addSearchCondition('description', $query, true, 'OR');


        $data_p = new CActiveDataProvider('Product', array(
            'sort'=>array(
                'defaultOrder'=>'ordering ASC , id DESC',
            ),
            'pagination'=>array(
                'pageSize' => 15,
            ),
            'criteria'=>$criteria,
        ));
		
        $this->prepareSeo('Результаты поиска');
		$this->breadcrumbs->add('Результаты поиска');
		
		$this->render('index', compact('eventsDataProvider', 'pagesDataProvider', 'data_p'));
	}
}