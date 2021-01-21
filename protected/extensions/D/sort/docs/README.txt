/**
 * Документация использования расширения \ext\D\sort
 */
Общие положения:
а) Категория (category[string]) имя группы сортировки.
б) Ключ (key[integer]) может использован для разделения основной группы (категории) сортировки на подгруппы (подкатегории).
Например:
    Для сортировки товаров в категориях магазина, можно использовать одно имя общей категории "shop_category", 
    а в качестве ключа передавать ID самой категории, для которой сохраняется сортировка.


1) Подключить поведение в модель.
    
    public function behaviors()
    {
        return [
            ...
        	'sortBehavior'=>['class'=>'\ext\D\sort\behaviors\SortBehavior']
            ...
        ];
    }

2) Администрирование (сохранение сортировки)

2.1) Подключить действие к контроллеру
    
    public function actions()
	{
		return \CMap::mergeArray(parent::actions(), [
			'saveMySort'=>[
				'class'=>'\ext\D\sort\actions\SaveAction',
				'categories'=>['my_category']
			]	
		]);
	}
    
    Параметр "categories" используется для задания разрешенных категорий, которые будет обрабатывать данное действие.
    
2.2) На странице с элементами подключить виджет
    
    <? $this->widget('\ext\D\sort\widgets\Sortable', [
        'category'=>'my_category',
        'actionUrl'=>$this->createUrl('saveMySort'),
        'selector'=>'.my-sort-wrapper'
    ]); ?>
    
    В параметре "selector" задается jQuery выражение для выборки родительского элемента.
    
    DOM-элементы, которые будут учитываться при сортировки должны содержать параметр "data-sort-id", значение которого, должно быть ID модели.
    Например: <li data-sort-id="<?= $data->id; ?>">
    
    Имя данного параметра можно сменить, указав параметр виджета: 'optionId'=>'my-data-sort-id'
    
3) Получение отортированных моделей.

Для добавления условия сортировки используйте метод поведения scopeSort()

Пример:
    $dataProvider=MyModel::model()
		->scopeSort('my_category')
		->getDataProvider();