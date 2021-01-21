<?use YiiHelper as Y;?>
<?php $this->beginContent('/layouts/main'); ?>
    <style type="text/css">
        .right-col {
            float: right;
            width: 960px;
        }
    </style>


    <div class="right-col">
      <div id="content" class="content">
      		<?if(Y::hasFlash(Y::FLASH_SYSTEM_SUCCESS)):?>
      			<div class="alert alert-success"><?=Y::getFlash(Y::FLASH_SYSTEM_SUCCESS)?></div>
      		<?endif?>
      		<?if(Y::hasFlash(Y::FLASH_SYSTEM_ERROR)):?>
      			<div class="alert alert-danger"><?=Y::getFlash(Y::FLASH_SYSTEM_ERROR)?></div>
      		<?endif?>
          	<?php echo $content; ?>
      </div>
    </div>

    <div class="clr"></div>
<?php $this->endContent(); ?>
