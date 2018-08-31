<?php
use yii\helpers\Html;
use yii\helpers\Url;?>
<div style="font-family:Verdana">
  Dear <?php echo $params['name'];?>,<br />
  This is a resend of the original mail requesting you to complete the following Survey:<br/>
  <strong>Survey :</strong> <?php echo $params['survey'];?><br/>
  <strong>Deadline for survey:</strong> <?php echo $params['deadline'];?><br/>
  Please click here to go to Rode Surveys and complete the survey.<br/>
  <?php echo @$params['site_url'];?>
  <hr>
  Thank You,<br />
  Support Rode Survey.
</div>				  
