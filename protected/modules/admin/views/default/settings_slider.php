<?if(D::role('sadmin')):?>
<?foreach(Slide::model()->getTypeNames() as $type=>$name):?>
<div class="row">
    <?php echo $form->checkBox($model, "slider_{$name}_active"); ?>
    <?php echo $form->labelEx($model, "slider_{$name}_active", array('class'=>'inline')); ?>
	<?php echo $form->error($model, "slider_{$name}_active"); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, "slider_{$name}_width"); ?>
    <?php echo $form->textField($model, "slider_{$name}_width", array('class'=>'w10 form-control')); ?>
    <?php echo $form->error($model, "slider_{$name}_width"); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, "slider_{$name}_height"); ?>
    <?php echo $form->textField($model, "slider_{$name}_height", array('class'=>'w10 form-control')); ?>
    <?php echo $form->error($model, "slider_{$name}_height"); ?>
</div>
<?endforeach?>
<?endif?>