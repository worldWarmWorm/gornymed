<?php
/** @var \DOrder\widget\admin\OrderButtonWidget $this */
/** @var \DOrder\models\Order $model */
?>

<li>
	<a href="<?=Yii::app()->createUrl('/cp/dOrder')?>"><i class="glyphicon glyphicon-shopping-cart"></i> Заказы<span class="notification_new_count dorder-order-button-widget-count"><?=(int)$model->uncompleted()->count()?></span></a>
</li>
