<?php

$breadcrumbs = array();

$breadcrumbs[D::cms('shop_title', 'Каталог')] = array('shop/index');
$breadcrumbs = $this->getBreadcrumbs(Yii::app()->request->getQuery('parent_id',Yii::app()->request->getQuery('id', 1)), true);
if($model->isNewRecord){
  $breadcrumbs[] = 'Добавление категории';
}
else {
  $breadcrumbs[] = 'Редактирование категории';
}
$this->breadcrumbs = $breadcrumbs;
?>
<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'page-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
            'validateOnChange'=>false
        ),
        'htmlOptions' => array('enctype'=>'multipart/form-data'),
    )); ?>

    <?=$form->errorSummary($model)?>
    
    <?php $this->widget('zii.widgets.jui.CJuiTabs', array(
        'tabs'=>array(
            'Основное'=>array('content'=>$this->renderPartial('_form_category', compact('model', 'form'), true), 'id'=>'tab-general'),
            'Seo'=>array('content'=>$this->renderPartial('_form_category_seo', compact('model', 'form'), true), 'id'=>'tab-seo'),
        ),
        'options'=>array()
    )); ?>

    <div class="row buttons">
        <div class="left">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('class'=>'btn btn-primary')); ?>
            <?=CHtml::submitButton($model->isNewRecord ? 'Создать и выйти' : 'Сохранить и выйти', array('class'=>'btn btn-info', 'name'=>'saveout'))?>
            <?php echo CHtml::link('Отмена', array('index'), array('class'=>'btn btn-default')); ?>
        </div>

        <?php if (!$model->isNewRecord && !count($model->tovars)): ?>
        <div class="right with-default-button">
            <a href="<?php echo $this->createUrl('shop/categoryDelete', array('id'=>$model->id)); ?>"
               onclick="return confirm('Вы действительно хотите удалить категорию?')">Удалить категорию</a>
        </div>
        <?php endif; ?>
        <div class="clr"></div>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->
