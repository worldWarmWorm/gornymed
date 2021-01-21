<?php
/** @var DynamicAttributesWidget $this */  
use \AttributeHelper as A;
?>
<div class="daw-wrapper " data-item="<?php echo $this->attribute; ?>">
	<table class="daw-template" style="display: none !important;"><tbody><?php echo $this->generateRow(null); ?></tbody></table>
	<table class="daw-table table table-bordered" border=0 cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<?if(!$this->hideActive):?><th class="daw-thead-visible" title="Отображать на сайте">Акт.</th><?endif?>
				<?php foreach($this->header as $title) echo "<th>{$title}</th>"; ?>
				<?if(!$this->hideDeleteButton):?><th class="daw-thead-remove">Удалить</th><?endif?>
			</tr>
		</thead>
		<?php if(!$this->hideAddButton): ?>
		<tfoot>
			<tr><td colspan="<?=count($this->header)+2?>"><button class="btn btn-primary btn-sm daw-btn-add" data-attribute="<?php echo $this->attribute; ?>">Добавить</button></td></tr>
		</tfoot>
		<?php endif;?>
		<tbody>
			<?php $index=0; 
			foreach(($this->behavior->get() ?: $this->default) as $data)
				echo $this->generateRow($index++, $data);
			?>
		</tbody>
	</table>
</div>
