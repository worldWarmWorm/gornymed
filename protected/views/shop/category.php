<?if(D::role('admin')) CmsHtml::editPricePlugin()?>
<h1><?=$category->getMetaH1()?></h1>

<?if($category->description && (D::cms('shop_pos_description') <> 1)):?>
<div id="category-description" class="category-description">
    <?=$category->description?>
</div>
<?endif?>

<div id="product-list-module">
	<?$this->renderPartial('_products_listview', compact('model'))?>
</div>

<?if($category->description && (D::cms('shop_pos_description') == 1)):?>
<div id="category-description" class="category-description">
    <?=$category->description?>
</div>
<?endif?>
