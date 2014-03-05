<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 3 ) { 
	$_SESSION['message'] = "You are not authorized to edit maintenance steps.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
$procedure_id = $_GET['procedure_id'];
if (isset($procedure_id)) {
	$procedureClass = new Procedure();
	$procedure = $procedureClass->find_procedure_by_id($procedure_id);
}
if(isset($_POST['submit']) && $_POST['instruction'] != "") {
	if ($step = new Step()) {
		$stepClass-> newStep();
		$procedure_id = $_GET['procedure_id'];
		$redirect_link = "edit_maintenance_steps.php?procedure_id=".$procedure_id;
		redirect_to($redirect_link);
	}
} else {
	$_SESSION['message'] = "Please select all fields.";
	$instruction = "";
}
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">
        <div class="center-text"><h2>New Step for <?php echo $procedure['procedure_name'];?></h2></div>
        <div class="center-text"><small><?php echo output_message($message); unset($_SESSION['message']);?></small></div>
        <form class="form-horizontal" action="edit_maintenance_steps.php?procedure_id=<?php echo $procedure['id']; ?>" method="post" enctype="multipart/form-data" role="form">
        	<div class="form-group">
                <label for="procedure_name" class="sr-only">Selected Procedure <?php echo $procedure['procedure_name'];?></label>
                <input type="hidden" name="procedure_id" id="procedure_id"  value='<?php echo $procedure_id;?>'>
            </div>
        	<div class="form-group">
             	<label for="step_num">Select Step Number</label>
                <select class="form-control" name="step_num" id="step_num" required>
					<?php $stepClass-> getnewSteps(); ?>
                    <?php $stepClass-> getallSteps(); ?>
                </select>
            </div>
        	<div class="form-group">
               	<label for="instruction">Step Instructions</label>
                <textarea class="form-control input-block-level" rows="4" type="text" name="instruction" id="instruction" placeholder="Detailed instructions for this step." maxlength="500" value="" required></textarea>
                <script>
				$('textarea#instruction').maxlength({
					alwaysShow: false,
					threshold: 495,
					warningClass: "label label-success",
					limitReachedClass: "label label-danger",
					placement: 'top',
					preText: 'Used ',
					separator: ' of ',
					postText: ' chars.'
				});
				</script>
            </div>
        	<div class="form-group">
            	<label for="file">Step Photo <small class="text-danger">(optional)</small></label>
                <div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
                    <div class="center-text">
                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;"></div>
                        <div>
                            <span class="btn btn-primary btn-block btn-file">
                                <span class="fileinput-new"><i class="icon-camera"></i> Picture</span>
                                <span class="fileinput-exists"><i class="icon-refresh"></i> Change</span>
                                <input type="file" name="file" id="file">
                            </span>
                            <a href="#" class="btn btn-danger btn-block fileinput-exists" data-dismiss="fileinput"><i class="icon-trash"></i> Remove</a>
                        </div>
                    </div>
                </div>
            </div>
        	<div class="form-group">
            	<button class="btn btn-success btn-block" type="submit" name="submit" value="Submit"><i class="icon-plus-sign"></i> Create Step</button>
            </div>
        </form>
    </div>
    <a class="btn btn-info" style='text-align:center;vertical-align:middle' href="view_steps.php?procedure_id=<?php echo $_GET['procedure_id']; ?>"><i class="icon-chevron-left"></i> Done, return to view steps</a>
</div>	
<?php include_layout_template('admin_footer.php'); ?>