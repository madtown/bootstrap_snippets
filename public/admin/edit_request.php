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
    <div class="center-text"><h2><i class="icon-plus-sign"></i> New Maintenance Request</h2></div>
    <h5><?php echo output_error($message); ?></h5>
    </br>
    <form class="form-horizontal" action="edit_request.php" method="post" role="form">
    	<div class="row">
        	<div class="col-sm-7 col-sm-offset-0">		
                <div class="form-group">
                    <label for="short_desc">Short Description</label>
                        <input class="form-control" type="text" name="serial_num" placeholder="Required-Ex: Door missing on Punch" maxlength="15" class="input-block-level" value="<?php if (isset($serial_num)) {echo htmlentities($serial_num);} ?>" required/>
                </div>
         	</div>
            <div class="col-sm-4 col-sm-offset-1">
                
        	</div>
      	</div>
        <hr class="featurette-divider">
        <div class="center-text">
            <h3><p class="lead"><strong>Subject of Request</strong></br><small>Complete A OR B</small></p></h3>
        </div>
        <div class="row">
            <div class="col-sm-5 col-sm-offset-0">
            	<div class="center-text">
                    <h4><p class="lead">Option A</br><small>Machine(s)</small></p></h4>
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <p id="getTypes" onchange="getSerials()"></p>
                </div>
                <div class="form-group">
                    <label for="machine_serial">Machine Serial</label>
                    <p change="type" mutliple="multiple" id="getSerials"></p>
                </div>
            </div>
            <div class="col-sm-6 col-sm-offset-1">
            	<div class="center-text">
                    <h4><p class="lead">Option B</br><small>Other</small></p></h4>
                </div>
                <div class="form-group">
                    <label for="object">Describe what is to be worked on.</label>
                        <textarea class="form-control" rows="2" type="text" name="object" maxlength="100" class="input-block-level" value="<?php if (isset($comment)) {echo htmlentities($comment);} ?>" placeholder="Ex: Air hose SW corner"></textarea>
                </div>
            </div>
        </div>
        <hr class="featurette-divider">
        <div class="form-group">
            <label for="details">Details of Request</label>
                <textarea class="form-control" rows="5" type="text" name="comment" maxlength="256" class="input-block-level" value="<?php if (isset($comment)) {echo htmlentities($comment);} ?>" placeholder="Write details about the work being requested. Any details that you provide will speed up the resolution time. Parts missing, exact locations, description of cause, what parts may need to be ordered,...etc."></textarea>
     	</div>
        <hr class="featurette-divider">
        <div class="form-group">
                <label for="requested_turnaround">Requested Turnaround</label>
                <select class="form-control" name="requested_turnaround" id="requested_turnaround">
                    <option value="0" selected>Open</option> 
                    <option value="1">1 Day</option>
                    <option value="2">2 Days</option>
                    <option value="3">3 Days</option>
                    <option value="4">4 Days</option>
                    <option value="5">1 Week</option>
                </select>
      	</div>
        <hr class="featurette-divider">
        <div class="center-text">
            <h3><p class="lead"><strong>Severity</strong></p></h3>
            <div class="checkbox">
                    <label>
                        <input type="radio" name="severity" id="emergency" value="0"><p class="btn btn-danger lead">EMERGENCY MAINTENANCE</p>
                    </label>
                </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
            	<div class="center-text">
                    <h4><p class="lead text-primary">Minor</p></h4>
                </div>
                <div class="row">
            		<div class="col-sm-4">
                        <div class="checkbox center-text">
                            <label>
                                <input type="radio" name="severity" id="1" value="1"><p class="lead text-primary" style="font-size:35;">1</p>
                            </label>
                        </div>
                  	</div>
                   	<div class="col-sm-4">
                        <div class="checkbox center-text">
                            <label>
                                <input type="radio" name="severity" id="2" value="2"><p class="lead text-primary" style="font-size:35;">2</p>
                            </label>
                        </div>
                  	</div>
                   	<div class="col-sm-4">
                        <div class="checkbox center-text">
                            <label>
                                <input type="radio" name="severity" id="3" value="3"><p class="lead text-primary" style="font-size:35;">3</p>
                            </label>
                        </div>
                 	</div>
            	</div>
           	</div>
            <div class="col-sm-3">
            	<div class="center-text">
                    <h4><p class="lead text-success">Needs Attention</p></h4>
                </div>
        		<div class="row">
            		<div class="col-sm-4">
                        <div class="checkbox center-text">
                            <label>
                                <input type="radio" name="severity" id="4" value="4"><p class="lead text-success" style="font-size:35;">4</p>
                            </label>
                        </div>
                  	</div>
                   	<div class="col-sm-4">
                        <div class="checkbox center-text">
                            <label>
                                <input type="radio" name="severity" id="5" value="5"><p class="lead text-success" style="font-size:35;">5</p>
                            </label>
                        </div>
                  	</div>
                   	<div class="col-sm-4">
                        <div class="checkbox center-text">
                            <label>
                                <input type="radio" name="severity" id="6" value="6"><p class="lead text-success" style="font-size:35;">6</p>
                            </label>
                        </div>
                 	</div>
            	</div>
           	</div>
            <div class="col-sm-3">
            	<div class="center-text">
                    <h4><p class="lead text-warning">Major Problem</p></h4>
                </div>
        		<div class="row">
            		<div class="col-sm-4">
                        <div class="checkbox center-text">
                            <label>
                                <input type="radio" name="severity" id="7" value="7"><p class="lead text-warning" style="font-size:35;">7</p>
                            </label>
                        </div>
                  	</div>
                   	<div class="col-sm-4">
                        <div class="checkbox center-text">
                            <label>
                                <input type="radio" name="severity" id="8" value="8"><p class="lead text-warning" style="font-size:35;">8</p>
                            </label>
                        </div>
                  	</div>
                   	<div class="col-sm-4">
                        <div class="checkbox center-text">
                            <label>
                                <input type="radio" name="severity" id="9" value="9"><p class="lead text-warning" style="font-size:35;">9</p>
                            </label>
                        </div>
                 	</div>
            	</div>
           	</div>
            <div class="col-sm-3">
            	<div class="center-text">
                    <h4><p class="lead text-danger">Potentially Catastrophic</p></h4>
                </div>
        		<div class="row">
            		<div class="col-sm-4">
                        <div class="checkbox center-text">
                            <label>
                                <input type="radio" name="severity" id="10" value="10"><p class="lead text-danger" style="font-size:35;">10</p>
                            </label>
                        </div>
                  	</div>
                   	<div class="col-sm-4">
                        <div class="checkbox center-text">
                            <label>
                                <input type="radio" name="severity" id="11" value="11"><p class="lead text-danger" style="font-size:35;">11</p>
                            </label>
                        </div>
                  	</div>
                   	<div class="col-sm-4">
                        <div class="checkbox center-text">
                            <label>
                                <input type="radio" name="severity" id="12" value="12"><p class="lead text-danger" style="font-size:35;">12</p>
                            </label>
                        </div>
                 	</div>
            	</div>
           	</div>
        <div class="form-group">
        	<input class="btn btn-lg btn-primary btn-block btn-success" type="submit" name="submit" placeholder="Required" value="Submit Request">
      	</div>
	</form>
</div>
</div>
<?php include_layout_template('admin_footer.php'); ?>