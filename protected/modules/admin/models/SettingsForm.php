<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alexok
 * Date: 01.06.11
 * Time: 15:27
 */
use AttributeHelper as A;

class SettingsForm extends CFormModel
{
    public $slogan;
    public $address;
    public $sitename;
    public $phone;
    public $phone2;
    public $email;
    public $emailPublic;
    public $firm_name;
    public $counter;
    public $hide_news;
    public $menu_limit;
    public $cropImages;
    public $comments;
    public $meta_title;
    public $meta_key;
    public $meta_desc;
    public $watermark;
    public $blog_show_created;
    // Slider
    public $slider_slider_active;
    public $slider_slider_width;
    public $slider_slider_height;
    public $slider_carousel_active;
    public $slider_carousel_width;
    public $slider_carousel_height;
    public $slider_banner_active;
    public $slider_banner_width;
    public $slider_banner_height;
    // Tree Menu
    public $treemenu_fixed_id;
    public $treemenu_show_id;
    public $treemenu_show_breadcrumbs;
    // Question (FAQ)
    public $question_collapsed;
    // Shop
    public $shop_title;
	public $shop_pos_description;
	public $shop_enable_attributes;
	public $shop_enable_reviews;
	public $shop_enable_carousel;
    // Gallery
    public $gallery_title;
    // Events
    public $events_title;
    public $events_link_all_text;
    // Sale
    public $sale_title;
    public $sale_link_all_text;
    public $sale_preview_width;
    public $sale_preview_height;
    public $sale_meta_h1;
    public $sale_meta_title;
    public $sale_meta_key;
    public $sale_meta_desc;
    
    /**
     * @var array $_defaults массив значений по умолчанию. array(attribute=>value)
     */ 
    private $_defaults=array(
    	'shop_title'=>'Kаталог',
    	'shop_enable_reviews'=>1,
    	'gallery_title'=>'Фотогалерея',
    	'events_title'=>'Новости',
    	'events_link_all_text'=>'Все новости',
    	'sale_title'=>'Акции',
    	'sale_link_all_text'=>'Все акции',
    	'sale_preview_width'=>320,
    	'sale_preview_height'=>240
    );
    /**
     * @var array $_last массив предыдущих значений. array(attribute=>value). 
     * Необходим для корректного сохранения.
     */
    private $_last=array();

    public $sitemap;

    public function rules()
    {
    	$rules =  array(
            array('sitename', 'length', 'max'=>40),
            array('email, emailPublic', 'email'),
            array('sitename', 'length', 'max'=>40),
            array('slogan, address, sitename, phone, phone2, email, emailPublic, firm_name, counter, hide_news, menu_limit', 'safe'),
            array('comments, meta_title, meta_key, meta_desc, cropImages, watermark, blog_show_created, sitemap', 'safe'),
            array('events_title, events_link_all_text', 'safe')
        );
    	if(D::yd()->isActive('gallery') && D::role('sadmin')) {
    		$rules = \CMap::mergeArray($rules, array(
            	array('gallery_title', 'safe')
    		));
    	}
        if(D::yd()->isActive('slider') && D::role('sadmin')) {
        	$rules = \CMap::mergeArray($rules, array(
            	array('slider_slider_width, slider_slider_height', 'validateRequiredBy', 'attribute'=>'slider_slider_active'),
            	array('slider_carousel_width, slider_carousel_height', 'validateRequiredBy', 'attribute'=>'slider_carousel_active'),
            	array('slider_banner_width, slider_banner_height', 'validateRequiredBy', 'attribute'=>'slider_banner_active'),
            	array('slider_slider_width, slider_slider_height', 'numerical', 'integerOnly'=>true),
            	array('slider_carousel_width, slider_carousel_height', 'numerical', 'integerOnly'=>true),
            	array('slider_banner_width, slider_banner_height', 'numerical', 'integerOnly'=>true),
            	array('slider_slider_active, slider_slider_width, slider_slider_height', 'safe'),
            	array('slider_carousel_active, slider_carousel_width, slider_carousel_height', 'safe'),
            	array('slider_banner_active, slider_banner_width, slider_banner_height', 'safe'),
        	));
        }
        if(D::yd()->isActive('treemenu') && D::role('sadmin')) {
            $rules = \CMap::mergeArray($rules, array(
            	array('treemenu_fixed_id', 'match', 'pattern'=>'/^[0-9,]+$/', 'message'=>'Разрешены только цифры и запятая'),
            	array('treemenu_fixed_id, treemenu_show_id, treemenu_show_breadcrumbs', 'safe')
        	));
        }
        if(D::yd()->isActive('question') && D::role('sadmin')) {
            $rules = \CMap::mergeArray($rules, array(
            	array('question_collapsed', 'safe')
        	));
        }
        if(D::yd()->isActive('shop') && D::role('sadmin')) {
            $rules = \CMap::mergeArray($rules, array(
            	array('shop_title', 'required'),
            	array('shop_title, shop_pos_description, shop_enable_attributes, shop_enable_reviews, shop_enable_carousel', 'safe')
        	));
        }
        if(D::yd()->isActive('sale') && D::role('sadmin')) {
            $rules = \CMap::mergeArray($rules, array(
            	array('sale_title, sale_link_all_text, sale_preview_width, sale_preview_height', 'safe'),
            	array('sale_meta_h1, sale_meta_title, sale_meta_key, sale_meta_desc', 'safe')
        	));
        }

        return $rules;
    }

