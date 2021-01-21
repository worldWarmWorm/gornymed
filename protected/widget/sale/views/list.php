<?
/** @var \widget\sale\SaleList $this */
/** @var array[\Sale] $models */ 
?>
<?if($this->wrapperTagName) echo \CHtml::openTag($this->wrapperTagName, $this->wrapperOptions)?>

<?if($this->showTitle):?>
	<div class="module__head module-action__head"><a href="/sale"><?=\D::cms('sale_title', \Yii::t('sale','title'))?></a></div>
<?endif?>

<?=\CHtml::openTag($this->itemsTagName, $this->htmlOptions)?>
  <?foreach($models as $model):?>
	  <?=\CHtml::openTag($this->itemTagName)?>
	    <?=D::c($this->showSaleTitle, \CHtml::link($model->title, array('sale/view', 'id'=>$model->id), array('class'=>'action-head')))?>
	    	<?if(!empty($model->preview)):?>
	    		<div class="action_img">
	    			<a href="<?=\Yii::app()->createUrl('sale/view', array('id'=>$model->id))?>">
	    				<img src="<?=$model->imageBehavior->getSrc()?>" alt="<?=$model->title?>" title="<?=$model->title?>" class="img-responsive">
	    			</a>
	    		</div>
				<?endif?>
				<div class="action-intro">
    			<p><?=$model->preview_text?></p>
				</div>
	  <?=\CHtml::closeTag($this->itemTagName)?>
  <?endforeach?>
<?=\CHtml::closeTag($this->itemsTagName)?>

<?if($this->showLinkAll) 
	echo \CHtml::link(\D::cms('sale_link_all_text', \Yii::t('sale','link.all')), array('/sale'), $this->linkAllOptions)?>

<?if($this->wrapperTagName) echo \CHtml::closeTag($this->wrapperTagName)?>
