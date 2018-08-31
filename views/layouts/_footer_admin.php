<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use dosamigos\datepicker\DatePicker;?>
 
            
<footer class="footer-content">
    2012 - <span id="copyright-year"></span> &copy; Rode Survey Admin.
    
</footer><!-- /.footer-content -->

</section><!-- /#page-content  present in middle content-->

<!--  Modal popups placed at the end cause Placing it inside section causes class conflict.-->
<!-- ##------- Delete Confirmation Popup-----##-->
<div aria-hidden="true" role="dialog" tabindex="-1" class="modal fade modal-success model-delete" >
    <div class="modal-dialog modal-md">
                    <div class="modal-content">
                    <?php echo Html::beginForm(Url::to(['delete']), 'post',['id'=>'header-search-forms']) ?>
                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 class="modal-title">Confirm Delete</h4>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this item?</p>
                        </div>
                         <?php echo Html::input('hidden', 'id','',['id' => 'id']) ?>
                        <div class="modal-footer">
                           <?php echo Html::a('No','#',['href' => 'javascript:void(0)', 'class' => 'btn btn-default', 'data-dismiss'=>"modal"]);?>
                           <?php echo Html::submitButton('Yes', ["class" =>"btn btn-success"]) ?>
                        </div>
                       <?php echo Html::endForm();?>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
</div>
<!-- ##---------Popup for showing email template placeholders-------##-->
<div aria-hidden="true" role="dialog" tabindex="-1" class="modal fade modal-success model-email-info" >
    <div class="modal-dialog modal-md">
                    <div class="modal-content">
                    <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 class="modal-title">Email Template Place Holders</h4>
                        </div>
                        <div class="modal-body">
                            <p>Place ##place_holder## inside the template, when the mail is sent it will reflect the corresponding values</p>
                            <div class="panel">
                            <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Value</th>
                                <th class="text-center">Place Holder</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            	<td>Contributor name</td>
                                <td>##contributor_name## </td>
                            </tr>
                             <tr>
                            	<td>Survey name</td>
                                <td>##survey_name##</td>
                            </tr>
                            <tr>
                            	<td>Survey deadline </td>
                                <td>##survey_deadline##</td>
                            </tr>
                            <tr>
                            	<td>Survey link </td>
                                <td>##survey_link## </td>
                            </tr>
                        </tbody>
                        </table>
                            </div>
                        </div>
                          <div class="modal-footer">
                            <?php echo Html::a('Ok','#',['href' => 'javascript:void(0)', 'class' => 'btn btn-default', 'data-dismiss'=>"modal"]);?>
                        </div>
                      
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
 </div> 
 
 <!-- ##---------Popup for showing message if record is used somewhere-------##-->
<div aria-hidden="true" class="modal fade bs-example-modal-lg modal-cant-del" tabindex="-1" role="dialog" >
     <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Message</h4>
                        </div>
                        <div class="modal-body">
                            <p>You are not allowed to delete this record as it is already used by some other module. </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
</div>

<!-- ##------- SEt Deadline Popup-----##-->
<div aria-hidden="true" role="dialog" tabindex="-1" class="modal fade modal-success model-deadline" >
    <div class="modal-dialog modal-md">
                    <div class="modal-content">
                    <?php echo Html::beginForm(Url::to(['setdeadline']), 'post',['id'=>'deadline-set-form']) ?>
                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 class="modal-title">Survey Deadline</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                            <div class="col-md-2">Deadline <span class="deleted">*</span></div>
                            <div class="col-md-6">
                            <?= DatePicker::widget([
    'name' => 'deadline',
	'id' =>'deadline',
    'value' => date('Y-m-d'),
	'template' => '{addon}{input}',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-d',
			'startDate' => date('Y-m-d'),
		]
]);?></div>
                            </div>
                        </div>
                        <div class="row"><div class="col-md-6">
                        <div class="hideOption deleted text-center" id="err-deadline"></div></div>
                        </div>
                         <?php echo Html::input('hidden', 'quarterId','',['id' => 'quarterId']) ?>
                         
                        <div class="modal-footer">
                           <?php echo Html::a('Cancel','#',['href' => 'javascript:void(0)', 'class' => 'btn btn-default', 'data-dismiss'=>"modal"]);?>
                           <?php echo Html::submitButton('Save', ["class" =>"btn btn-success", "id" => "save-deadline"]) ?>
                        </div>
                       <?php echo Html::endForm();?>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
</div>

<!-- ## ------- confirmation of survey closing --------## --> 
<div aria-hidden="true" role="dialog" tabindex="-1" class="modal fade modal-success model-close-survey" >
    <div class="modal-dialog modal-md">
                    <div class="modal-content">
                    <?php echo Html::beginForm(Url::to(['close']), 'post',['id'=>'close-form']); ?>
                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 class="modal-title">Confirm Closing of Survey</h4>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to close this survey?</p>
                        </div>
                         <?php echo Html::input('hidden', 'quartId','',['id' => 'quartId']) ?>
                        <div class="modal-footer">
                           <?php echo Html::a('No','#',['href' => 'javascript:void(0)', 'class' => 'btn btn-default', 'data-dismiss'=>"modal"]);?>
                           <?php echo Html::submitButton('Yes', ["class" =>"btn btn-success"]) ?>
                        </div>
                       <?php echo Html::endForm();?>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
</div>