    public function validateRequiredBy($attribute, $params)
    {
    	$attributeBy=$params['attribute'];
    	if($this->$attributeBy && !$this->$attribute) {
			$this->addError($attribute, 'Поле "'.$this->attributeLabels()[$attribute].'" является обязательным для заполнения');
			return false;
    	}
    	return true;
    }

    public function attributeLabels()
    {
        return array(
            'slogan'=>'Слоган сайта',
            'address'=>'Контактные данные',
            'sitename'=>'Название сайта',
            'phone'=>'Телефон',
            'phone2'=>'Дополнительный телефон',
            'email'=>'Email администратора',
            'emailPublic'=>'Email на сайте',
            'firm_name'=>'Название организации',
            'counter'=>'Счетчики',
            'hide_news'=>'Скрыть новости',
            'menu_limit'=>'Кол-во пунктов меню',
            'cropImages'=>'Обрезка изображений',
            'comments'=>'Код комментариев',
            'meta_title'=>'SEO заголовок',
            'meta_key'=>'Ключевые слова',
            'meta_desc'=>'Описание',
            'watermark'=>'Водяной знак',
            'blog_show_created'=>'Показывать дату создания',
            // slider
            'slider_slider_active'=>'Включить тип "Слайдер"', 
            'slider_slider_width'=>'Ширина', 
            'slider_slider_height'=>'Высота', 
            'slider_carousel_active'=>'Включить тип "Карусель"',
        	'slider_carousel_width'=>'Карусель: ширина',
        	'slider_carousel_height'=>'Карусель: высота',
            'slider_banner_active'=>'Включить тип "Баннер"',
        	'slider_banner_width'=>'Баннер: ширина',
        	'slider_banner_height'=>'Баннер: высота',
            // treemenu
            'treemenu_fixed_id'=>'Id фиксированных пунктов меню (через запятую)',
            'treemenu_show_id'=>'Показать id menu',
            'treemenu_show_breadcrumbs'=>'Показать "хлебные крошки"',
            // question
            'question_collapsed'=>'Свернуть ответы',
        	// shop
        	'shop_title'=>'Название магазина',
        	'shop_pos_description'=>'Позиция текста описания категории',
        	'shop_enable_attributes'=>'Активировать аттрибуты товара',
        	'shop_enable_reviews'=>'Отзывы на товар',
        	'shop_enable_carousel'=>'Активировать блок "Популярные товары"',
        	// gallery
        	'gallery_title'=>'Название фотогалереи',
        	// events
        	'events_title'=>'Название модуля новостей',
        	'events_link_all_text'=>'Текст ссылки "Все новости"',
            // sitemap 
            'sitemap'=>'Дополнение к карте сайта',
        	// sale
        	'sale_title'=>'Название акций',
        	'sale_link_all_text'=>'Текст ссылки "Все акции"',
        	'sale_preview_width'=>'Ширина изображения анонса',
        	'sale_preview_height'=>'Высота изображения анонса',
        	'sale_meta_title'=>'META: Заголовок',
        	'sale_meta_key'=>'META: Ключевые слова',
        	'sale_meta_desc'=>'META: Описание',
        	'sale_meta_h1'=>'H1',
        );
    }

