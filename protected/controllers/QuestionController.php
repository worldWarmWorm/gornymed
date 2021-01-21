<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rick
 * Date: 05.03.12
 * Time: 11:49
 * To change this template use File | Settings | File Templates.
 */
class QuestionController extends Controller
{
	/**
	 * (non-PHPdoc)
	 * @see AdminController::filters()
	 */
	public function filters()
	{
		return CMap::mergeArray(parent::filters(), array(
			array('DModuleFilter', 'name'=>'question')
		));
	}
	
    public function actionIndex()
    {
        $model = new Question();

        if (isset($_POST['Question'])) {
            $model->attributes = $_POST['Question'];

            if ($model->save())
                echo 'ok';
            else
                echo 'error';

            Yii::app()->end();
        }

        $list = Question::model()->published()->findAll(array('order'=>'created DESC'));

        $this->prepareSeo('Вопрос-ответ');
        
        $this->breadcrumbs->add('Вопрос-ответ');
        
		$this->render('index', compact('list', 'model'));
    }
}
