<div class="row">
    <?php echo $form->label($model,'sitename'); ?>
    <?php echo $form->textField($model,'sitename', array('style'=>'width: 100%', 'class'=>'form-control')); ?>
    <?php echo $form->error($model,'sitename'); ?>
</div>

<div class="row">
    <?php echo $form->label($model,'firm_name'); ?>
    <?php echo $form->textField($model,'firm_name', array('class'=>'form-control')); ?>
    <?php echo $form->error($model,'firm_name'); ?>
</div>

<div class="row">
    <?php echo $form->label($model, 'email'); ?>
    <?php echo $form->textField($model, 'email', array('class'=>'form-control')); ?>
    <?php echo $form->error($model,'email'); ?>
</div>

<div class="row">
    <?php echo $form->label($model, 'emailPublic'); ?>
    <?php echo $form->textField($model, 'emailPublic', array('class'=>'form-control')); ?>
    <?php echo $form->error($model,'emailPublic'); ?>
</div>

<div class="row">
    <?php echo $form->label($model,'phone'); ?>
    <?php echo $form->textField($model,'phone', array('class'=>'form-control'))?>
    <?php echo $form->error($model,'phone'); ?>
</div>

<div class="row">
    <?php echo $form->label($model,'phone2'); ?>
    <?php echo $form->textField($model,'phone2', array('class'=>'form-control'))?>
    <?php echo $form->error($model,'phone2'); ?>
</div>

<!-- <div class="row">
    <?/*php
        $this->widget('\common\ext\file\widgets\UploadFile', [
            'behavior'=>$model->loadFileBhavior,
            'form'=>$form,
            'actionDelete'=>$this->createAction('removeFile'),
            'view'=>'pannel_upload_file'
        ]);*/
    ?>
</div> -->

<div class="row">
    <?php echo $form->label($model, 'slogan'); ?>
    <?php 
        $this->widget('admin.widget.EditWidget.TinyMCE', array(
        	'editorSelector'=>'sloganEditor',
            'model'=>$model,
            'attribute'=>'slogan',
            'full'=>false,
            'htmlOptions'=>array('class'=>'big')
        )); 
    ?>
    <?php echo $form->error($model,'slogan'); ?>
</div>

<div class="row">
    <?php echo $form->label($model, 'address'); ?>
    <?php 
        $this->widget('admin.widget.EditWidget.TinyMCE', array(
        	'editorSelector'=>'addressEditor',
            'model'=>$model,
            'attribute'=>'address',
            'full'=>false,
            'htmlOptions'=>array('class'=>'big')
        )); 
    ?>
    <?php echo $form->error($model,'address'); ?>
</div>

<div class="row">
    <?php echo $form->label($model, 'counter'); ?>
    <?php echo $form->textArea($model, 'counter', array('class'=>'form-control')); ?>
    <?php echo $form->error($model,'counter'); ?>
</div>

<div class="row">
    <?php echo $form->label($model, 'hide_news');?>
    <?php echo $form->dropDownList($model, 'hide_news', array(0=>'Нет', 1=>'Да'), array('class'=>'form-control w10')); ?>
    <?php echo $form->error($model, 'hide_news');?>
</div>

<?php if (Yii::app()->params['watermark']): ?>
<div class="row">
    <?php echo $form->label($model, 'watermark'); ?>
    <?php echo $form->dropDownList($model, 'watermark', array(0=>'Нет', 1=>'Да'), array('class'=>'form-control w10')); ?>
    <?php echo $form->error($model,'watermark'); ?>
</div>
<?php endif; ?>

<?=$form->hiddenField($model, 'menu_limit')?>