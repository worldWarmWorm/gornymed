<?
/** @var \common\widgets\form\TextField $this */

use common\components\helpers\HHash;

echo $this->openTag();
echo $this->labelTag();


echo $this->form->textField($this->model, $this->attribute, $this->htmlOptions);
echo $this->form->hiddenField($this->model, $this->attributeCountryCode);
echo $this->form->hiddenField($this->model, $this->attributeCountry);
echo $this->form->hiddenField($this->model, $this->attributeMask);

echo $this->errorTag();

if($this->note) {
    echo \CHtml::tag($this->noteTag, $this->noteOptions, $this->note);
}

echo $this->closeTag();
