<?php
use yii\helpers\Html;
use app\models\Contributornodes;
$this->title = 'View nodes';

?>

<section id="page-content">
                <!-- Start page header -->
    <div class="header-content">
        <h2><i class="fa fa-street-view"></i> <?php echo $this->title;?></h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?= Yii::$app->getUrlManager()->createUrl('admin/dashboard/index') ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                 <li><a href="<?= Yii::$app->getUrlManager()->createUrl('admin/users/index') ?>">Manage Contributors</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li class="active"><?php echo $this->title;?> </li>
            </ol>
        </div><!-- /.breadcrumb-wrapper -->
    </div><!-- /.header-content -->
    <!--/ End page header -->

    <!-- Start body content -->
    <div class="body-content animated fadeIn">
        <div class="row">
            <div class="col-md-12">
              <?php if(count($locationNodes) >0) {
                foreach($locationNodes as $property) {
                    $arrLocationNodes   =   Contributornodes::find()
                  ->select(['location_nodes.name','location_nodes.location_node_id', 'contributor_nodes.id'])
                  ->where(['contributor_id' => $contributor_id, 'contributor_nodes.property_type_id' => $property['property_type_id']])
                  ->innerJoinWith(['nodes'])
                  ->orderBy(['location_nodes.name' => SORT_ASC , 'location_nodes.location_node_id' => SORT_ASC])
                  //->asArray()
                  ->all();
                    if(!empty($arrLocationNodes)) { ?>
                        <div class="col-md-6">
                            <div class="panel panel-success shadow">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h3 class="panel-title"><?php echo $property['property'];?></h3>
                                    </div>
                                    <div class="pull-right">
                                        <button class="btn btn-sm" data-action="collapse" data-toggle="tooltip" data-placement="top" data-title="Collapse" data-original-title="" title=""><i class="fa fa-angle-up"></i></button>
                                        <button onclick="remove_property('<?=$property['property_type_id'];?>', '<?=$contributor_id;?>', this); return false;" class="btn btn-sm" data-placement="right" data-title="Delete" data-original-title="" title=""><i class="fa fa-close"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div><!-- /.panel-heading -->
                                <div class="panel-body no-padding">
                                    <ul class="list-group no-margin">
                                    <?php  foreach($arrLocationNodes as $node) {
                                        $strClass = '';
                                        if($node->location_node_id == "") {
                                            $strClass = 'bold-class';
                                        }
                                        ?>
                                        <li class="list-group-item <?php echo $strClass; ?>"><?php  echo $node->name;?></li>
                                    <?php } ?>
                                    </ul>
                                </div><!-- /.panel-body -->
                            </div>
                        </div>
                      <?php }
                  } ?>
          <?php } ?>
            </div><!-- /.col-md-12 -->
        </div><!-- /.row -->
    </div><!-- /.body-content -->
    <!--/ End body content -->

<!--</div>-->