    public function init()
    {
        if(D::yd()->isActive('slider') && $this->slider_slider_active===null) {
        	$this->slider_slider_active=1;
        }
    }

    public function saveSettings()
    {
    	// shop
    	if(D::yd()->isActive('shop') && D::role('admin')) {
    		if($this->shop_title != $this->_last['shop_title']) {
    			$menu = \Menu::model()->find('options=:options', array(':options'=>'{"model":"shop"}'));
    			if(!$menu) throw new Exception('Shop module install failed');
    			$menu->title = $this->shop_title;
    			$menu->save();
    		}
    	}
    	// gallery
    	if(D::yd()->isActive('gallery') && D::role('admin')) {
	    	if($this->gallery_title != $this->_last['gallery_title']) {
    			$menu = \Menu::model()->find('options=:options', array(':options'=>'{"model":"gallery"}'));
    			if($menu) {
		    		$menu->title = $this->gallery_title;
    				$menu->save();
    			}
    		}
    	}
    	// events
    	if($this->events_title != $this->_last['events_title']) {
    		$menu = \Menu::model()->find('options=:options', array(':options'=>'{"model":"event"}'));
    		if($menu) {
	    		$menu->title = $this->events_title;
    			$menu->save();
    		}
    	}
    	// sale
    	if(D::yd()->isActive('sale') && D::role('admin')) {
	    	if($this->sale_title != $this->_last['sale_title']) {
	    		$menu = \Menu::model()->find('options=:options', array(':options'=>'{"model":"sale"}'));
	    		if($menu) {
		    		$menu->title = $this->sale_title;
	    			$menu->save();
	    		}
	    	}
    	}
    	
        Yii::app()->settings->set('cms_settings', $this->attributes);
    }

    public function loadSettings()
    {
    	$fSetDefault=function($attribute, $attributeBy, $expr=true) {
    		if($expr && ($attribute == $attributeBy)) {
    			if(!$this->$attribute) $this->$attribute = $this->_defaults[$attributeBy];
    			$this->_last[$attributeBy] = $this->$attribute;
    		}
    	};
        foreach($this->attributeNames() as $attr) {
            $this->$attr = Yii::app()->settings->get('cms_settings', $attr);
            $fSetDefault($attr, 'shop_title', (D::yd()->isActive('shop') && D::role('admin')));
            $fSetDefault($attr, 'gallery_title', (D::yd()->isActive('gallery') && D::role('admin')));
            $fSetDefault($attr, 'events_title', (D::role('admin')));
            $fSetDefault($attr, 'events_link_all_text', (D::role('admin')));
            $fSetDefault($attr, 'sale_title', (D::yd()->isActive('sale') && D::role('admin')));
            $fSetDefault($attr, 'sale_link_all_text', (D::yd()->isActive('sale') && D::role('admin')));
            $fSetDefault($attr, 'sale_preview_width', (D::yd()->isActive('sale') && D::role('admin')));
            $fSetDefault($attr, 'sale_preview_height', (D::yd()->isActive('sale') && D::role('admin')));
        }

        if(D::yd()->isActive('slider') && $this->slider_slider_active===null) {
        	$this->slider_slider_active=1;
        }
    }
}
 
