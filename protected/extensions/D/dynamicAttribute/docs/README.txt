Использование

Версия 2.0
Для моделей с DActiveRecord

------------
I. Установка 
------------

1. Добавить поведение
public function behaviors()
{
    return [
        'dynamicAttributeBehavior'=>[
            'class'=>'\ext\D\dynamicAttribute\behaviors\DynamicAttributeBehavior',
            'attribute'=>'props_data'
            'attributeLabel'=>'Дополнительные параметры'
        ]
    ];
}

-----------------------------
II. Получение/установка значений в коде
-----------------------------

1. Получение значений
$model->dynamicAttributeBehavior->get()
$model->dynamicAttributeBehavior->getActive()

2. Установка значений
$model->dynamicAttributeBehavior->set($array)

------------
III. Видежты
------------

Пример:
$this->widget('\ext\D\dynamicAttribute\widgets\DynamicAttributeWidget', [
	'behavior' => $model->dynamicAttributeBehavior,
	'attribute' => 'props_data',
	'header'=>['title'=>'Название', 'value'=>'Значение'],
	'hideAddButton'=>true,
	'readOnly'=>['title'],
	'default' => [
		['title'=>'', 'value'=>'']
	]    
]);

--------------------------
IV. Часто используемый код
--------------------------

<div class="row">
	<?= $form->labelEx($model, 'props_data'); ?>
	<? $this->widget('\ext\D\dynamicAttribute\widgets\DynamicAttributeWidget', [
		'behavior' => $model->dynamicAttributeBehavior,
		'attribute' => 'props_data',
		'header'=>['title'=>'Название', 'value'=>'Значение'],
		'default' =>[
			['title'=>'', 'value'=>''],
		]
	]); ?>
</div>