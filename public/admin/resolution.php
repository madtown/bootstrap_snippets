<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 2 ) { 
	$_SESSION['message'] = "You are not authorized to edit machines.";
	redirect_to("index.php");
}
?>
<?php
/*//Set default fields
if(!isset($_POST['submit'])) {
	$manufacturer 		= NULL;
	$machine_desc 		= NULL;
	$serial_num 		= NULL;
	$mfg_date 			= NULL;
	$weight_lbs 		= NULL;
	$voltage 			= NULL;
	$amp 				= NULL;
	$phase 				= NULL;
	$data_conn 			= NULL;
	$comp_air 			= NULL;
	$current_location 	= NULL;
	$machine_num 		= NULL;
	$rig_req 			= NULL;
	$comment 			= NULL;
	$acquisition_date 	= NULL;
}
$errors_array = array();
if(isset($_POST['submit'])) {
	if(isset($_POST['submit']) && $_POST['manufacturer'] == NULL) {
		$manufacturer_message = "Please add a Manufacturer. </br>";
	} else {$manufacturer_message = "";}
	if(isset($_POST['submit']) && $_POST['machine_desc'] == NULL) {
		$machine_desc_message = "Please add a Machine Description. </br>";
	} else {$machine_desc_message = "";}
	if(isset($_POST['submit']) && $_POST['serial_num'] == NULL) {
		$serial_num_message = "Please add a Serial Number. </br>";
	} else {$serial_num_message = "";}
	if(isset($_POST['submit']) && $_POST['phase'] == NULL) {
		$phase_message = "Please add a Phase. </br>";
	} else {$phase_message = "";}
	if(isset($_POST['submit']) && $_POST['current_location'] == NULL) {
		$current_location_message = "Please add a Current Location. </br>";
	} else {$current_location_message = "";}
	if(isset($_POST['submit']) && $_POST['machine_num'] == NULL) {
		$machine_num_message = "Please add a Machine Number. </br>";
	} else {$machine_num_message = "";}
	$message = "Error:</br> ".$manufacturer_message."".$machine_desc_message."".$serial_num_message."".$phase_message."".$current_location_message."".$machine_num_message."</br>";
}

//Create the machine
if(isset($_POST['submit']) && ($_POST['manufacturer'] != "") && ($_POST['machine_desc'] != "") && ($_POST['serial_num'] != "") && ($_POST['phase'] != "") && ($_POST['current_location'] != "") && ($_POST['machine_num'] != "")) {
	if ($machine = new Machine()) {
		$machine->newMachine();
		unset($_POST['submit']);
		unset($_SESSION['message']);
		unset($_POST['type']);
		unset($_POST['manufacturer']);
		unset($_POST['machine_desc']);
		unset($_POST['serial_num']);
		unset($_POST['mfg_date']);
		unset($_POST['weight_lbs']);
		unset($_POST['voltage']);
		unset($_POST['amp']);
		unset($_POST['phase']);
		unset($_POST['data_conn']);
		unset($_POST['comp_air']);
		unset($_POST['current_location']);
		unset($_POST['machine_num']);
		unset($_POST['rig_req']);
		unset($_POST['comment']);
		unset($_POST['acquisition_date']);
		$message = "The machine was successfully created.";
		redirect_to('view_all_machines.php');
	}
}*/
?>
<script language="javascript">
	function getSerials() {
		var type = document.getElementById("type").value;
		$(document).ready(function() {
    		$("#getSerials").load("../scripts/getSerials.php?type=" + type);
});

}
</script>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
</br>
<div class="container col-md-12 col-md-offset-0">
<div class="col-md-12 col-md-offset-0">
    <div class="center-text"><h2><i class="icon-plus-sign"></i> Resolution Form</h2></div>
    <h5><?php echo output_error($message); ?></h5>
    </br>
    <form class="form-horizontal" action="edit_resolution.php" method="post" role="form">
        <div class="form-group">
            <label for="summary">Work Summary</label>
         	<textarea class="form-control" rows="4" type="text" name="summary" id="summary" maxlength="1200" class="input-block-level" value="<?php if (isset($comment)) {echo htmlentities($comment);} ?>" placeholder="Required"></textarea>
        </div>
        <hr class="featurette-divider">
        <div class="row">
        	<div class="col-sm-12 col-sm-offset-0">		
                <div class="form-group">
                    <label for="sus_cause">Suspected Cause</label>
                        <input class="form-control" type="text" name="serial_num" placeholder="Required" maxlength="15" class="input-block-level" value="<?php if (isset($serial_num)) {echo htmlentities($serial_num);} ?>" required/>
                </div>
         	</div>
            <div class="col-sm-12 col-sm-offset-0">
                <div class="checkbox">
                    <label>
                        <input type="radio" value="unav"><p class="lead">Unavoidable</p>
                        <input type="radio" value="prev"><p class="lead">Preventable</p>
                        <input type="radio" value="cor_act"><p class="lead">Corrective Action Suggested</p>
                    </label>
                </div>
        	</div>
      	</div>
        <hr class="featurette-divider">
        <div class="form-group">
            <label for="details">Additional Notes</label>
                <textarea class="form-control" rows="5" type="text" name="comment" maxlength="256" class="input-block-level" value="<?php if (isset($comment)) {echo htmlentities($comment);} ?>" placeholder="Write details about the work being requested. Any details that you provide will speed up the resolution time. Parts missing, exact locations, description of cause, what parts may need to be ordered,...etc."></textarea>
     	</div>
        <div class="form-group">
        	<input class="btn btn-lg btn-primary btn-block btn-success" type="submit" name="submit" placeholder="Required" value="Submit Request">
      	</div>
	</form>
</div>
</div>
<?php include_layout_template('admin_footer.php'); ?>