<script type="text/javascript">
$(function() {
	<?if(!YiiHelper::isAction($this,'shop','index')):?>
    $("#product-list").sortable({
        cursor: "move",
        stop: function(event, ui) {
            var order = $(this).sortable('toArray');
            console.log(order);
            $.ajax({
                    url: '/cp/default/shoporder',
                    type: 'post',
                    data: {products: order, cat_id: <?php echo isset($products[0]) ? $products[0]->category_id : 0; ?>},
                    success: function(data) {
                        console.log(data);
                    }
                });
        }
    });
    <?endif?>
    $("#site-menu").disableSelection();
});
</script>
<?if(YiiHelper::isAction($this,'shop','index')):?>
<h2>Последние добавленные товары</h2>
<?endif?>
<div id="product-list-module">
  <?php if (count($products)): ?>
  <ul id="product-list" class="product-list row">
    <?php foreach($products as $product): ?>
    <li id="item_<?php echo $product->id ?>" class="col-xs-3">
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


    