/**
 * Javascript for \ext\D\sort\widgets\Sortable widget.
 */

var Ext_D_sort_widgets_Sortable=(function() {
	
	/**
	 * Инициализация 
	 * @param object options параметры инициализации.
	 * Параметры:
	 * category (string) имя категории сортировки.
	 * key (int|null) ключ категории сортировки.
	 * selector (string) выражение выборки (jQuery) родительского элемента.
	 * actionUrl (string) ссылка на действие сохранения
	 * optionId (string) имя атрибута сортировки, в котором будут хранится id модели.
	 */
	this.init=function(options) {
		$(options.selector).sortable({
			cursor: "move",
		    stop: function(event, ui) {
		    	var data = $(this).sortable("toArray", {attribute: options.optionId});
		        $.post(options.actionUrl, {category: options.category, key: options.key, data: data});
		    }
		});
	};
	
	return this;
})();