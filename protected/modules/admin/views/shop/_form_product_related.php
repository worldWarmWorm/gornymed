<div class="row chosen">
	<label>Выберите сопутствующие товары</label>
	<?php
		$this->widget('ext.chosen.Chosen',array(
		   'name' => 'related', // input name
		   'multiple' => true,
		   'placeholderMultiple' => 'Выберите сопутствующие товары',
		   'data' => CHtml::listData($productsList, 'id', 'title'),
		));
	?>
</div>
<?php if(!$model->isNewRecord && $model->related):?>
<div class="row related">
	<?php foreach($relatedProducts as $item):?>
		<?php if($item['id'] != $model->id):?>
			<div class="item">
				<?php echo $item['title'];?> <a href="#" class="remove-related" data-id="<?php echo $model->id;?>" data-related="<?php echo $item['id'];?>">Удалить</a>
			</div>
		<?php endif;?>
	<?php endforeach;?>
</div>

<script type="text/javascript">
	$(function(){
		$('.remove-related').click(function(){
			if(!confirm('Вы действительно хотите удалить сопутствующий товар?')) return false;

			var self = $(this);

			var d = {
				'product': self.data('id'),
				'related': self.data('related')
			};

			$.post('/admin/shop/removeRelated/', {data: d}, function(){
				self.closest('.item').remove();
			});

			return false;
		});
	});
</script>
<?php endif;?>