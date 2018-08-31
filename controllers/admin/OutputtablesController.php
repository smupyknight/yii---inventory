<?php

namespace app\controllers\admin;

use Yii;
use app\models\User;
use app\models\OutputTables;
use app\models\OutputTablesSearch;
use app\models\SurveyTemplateQuestionNodes;
use app\models\DataFieldTemplates;
use app\models\OutputTableColumnExclusions;
use app\models\FieldFilters;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use app\models\SurveyTemplateQuestions;
use app\models\SurveyQuarters;
use app\models\DataFields;
use app\components\AccessRule;
use yii\filters\AccessControl;

/**
 * OutputTablesController implements the CRUD actions for OutputTables model.
 */
class OutputtablesController extends Controller
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
                'only' => ['view', 'create', 'index', 'update', 'delete','deleteall'],
                'rules' => [
                   [
                        'actions' => ['view', 'index', 'update', 'delete', 'create','deleteall'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN],
                   ],
                 ],
            ],
        ];
    }

    /**
     * Lists all OutputTables models.
     * @return mixed
     */
    public function actionIndex($quart ,$questid)
    {
        $intQuestionId = $questid;
        $searchModel = new OutputTablesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $intQuestionId);
        $questionModel = SurveyTemplateQuestions::findOne($intQuestionId);

        ## Get quarter details
        $objQuarterDetails = SurveyQuarters::find()
                            ->where(['survey_template_id' => $questionModel->survey_template_id, 'quarter' =>$quart ])
                            ->asArray()
                            ->one();
