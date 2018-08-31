<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\Surveytemplatequestions */

$this->title = 'Survey Question';

//$this->registerCssFile(Yii::$app->request->baseUrl.'/css/tabs.css');
$surveyId	=	 Yii::$app->getRequest()->getQueryParam('surveyid');
//$this->registerJsFile(Yii::$app->request->baseUrl.'/js/jquery-1.3.2.min.js',['position' => \yii\web\View::POS_HEAD]);
//$this->registerJsFile(Yii::$app->request->baseUrl.'/js/jquery-ui-1.7.custom.min.js',['position' => \yii\web\View::POS_HEAD]);
?>





<section id="page-content">
                <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-table"></i> Question</h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('contributor/surveys/dashboard') ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <?php echo Html::a('Manage Surveys', Url::previous('cont_survey'));?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                   <?php echo Html::a('Manage Questions', ['contributor/questions/index','id'=>$intSurveyQuarterId]);?>
                	<i class="fa fa-angle-right"></i>
                </li>
                 <li class="active">Question</li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
    </div><!-- /.header-content -->
	<!--/ End page header -->

    <div class="body-content animated fadeIn">
        <div class="col-sm-12">

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
        
         <div class="panel panel-default panel-blog rounded shadow">
                            <div class="panel-body">
                                <div class="row">
                                 <div class="col-md-6">
                                    <label>Survey Name :</label>
                                    <?php if(isset($objGetTemplateDetails->surveytemplates->name)) {echo $objGetTemplateDetails->surveytemplates->name;} ?>
                                    </div>
                                    <div class="col-md-6">
                                    <label>Who can contribute :</label>
                                    <?php if(isset($objGetTemplateDetails->surveytemplates->contributor_type)) {echo $objGetTemplateDetails->surveytemplates->contributor_type;} ?>
                                    </div>
                                 </div>
                                 <div class="row">
                                     <div class="col-md-6">
                                    <label>Quarter :</label>
                                    <?php if(isset($objGetTemplateDetails)) {echo$objGetTemplateDetails->quarter;} ?>
                                    </div>
                                    <div class="col-md-6">
                                    <label>Publication :</label>
                                    <?php if(isset($objGetTemplateDetails->surveytemplates->publication)) {echo $objGetTemplateDetails->surveytemplates->publication;} ?>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-6">
                                    <label>Deadline:</label>
                                    <?php if(isset($objGetTemplateDetails->deadline)) {echo $objGetTemplateDetails->deadline;} ?>
                                    </div>
                                    <div class="col-md-6">
                                    <label>Status :</label>
                                    <?php if(isset($objGetTemplateDetails->closed) && $objGetTemplateDetails->closed == '1') {echo 'Closed';} else {echo 'Open';} ?>
                                    </div>
                                 </div>
                            </div>
             </div>

