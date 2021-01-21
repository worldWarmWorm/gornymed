<? $this->widget('\ext\D\sort\widgets\Sortable', [
	'category'=>'product_carousel',
	'actionUrl'=>$this->createUrl('saveCarouselSort'),
	'selector'=>'#product-list'
]); ?>

<div id="product-list-module">
  <? if ($dataProvider->totalItemCount): ?>
  <ul id="product-list" class="product-list row">
    <? foreach($dataProvider->getData() as $product): ?>
    <li id="item_<?php echo $product->id ?>" class="col-xs-3" data-sort-id="<?= $product->id; ?>">
      <div class="product thumbnail">
        <div class="img">
          <a href="<?php echo $this->createUrl('shop/productUpdate', array('id'=>$product->id)); ?>"><img src="<?php echo $product->mainImg; ?>" alt="" /></a>
        </div>
        <div class="caption">
          <p class="title" title="<?php echo $product->title ?>"><?php echo Chtml::link($product->title, array('shop/productUpdate', 'id'=>$product->id)); ?></p>
          <div class="price_change btn btn-default btn-sm">
            <span class="price" title="Изменить цену"><?php echo $product->price; ?></span> руб.
            <div class="price_cotainer_change">
              <input type="text" class="price_val form-control">
              <div style="margin-top:7px;">
                <button data-id="<?php echo $product->id; ?>" class="price_status btn btn-primary btn-xs pull-right">Сохранить</button>
              </div>
            </div>
          </div>
          <a class="btn btn-danger btn-sm pull-right" href="<?=$this->createUrl('shop/productDelete', array('id'=>$product->id)); ?>" title="Удалить" onclick="return confirm('Вы действительно хотите удалить товар?')">Удалить</a>

        </div> 
      </div>  
    </li>
    <?php endforeach; ?>
  </ul>
  <?php else: ?>
    <p>Нет товаров</p>
  <?php endif; ?>
</div>
