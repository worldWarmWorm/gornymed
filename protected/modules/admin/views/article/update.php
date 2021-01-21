<?php
/* @var $this ArticleController */
/* @var $model Article */

$this->breadcrumbs=array(
	'Статьи'=>array('index'),
	$model->title . ' - Обновление',
);

?>

<h1>Обновление статьи <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>