//if($_SERVER["REMOTE_ADDR"]=="195.168.77.18") { print_r($dataProvider); die; }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'intQuestionId' => $intQuestionId,
            'objQuarterDetails' => $objQuarterDetails
        ]);
    }

    /**
     * Displays a single OutputTables model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        ## Fetch all the question nodes
        $arrNodes = SurveyTemplateQuestionNodes::find()
                    ->where(['survey_template_question_id' => $model->survey_template_question_id, 'included' => '1', 'capture_level' => '1'])
                    ->orderBy('id')
                    ->asArray()
                    ->all();

        ## Fetch column headings
        $arrColumnHeadings =ArrayHelper::toArray($model->outputColumnHeadings);
        $arroutputColumnHeadings = ArrayHelper::map($arrColumnHeadings, 'data_field_template_id', 'heading');

         ## Fetch column values with return values
        $arrColumnValues =ArrayHelper::toArray($model->fieldFilters);

        ## Fetch column exclusions
        $arrColumnExclusions =ArrayHelper::toArray($model->outputColumnExclusions);
        $arroutputColumnExclusions = ArrayHelper::map($arrColumnExclusions, 'data_field_template_id', 'excluded');
        //echo '<pre/>';print_r($arroutputColumnExclusions);exit;
        return $this->render('view', [
            'model' => $model,
            'arrColumnHeadings' => $arrColumnHeadings,
            'arrColumnValues' => $arrColumnValues,
            'arrNodes' => $arrNodes,
            'arroutputColumnExclusions' => $arroutputColumnExclusions
        ]);
    }

    /**
     * Creates a new OutputTables model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $intQuestionId = $id;
        $model = new OutputTables();
        $postedHideColumns = [];
        
        ## Fetch all the question nodes
        $arrNodes = SurveyTemplateQuestionNodes::find()
                    ->where(['survey_template_question_id' => $intQuestionId, 'included' => '1', 'capture_level' => '1'])
                    ->orderBy('id')
                    ->asArray()
                    ->all();

        ## Fetch all data columns
        $arrColumns = DataFieldTemplates::find()
                    ->where(['survey_template_question_id' => $intQuestionId])
                    ->orderBy('order')
                    ->asArray()
                    ->all();

        ## Get Orders
        $arrGrossColumn = [];
        $typeArr = [];
        if(!empty($arrColumns)) {
            foreach($arrColumns as $arr) {
               $arrGrossColumn[$arr['id']] = $arr['order'];
                $typeArr[$arr['field_type']][] = $arr['order'];
            }
        }//echo '<pre/>';print_r($arrGrossColumn);exit;

        ## Generate difference columns
        //echo '<pre/>';print_r($arrColumns);
        $arrDifference = [];
        if(!empty($typeArr)) {
            foreach($typeArr as $k=>$v) {
                if(count($v) >1) {
                    $arrDifference = array_merge($arrDifference ,$this->sampling($v , 2));
                }
            }
        }
        ## Sort array as per values
        asort($arrDifference);
        ## Set array values as keys
        array_flip(($arrDifference));

       ## Insert DAta 
         if ($model->load(Yii::$app->request->post())) {
           
            $postedData = Yii::$app->request->post();
            $postedFilters =isset($postedData['value']) ? $postedData['value'] : [];
            $postedReturnColumns = isset($postedData['return_column']) ? $postedData['return_column'] : []; 
            $postedHeadings =$postedData['OutputTables']['column_heading'];
            $postedHideColumns = (!empty($postedData['hide_column'])) ? $postedData['hide_column'] : [];
            $model->survey_template_question_id = $intQuestionId;
            $model->created_at = $model->updated_at = new Expression('NOW()');
            if($model->output_column == 'Difference') {
                $arrFirstLast = explode("-", $model->difference_presentation);//print_r($arrFirstLast);print_r($arrColumns);
                if(!empty($arrFirstLast)) {
                    foreach($arrColumns as $k=>$v) {
                        if($v['order'] == $arrFirstLast[0]) {
                            $model->first_field_id = $v['id'];
                        }
                        if($v['order'] == $arrFirstLast[1]) {
                            $model->last_field_id = $v['id'];
                        }
                    }
                 }
               
            } else {
                $model->difference_presentation = '';
            }
            $session = Yii::$app->session;
            if($model->save()) {
                //$model->link('fieldFilters', $filterModel);//link will insert 1 record at a time
                $connection = Yii::$app->db;
                
                ## Insert Field filters
                $arrFieldFilters = $arrColumnHeadings =[];
                //print_r($postedFilters);echo 'df';exit;
                for($i=0; $i< count($arrColumns);$i++) {
                    $arrFieldFilters [] = [
                        'output_table_id' => $model->id,
                        'data_field_template_id' => $arrColumns[$i]['id'],
                        'value' => $postedFilters[$i],
                        'return_column_id' =>  ($postedFilters[$i] == 'Gross Income Yields') ? $postedReturnColumns[$i] : ''
                    ];

                    $arrColumnHeadings [] = [
                        'output_table_id' => $model->id,
                        'data_field_template_id' => $arrColumns[$i]['id'],
                        'heading' => $postedHeadings[$i],
                    ];
                       
                    if(in_array($arrColumns[$i]['id'] , $postedHideColumns)) {
                        $arrHideColumns [] = [
                            'output_table_id' => $model->id,
                            'data_field_template_id' => $arrColumns[$i]['id'],
                            'excluded' => '1',
                        ];
                    }
                }
             if(!empty($arrFieldFilters)) {
             $boolStatus=$connection->createCommand()->batchInsert('field_filters',
                [
                    'output_table_id', 
                    'data_field_template_id',
                    'value',
                    'return_column_id'
                ], 
                $arrFieldFilters)
              ->execute();
            }
               ## Insert headings
              if(!empty($arrColumnHeadings)) { 
              $connection->createCommand()->batchInsert('output_table_column_headings',
                [
                    'output_table_id', 
                    'data_field_template_id',
                    'heading',
                ], 
                $arrColumnHeadings)
              ->execute();
            }

              ## Insert headings
              if(!empty($arrHideColumns)) { 
                $connection->createCommand()->batchInsert('output_table_column_exclusions',
                    [
                    'output_table_id', 
                    'data_field_template_id',
                    'excluded',
                    ], 
                    $arrHideColumns)
                ->execute();
                }
               $session->setFlash('success', OUTPUT_ADD_SUCC);
            } else {
                $session->setFlash('success', OUTPUT_ADD_ERR);
            }
            //return $this->redirect(['index' , 'id' => $intQuestionId]);  
            return $this->redirect(Yii::$app->request->referrer);
         }
        return $this->render('create', [
                'model' => $model,
                'arrNodes' => $arrNodes,
                'arrColumns' => $arrColumns,
                'arrGrossColumn' => array_combine($arrGrossColumn, $arrGrossColumn), // to set key as value
                'arrDifference' => $arrDifference,
                'intQuestionId' => $intQuestionId
            ]);
    }

    /**
     * Updates an existing OutputTables model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    ## Note: for now ,we are firing update query on relational table
    ## Once we are sure that primary key of these tables are not used anywhere ,
    ## we will perform batchinsert & delete older records.
    public function actionUpdate($id)
    { 
        $model = $this->findModel($id);
        $arrExclusions = $arrHeadings = $arrValues = $arrReturns =[];

        ## Fetch column exclusions
        $arrColumnExclusions =ArrayHelper::toArray($model->outputColumnExclusions);
        $arroutputColumnExclusions = ArrayHelper::map($arrColumnExclusions, 'data_field_template_id', 'excluded');
        //echo '<pre/>';print_r($arroutputColumnExclusions);exit;
        
        ## Fetch column headings
        $arrColumnHeadings =ArrayHelper::toArray($model->outputColumnHeadings);
        $arroutputColumnHeadings = ArrayHelper::map($arrColumnHeadings, 'data_field_template_id', 'heading');

         ## Fetch column values with return values
        $arrColumnValues =ArrayHelper::toArray($model->fieldFilters);
        $arrFieldFilters = ArrayHelper::map($arrColumnValues, 'data_field_template_id', 'value');
        $arrReturnColumns = ArrayHelper::map($arrColumnValues, 'data_field_template_id', 'return_column_id');
        //echo '<pre/>';print_r($arrReturnColumns);exit;
        ## Question ID
        $intQuestionId = $model->survey_template_question_id;
        ## Fetch all the question nodes
        $arrNodes = SurveyTemplateQuestionNodes::find()
                    ->where(['survey_template_question_id' => $intQuestionId, 'included' => '1', 'capture_level' => '1'])
                    ->orderBy('id')
                    ->asArray()
                    ->all();

        ## Fetch all data columns
        $arrColumns = DataFieldTemplates::find()
                    ->where(['survey_template_question_id' => $intQuestionId])
                    ->orderBy('order')
                    ->asArray()
                    ->all();
        //echo '<pre/>';print_r($arrColumns);
        //echo '<pre/>';print_r($arroutputColumnHeadings);exit;
        ## Fetch values to autopopulate in the form
        if(!empty($arrColumns)) {
            foreach($arrColumns as $col) {
                $arrExclusions[] = isset($arroutputColumnExclusions[$col['id']]) ? $arroutputColumnExclusions[$col['id']] : 0;
                $arrHeadings[] =  $arroutputColumnHeadings[$col['id']];
                $arrValues[] = $arrFieldFilters[$col['id']];
                $arrReturns[] = $arrReturnColumns[$col['id']];
            }
        }
        $model->hide_column = $arrExclusions;
        $model->column_heading = $arrHeadings;
        $model->value = $arrValues;
        $model->return_column = $arrReturns;
        
        ## Get Orders
        $arrGrossColumn = [];
        $typeArr = [];
        if(!empty($arrColumns)) {
            foreach($arrColumns as $arr) {
               $arrGrossColumn[$arr['id']] = $arr['order'];
                $typeArr[$arr['field_type']][] = $arr['order'];
            }
        }
//echo '<pre/>';print_r($arrGrossColumn);exit;
        ## Generate difference columns
        //echo '<pre/>';print_r($arrColumns);
        $arrDifference = [];
        if(!empty($typeArr)) {
            foreach($typeArr as $k=>$v) {
                if(count($v) >1) {
                    $arrDifference = array_merge($arrDifference ,$this->sampling($v , 2));
                }
            }
        }
        ## Sort array as per values
        //asort($arrDifference);
        ## Set array values as keys
        array_flip(($arrDifference));

        
        

        if ($model->load(Yii::$app->request->post())) {

            ## Get posted data
            $postedData = Yii::$app->request->post();
            $postedFilters = isset($postedData['value']) ? $postedData['value'] : [];
            $postedReturns = isset($postedData['return_column']) ? $postedData['return_column'] : [];
            $postedHeadings = $postedData['OutputTables']['column_heading'];
            $postedHideColumns = (!empty($postedData['hide_column'])) ? $postedData['hide_column'] : [];
//echo '<pre/>';print_r($postedHideColumns);exit;

            $model->updated_at = new Expression('NOW()');
            if($model->output_column == 'Difference') {
                $arrFirstLast = explode("-", $model->difference_presentation);//print_r($arrFirstLast);print_r($arrColumns);
                if(!empty($arrFirstLast)) {
                    foreach($arrColumns as $k=>$v) {
                        if($v['order'] == $arrFirstLast[0]) {
                            $model->first_field_id = $v['id'];
                        }
                        if($v['order'] == $arrFirstLast[1]) {
                            $model->last_field_id = $v['id'];
                        }
                    }
                 }
               
            } else {
                $model->difference_presentation = '';
            }
            $session = Yii::$app->session;
        
            if($model->save()) {
                $arrHideColumns = [];
                $connection = Yii::$app->db;
                if(!empty($postedHideColumns)) {
                $exclumodel = $connection->createCommand(
                    'DELETE FROM output_table_column_exclusions WHERE output_table_id='.$model->id.' AND data_field_template_id NOT IN ('.implode(',',$postedHideColumns).')');
                } else {
                    $exclumodel = $connection->createCommand(
                    'DELETE FROM output_table_column_exclusions WHERE output_table_id='.$model->id);
                }
                $exclumodel->execute();
                 
                //$connection->createCommand() ->delete('output_table_column_exclusions', ['output_table_id' =>$model->id , ['NOT IN','data_field_template_id' ,$postedHideColumns]])->execute();
                for($i=0;$i<count($arrColumns);$i++) {

                ## Update Field Filters
                $query =$connection ->createCommand() ->update('field_filters', 
                    [
                        'value' => $postedFilters[$i],
                        'return_column_id' =>  ($postedFilters[$i] == 'Gross Income Yields') ? $postedReturns[$i] : ''
                    ],
                    'output_table_id = :table_id and data_field_template_id= :template_id'
                    , [ 
                    //':val' =>$postedFilters[$i],
                    ':table_id' => $model->id, 
                    ':template_id' => $arrColumns[$i]['id']
                     ])->execute();

                ## Update Column Headings
                $query =$connection ->createCommand() ->update('output_table_column_headings', 
                    [
                        'heading' => $postedHeadings[$i],
                    ],
                    'output_table_id = :table_id and data_field_template_id= :template_id'
                    , [ 
                    //':val' =>$postedFilters[$i],
                    ':table_id' => $model->id, 
                    ':template_id' => $arrColumns[$i]['id']
                     ])->execute();

                ## Create a array to insert new hide columns
                if( in_array($arrColumns[$i]['id'], $postedHideColumns) &&  !ArrayHelper::keyExists($arrColumns[$i]['id'], $arroutputColumnExclusions)) {
                        $arrHideColumns [] = [
                            'output_table_id' => $model->id,
                            'data_field_template_id' => $arrColumns[$i]['id'],
                            'excluded' => '1',
                        ];
                    } 
        

    }
     ## Insert headings
    if(!empty($arrHideColumns)) { //echo '<pre/>';print_r($arrHideColumns);exit;
          $connection->createCommand()->batchInsert('output_table_column_exclusions',
            [
                'output_table_id', 
                'data_field_template_id',
                'excluded',
            ], 
            $arrHideColumns)
          ->execute();
      }
         $session->setFlash('success', OUTPUT_UPDATE_SUCC);
            //return $this->redirect(['update', 'id' => $model->id]);
         return $this->redirect(Yii::$app->request->referrer);
            }
        } return $this->render('update', [
                'model' => $model,
                'arrNodes' => $arrNodes,
                'arrColumns' => $arrColumns,
                'arrGrossColumn' => $arrGrossColumn ,//array_combine($arrGrossColumn, $arrGrossColumn), // to set key as value
                'arrDifference' => $arrDifference,
                'intQuestionId' => $intQuestionId
            ]);
    }

    /**
     * Deletes an existing OutputTables model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $intId = Yii::$app->request->post('id');
        $session = Yii::$app->session;
        if($this->findModel($intId)->delete()) {
            ## Delete related records
            $connection = Yii::$app->db;
            $connection->createCommand()->delete('output_table_column_exclusions',['output_table_id' => $intId])->execute();
            $connection->createCommand()->delete('output_table_column_headings',['output_table_id' => $intId])->execute();
            $connection->createCommand()->delete('field_filters',['output_table_id' => $intId])->execute();
            $session->setFlash('success', OUTPUT_DEL_SUCC);
        } else {
            $session->setFlash('error', OUTPUT_DEL_ERR);
            //print_r($model->getErrors());exit;
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeleteall()
    {
       $connection = Yii::$app->db;
       $session = Yii::$app->session;
       $postedSelection = Yii::$app->request->post('selection'); 
       if(!empty($postedSelection)) {
           $strSelection = implode(',', $postedSelection);
           $model =$connection->createCommand('DELETE FROM output_tables WHERE id IN ('.$strSelection.')');
           if($model->execute()) {
                $connection->createCommand('DELETE FROM output_table_column_exclusions WHERE output_table_id IN ('.$strSelection.')')->execute();
                $connection->createCommand('DELETE FROM output_table_column_headings WHERE output_table_id IN ('.$strSelection.')')->execute();
                $connection->createCommand('DELETE FROM field_filters WHERE output_table_id IN ('.$strSelection.')')->execute();
                $session->setFlash('success', OUTPUT_DEL_SUCC);
           } else {
                 $session->setFlash('error', OUTPUT_DEL_ERR);
           }
        }
      return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the OutputTables model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OutputTables the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OutputTables::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    ## For the combination of orders for difference in additional column
    public function sampling($chars, $size, $combinations = array()) {

    # if it's the first iteration, the first set 
    # of combinations is the same as the set of characters
    if (empty($combinations)) {
        $combinations = $chars;
    }

    # we're done if we're at size 1
    if ($size == 1) {
        return $combinations;
    }

    # initialise array to put new values in
    $new_combinations = array();

    # loop through existing combinations and character set to create strings
    foreach ($combinations as $combination) {
        foreach ($chars as $char) {
            if($combination != $char)
            $new_combinations[$combination .'-' .$char] = $combination .'-' .$char;
        }
    }

    # call same function again for the next iteration
    return $this->sampling($chars, $size - 1, $new_combinations);
    }

    
}
