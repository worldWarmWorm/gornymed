<?php
/* @var $this ArticleController */
/* @var $model Article */

$this->breadcrumbs=array(
	'Статьи'=>array('index'),
	'Управление',
);
?>
<h1>Редактирование статей</h1>

<a href="/cp/article/create" type="button" class="btn btn-primary">Создать статью</a>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'article-grid',
	'dataProvider'=>$model->search(),
	'itemsCssClass'=>'table table-striped  table-bordered table-hover items_sorter',
	'filter'=>$model,
	'columns'=>array(
		'id',
		'title',
		'short',
		'link',
		'preview',
		'sort',
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
            'updateButtonImageUrl'=>false,
            'deleteButtonImageUrl'=>false,
            'buttons'=>array
            (
                'delete' => array
                (   
                    'label'=>'<span class="glyphicon glyphicon-remove"></span> ',
                    'options'=>array('title'=>'Удалить'),
                ),
                'update' => array
                (      
                    'label'=>'<span class="glyphicon glyphicon-pencil"></span> ',
                    'options'=>array('title'=>'Редактировать'),
                ),
            ),
        ),
	),
)); ?>
