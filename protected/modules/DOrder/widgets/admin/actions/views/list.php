<?php
/** @var \DOrder\widget\admin\ListWidget $this */
/** @var \DOrder\models\Order $model */
/** @var \CPagination $pages */

$modelProduct=Product::model();
?>
<h1>Заказы</h1>

<table id="orders" class="dorder-admin-list">
    <tr class="head">
        <td>№</td>
        <td>ФИО, контакты</td>
        <td></td>
        <td>Сумма</td>
        <td>Дата</td>
        <td>Статус</td>
        <?/* ?><td>Оплачен</td> <?php */?>
        <td></td>
    </tr>

    <?foreach($model as $item):?>
        <tr class="order<?=D::c(($item->paid == 1), ' payment_complete')?> dorder-list-item" data-item="<?=$item->id?>">
            <td class="number"><?=$item->id?>.</td>
            <td class="info" colspan="2">
            	<?$customer = $item->getCustomerData()?>
                    <?=\CHtml::link($customer['name']['value'], 'javascript:void()', array('class' => 'orderuser', 'data-item' => $item->id))?>
                    <?foreach(array('email'=>'Email','phone'=>'Тел','address'=>'Адрес') as $key=>$title):
                    	if($customer[$key]['value']):
                    	?><span><em><?=$title?>:</em> <?=$customer[$key]['value']?></span><?
                    	endif;
                    endforeach?>
                    <?/*?><br/><i>Тип оплаты:</i> <?php echo $item->payment; ?><?*/?>
            </td>

            <td class="sumprice"><?=$item->getTotalPrice()?> р.</td>
            <td><?=\YiiHelper::formatDate($item->create_time)?></td>
            <td><div class="mark <?=(!$item->completed) ? 'marked' : 'unmarked'?>" data-item="<?=$item->id?>"></div></td>
            <?/*?><td><div class="mark_green <?php echo !$item->paid ? 'marked_green' : 'unmarked_green'; ?>" data-item="<?php echo $item->id; ?>"></div></td><?*/?>
            <td><?=\CHtml::link('Удалить', 'javascript:void()', array('class'=>'dorder-btn-delete', 'data-item'=>$item->id))?></td>
        </tr>
        <?foreach ($item->getOrderData() as $hash=>$attributes):?>
            <tr class="details dorder-list-item-details" data-item="<?=$item->id?>">
                <td colspan="2"><?
	            	$productId=$attributes['id']['value'];
	            	$modelProduct->id=$productId;
	            	$itemLink=Yii::app()->createUrl('shop/product', array('id'=>$productId));
					echo \CHtml::link(CHtml::image($modelProduct->getMainImg()?:'http://placehold.it/36'), $itemLink, array('target'=>'_blank', 'class'=>'image'))
					?>
                	<?=$attributes['title']['value']?><br />
                	<?foreach($attributes as $attribute=>$data): 
                		if($data['value'] && !in_array($attribute, array('id', 'model', 'categoryId', 'price', 'count', 'title'))):?>
	    				/ <small><b><?=$data['label']?>:</b> <?=$data['value']?></small>
	    				<?endif; 
	    			endforeach; ?>
                </td>
                <td class="count"><?=$attributes['count']['value']?></td>
                <td class="sum"><?=$attributes['price']['value']?> р.</td>
                <td colspan="3"><?=$attributes['count']['value'] * $attributes['price']['value']?> р.</td>
            </tr>
        <?endforeach?>
            <tr class="details dorder-list-item-comment" data-item="<?=$item->id?>">
                <td colspan="7"><textarea data-item="<?=$item->id?>" class="comment"><?=$item->comment ?: @$customer['comment']['value']?></textarea></td>
            </tr>
    <?endforeach?>
</table>
<br />
<?$this->widget('CLinkPager', array(
    'pages' => $pages,
));?>