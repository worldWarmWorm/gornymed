<?php 
    $this->pageTitle = 'Настройки сайта - '. $this->appName; 
    $this->breadcrumbs=array(
        'Настройки'=>array('default/settings'),
        'Общие'=>'#swither',
    );
?>

<h1>Настройки</h1>

<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'settings-form',
        'enableClientValidation'=>!D::role('sadmin'),
        'enableAjaxValidation'=>D::role('sadmin'),
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        )
    )); ?>

    <?=D::role('sadmin') ? $form->errorSummary($model) : ''?>

    <?php 
    $tabs = array(
        'Общие'=>array('content'=>$this->renderPartial('settings_general', compact('model', 'form'), true), 'id'=>'tab-general'),
        'Блог' =>array('content'=>$this->renderPartial('settings_blog'   , compact('model', 'form'), true), 'id'=>'tab-blog'),
        'Seo'  =>array('content'=>$this->renderPartial('settings_seo'    , compact('model', 'form'), true), 'id'=>'tab-seo'),
    );
    if(D::yd()->isActive('slider') && D::role('sadmin')) {
    	$tabs['Слайдер'] = array('content'=>$this->renderPartial('settings_slider', compact('model', 'form'), true), 'id'=>'tab-slider');
    }
    if(D::yd()->isActive('treemenu') && D::role('sadmin')) {
    	$tabs['TreeMenu'] = array('content'=>$this->renderPartial('settings_treemenu', compact('model', 'form'), true), 'id'=>'tab-treemenu');
    }
    if(D::yd()->isActive('question') && D::role('sadmin')) {
    	$tabs['Вопрос-Ответ'] = array('content'=>$this->renderPartial('settings_question', compact('model', 'form'), true), 'id'=>'tab-question');
    }
    if(D::yd()->isActive('shop') && D::role('sadmin')) {
    	$tabs['Магазин'] = array('content'=>$this->renderPartial('settings_shop', compact('model', 'form'), true), 'id'=>'tab-shop');
    }
    if(D::role('sadmin')) {
		$tabs['Новости'] = array('content'=>$this->renderPartial('settings_events', compact('model', 'form'), true), 'id'=>'tab-events');
	}
    if(D::yd()->isActive('gallery') && D::role('sadmin')) {
		$tabs['Фотогалерея'] = array('content'=>$this->renderPartial('settings_gallery', compact('model', 'form'), true), 'id'=>'tab-gallery');
	}
    if(D::yd()->isActive('sale')) {
		$tabs['Акции'] = array('content'=>$this->renderPartial('settings_sale', compact('model', 'form'), true), 'id'=>'tab-sale');
	}

    $this->widget('zii.widgets.jui.CJuiTabs', array(
        'tabs'=>$tabs,
        'options'=>array(
            /*'collapsible'=>true,*/
        )
    )); 
    ?>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Сохранить', array('class'=>'btn btn-primary')); ?>
        <?php echo CHtml::link('отмена', array('default/index'), array('class'=>'btn btn-default')); ?>
    </div>

    <?php $this->endWidget();  ?>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click', '.ui-tabs-nav li', function(){
            $('a[href="#swither"]').text($(this).text());
            return false;
        });
        $('body').on('click', 'a[href="#swither"]', function(){
            return false;
        });
    });

</script>
