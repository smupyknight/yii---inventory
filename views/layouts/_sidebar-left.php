<?php use yii\Helpers\Html;?>

<aside id="sidebar-left" class="<?php echo !empty($classLeft)? $classLeft : 'sidebar-circle' ?>"> 
  
  <!-- Start left navigation - profile shortcut -->
  <div class="sidebar-content"> 
    <!-- <div class="media">
            <a class="pull-left has-notif avatar" href="<?= Yii::$app->getUrlManager()->createUrl('admin/page/profile') ?>">
                <!--<img src="<?php echo \Yii::getAlias('@web') ?>/images/11.png" alt="admin">
                <i class="online"></i>
            </a>
           <div class="media-body">
                <h4 class="media-heading">Hello, <span><?php echo Yii::$app->user->identity->name ? Yii::$app->user->identity->name : Yii::$app->user->identity->login;?></span></h4>
                <small>Web Designer</small> 
           <!-- </div> 
        </div>--> 
  </div>
  <!-- /.sidebar-content --> 
  <!--/ End left navigation -  profile shortcut --> 
  
  <!-- Start left navigation - menu -->
  <ul class="sidebar-menu">
    <?php if(Yii::$app->user->identity->role == 'Administrator') : ?>
    <!-- Start navigation - dashboard -->
    <li class="<?php echo (Yii::$app->controller->id == 'dashboard') ? 'active' : '' ?> <?= Yii::$app->user->identity->role == 'Administrator' ? '' : 'hidden' ?>" > <a href="<?php echo Yii::$app->getUrlManager()->createUrl('/admin/dashboard/index') ?>"> <span class="icon"><i class="fa fa-home"></i></span> <span class="text">Dashboard</span> <?php echo (Yii::$app->controller->id == 'dashboard') ? '<span class="selected"></span>' : '' ?> </a> </li>
    <!--/ End navigation - dashboard -->
    
    <li class="<?php echo (Yii::$app->controller->id == 'users') ? 'active' : '' ?>"> <a href="<?php echo Yii::$app->getUrlManager()->createUrl('/admin/users/index') ?>"> <span class="icon"><i class="fa fa-users"></i></span> <span class="text">Contributors</span> <?php echo (Yii::$app->controller->id == 'users') ? '<span class="selected"></span>' : '' ?> </a> </li>
    <!--/ End navigation - dashboard --> 
    
    <!-- Start navigation - Property -->
    <li class="submenu <?php echo (Yii::$app->controller->id == 'admin/properties') ? 'active' : '' ?>"> <a href="javascript:void(0);"> <span class="icon"><i class="fa fa-globe"></i></span> <span class="text">Property Type</span> <span class="arrow"></span> <?php echo (Yii::$app->controller->id == 'admin/properties') ? '<span class="selected"></span>' : '' ?> </a>
      <ul>
        <li class="<?php echo (Yii::$app->controller->action->id == 'index') ? 'active' : '' ?>"> <?php echo Html::a('Manage Property Types', ['/admin/properties']);?> </li>
        <li class="<?php echo (Yii::$app->controller->action->id == 'create') ? 'active' : '' ?>"> <?php echo Html::a('Create Property Type', ['/admin/properties/create']);?> </li>
      </ul>
    </li>
    <!--/ End navigation - Property --> 
    
    <!-- Start navigation - Categories -->
    <li class="submenu <?php echo (Yii::$app->controller->id == 'admin/categories') ? 'active' : '' ?>"> <a href="javascript:void(0);"> <span class="icon"><i class="fa fa-cubes"></i></span> <span class="text">Categories</span> <span class="arrow"></span> <?php echo (Yii::$app->controller->id == 'admin/categories') ? '<span class="selected"></span>' : '' ?> </a>
      <ul>
        <li class="<?php echo (Yii::$app->controller->action->id == 'index') ? 'active' : '' ?>"> <?php echo Html::a('Manage Survey Categories', ['/admin/categories']);?> </li>
        <li class="<?php echo (Yii::$app->controller->action->id == 'create') ? 'active' : '' ?>"> <?php echo Html::a('Create Category', ['/admin/categories/create']);?> </li>
      </ul>
    </li>
    <!--/ End navigation - Categories --> 
    
    <!-- Start navigation - Email Template -->
    <li class="submenu <?= (Yii::$app->controller->id == 'admin/emailtemplates') ? 'active' : '' ?>"> <a href="javascript:void(0);"> <span class="icon"><i class="fa fa-envelope"></i></span> <span class="text">Email Templates</span> <span class="arrow"></span>
      <?= (Yii::$app->controller->id == 'admin/emailtemplates') ? '<span class="selected"></span>' : '' ?>
      </a>
      <ul>
        <li class="<?= (Yii::$app->controller->action->id == 'index') ? 'active' : '' ?>"> <?php echo Html::a('Manage Email Templates', ['/admin/emailtemplates']);?> </li>
        <li class="<?= (Yii::$app->controller->action->id == 'create') ? 'active' : '' ?>"> <?php echo Html::a('Create Email Template', ['/admin/emailtemplates/create']);?> </li>
      </ul>
    </li>
    
    <!--/ End navigation - Email Template --> 
    
    <!-- Start navigation - Nodes -->
    <li class="submenu <?= (Yii::$app->controller->id == 'admin/nodes') ? 'active' : '' ?>"> <a href="javascript:void(0);"> <span class="icon"><i class="fa fa-map-marker"></i></span> <span class="text">Node Management</span> <span class="arrow"></span>
      <?= (Yii::$app->controller->id == 'admin/nodes') ? '<span class="selected"></span>' : '' ?>
      </a>
      <ul>
        <li class="<?= (Yii::$app->controller->action->id == 'index') ? 'active' : '' ?>"> <?php echo Html::a('Manage Parent Nodes', ['/admin/nodes/']);?> </li>
        <!--<li class="<?= (Yii::$app->controller->action->id == 'create') ? 'active' : '' ?>">
                <?php echo Html::a('Create Parent Nodes', ['/admin/nodes/create']);?>
                </li>-->
        
      </ul>
    </li>
    <!--/ End navigation - Nodes -->
    
    <li class="submenu <?php echo (Yii::$app->controller->id == 'admin/companies') ? 'active' : '' ?>"> <a href="javascript:void(0);"> <span class="icon"><i class="fa fa-bank"></i></span> <span class="text">Companies</span> <span class="arrow"></span> <?php echo (Yii::$app->controller->id == 'admin/companies') ? '<span class="selected"></span>' : '' ?> </a>
      <ul>
        <li class="<?php echo (Yii::$app->controller->action->id == 'index') ? 'active' : '' ?>"> <?php echo Html::a('Manage Companies', ['/admin/companies']);?> </li>
        <li class="<?php echo (Yii::$app->controller->action->id == 'create') ? 'active' : '' ?>"> <?php echo Html::a('Create Companies', ['/admin/companies/create']);?> </li>
      </ul>
    </li>
    <!--/ End navigation - pages --> 
    
    <!-- Start navigation - Surveys -->
    <li class="submenu <?= (Yii::$app->controller->id == 'admin/surveys') ? 'active' : '' ?>"> <a href="javascript:void(0);"> <span class="icon"><i class="fa fa-list"></i></span> <span class="text">Surveys</span> <span class="arrow"></span>
      <?= (Yii::$app->controller->id == 'admin/surveys') ? '<span class="selected"></span>' : '' ?>
      </a>
      <ul>
        <li class="<?= (Yii::$app->controller->action->id == 'index') ? 'active' : '' ?>"> <?php echo Html::a('Manage Surveys', ['/admin/surveys/']);?> </li>
        <!--<li class="<?= (Yii::$app->controller->action->id == 'create') ? 'active' : '' ?>">
                <?php echo Html::a('Create Parent Nodes', ['/admin/nodes/create']);?>
                </li>-->
        
      </ul>
    </li>
    <!--/ End navigation - Surveys --> 
    
    <!-- Start navigation - Surveys -->
    <li class="submenu <?= (Yii::$app->controller->id == 'admin/reports') ? 'active' : '' ?>"> <a href="javascript:void(0);"> <span class="icon"><i class="fa fa-area-chart"></i></span> <span class="text">Reports</span> <span class="arrow"></span>
      <?= (Yii::$app->controller->id == 'admin/reports') ? '<span class="selected"></span>' : '' ?>
      </a>
      <ul>
        <li class="<?= (Yii::$app->controller->action->id == 'excludedcontributors') ? 'active' : '' ?>"> <?php echo Html::a('Contributors', ['/admin/reports/excludedcontributors']);?> </li>
        <li class="<?= (in_array(Yii::$app->controller->action->id,['surveys','surveyquarter','individualcontribution'])) ? 'active' : '' ?>"> <?php echo Html::a('Surveys', ['/admin/reports/surveys']);?> </li>
      </ul>
    </li>
    <!--/ End navigation - Surveys -->
    <?php elseif(Yii::$app->user->identity->role == 'Contributor') :?>

      <!-- Start navigation - dashboard -->
    <li class="<?php echo (Yii::$app->controller->id == 'dashboard') ? 'active' : '' ?> <?= Yii::$app->user->identity->role == 'Contributor' ? 'hidden' : 'hidden' ?>" > <a href="<?php echo Yii::$app->getUrlManager()->createUrl('/contributor/surveys/dashboard') ?>"> <span class="icon"><i class="fa fa-home"></i></span> <span class="text">Dashboard</span> <?php echo (Yii::$app->controller->id == 'dashboard') ? '<span class="selected"></span>' : '' ?> </a> </li>
    <!--/ End navigation - dashboard -->
    <!-- Start navigation - Surveys -->
    <li class="submenu <?= (Yii::$app->controller->id == 'contributor/surveys') ? 'active' : '' ?>"> <a href="javascript:void(0);"> <span class="icon"><i class="fa fa-list"></i></span> <span class="text">Surveys</span> <span class="arrow"></span>
      <?= (Yii::$app->controller->id == 'contributor/surveys') ? '<span class="selected"></span>' : '' ?>
      </a>
      <ul>
        <li class="<?= (Yii::$app->controller->action->id == 'index') ? 'active' : '' ?>"> <?php echo Html::a('Manage Surveys', ['contributor/surveys/index']);?> </li>
        <li> <a href="#" onclick="restartTour();">Replay tour</a></li>
      </ul>
    </li>
    <!--/ End navigation - Surveys -->
    <?php endif;?>
  </ul>
  <!-- /.sidebar-menu --> 
  <!--/ End left navigation - menu --> 
  
</aside>
<!-- /#sidebar-left --> 
