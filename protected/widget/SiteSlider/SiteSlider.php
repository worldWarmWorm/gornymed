<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rick
 * Date: 28.08.12
 * Time: 13:25
 * To change this template use File | Settings | File Templates.
 */
class SiteSlider extends CWidget
{
	/**
	 * @var integer $type
	 * @see \Slide class type consts.
	 */
	public $type;
    
    public function run()
    {
    	if(!$this->type)
    		throw new CException('Attribute type is empty');

    	$views = array(
    		Slide::TYPE_CAROUSEL=>'carousel',
    		Slide::TYPE_SLIDER=>'slide',
    		Slide::TYPE_BANNER=>'banner'
    	);
    	
    	if(!($this->type==Slide::TYPE_CAROUSEL || $this->type==Slide::TYPE_SLIDER))
        	Yii::app()->clientScript->registerScriptFile('/js/jquery.bgImageTween.cc.js');

        $slides = Slide::model()->findAll(array('order'=>'ordering', 'condition' => 'type = :type', 'params' => array(':type' => $this->type)));
        $this->render($views[$this->type], compact('slides'));
    }
}
