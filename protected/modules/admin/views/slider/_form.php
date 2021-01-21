<?php

$breadcrumbs = array();

$breadcrumbs['Слайдер'] = array('slider/index');
if($model->isNewRecord){
  $breadcrumbs[] = 'Добавление слайда';
}
else {
  $breadcrumbs[] = $model->title.' - редактирование';
}
$this->breadcrumbs = $breadcrumbs;
?>

<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
    'id'=>'slide-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
    'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title', array('class'=>'inline form-control')); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'link'); ?>
        <?php echo $form->textField($model,'link', array('class'=>'inline form-control')); ?>
        <?php echo $form->error($model,'link'); ?>
    </div>

    <?$types=$model->getTypes();?>
    <?if(count($types) > 1):?>
    <div class="row">
        <?=$form->labelEx($model,'type')?>
        <?=$form->dropDownList($model,'type', $types)?>
        <?=$form->error($model,'type')?>
    </div>
    <?elseif(count($types)):
    	echo $form->hiddenField($model, 'type', array('value'=>key($types))); 
    else:
    	echo '<div class="bg-danger">Не включен ни один тип слайдера</div>';
    endif;?>

    <?php if ($src = $model->src): ?>
    <div class="row">
        <img src="<?php echo $src; ?>" alt="" style="max-width:100%" />
    </div>
    <div class="row">
        <a id="change-file" class="js-link">Сменить</a>
    </div>
    <script type="text/javascript">
        $(function(){
            $('#file_field').hide();
            $('#change-file').click(function() {
                $('#file_field').show();
                $(this).remove();
            });
        });
    </script>
    <?php endif; ?>

    <div class="row" id="file_field">
        <?php echo $form->labelEx($model,'file'); ?>
        <?php echo $form->fileField($model, 'file'); ?>
        <?php echo $form->error($model,'file'); ?>
    </div>

    <div class="row buttons">
        <?=CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('class'=>'btn btn-primary')); ?>
        <?=CHtml::submitButton($model->isNewRecord ? 'Создать и выйти' : 'Сохранить и выйти', array('class'=>'btn btn-info', 'name'=>'saveout'))?>
        <?=CHtml::link('Отмена', array('index'), array('class'=>'btn btn-default')); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->
