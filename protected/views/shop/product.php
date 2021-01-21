<?php CmsHtml::fancybox(); ?>
<h1><?=$product->getMetaH1()?></h1>

<div class="product-page">
  <div class="options">
    <div class="images">
		<?$fMark=function($attrs) use (&$product, &$fMark) { return ($attr=array_shift($attrs)) ? ($product->$attr ? " {$attr}" : $fMark($attrs)) : ''; };?>
        <div class="main-img<?=$fMark(array('sale','new','hit'))?>">
            <?if($product->getFullImg(true)):?>
            	<?=CHtml::link(
            		CHtml::image($product->bigMainImg, $product->alt_title?:$product->title, array('title'=>$product->alt_title?:$product->title)), 
            		$product->fullImg,
            		array('class'=>'image-full', 'rel'=>'group')
            	)?>
            <?else:?>
            	<?=CHtml::image($product->bigMainImg, $product->alt_title?:$product->title, array('title'=>$product->alt_title?:$product->title))?>
            <?endif?>
        </div>

        <table class="more-images">
        <tr>
            <?foreach($product->moreImages as $id=>$img):?>
            <td<?=D::c(($id > 2),' style="display: none;"')?>>
                <a class="image-full" rel="group" href="<?=$img->url?>" title="<?=$img->description?>"><?=CHtml::image($img->tmbUrl, $img->description)?></a>
            </td>
            <?endforeach?>
        </tr>
        </table>
    </div>
    
    <?if(!empty($product->code)):?>
    <div class="product-code">
        Артикул: <strong><?=$product->code?></strong>
    </div>
    <?endif?>

    <?if(!empty($product->description)):?>
    <div class="description">
        <?=$product->description?>
    </div>
    <?endif?>

  <?if(D::yd()->isActive('shop') && (int)D::cms('shop_enable_attributes') && count($product->productAttributes)):?>
      <div class="product-attributes">
        <ul>
          <?php foreach ($product->productAttributes as $productAttribute):?>
            <li><span><?=$productAttribute->eavAttribute->name;?></span><span><?=$productAttribute->value;?></span></li>
          <?php endforeach;?>
        </ul>
      </div>
    <?php endif;?>

    <div class="buy">
        <?if($product->price > 0 || D::role('admin')):?> 
    	    <div class="product-price">
                <?=HtmlHelper::priceFormat($product->price)?> <span>i</span>
            </div>
        <?endif?>
        <?if($product->notexist):?>
	        нет в наличии
        <?else:?>
            <div class="product-cart">
                <a class="change-count down" href="#">-</a>
                <a class="change-count up" href="#">+</a>
                <input id="product-page-count-<?= $product->id ?>" type="text" placeholder="" value="1" class="product-count__input"/>
                <?php
                $this->widget('\DCart\widgets\AddToCartButtonWidget', array(
                    'id' => $product->id,
                    'model' => $product,
                    'title'=>'',
                    'cssClass'=>'shop-button to-cart-custom open-cart',
                    'attributes' => array(
                        array('count', '#product-page-count-' . $product->id),
                    )
                ));
                ?>
            </div>
        <?endif?>
    </div>
  </div>
  <div class="clr"></div>

    <?php if($product->related): ?>
        <br>
        <div class="popular-products">
            <?php if(count($product->related) > 3): ?>
                <div class="popular-slides"></div>
            <?php endif; ?>
            <h2 class="h1">Рекомендуемые товары</h2>
            <div class="product-list-custom">
                <div class="popular-slider">
                    <?php foreach($product->related as $related): ?>
                        <?php $data = Product::model()->findByPk($related->related_id); ?>
                        <div class="slide">
                            <div class="product-custom">
                                <div class="image">
                                    <?=CHtml::link(CHtml::image($data->mainImg, $data->alt_title?:$data->title, array('title'=>$data->alt_title?:$data->title)), Yii::app()->createUrl('shop/product', array('id'=>$data->id))); ?>
                                </div>
                                <div class="product-title">
                                    <a href="<?= Yii::app()->createUrl('shop/product', array('id' => $data->id)) ?>"><?= $data->title ?></a>
                                </div>
                                <div class="product-price">
                                    Цена: 80 <span>i</span>
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
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<?if(D::cms('shop_enable_reviews')) $this->widget('widget.productReviews.ProductReviews', array('product_id' => $product->id))?>
