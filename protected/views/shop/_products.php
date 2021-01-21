<div class="product-custom">
    <div class="image">
        <?=CHtml::link(CHtml::image($data->mainImg, $data->alt_title?:$data->title, array('title'=>$data->alt_title?:$data->title)), Yii::app()->createUrl('shop/product', array('id'=>$data->id))); ?>
    </div>
    <div class="product-title">
        <a href="<?= Yii::app()->createUrl('shop/product', array('id' => $data->id)) ?>"><?= $data->title ?></a>
    </div>
    <div class="product-price">
        Цена: <?= HtmlHelper::priceFormat($data->price) ?> <span>i</span>
    </div>
    <div class="product-cart">
        <a class="change-count down" href="#">-</a>
        <a class="change-count up" href="#">+</a>
        <input id="product-page-count-<?= $data->id ?>" type="text" placeholder="" value="1" class="product-count__input"/>
        <?php
        $this->widget('\DCart\widgets\AddToCartButtonWidget', array(
            'id' => $data->id,
            'model' => $data,
            'title'=>'',
            'cssClass'=>'shop-button to-cart-custom open-cart',
            'attributes' => array(
                array('count', '#product-page-count-' . $data->id),
            )
        ));
        ?>
    </div>
</div>