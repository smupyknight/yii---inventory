<?php
/*
#############################################################################
# eLuminous Technologies - Copyright http://eluminoustechnologies.com
# This code is written by eLuminous Technologies, Its a sole property of
# eLuminous Technologies and cant be used / modified without license.
# Any changes/ alterations, illegal uses, unlawful distribution, copying is strictly
# prohibhited
#############################################################################
# Name : Dashboard.php
# Created on : 10th Aug 2015 by Bakhtawar Khan
# Update on : 10th Sep 2015 by Bakhtawar Khan
# Purpose : This page will perform CRUD on companies.
*/
namespace app\controllers\admin;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\User;
use app\models\SurveyTemplatesSearch;

/**
 * CompaniesController implements the CRUD actions for Companies model.
 */
class DashboardController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
			'access' => [
                'class' => AccessControl::className(),
				'ruleConfig' => [
        				'class' => AccessRule::className(),
    			],
                'only' => ['index'],
                'rules' => [
                   [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN],
                    ],
                 ],
            ],
        ];
    }

    /**
     * Lists all Companies models.
     * @return mixed
     */
    public function actionIndex()
    {
		## Find Current Quarter
		$quarter =floor((date('n') - 1) / 3) + 1;
		$currQuarter = date('Y').':'.$quarter;
		$searchModel = new SurveyTemplatesSearch();
		$dataProvider = $searchModel->getLatestSurvey($currQuarter);
		$searchModel->quarter = $currQuarter;
        return $this->render('dashboard', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider
		]);
    }

    
}