<!-- ##---------Popup for showing audit changes details-------##-->
<div aria-hidden="true" role="dialog" tabindex="-1" class="modal fade modal-success model-audit" >
<style type="text/css">
    .MsoNormal {
    //margin: 0px !important;
    width: auto !important;
    margin-right: 0px !important;
    }
</style>
    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 class="modal-title">Changes of <span id="mod_date"></span></h4>
                        </div>
                        <div class="modal-body">
                            <div class="panel table-responsive">
                            <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Attribute</th>
                                <th class="text-center">Before</th>
                                <th class="text-center">After</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            
                        </tbody>
                        </table>
                            </div>
                        </div>
                          <div class="modal-footer">
                            <?php echo Html::a('Ok','#',['href' => 'javascript:void(0)', 'class' => 'btn btn-default', 'data-dismiss'=>"modal"]);?>
                        </div>
                      
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
 </div> 
 
 <!-- ##---------Popup for showing message if record is used somewhere-------##-->
<?php
yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader', 'class' => 'modal-success',],
    'id' => 'modal',
    'size' => 'modal-md',
    
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
echo "<div id='modalContent'><div style='text-align:center'><img src='".\Yii::getAlias('@web')."/web/images/loader.gif'></div></div>";
yii\bootstrap\Modal::end();
?>

<!-- ##---------Popup alert for delete all-------##-->
<?php
yii\bootstrap\Modal::begin([
    'options' => ['id' => 'modaldeleteall', 'class' => 'modal-success'],
    'header' => '<h4 class="modal-title">Confirm Delete</h4>',
    'id' => 'modaldeleteall',
    'footer' => Html::a('No','#',['href' => 'javascript:void(0)', 'class' => 'btn btn-default', 'data-dismiss'=>"modal"]).
                           Html::submitButton('Yes', ["class" =>"btn btn-success del-yes"]) ,
    //'size' => 'modal-md',
    
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
echo '<p>Are you sure you want to delete selected record(s)?</p>';
yii\bootstrap\Modal::end();
?>

<!-- ##---------Popup alert for complete survey answer in contributor panel-------##-->
<?php
yii\bootstrap\Modal::begin([
    'options' => ['id' => 'modalcompletesurvey', 'class' => 'modal-success'],
    'header' => '<h4 class="modal-title">Confirm survey Completion</h4>
    <div id="surveyId" class="hideOption"></div><div id="contributorId" class="hideOption"></div>',
    'id' => 'modalcompletesurvey',
    'footer' => Html::a('No','#',['href' => 'javascript:void(0)', 'class' => 'btn btn-default', 'data-dismiss'=>"modal"]).
                           Html::a('Yes', ['markcomplete'],["class" =>"btn btn-success complete-yes"]) ,
    //'size' => 'modal-md',
    
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
echo '<p>Are you sure you want to mark this survey as Completed? <br/>
<strong>Once completed , you can not change your answer.</strong></p>';
yii\bootstrap\Modal::end();
?>

<!-- ## ------- popup for comment --------## -->
<?php
yii\bootstrap\Modal::begin([
    'options' => ['id' => 'modalcomment', 'class' => 'modal-success'],
    'header' => Html::beginForm(Url::to(['addcomment']), 'post',['id'=>'comment-form']).'<h4 class="modal-title">comment</h4>',
    'id' => 'modalcomment',
    'footer' => Html::input('hidden', 'survey_id','',['id' => 'survey_id']).
                Html::input('hidden', 'contributor_id','',['id' => 'contributor_id']).
                Html::a('No','#',['href' => 'javascript:void(0)', 'class' => 'btn btn-default', 'data-dismiss'=>"modal"]).
                Html::submitButton('Yes', ["class" =>"btn btn-success"]).
                Html::endForm() ,
    //'size' => 'modal-md',
    
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
echo '<p><strong>Thank you for completing Survey !</strong></p><br/>';
echo '<p>Your input is valuable to us & would like to hear from you.Please make comments to help us improve this survey.</p>';
echo Html::textarea('body','',['class' => 'form-control', 'rows' => '5']);
yii\bootstrap\Modal::end();
?>

<!-- ## ------- popup for exclude --------## -->
<?php
yii\bootstrap\Modal::begin([
    'options' => ['id' => 'modalexclude', 'class' => 'modal-success'],
    'header' => Html::beginForm(Url::to(['excludeanswer']), 'post',['id'=>'exclude-form']).'<h4 class="modal-title" id="include_confirm">Exclude</h4>',
    'id' => 'modalexclude',
    'footer' => Html::input('hidden', 'data_field_id','',['id' => 'data_field_id']).
                Html::input('hidden', 'included','',['id' => 'included']).
                Html::a('No','#',['href' => 'javascript:void(0)', 'class' => 'btn btn-default', 'data-dismiss'=>"modal"]).
                Html::submitButton('Yes', ["class" =>"btn btn-success include_answer"]).
                Html::endForm() ,
    //'size' => 'modal-md',
    
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
echo '<p id="msg"><strong>Please provide a reason to exclude this answer.</strong></p><br/>';
echo Html::textarea('exclude_reason','',['class' => 'form-control', 'rows' => '5' , 'id' => 'exclude_reason']);
echo '<div class="row"><div class="col-md-6">
     <div class="hideOption deleted text-center" id="err-reason"></div></div>
      </div>';
yii\bootstrap\Modal::end();
?>
