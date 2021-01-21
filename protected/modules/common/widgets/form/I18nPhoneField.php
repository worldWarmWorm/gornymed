<?php
/**
 * Виджет однострочного текстового поля формы для номера телефона 
 * с выбором кода. 
 * 
 */
namespace common\widgets\form;

use common\components\helpers\HYii as Y;
use common\components\helpers\HArray as A;
use common\components\widgets\form\BaseField;

class I18nPhoneField extends BaseField
{
    /**
     * Атрибут символьный кода страны для номера телефона
     * @var string
     */
    public $attributeCountry;
    
    /**
     * Атрибут кода страны для номера телефона
     * @var string
     */
    public $attributeCountryCode;

    /**
     * Код страны по умолчанию. 
     * По умолчанию "ru".
     * @var string
     */
    public $defaultCountryCode='ru';
    
    /**
     * Атрибут маски номера телефона
     * @var string
     */
    public $attributeMask;
    
    /**
     * Дополнительные опции для плагина intlTelInput
     * @var array
     */
    public $options=[];
	
    /**
	 * (non-PHPDoc)
	 * @see \common\components\widgets\form\BaseField::$htmlOptions
	 */
	public $htmlOptions=['class'=>'form-control', 'style'=>'width:100% !important'];
	
	/**
	 * (non-PHPDoc)
	 * @see \common\components\widgets\form\BaseField::$view
	 */
	public $view='i18n-phone-field';
    
    public function run()
    {
        $assetsUrl=$this->publish(
            'vendor/intl-tel-input/js/intlTelInput-jquery.min.js',
            'vendor/intl-tel-input/css/intlTelInput.min.css'
        );
        $jsClassName=$this->attribute . rand(0, 1000000);
        $this->htmlOptions['class']=trim($jsClassName . ' ' . A::get($this->htmlOptions, 'class', ''));
        $this->htmlOptions['placeholder']='';
        
        $this->options['utilsScript']=$assetsUrl . '/vendor/intl-tel-input/js/utils.js';
        $this->options['formatOnDisplay']=true;
        $this->options['separateDialCode']=true;
        
        $country=$this->model->{$this->attributeCountry} ?: $this->defaultCountryCode;
        $options=\CJavaScript::encode($this->options);
        $attributeMaskName=\CHtml::resolveName($this->model, $this->attributeMask);
        $attributeCountryName=\CHtml::resolveName($this->model, $this->attributeCountry);
        $attributeCountryCodeName=\CHtml::resolveName($this->model, $this->attributeCountryCode);
        $jsInitCode=<<<EOT
jQuery(function(){
    let input=jQuery('.{$jsClassName}');input.intlTelInput({$options});
    input.on('countrychange',function(e){
        let mask=input.attr('placeholder').replace(/[0-9]/g, '9');
        input.mask(mask);
        jQuery('[name="{$attributeCountryCodeName}"]').val(input.intlTelInput('getSelectedCountryData').dialCode);
        jQuery('[name="{$attributeCountryName}"]').val(input.intlTelInput('getSelectedCountryData').iso2);
        jQuery('[name="{$attributeMaskName}"]').val(mask);
    });
    input.intlTelInput('setCountry','{$country}');
    setTimeout(function(){input.trigger('countrychange');}, 700);
});
EOT;
        Y::jsCore('maskedinput');
        Y::js(false, $jsInitCode, \CClientScript::POS_READY);
        
        parent::run();
    }
}
