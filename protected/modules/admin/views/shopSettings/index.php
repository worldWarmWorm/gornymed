<?php 
$this->pageTitle = 'Настройки магазина - '. $this->appName; 

$breadcrumbs = array();
$breadcrumbs[D::cms('shop_title', 'Каталог')] = array('shop/index');
$breadcrumbs[] = 'Настройка магазина';
$this->breadcrumbs = $breadcrumbs;
?>

<h1>Настройки магазина</h1>

<div class="form">
    <?$form = $this->beginWidget('CActiveForm', array(
        'id'=>'shop-settings-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
            'validateOnChange'=>false
        )
    ))?>

    <div class="row">
        <?=$form->labelEx($model, 'cropTop')?>
        <?=$form->dropDownList($model, 'cropTop', array('top'=>'Верх', 'center'=>'Центр', 0=>'Нет'))?>
        <?=$form->error($model, 'cropTop')?>
    </div>
    
    <h2>SEO</h2>
    
    <?foreach(array('meta_title','meta_key', 'meta_desc', 'meta_h1') as $attribute):?>
	    <div class="row">
	    	<?=$form->label($model, $attribute)?>
	    	<?=$form->textField($model, $attribute, array('class'=>'form-control'))?>
	    	<?=$form->error($model, $attribute)?>
		</div>
    <?endforeach?>

    <div class="row buttons">
        <?=CHtml::submitButton('Сохранить', array('class'=>'default-button'))?>
        <?=CHtml::link('отмена', array('shop/index'))?>
    </div>
    <?$this->endWidget()?>
</div>
