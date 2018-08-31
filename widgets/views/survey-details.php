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
         <?php if(!empty($questionDetail)) {?>
         <div class="row">
        	<div class="col-md-12">
    		<label>Question :</label>
            <?php echo strip_tags($questionDetail->question); ?>
            </div>
         </div>
         <?php }?>
    </div>
</div>
                           <!-- Start danger color table -->