<?php //print_r($arrPreviousData);?>
            <div class="panel mail-wrapper rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title"></h3>
                    </div>
                    <div class="clearfix"></div>
                </div><!-- /.panel-heading -->
                <div class="panel-sub-heading inner-all">
                    <div class="pull-left">
                        <h3 class="lead no-margin">Question :<?php if(isset($model->question) && !empty($model->question)) { echo $model->question; } ?></h3>
                    </div>

                <div class="clearfix"></div>
                </div><!-- /.panel-sub-heading -->
                <div class="panel-sub-heading inner-all">
                    <div class="row">
                        <div class="col-md-12 col-sm-8 col-xs-7">
                            <!--<img src="../../../assets/global/img/avatar/35/1.png" alt="..." class="img-circle">-->
                            <h3 class="lead no-margin">Relevant Information :<br/>
                            <?php if(isset($model->information) && !empty($model->information)) { echo $model->information; } ?>
                            </h3>
                        </div>
                    </div>
                </div><!-- /.panel-sub-heading -->
            </div>
             </div>

               <div class="row">
                        <div class="col-md-12">
                             <div>
                              <ul>
                             <li>Values shown in a blue box ( <span style="background-color: #3399ff;">20</span> ) represent your previous quarter entry for that specific node and data combination. It is shown purely as a reference.
 <li>Values shown in a green box ( <span style="background-color:  #99ff00;">21</span> ) represent the previous quarter's mean for that specific node and data combination. It is shown purely as a reference.</li>
                             </ul>
                             </div>

                           <!-- Start danger color table -->
                        <?php $form = ActiveForm::begin([
                        'options' => [
                           'class' => 'form-horizontal'
                        ],
                        'fieldConfig' => [
                            'template' => '{label}<div>{input}</div>',
                            'labelOptions' => ['style' => 'display:none;']
                        ]

                    ]); ?>
                     <?php echo $form->errorSummary($dataModel); ?>

                    <!-- table start-->
  <div class="table-responsive">
            <table class="table table-success table-condensed table-bordered table-striped sticky-header">
            <thead>
                <th>
                  Area
                </th>
                <?php  for($i=0;$i<count($arrColumnHeadings);$i++) { ?>
                    <th>
                    <?php echo $arrColumnHeadings[$i]['heading'];?>
                    </th>
                 <?php } ?>

             </thead>
             <tbody>
                 <?php foreach($arrNodes as $node) {
                     $flag = $childFlag = 0;
                     $boldClass = '';
                      if($node['included'] != 1 || $node['capture_level'] != 1) {
                        $flag = 1;
                        $boldClass = 'class = "bold-class"';
                        ## do not show child nodes if excluded
                        if($node['survey_template_question_node_id'] != "") {
                            $childFlag = 1;
                        } elseif(isset($node['hasChild']) && $node['hasChild'] == 0) {
                        	$childFlag = 1;
                        }
                    }
                    if($childFlag != 1) {
                    ?>
                         <tr>
                               <td <?php echo $boldClass;?>><?php echo $node['name']; ?></td>
                                <?php for($i=0;$i<count($arrColumnHeadings);$i++) {?>
                                    <td>
                                   <?php $dataId = $arrColumnHeadings[$i]['id'];
                                    if($flag == 0) {
                                   $readOnly = false;
                                    // data_template_id
                                   if (isset($arroutputColumnExclusions[$dataId]) &&  $node['id'] == $arroutputColumnExclusions[$dataId]) {
                                     $readOnly = true;
                                    } ?>

                                  <?php  if($arrColumnHeadings[$i]['field_type'] == 'selection') {
                                        $arrSelection = [];
                                        $arrSelection = explode(',',$arrColumnHeadings[$i]['options']);
                                        $arrSelection = array_combine($arrSelection, $arrSelection );

                                        if(isset($dataArr[$node['id']])) {
                                            $dataModel->value = $dataArr[$node['id']][$dataId];
                                        }
                                        echo $form->field($dataModel, 'value[]')
                                        ->dropDownList($arrSelection, ['class' => 'form1' , 'disabled' => $readOnly ,'options'=>[$dataModel->value =>['Selected'=>true]] ])
                                        ->label(false);
                                        if($readOnly == true) {
                                            echo $form->field($dataModel, 'value[]')->hiddenInput(['value' => current($arrSelection), 'class' => 'form1'])->label('');
                                        }
                                    } else {

                                        echo $form->field($dataModel, 'value[]')
                                                ->textInput(['maxlength' => true, 'class' => 'form1',  'readonly' => $readOnly, 'value'=> isset($dataArr[$node['id']]) ? $dataArr[$node['id']][$dataId] : ''])
                                                ->label(false);
                                    }
                                    echo $form->field($dataModel, 'data_field_template_id[]')->hiddenInput(['value' => $dataId, 'template' => '<input>','class' => 'form1'])->label('');
                                    echo $form->field($dataModel, 'survey_template_question_node_id[]')->hiddenInput(['value' => $node['id']])->label('');


                                }
                                    ?>
                                    <div class="previousMean"><?php  echo isset($previousQuarterMean[$node['id']][$dataId]) ? round($previousQuarterMean[$node['id']][$dataId],1, PHP_ROUND_HALF_UP) : '';?></div>

                                    <div class="previousValue"><?php  echo isset($arrPreviousData[$node['id']]) ? $arrPreviousData[$node['id']][$dataId] : '';?></div>
                                    </td>
                                <?php } ?>
                         </tr>
                  <?php }
                  } ?>


             </tbody>
            </table>
        </div>
<!-- table end-->


         <div class="form-footer">
          <div class="pull-left">

                <?php if(!empty($arrPreviousId)) {
                  echo Html::a('Previous', ['contributor/questions/answer', 'id' => $arrPreviousId['id'] , 'quarter_id' => $intSurveyQuarterId], ['class' => 'btn btn-warning mr-5']);
                }?>
                 <?php  if(($objGetTemplateDetails->closed == '1') && !empty($arrNextId)) {
                  echo Html::a('Next', ['contributor/questions/answer', 'id' => $arrNextId['id'] , 'quarter_id' => $intSurveyQuarterId], ['class' => 'btn btn-primary mr-5']);
                }?>
            </div>
            <div class="pull-right">
                <?php echo Html::a('Cancel', ['contributor/questions/index', 'id' => $intSurveyQuarterId], ['class' => 'btn btn-danger mr-5']);?>
                <?php if($objGetTemplateDetails->closed != '1') {
                  // && count($dataArr) > 0
                  if(!empty($arrNextId)) {
                    echo Html::submitButton('Submit & continue', ['class' => 'btn btn-success']);
                  } else {
                    echo Html::submitButton('Submit & finish', ['class' => 'btn btn-success']);
                  }
                }?>
            </div>
            <div class="clearfix"></div>



    </div>
                            <script src="/rode_survey/js/jquery.stickytableheaders.js"></script>
