$(document).ready(function() {
	// Скрипт оберзки текста
	(function(selector) {
		var maxHeight=100, // максимальная высота свернутого блока
			togglerClass="read-more", // класс для ссылки Читать далее
			smallClass="small", // класс, который добавляется к блоку, когда текст свернут
			labelMore="Читать далее", 
			labelLess="Скрыть";
			
		$(selector).each(function() {
			var $this=$(this),
				$toggler=$($.parseHTML('<a href="#" class="'+togglerClass+'">'+labelMore+'</a>'));
			$this.after(["<div>",$toggler,"</div>"]);
			$toggler.on("click", $toggler, function(){
				$this.toggleClass(smallClass);
				$this.css('height', $this.hasClass(smallClass) ? maxHeight : $this.attr("data-height"));
				$toggler.text($this.hasClass(smallClass) ? labelMore : labelLess);
				return false;
			});
			$this.attr("data-height", $this.height());
			if($this.height() > maxHeight) {
				$this.addClass(smallClass);
				$this.css('height', maxHeight);
			}
			else {
				$toggler.hide();
			}
		});
	})(".is_read_more"); // это селектор элементов для которых навешивать обрезку текста.

    var fancyboxImages = $('a.image-full'); 
    if (fancyboxImages.length) {
        $(fancyboxImages).fancybox({
            overlayColor: '#333',
            overlayOpacity: 0.8,
            titlePosition : 'over',
            helpers: {
            overlay: {
                    locked: false
                }
            }
        });
    }

    $('body').on('click', '.yiiPager li', function(){
        $('html, body').animate({ scrollTop: $('.content').offset().top }, 500); // анимируем скроолинг к элементу scroll_el
    });
});
