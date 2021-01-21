<?php $this->pageTitle = 'Редактирование новости - '. $this->appName; 

$this->breadcrumbs=array(
    'Новости'=>array('event/index'),
    'Редактирование новости - '.$model->title,
);


?>

<h1>Редактирование новости</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
