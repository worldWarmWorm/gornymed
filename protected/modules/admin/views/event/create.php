<?php $this->pageTitle = 'Новое событие - '. $this->appName; 

$this->breadcrumbs=array(
    'Новости'=>array('event/index'),
    'Новое событие',
);

?>

<h1>Новое событие</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
