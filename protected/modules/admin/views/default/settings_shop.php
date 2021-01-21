<?if(D::role('sadmin')):?>
<div class="row">
    <?php echo $form->labelEx($model, 'shop_title'); ?>
    <?php echo $form->textField($model, 'shop_title', array('class'=>'form-control')); ?>
    <?php echo $form->error($model,'shop_title'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'shop_pos_description'); ?>
    <?php echo $form->error($model,'shop_pos_description'); ?>
    <?$model->shop_pos_description = $model->shop_pos_description<>1 ? 0 : 1;?>
    <?php echo $form->radioButtonList($model, 'shop_pos_description', array(0=>'перед списком товаров', 1=>'после списка товаров'), array(
    	'labelOptions'=>array('class'=>'inline'), 
    )); ?>
</div>

<div class="row">
    <?php echo $form->checkBox($model, 'shop_enable_carousel'); ?>
    <?php echo $form->labelEx($model, 'shop_enable_carousel', array('class'=>'inline')); ?>
	<?php echo $form->error($model, 'shop_enable_carousel'); ?>
</div>

<div class="row">
    <?php echo $form->checkBox($model, 'shop_enable_reviews'); ?>
    <?php echo $form->labelEx($model, 'shop_enable_reviews', array('class'=>'inline')); ?>
	<?php echo $form->error($model, 'shop_enable_reviews'); ?>
</div>

<div class="row">
    <?php echo $form->checkBox($model, 'shop_enable_attributes'); ?>
    <?php echo $form->labelEx($model, 'shop_enable_attributes', array('class'=>'inline')); ?>
	<?php echo $form->error($model, 'shop_enable_attributes'); ?>
</div>

<?endif?>