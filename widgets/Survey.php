<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\widgets;
use app\models\SurveyTemplates;
use app\models\SurveyTemplateQuestions;
/**
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Survey extends \yii\bootstrap\Widget
{
    public $surveyDetails = [];
    public $questionDetail = [];
    public $id;
    public $type;

	    public function init(){

	        parent::init();
        	
    }
    public function run(){
    	if($this->type == 'Survey') {
    		$this->surveyDetails = SurveyTemplates::findOne($this->id);
    	} else {//echo $this->id;
    		$this->questionDetail = SurveyTemplateQuestions::findOne($this->id);
    		$this->surveyDetails = SurveyTemplates::findOne($this->questionDetail->survey_template_id);
        }
    	//echo '<pre/>';print_r($this->questionDetail);exit;
        return $this->render('survey-details',['surveyDetails'=>$this->surveyDetails, 'questionDetail'=>$this->questionDetail]);

	    }
}
