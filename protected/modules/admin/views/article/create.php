<?php
/* @var $this ArticleController */
/* @var $model Article */

$this->breadcrumbs=array(
	'Статьи'=>array('index'),
	'Создание',
);
?>

<h1>Создание статьи</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>