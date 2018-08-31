<?php
   use yii\helpers\Html;
?>
<header id="header">

  <!-- Start header left -->
  <div class="header-left">
    <!-- Start offcanvas left: This menu will take position at the top of template header (mobile only). Make sure that only #header have the `position: relative`, or it may cause unwanted behavior -->
    <div class="navbar-minimize-mobile left"> <i class="fa fa-bars"></i> </div>
    <!--/ End offcanvas left -->

    <!-- Start navbar header -->
    <div class="navbar-header">
      <?php if(Yii::$app->user->identity->role=='Administrator') { ?>
      <!-- Start brand -->
      <a class="navbar-brand" href="<?= (!Yii::$app->user->isGuest) ? Yii::$app->getUrlManager()->createUrl('admin/dashboard/index') :Yii::$app->getUrlManager()->createUrl('site/login') ; ?>"> <img class="logo" src="<?= \Yii::getAlias('@web') ?>/images/logo-vertical.png" alt="brand logo"/> </a><!-- /.navbar-brand --> <?php }else { ?>
      <a class="navbar-brand" href="<?= (!Yii::$app->user->isGuest) ? Yii::$app->getUrlManager()->createUrl('site/index') :Yii::$app->getUrlManager()->createUrl('site/login') ; ?>"> <img class="logo" src="<?= \Yii::getAlias('@web') ?>/images/logo-vertical.png" alt="brand logo"/> </a>
      <?php } ?>
      <!--/ End brand -->

    </div>
    <!-- /.navbar-header -->
    <!--/ End navbar header -->



    <div class="clearfix"></div>
  </div>
  <!-- /.header-left -->
  <!--/ End header left -->

  <!-- Start header right -->
  <div class="header-right">
      <!-- Start navbar toolbar -->
      <div class="navbar navbar-toolbar">

      <!-- Start left navigation -->
      <ul class="nav navbar-nav navbar-left">

        <!-- Start sidebar shrink -->
        <li class="navbar-minimize"> <a href="javascript:void(0);" title="Minimize sidebar"> <i class="fa fa-bars"></i> </a> </li>
        <!--/ End sidebar shrink -->

        <!-- Start form search -->
        <!-- <li class="navbar-search">
                   <a href="#" class="trigger-search"><i class="fa fa-search"></i></a>
                    <form class="navbar-form">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control typeahead rounded" placeholder="Search for people, places and things">
                            <button type="submit" class="btn btn-theme fa fa-search form-control-feedback rounded"></button>
                        </div>
                    </form>
                </li>-->
        <!--/ End form search -->

      </ul>
      <!-- /.nav navbar-nav navbar-left -->
      <!--/ End left navigation -->

      <!-- Start right navigation -->
      <ul class="nav navbar-nav navbar-right">

        <!-- /.nav navbar-nav navbar-right -->

        <!-- Start messages -->

        <!--/ End messages -->

        <!-- Start notifications -->

        <!--/ End notifications -->

        <!-- Start profile -->
        <?php if(!Yii::$app->user->isGuest) : ?>
        <li class="dropdown navbar-profile"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <span class="meta">  <span class="text hidden-xs hidden-sm text-muted"><?php echo Yii::$app->user->identity->name ? Yii::$app->user->identity->name : Yii::$app->user->identity->login;?></span> <span class="caret"></span> </span> </a>
          <!-- Start dropdown menu -->
          <?php
				$editProfileUrl	=	'';
				$changePassUrl	=	'';
			  	if(Yii::$app->user->identity->role=='Administrator')
				{
						$editProfileUrl	=	'admin/editprofile';
						$changePassUrl	=	'admin/changepassword';
				}
				else
				{
						$editProfileUrl	=	'contributor/contributors/update';
						$changePassUrl	=	'contributor/users/changepassword';
				}
		  ?>
          <ul class="dropdown-menu animated flipInX">
            <li class="dropdown-header">Account</li>
            <li><?php echo Html::a('<i class="fa fa-user"></i>Edit Profile', [$editProfileUrl]);?></li>
            <li><?php echo Html::a('<i class="fa fa-key"></i>Change Password', [$changePassUrl]);?></li>
            <!--<li><a href="mail-inbox.html"><i class="fa fa-envelope-square"></i>Inbox <span class="label label-info pull-right">30</span></a>
                        <li><a href="#"><i class="fa fa-share-square"></i>Invite a friend</a></li>
                        <li class="dropdown-header">Product</li>
                        <li><a href="#"><i class="fa fa-upload"></i>Upload</a></li>
                        <li><a href="#"><i class="fa fa-dollar"></i>Earning</a></li>
                        <li><a href="#"><i class="fa fa-download"></i>Withdrawals</a></li>
                        <li class="divider"></li>-->
            <li class="divider"></li>
            <li> <?php echo Html::a('<i class="fa fa-sign-out"></i>Logout',['site/logout'],['data-method'=>'post']);?> </li>
          </ul>
          <!--/ End dropdown menu -->
        </li>
        <?php endif;?>
        <!-- /.dropdown navbar-profile -->
        <!--/ End profile -->



      </ul>
      <!--/ End right navigation -->

    </div>
    <!-- /.navbar-toolbar -->
    <!--/ End navbar toolbar -->
  </div>
  <!-- /.header-right -->
  <!--/ End header left -->

</header>
<!-- /#header -->