<script>
    // @preserve jQuery.floatThead 1.2.9 - http://mkoryak.github.io/floatThead/ - Copyright (c) 2012 - 2014 Misha Koryak
    // @license MIT
    !function(a){function b(a,b,c){if(8==g){var d=j.width(),e=f.debounce(function(){var a=j.width();d!=a&&(d=a,c())},a);j.on(b,e)}else j.on(b,f.debounce(c,a))}function c(a){window.console&&window.console&&window.console.log&&window.console.log(a)}function d(){var b=a('<div style="width:50px;height:50px;overflow-y:scroll;position:absolute;top:-200px;left:-200px;"><div style="height:100px;width:100%"></div>');a("body").append(b);var c=b.innerWidth(),d=a("div",b).innerWidth();return b.remove(),c-d}function e(a){if(a.dataTableSettings)for(var b=0;b<a.dataTableSettings.length;b++){var c=a.dataTableSettings[b].nTable;if(a[0]==c)return!0}return!1}a.floatThead=a.floatThead||{},a.floatThead.defaults={cellTag:null,headerCellSelector:"tr:first>th:visible",zIndex:1001,debounceResizeMs:10,useAbsolutePositioning:!0,scrollingTop:0,scrollingBottom:0,scrollContainer:function(){return a([])},getSizingRow:function(a){return a.find("tbody tr:visible:first>*")},floatTableClass:"floatThead-table",floatWrapperClass:"floatThead-wrapper",floatContainerClass:"floatThead-container",copyTableClass:!0,debug:!1};var f=window._,g=function(){for(var a=3,b=document.createElement("b"),c=b.all||[];a=1+a,b.innerHTML="<\!--[if gt IE "+a+"]><i><![endif]-->",c[0];);return a>4?a:document.documentMode}(),h=null,i=function(){if(g)return!1;var b=a("<table><colgroup><col></colgroup><tbody><tr><td style='width:10px'></td></tbody></table>");a("body").append(b);var c=b.find("col").width();return b.remove(),0==c},j=a(window),k=0;a.fn.floatThead=function(l){if(l=l||{},!f&&(f=window._||a.floatThead._,!f))throw new Error("jquery.floatThead-slim.js requires underscore. You should use the non-lite version since you do not have underscore.");if(8>g)return this;if(null==h&&(h=i(),h&&(document.createElement("fthtr"),document.createElement("fthtd"),document.createElement("fthfoot"))),f.isString(l)){var m=l,n=this;return this.filter("table").each(function(){var b=a(this).data("floatThead-attached");if(b&&f.isFunction(b[m])){var c=b[m]();"undefined"!=typeof c&&(n=c)}}),n}var o=a.extend({},a.floatThead.defaults||{},l);return a.each(l,function(b){b in a.floatThead.defaults||!o.debug||c("jQuery.floatThead: used ["+b+"] key to init plugin, but that param is not an option for the plugin. Valid options are: "+f.keys(a.floatThead.defaults).join(", "))}),this.filter(":not(."+o.floatTableClass+")").each(function(){function c(a){return a+".fth-"+y+".floatTHead"}function i(){var b=0;A.find("tr:visible").each(function(){b+=a(this).outerHeight(!0)}),Z.outerHeight(b),$.outerHeight(b)}function l(){var a=z.outerWidth(),b=I.width()||a;if(X.width(b-F.vertical),O){var c=100*a/(b-F.vertical);S.css("width",c+"%")}else S.outerWidth(a)}function m(){C=(f.isFunction(o.scrollingTop)?o.scrollingTop(z):o.scrollingTop)||0,D=(f.isFunction(o.scrollingBottom)?o.scrollingBottom(z):o.scrollingBottom)||0}function n(){var b,c;if(V)b=U.find("col").length;else{var d;d=null==o.cellTag&&o.headerCellSelector?o.headerCellSelector:"tr:first>"+o.cellTag,c=A.find(d),b=0,c.each(function(){b+=parseInt(a(this).attr("colspan")||1,10)})}if(b!=H){H=b;for(var e=[],f=[],g=[],i=0;b>i;i++)e.push('<th class="floatThead-col"/>'),f.push("<col/>"),g.push("<fthtd style='display:table-cell;height:0;width:auto;'/>");f=f.join(""),e=e.join(""),h&&(g=g.join(""),W.html(g),bb=W.find("fthtd")),Z.html(e),$=Z.find("th"),V||U.html(f),_=U.find("col"),T.html(f),ab=T.find("col")}return b}function p(){if(!E){if(E=!0,J){var a=z.width(),b=Q.width();a>b&&z.css("minWidth",a)}z.css(db),S.css(db),S.append(A),B.before(Y),i()}}function q(){E&&(E=!1,J&&z.width(fb),Y.detach(),z.prepend(A),z.css(eb),S.css(eb))}function r(a){J!=a&&(J=a,X.css({position:J?"absolute":"fixed"}))}function s(a,b,c,d){return h?c:d?o.getSizingRow(a,b,c):b}function t(){var a,b=n();return function(){var c=s(z,_,bb,g);if(c.length==b&&b>0){if(!V)for(a=0;b>a;a++)_.eq(a).css("width","");q();var d=[];for(a=0;b>a;a++)d[a]=c.get(a).offsetWidth;for(a=0;b>a;a++)ab.eq(a).width(d[a]),_.eq(a).width(d[a]);p()}else S.append(A),z.css(eb),S.css(eb),i()}}function u(a){var b=I.css("border-"+a+"-width"),c=0;return b&&~b.indexOf("px")&&(c=parseInt(b,10)),c}function v(){var a,b=I.scrollTop(),c=0,d=L?K.outerHeight(!0):0,e=M?d:-d,f=X.height(),g=z.offset(),i=0;if(O){var k=I.offset();c=g.top-k.top+b,L&&M&&(c+=d),c-=u("top"),i=u("left")}else a=g.top-C-f+D+F.horizontal;var l=j.scrollTop(),m=j.scrollLeft(),n=I.scrollLeft();return b=I.scrollTop(),function(k){if("windowScroll"==k?(l=j.scrollTop(),m=j.scrollLeft()):"containerScroll"==k?(b=I.scrollTop(),n=I.scrollLeft()):"init"!=k&&(l=j.scrollTop(),m=j.scrollLeft(),b=I.scrollTop(),n=I.scrollLeft()),!h||!(0>l||0>m)){if(R)r("windowScrollDone"==k?!0:!1);else if("windowScrollDone"==k)return null;g=z.offset(),L&&M&&(g.top+=d);var o,s,t=z.outerHeight();if(O&&J){if(c>=b){var u=c-b;o=u>0?u:0}else o=P?0:b;s=i}else!O&&J?(l>a+t+e?o=t-f+e:g.top>l+C?(o=0,q()):(o=C+l-g.top+c+(M?d:0),p()),s=0):O&&!J?(c>b||b-c>t?(o=g.top-l,q()):(o=g.top+b-l-c,p()),s=g.left+n-m):O||J||(l>a+t+e?o=t+C-l+a+e:g.top>l+C?(o=g.top-l,p()):o=C,s=g.left-m);return{top:o,left:s}}}}function w(){var a=null,b=null,c=null;return function(d,e,f){null==d||a==d.top&&b==d.left||(X.css({top:d.top,left:d.left}),a=d.top,b=d.left),e&&l(),f&&i();var g=I.scrollLeft();J&&c==g||(X.scrollLeft(g),c=g)}}function x(){if(I.length){var a=I.width(),b=I.height(),c=z.height(),d=z.width(),e=d>a?G:0,f=c>b?G:0;F.horizontal=d>a-f?G:0,F.vertical=c>b-e?G:0}}var y=k,z=a(this);if(z.data("floatThead-attached"))return!0;if(!z.is("table"))throw new Error('jQuery.floatThead must be run on a table element. ex: $("table").floatThead();');var A=z.find("thead:first"),B=z.find("tbody:first");if(0==A.length)throw new Error("jQuery.floatThead must be run on a table that contains a <thead> element");var C,D,E=!1,F={vertical:0,horizontal:0},G=d(),H=0,I=o.scrollContainer(z)||a([]),J=o.useAbsolutePositioning;null==J&&(J=o.scrollContainer(z).length);var K=z.find("caption"),L=1==K.length;if(L)var M="top"===(K.css("caption-side")||K.attr("align")||"top");var N=a('<fthfoot style="display:table-footer-group;"/>'),O=I.length>0,P=!1,Q=a([]),R=9>=g&&!O&&J,S=a("<table/>"),T=a("<colgroup/>"),U=z.find("colgroup:first"),V=!0;0==U.length&&(U=a("<colgroup/>"),V=!1);var W=a('<fthrow style="display:table-row;height:0;"/>'),X=a('<div style="overflow: hidden;"></div>'),Y=a("<thead/>"),Z=a('<tr class="size-row"/>'),$=a([]),_=a([]),ab=a([]),bb=a([]);if(Y.append(Z),z.prepend(U),h&&(N.append(W),z.append(N)),S.append(T),X.append(S),o.copyTableClass&&S.attr("class",z.attr("class")),S.attr({cellpadding:z.attr("cellpadding"),cellspacing:z.attr("cellspacing"),border:z.attr("border")}),S.css({borderCollapse:z.css("borderCollapse"),border:z.css("border")}),S.addClass(o.floatTableClass).css("margin",0),J){var cb=function(a,b){var c=a.css("position"),d="relative"==c||"absolute"==c;if(!d||b){var e={paddingLeft:a.css("paddingLeft"),paddingRight:a.css("paddingRight")};X.css(e),a=a.wrap("<div class='"+o.floatWrapperClass+"' style='position: relative; clear:both;'></div>").parent(),P=!0}return a};O?(Q=cb(I,!0),Q.append(X)):(Q=cb(z),z.after(X))}else z.after(X);X.css({position:J?"absolute":"fixed",marginTop:0,top:J?0:"auto",zIndex:o.zIndex}),X.addClass(o.floatContainerClass),m();var db={"table-layout":"fixed"},eb={"table-layout":z.css("tableLayout")||"auto"},fb=z[0].style.width||"";x();var gb,hb=function(){(gb=t())()};hb();var ib=v(),jb=w();jb(ib("init"),!0);var kb=f.debounce(function(){jb(ib("windowScrollDone"),!1)},300),lb=function(){jb(ib("windowScroll"),!1),kb()},mb=function(){jb(ib("containerScroll"),!1)},nb=function(){m(),x(),hb(),ib=v(),(jb=w())(ib("resize"),!0,!0)},ob=f.debounce(function(){x(),m(),hb(),ib=v(),jb(ib("reflow"),!0)},1);O?J?I.on(c("scroll"),mb):(I.on(c("scroll"),mb),j.on(c("scroll"),lb)):j.on(c("scroll"),lb),j.on(c("load"),ob),b(o.debounceResizeMs,c("resize"),nb),z.on("reflow",ob),e(z)&&z.on("filter",ob).on("sort",ob).on("page",ob),z.data("floatThead-attached",{destroy:function(){var a=".fth-"+y;q(),z.css(eb),U.remove(),h&&N.remove(),Y.parent().length&&Y.replaceWith(A),z.off("reflow"),I.off(a),P&&(I.length?I.unwrap():z.unwrap()),J&&z.css("minWidth",""),X.remove(),z.data("floatThead-attached",!1),j.off(a)},reflow:function(){ob()},setHeaderHeight:function(){i()},getFloatContainer:function(){return X},getRowGroups:function(){return E?X.find("thead").add(z.find("tbody,tfoot")):z.find("thead,tbody,tfoot")}}),k++}),this}}(jQuery),function(a){a.floatThead=a.floatThead||{},a.floatThead._=window._||function(){var b={},c=Object.prototype.hasOwnProperty,d=["Arguments","Function","String","Number","Date","RegExp"];return b.has=function(a,b){return c.call(a,b)},b.keys=function(a){if(a!==Object(a))throw new TypeError("Invalid object");var c=[];for(var d in a)b.has(a,d)&&c.push(d);return c},a.each(d,function(){var a=this;b["is"+a]=function(b){return Object.prototype.toString.call(b)=="[object "+a+"]"}}),b.debounce=function(a,b,c){var d,e,f,g,h;return function(){f=this,e=arguments,g=new Date;var i=function(){var j=new Date-g;b>j?d=setTimeout(i,b-j):(d=null,c||(h=a.apply(f,e)))},j=c&&!d;return d||(d=setTimeout(i,b)),j&&(h=a.apply(f,e)),h}},b}()}(jQuery);



    $(document).ready(function(){

        $(".sticky-header").floatThead({scrollingTop:50});

    });

</script>

                            <!-- /.form-footer -->



                                <?php if($objGetTemplateDetails->closed == '1' ) {//|| $objGetTemplateDetails->completed == 1
                ?>
        <script type="text/javascript">
            $('input').attr('disabled', true);
            $('select').attr('disabled', true);
            //$('input').prop('readonly',true);
            //$('input[type="text"], textarea').attr('readonly','readonly');
        </script>
    <?php } ?>
        <?php  ActiveForm::end();?>
                        </div>
                    </div><!-- row end-- >




        </div>
