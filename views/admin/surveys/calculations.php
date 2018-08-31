<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
//use app\widgets\Survey;
use yii\helpers\Url;
//use dosamigos\tableexport\ButtonTableExport;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
//use dosamigos\tableexport\ButtonTableExportAsset;

//ButtonTableExportAsset::register($this);
$this->title = 'View Output Table';
?>
<?php
//$this->registerJsFile('@web/js/pages/tableexport.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile('@web/js/pages/jquery.base64.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile('@web/js/pages/FileSaver.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile('@web/js/pages/jquery.table2excel.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerCssFile('@web/css/tableexport.min.css');
$this->registerJsFile('@web/bower_components/dataTables/jquery.dataTables.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/bower_components/dataTables/dataTables.buttons.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/bower_components/dataTables/buttons.flash.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile('@web/bower_components/dataTables/jszip.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile('@web/bower_components/dataTables/pdfmake.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile('@web/bower_components/dataTables/vfs_fonts.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/bower_components/dataTables/buttons.html5.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/bower_components/dataTables/buttons.print.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCssFile('@web/bower_components/dataTables/buttons.dataTables.min.css');

?>




<script type="text/javascript">


  $(document).ready(function() {
    $('#export').DataTable( {
      responsive: true,
        //dom: 'T<"clear">lfrtip',
        dom: 'Bfrtip',
       "bSort" : false,
        "paging":   false,
        "bFilter": false,
        "info":     false,


    buttons: [
      'excel',
       {
            extend:'csv',
            charset : false,
        },
      /*{
            extend: 'pdf',
            orientation: "landscape",
            //download: 'open',
    //pageSize: "LETTER",
            exportOptions: {
                modifier: {
                    page: 'current'
                }

            },
          },*/
      'print',
    ]
   /* buttons: [
      {
         extend: 'collection',
         text: 'Export',
         buttons: [ 'pdfHtml5', 'csvHtml5', 'copyHtml5', 'excelHtml5' ]
      }
   ]*/
    } );
} );
</script>

<section id="page-content">
<div class="header-content">
        <h2><i class="fa fa-table"></i> Output Table</h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('admin/dashboard/index') ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                 <li>
                    <?php echo Html::a('Manage Surveys',Url::previous('survey'));?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <?php echo Html::a('List of Output Tables',['outputtables', 'id' => $surveyDetails->id , 'quarter' => $quarter ]);?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li class="active">View </li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
</div>





<div class="body-content animated fadeIn">
    <div class="row">
        <div class="col-md-12">
        <div class="btn-group">
        <p>
        <?php echo Html::a('Update Output Table', ['admin/outputtables/update/', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        </p>
         <!-- <a href="#" onClick ='$("table").tableExport({headings: true,                    // (Boolean), display table headings (th elements) in the first row
    formats: ["xls", "csv", "txt"],    // (String[]), filetypes for the export
    fileName: "id",                    // (id, String), filename for the downloaded file
    bootstrap: true,                   // (Boolean), style buttons using bootstrap
    position: "bottom"});'>JSON</a>-->



</div>

       <div class="panel panel-default panel-blog rounded shadow">
    <div class="panel-body">
        <div class="row">

            <div class="col-md-6">
            <label>Survey Category :</label>
            <?php if(isset($surveyDetails->surveyCategory)) {echo $surveyDetails->surveyCategory->name;} ?>
            </div>
            <div class="col-md-6">
            <label>Publication :</label>
            <?php if(isset($surveyDetails->publication)) {echo $surveyDetails->publication;} ?>
            </div>
         </div>
         <div class="row">
            <div class="col-md-6">
            <label>Survey Name :</label>
            <?php if(isset($surveyDetails->name)) {echo $surveyDetails->name;} ?>
            </div>
            <div class="col-md-6">
            <label>Who can contribute :</label>
            <?php if(isset($surveyDetails->contributor_type)) {echo $surveyDetails->contributor_type;} ?>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
            <label>Question :</label>
            <?php echo strip_tags($surveyDetails->survey_question); ?>
            </div>
         </div>

    </div>
</div>
                           <!-- Start danger color table -->
         <!-- Start input masks -->
            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title"> <strong> Output Table Detail<?php //if(isset($model->contributors->fullName)) { echo $model->contributors->fullName; } else { echo '-'; } ?></strong></h3>
                    </div>
                    <div class="clearfix"></div>
                </div>

            <div class="panel-body">
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>Heading :</strong></label><br/>
                                 <?php if(isset($model->heading)) { echo $model->heading; } else { echo '_'; } ?>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Sub-Heading :</strong></label><br/>
                                 <?php if(isset($model->sub_heading)) { echo $model->sub_heading; } else { echo '_'; } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>Parent Node Visibility :</strong></label><br/>
                                 <?php if(isset($model->parent_node_visibility)) { echo $model->parent_node_visibility; } else { echo '_'; } ?>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Output Column :</strong></label><br/>
                                 <?php if(isset($model->output_column)) { echo $model->output_column; } else { echo '_'; }
                                 if($model->output_column == 'Difference') { echo '<strong>('.$model->difference_presentation.')</strong>';} ?>
                            </div>
                        </div>
                        <?php if($model->output_column == 'Difference') { ?>
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>First Field :</strong></label><br/>
                                 <?php echo $model->getFieldId($model->id , $model->first_field_id);  ?>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Last Field :</strong></label><br/>
                                 <?php echo $model->getFieldId($model->id , $model->last_field_id);  ?>
                            </div>
                        </div>
                        <?php }?>
                         <div class="row">
                            <div class="col-md-6">
                                <label><strong>Standard Deviation Limit :</strong></label><br/>
                                 <?php if(isset($model->sd)) { echo $model->sd; } else { echo '_'; } ?>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Show Contributor Code? :</strong></label><br/>
                                 <?php if($model->contributor_code == '1') { echo 'Yes'; } else { echo 'No'; } ?>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6">
                                <label><strong>Show a , b , r Values? :</strong></label><br/>
                                 <?php if($model->a_b_r == '1') { echo 'Yes'; } else { echo 'No'; } ?>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Show Parent Average? :</strong></label><br/>
                                 <?php if($model->show_parent_average == '1') { echo 'Yes'; } else { echo 'No'; } ?>
                            </div>
                         </div>

                    </div>
                    <div style="clear:both"></div>

                </div>
            </div>


            <div class="panel-body">
            <!--<div class="hideOption" id="note">NOTE : Safari users must have adobe flash player installed to export.</div>-->
                    <div class="row">
                        <div class="col-md-12">
                          <div class="table-responsive ">
            <table class="table table-success table-condensed table-bordered" id="export">
            <thead>
                <tr>
                <th style="vertical-align:top;"><?php echo $model->node_heading;?></th>
                <?php  for($i=0;$i<count($arrColumnHeadings);$i++) {
                        ## Do not show columns that are excluded
                       if(!ArrayHelper::KeyExists($arrColumnHeadings[$i]['data_field_template_id'], $arroutputColumnExclusions)) {
                         $arrValues = explode(',', $arrColumnValues[$i]['value']);
                    ?>
                    <?php for($l=0;$l<count($arrValues);$l++) {$class="";
                      if($l == 0) { $class="spanner";}?>
                    <th >
                    <?php if($l == 0) {echo $arrColumnHeadings[$i]['heading']; }?>
                    </th><?php } ?>
                    <?php }
                    }?>
                <?php  if($model->contributor_code == '1') {?>
                     <th> Contributors </th>
                <?php } if($model->a_b_r == '1') {?>
                <th> Show a,b,r values </th>
                <th></th><th></th>
                <?php }?>
                  </tr>


             </thead>
             <tbody>
              <tr><td></td>
                    <?php  for($i=0;$i<count($arrColumnHeadings);$i++) {
                        ## Do not show columns that are excluded
                        if(!ArrayHelper::KeyExists($arrColumnHeadings[$i]['data_field_template_id'], $arroutputColumnExclusions)) {
                            $arrValues = explode(',', $arrColumnValues[$i]['value']);
                        ?>

                         <?php foreach($arrValues as $arr) {?>
                            <th>
                                <strong><?php echo $arr;?></strong>

                            </th>
                        <?php }?>

                     <?php }
                     }?>
                     <?php  if($model->contributor_code == '1') {?>
                     <td></td>
                <?php }if($model->a_b_r == '1') {?>
                <td> a </td>
                <td> b </td>
                <td> r </td>
                <?php }?>
                  </tr>
                  <?php foreach($arrNodes as $node) {
                    $i=0;
                    $parentFlag = $hideParentFlag = $isAverage= 0;
                    $parentArr= [];
                    $parentClass = '';
                      if($node['survey_template_question_node_id'] == '' &&  ($node['capture_level'] != "1" || $node['included'] != "1") ) {
                            if($node['childCount'] > 0) {
                                if($model->parent_node_visibility == 'text') {
                                    $parentFlag = 1;
                                    $parentClass = 'parent-class';
                                }
                                if($model->parent_node_visibility == 'hidden') {
                                    $hideParentFlag = 1;
                                }
                            } else {
                                $hideParentFlag = 1;
                            }
                        }
                        if(isset($node['calculation']) && $node['calculation'] == 'average') {
                            $parentClass = 'parent-class';
                            $isAverage = 1;
                            $hideParentFlag = 0;
                            }?>
                        <?php  if($hideParentFlag == 0) {?>
                         <tr>
                               <td class="<?php echo $parentClass;?>"><?php echo $node['name']; ?></td>
                                <?php foreach($arrColumnHeadings as $col) {
                                    $dataId = $col['data_field_template_id'];
                                    $parentArr[$dataId] = [];
                                    ## Do not show columns that are excluded
                        if(!ArrayHelper::KeyExists($col['data_field_template_id'], $arroutputColumnExclusions)) {
                            //echo $arrColumnValues[$i]['value'];exit;
                            $arrValues = explode(',', $arrColumnValues[$i]['value']);
                            foreach($arrValues as $filter) {?>
                                    <td>
                                        <?php  if($filter == 'Mean')
                                                {
                                                    if(($parentFlag == 1  || $isAverage == 1) && isset($arrParentMean[$node['id']][$dataId]['mean']) && $arrParentMean[$node['id']][$dataId]['mean'] !== "0") {
                                                        echo number_format($arrParentMean[$node['id']][$dataId]['mean'], 2);
                                                     }
                                                     else if($parentFlag != 1 && isset($MeanArr[$node['id']][$dataId]) ) {
                                                        if(isset($Narr[$node['id']][$dataId]) && $Narr[$node['id']][$dataId] > 0) {
                                                        echo Html::a(number_format($MeanArr[$node['id']][$dataId], 2), ['contribution', 'quarter_id' =>$quarter_id , 'filter_id' => $arrColumnValues[$i]['id'], 'node_id' =>$node['id'] ]);
                                                        } else {echo 'N/A';}

                                                    }  else {
                                                        echo 'N/A';
                                                        //echo "(".$parentFlag."|".$isAverage."|".$node['id']."|".$dataId.")"; var_dump($MeanArr);
                                                        //echo "(".$parentFlag."|".$isAverage."|".$arrParentMean[$node['id']][$dataId]['mean']."|".$arrParentMean[$node['id']][$dataId]['mean'].")";
                                                    }
                                                }  else if($filter == 'SD')
                                                {
                                                    if(($parentFlag == 1 || $isAverage == 1) && isset($arrParentMean[$node['id']][$dataId]['sd']) && $arrParentMean[$node['id']][$dataId]['sd'] != "0") {
                                                        if(isset($node['calculation']) && $node['calculation'] == 'average')
                                                            { echo 'N/A';}
                                                        else {
                                                                $deviationClass = "";
                                                               if($model->sd !="" && $model->sd < $arrParentMean[$node['id']][$dataId]['sd']) {
                                                                  $deviationClass = "deviation-higher";
                                                               }
                                                           echo '<span class="'.$deviationClass.'">'.number_format($arrParentMean[$node['id']][$dataId]['sd'], 2).'</span>';

                                                        }
                                                     }
                                                     else if($parentFlag != 1 && isset($SDArr[$node['id']][$dataId]) ) {
                                                         if(isset($Narr[$node['id']][$dataId]) && $Narr[$node['id']][$dataId] > 1) {//greater than 1 cause we cant caclulate deviation o n1 value
                                                            $deviationClass = "";
                                                               if($model->sd !="" && $model->sd < $SDArr[$node['id']][$dataId]) {
                                                                  $deviationClass = "deviation-higher";
                                                               }
                                                            echo Html::a('<span class="'.$deviationClass.'">'.number_format($SDArr[$node['id']][$dataId], 2).'</span>', ['contribution', 'quarter_id' =>$quarter_id , 'filter_id' => $arrColumnValues[$i]['id'], 'node_id' =>$node['id'] ]);
                                                          } else {echo 'N/A';}
                                                    }  else {
                                                        echo 'N/A';
                                                    }
                                                }
                                                else if($filter == 'n')
                                                {
                                                    if(($parentFlag == 1 || $isAverage == 1) && isset($arrParentMean[$node['id']][$dataId]['n']) && $arrParentMean[$node['id']][$dataId]['n'] != "0") {
                                                        echo $arrParentMean[$node['id']][$dataId]['n'];
                                                     }
                                                     else if($parentFlag != 1 && isset($Narr[$node['id']][$dataId]) && $Narr[$node['id']][$dataId] != "0") {
                                                        echo Html::a($Narr[$node['id']][$dataId] , ['contribution', 'quarter_id' =>$quarter_id , 'filter_id' => $arrColumnValues[$i]['id'], 'node_id' =>$node['id'] ]);
                                                    }  else {
                                                        echo 'N/A';
                                                    }
                                                }
                                                 else if($filter == 'Gross Income Yields') {
                                                    if(($parentFlag == 1 ) && isset($arrParentMean[$node['id']][$dataId]['gross']) && $arrParentMean[$node['id']][$dataId]['gross'] != "0") {
                                                        echo number_format($arrParentMean[$node['id']][$dataId]['gross'], 2).' %';
                                                     }
                                                     else if($parentFlag != 1 && isset($grossArr[$node['id']][$dataId]) ) {
                                                        if(isset($Narr[$node['id']][$dataId]) && $Narr[$node['id']][$dataId] > 0) {
                                                        echo Html::a(number_format($grossArr[$node['id']][$dataId], 2).' %', ['contribution', 'quarter_id' =>$quarter_id , 'filter_id' => $arrColumnValues[$i]['id'], 'node_id' =>$node['id'] ]);
                                                        } else {echo 'N/A';}

                                                    }  else {
                                                        echo 'N/A';
                                                    }
                                                 }
                                                else if($filter == 'Current Q - Last Q')
                                                {
                                                    if($isAverage == 1) {
                                                         echo 'N/A';
                                                    }
                                                    else if(($parentFlag == 1 ) && isset($arrParentMean[$node['id']][$dataId]['current']) ) {
                                                        echo $arrParentMean[$node['id']][$dataId]['current'];
                                                     }
                                                     else if($parentFlag != 1 && isset($oldQuarter[$node['id']][$dataId]) && isset($MeanArr[$node['id']][$dataId])) {
                                                        echo Html::a(number_format(($MeanArr[$node['id']][$dataId] -$oldQuarter[$node['id']][$dataId]),2).'%', ['contribution', 'quarter_id' =>$quarter_id , 'filter_id' => $arrColumnValues[$i]['id'], 'node_id' =>$node['id'] ]);
                                                    }  else {
                                                        echo 'N/A';
                                                    }
                                                }
                                                else if($filter == 'Highest Value') {
                                                    if(($parentFlag == 1 ) && isset($arrParentMean[$node['id']][$dataId]['highest']) && $arrParentMean[$node['id']][$dataId]['highest'] != "0") {
                                                        echo number_format($arrParentMean[$node['id']][$dataId]['highest'], 2);
                                                     }
                                                     else if($parentFlag != 1 && isset($higestArr[$node['id']][$dataId]) ) {
                                                        echo Html::a(number_format($higestArr[$node['id']][$dataId], 2), ['contribution', 'quarter_id' =>$quarter_id , 'filter_id' => $arrColumnValues[$i]['id'], 'node_id' =>$node['id'] ]);

                                                    }  else {
                                                        echo 'N/A';
                                                    }
                                                 }

                                        ?>
                                    </td>
                                <?php }
                            }
                               $i++;//declared in nodes loop & incremented in column loop
                                }?>
                             <?php  if($model->contributor_code == '1') {?>
                                <td> <?php if(isset($contributorsCode[$node['id']]) && $parentFlag != 1) {
                                        if(isset($node['calculation']) &&  $node['calculation']== 'average')
                                            {echo '';} else {
                                              $contributors = $contributorsCode[$node['id']];
                                              $contributors = array_unique($contributors);
                                              asort($contributors);
                                        echo implode(',', $contributors);
                                    }
                                    }?></td>
                            <?php } if($model->a_b_r == '1') {
                              if($parentFlag == 1 || $isAverage == 1) {?>
                                <td>a</td>
                                <td>b</td>
                                <td>r</td>
                            <?php } else {?>

                            <td>
                            <?php echo (isset($arrABRvalues[$node['id']]['a']) && $arrABRvalues[$node['id']]['a']!="")  ? number_format($arrABRvalues[$node['id']]['a'], 4) : 'N/A'; ?>
                            </td>
                            <td>
                            <?php echo (isset($arrABRvalues[$node['id']]['b']) && $arrABRvalues[$node['id']]['b']!="")  ? number_format($arrABRvalues[$node['id']]['b'], 4) : 'N/A'; ?>
                            </td>
                            <td>
                            <?php echo (isset($arrABRvalues[$node['id']]['r']) && $arrABRvalues[$node['id']]['r']!="")  ? number_format($arrABRvalues[$node['id']]['r'], 4) : 'N/A'; ?>
                            </td>
                            <?php }
                            }?>
                         </tr>
                         <?php } ?>
                  <?php } ?>


             </tbody>
            </table>
        </div>

                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
</div>

 <script type="text/javascript">
 var is_safari = navigator.userAgent.indexOf("Safari") > -1;
 if(is_safari == true) {
  $('#note').removeClass('hideOption');
 }
</script>
