<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SurveyTemplatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List of Output Tables';

?>


<section id="page-content">

                <!-- Start page header -->
                <div class="header-content">

                    <h2><i class="fa fa-question-circle"></i> <?php echo Html::encode($this->title);?>
                  
                    </h2>

                    <div class="breadcrumb-wrapper hidden-xs">
                        <span class="label">You are here:</span>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-home"></i>
                                <?php echo Html::a('Dashboard', ['site/index']);?>
                                <i class="fa fa-angle-right"></i>
                            </li>
                             <li>
                                <?php echo Html::a('Manage Surveys', Url::previous('survey'));?>
                                <i class="fa fa-angle-right"></i>
                            </li>
                             <li class="active"><?php echo Html::encode($this->title);?></li>
                        </ol>
                    </div><!-- /.breadcrumb-wrapper -->
                </div><!-- /.header-content -->
                <!--/ End page header -->

                <!-- Start body content -->
                <div class="body-content animated fadeIn">

                    <div class="row">
                        <div class="col-md-12">

                        

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
                            </div>
                            </div>
                           <!-- Start danger color table -->
                           
                          
                           <?php if(Yii::$app->session->hasFlash('error')) : ?>
            				<div class="alert alert-danger">
               				 <span><i class="icon fa fa-ban"></i> </span>
                             <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            	<span><?php echo Yii::$app->session->getFlash('error'); ?></span>
                            </div>
                            
                    	<?php elseif(Yii::$app->session->hasFlash('success')) : ?>
            				<div class="alert alert-success alert-dismissable">
               				 <span><i class="icon fa fa-check"></i> </span>
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <span> <?php echo Yii::$app->session->getFlash('success'); ?></span>
                            	
                        	</div>
                    	<?php endif; ?>
                        
                            <div class="table-responsive mb-20">
                           
                                 <table class="table table-success table-condensed table-bordered">
                                    <thead>
                                      <th>Property Type</th>
                                      <th>Question</th>
                                      <th>Heading</th>
                                    </thead>
                                    <tbody>
                                    <?php if(count($arrListOutput) >0) {
                                          foreach($arrListOutput as $out) {?>
                                        <tr>
                                           <td><?php echo $out['name'];?></td>
                                           <td><?php echo Html::a($out['sequence'],['questions', 'id' => $out['id'], 'quarter' => $quarter]);?></td>
                                           <td>
                                           <?php $strHeading =$out['heading'];
                                           if($out['sub_heading']!= "") {
                                            $strHeading .= '('.$out['sub_heading'].')';
                                           }
                                           echo Html::a($strHeading,['calculations', 'id' => $out['out_id'], 'quarter' => $quarter, 'qid' => $objQuarterDetails['id']]);
                                           ?>
                                           </td>
                                        </tr>
                                    <?php }
                                    } else {?>
                                    <tr><td colspan="3">No records found.</td></tr>
                                    <?php } ?>
                                    </tbody>
                                 </table>
                           
                           
</div><!-- /.table-responsive -->
<div class="clearfix"></div>
<?php  if(count($arrComments) >0) {?>
<div class="tab-content no-padding">
<strong>Comments</strong>
<div class="blog-list tab-pane active" id="comments">
       <?php foreach($arrComments as $com) {?>
      <div class="media">
          <div class="media-body">
                <h5 class="media-heading"><b>Posted on : </b><span class="text-danger"><?php echo $com['created_at'];?></span></h5>
                <small class="media-desc"><i class="fa fa-quote-left"></i> <?php echo $com['body'];?> </small>By
                <strong><?php echo Html::a($com['firstname'].' '.$com['lastname'], ['admin/contributors/update' , 'id' => $com['user_id']]);?></strong>
          </div><!-- /.media-body -->
      </div><!-- /.media -->
      <?php } ?>
</div>
     </div>
    <?php } ?>
      <!--/ End danger color table -->
                     </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->

                </div><!-- /.body-content -->
                <!--/ End body content -->
     