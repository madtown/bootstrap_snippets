<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 3 ) { 
	$_SESSION['message'] = "You are not authorized to edit maintenance steps.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
if (isset($_GET['id']) and $_GET['id'] != "") {
	$stepClass = new Step;
	$id = $_GET['id'];
	$step = $stepClass->find_by_id($id);
}
$procedure_id = $_GET['procedure_id'];
if (isset($procedure_id)) {
	$procedureClass = new Procedure();
	$procedure = $procedureClass->find_procedure_by_id($procedure_id);
}
if(isset($_POST['submit']) && $_POST['instruction'] != "") {
	if ($step = new Step()) {
		$stepClass-> updateStep();
		$procedure_name = $_POST['procedure_name'];
		$redirect_link = "view_steps.php?procedure_id=".$procedure_name;
		redirect_to($redirect_link);
	}} else {
		$_SESSION['message'] = "Please select all fields.";
		$instruction = "";
		}
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>

	<div class="center-text"><h1>Edit Step #<?php echo htmlentities($step->step_num);?></h1><h2>Procedure: <?php echo htmlentities($procedure['procedure_name']);?></h2></div>

	<?php echo output_message($message); 
	unset($_SESSION['message']);
	?>
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">
		<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" role="form">
        	<div class="form-group">
                <label for="procedure_name" class="sr-only">Selected Procedure: <?php echo htmlentities($procedure['procedure_name']); ?></label>
                <input class="form-control" type="hidden" name="procedure_name" id="procedure_name"  value='<?php echo htmlentities($procedure['procedure_name']);?>'>
				</div>
        	<div class="form-group">
             	<label for="step_num" class="sr-only">Step Number:</br><h1><?php echo htmlentities($step->step_num);?></h1></label>
				<input class="form-control" type="hidden" name="instruction" maxlength="500" value="<?php echo htmlentities($step->step_num); ?>">
			</div>
        	<div class="form-group">
             	<label for="step_num">Step Instructions</label>
				<textarea class="form-control" rows="4" type="text" name="instruction" id="instruction" placeholder="Detailed instructions for this step ... max 500 characters" maxlength="500" required><?php echo htmlentities($step->instruction); ?></textarea>
                <script>
				$('textarea#instruction').maxlength({
					alwaysShow: false,
					threshold: 400,
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
            	<label for="file">Step Image</br><small class="text-danger">Will remain the same unless selected here</small></label>
                <div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
                    <div class="center-text">
                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;"></div>
                        <div>
                            <span class="btn btn-primary btn-block btn-file">
                                <span class="fileinput-new"><i class="icon-camera"></i> Step Image</span>
                                <span class="fileinput-exists"><i class="icon-refresh"></i> Change</span>
                                <input type="file" name="file" id="file">
                            </span>
                            <a href="#" class="btn btn-danger btn-block fileinput-exists" data-dismiss="fileinput"><i class="icon-trash"></i> Remove</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input class="form-control" type="hidden" name="procedure_name" id="procedure_name" value="<?php echo htmlentities($procedure['procedure_name']); ?>">
			</div>
        	<div class="form-group">
            	<button class="btn btn-success btn-block" type="submit" name="submit" value="Submit"><i class="icon-plus-sign"></i> Update Step</button>
            </div>
        </form>
	</div>
	<a class="btn btn-info" style='text-align:center;vertical-align:middle' href="view_steps.php?procedure_id=<?php echo htmlentities($step->procedure_name); ?>"><i class="icon-chevron-left"></i> Nevermind, return to view steps</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>