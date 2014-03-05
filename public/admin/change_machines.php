<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 2 ) { 
	$_SESSION['message'] = "You are not authorized to edit machines.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
?>
<?php
//Set default fields

//Create the machine
if(isset($_POST['submit']) && ($_POST['manufacturer'] != "") && ($_POST['machine_desc'] != "") && ($_POST['serial_num'] != "") && ($_POST['phase'] != "") && ($_POST['current_location'] != "") && ($_POST['machine_num'] != "")) {
	if ($machine = new Machine()) {
		$machine->updateMachine();
		global $machineClass;
		$idmachine = $machineClass->find_by_id($_GET['id']);
		unset($_POST['submit']);
		unset($_SESSION['message']);
		unset($_POST['type_id']);
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
		unset($_POST['location_id']);
		unset($_POST['machine_num']);
		unset($_POST['rig_req']);
		unset($_POST['comment']);
		unset($_POST['acquisition_date']);
		$message = "The machine was successfully updated.";
		redirect_to('view_all_machines.php');
	}
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

if (isset($_GET['id'])) {
	global $machineClass;
	$idmachine = $machineClass->find_by_id($_GET['id']);
	$manufacturer = 		$idmachine->manufacturer;
	$type_id = 				$idmachine->type_id;
	$machine_desc =	 		$idmachine->machine_desc;
	$serial_num =			$idmachine->serial_num;
	$mfg_date = 			$idmachine->mfg_date;
	$weight_lbs = 			$idmachine->weight_lbs;
	$voltage = 				$idmachine->voltage;
	$amp = 					$idmachine->amp;
	$phase = 				$idmachine->phase;
	$data_conn = 			$idmachine->data_conn;
	$comp_air = 			$idmachine->comp_air;
	$location_id = 			$idmachine->location_id;
	$machine_num = 			$idmachine->machine_num;
	$rig_req = 				$idmachine->rig_req;
	$comment = 				$idmachine->comment;
	$acquisition_date = 	$idmachine->acquisition_date;
} 
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
</br>
<div class="container col-md-12 col-md-offset-0">
<div class="col-md-12 col-md-offset-0">
		   	<div class="center-text"><h2><i class="icon-cog"></i> Edit <?php echo $idmachine->machine_num; ?> </h2><h5><?php echo output_error($message); ?></h5>
       		</div>
 	<form class="form-horizontal" action="change_machines.php?id=<?php echo htmlentities($_GET['id']); ?>" method="post">
        <div class="form-group">
                <label for="type_id">Type</label>
                <p id="getTypes"></p>
            </div>
        <div class="form-group">
            <label for="manufacturer">Manufacturer</label>
            <input class="form-control" type="text" name="manufacturer" id="manufacturer" placeholder="Required" maxlength="25" class="input-block-level" value="<?php if (isset($manufacturer)) {echo htmlentities($manufacturer);} ?>"/>
            <script>
			$('input#manufacturer').maxlength({
				alwaysShow: false,
				threshold: 15,
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
            <label for="machine_desc">Machine Description</label>
            <input class="form-control" type="text" name="machine_desc" id="machine_desc" placeholder="Required" maxlength="15" class="input-block-level" value="<?php if (isset($machine_desc)) {echo htmlentities($machine_desc);} ?>"/>
            <script>
			$('input#machine_desc').maxlength({
				alwaysShow: false,
				threshold: 10,
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
            <label for="serial_num">Serial Number</label>
            <input class="form-control" type="text" name="serial_num" placeholder="Required" maxlength="15" class="input-block-level" value="<?php if (isset($serial_num)) {echo htmlentities($serial_num);} ?>" disabled/>
        </div>
        <div class="form-group">
        	<label for="mfg_date">Manufacture Date</label>
        	<div class="input-group">
                <span class="input-group-addon"><i class="icon-calendar"></i></span>
                <input class="form-control" type="text" name="mfg_date" id="mfg_date" maxlength="256" class="input-block-level" value="<?php if (isset($mfg_date)) {echo htmlentities($mfg_date);} ?>"/>
                <script>
				$('input#mfg_date').maxlength({
					alwaysShow: false,
					threshold: 100,
					warningClass: "label label-success",
					limitReachedClass: "label label-danger",
					placement: 'top',
					preText: 'Used ',
					separator: ' of ',
					postText: ' chars.'
				});
				</script>
        	</div>
        </div>
        <div class="form-group">
        	<label for="weight_lbs">Weight</label>
            <div class="input-group">
                <input class="form-control" type="text" name="weight_lbs" id="weight_lbs" maxlength="10" class="input-block-level" value="<?php if(isset($weight_lbs)) {echo htmlentities($weight_lbs); } ?>" />
                <span class="input-group-addon">lbs</span>
                </div>
                <script>
				$('input#weight_lbs').maxlength({
					alwaysShow: false,
					threshold: 5,
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
        	<label for="voltage">Voltage</label>
        	<div class="input-group">
                <span class="input-group-addon">&#9650; V</span>
                <select class="form-control" name="voltage"> 
                <option <?php if (isset($voltage) and $voltage == 110) {echo "selected='selected'";} ?> value="110">110</option>
                <option <?php if (isset($voltage) and $voltage == 200) {echo "selected='selected'";} ?> value="200">200</option>
                <option <?php if (isset($voltage) and $voltage == 220) {echo "selected='selected'";} ?> value="220">220</option>
                <option <?php if (isset($voltage) and $voltage == 230) {echo "selected='selected'";} ?> value="230">230</option>
                <option <?php if (isset($voltage) and $voltage == 250) {echo "selected='selected'";} ?> value="250">250</option>
                <option <?php if (isset($voltage) and $voltage == 480) {echo "selected='selected'";} ?> value="480">480</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="amp">Amperage</label>
            <div class="input-group">
                <span class="input-group-addon">I</span>
                <select class="form-control" name="amp"> 
                <option <?php if (isset($amp) and $amp == 15) {echo "selected='selected'";} ?> value="15">15</option>
                <option <?php if (isset($amp) and $amp == 20) {echo "selected='selected'";} ?> value="20">20</option>
                <option <?php if (isset($amp) and $amp == 30) {echo "selected='selected'";} ?> value="30">30</option>
                <option <?php if (isset($amp) and $amp == 50) {echo "selected='selected'";} ?> value="50">50</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="phase">Phase</label>
            <input class="form-control" type="text" name="phase" placeholder="Required" maxlength="1" class="input-block-level" value="<?php if (isset($phase)) {echo htmlentities($phase);} ?>" />
        </div>
        <div class="form-group">
            <label for="data_conn">Data Connection</label> 
            <div class="input-group">                
           		<span class="input-group-addon"><i class="custom-plug"></i></span>  
                <select class="form-control" name="data_conn"> 
                    <option <?php if (isset($data_conn) and $data_conn == "y") {echo "selected='selected'";} ?> value="y">Yes</option>
                    <option <?php if (isset($data_conn) and $data_conn == "n") {echo "selected='selected'";} ?> value="n">No</option>
                </select>
          	</div>
        </div>
        <div class="form-group">
            <label for="comp_air">Compressed Air</label>    
            <select class="form-control" class="form-control" name="comp_air"> 
                <option <?php if (isset($comp_air) and $comp_air == "y") {echo "selected='selected'";} ?> value="y">Yes</option>
                <option <?php if (isset($comp_air) and $comp_air == "n") {echo "selected='selected'";} ?> value="n">No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="current_location">Current Location</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-map-marker"></i></span>
                <select class="form-control" type="text" name="current_location" id="current_location" class="input-block-level">
                    <?php include('../scripts/locationSelect.php');?>
                </select>
                <script>
                    function format(current_location) {
                        var originalOption = current_location.element;
                        return "" + current_location.text + "";
                    }
                    $("#current_location").select2({
                        placeholder: "Select Location",
                        allowClear: true,
                        formatResult: format,
                        formatSelection: format,
                        escapeMarkup: function(m) { return m; }
                    });
                </script>
            </div>
        </div>
        <div class="form-group">
            <label for="machine_num">Machine Number</label>
            <input class="form-control" type="text" name="machine_num" id="machine_num" placeholder="Required" maxlength="30" class="input-block-level" value="<?php if (isset($machine_num)) {echo htmlentities($machine_num);} ?>" />
            <script>
			$('input#machine_num').maxlength({
				alwaysShow: false,
				threshold: 20,
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
            <label for="rig_req">Rigger Required</label>
            <select class="form-control" name="rig_req"> 
                <option <?php if (isset($rig_req) and $rig_req == "y") {echo "selected='selected'";} ?> value="y">Yes</option>
                <option <?php if (isset($rig_req) and $rig_req == "n") {echo "selected='selected'";} ?> value="n">No</option>
                <option <?php if (isset($rig_req) and $rig_req == "N/A") {echo "selected='selected'";} ?> value="N/A">Not Applicable</option>
            </select>
        </div>
        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea class="form-control" rows="4" type="text" name="comment" id="comment" maxlength="256" class="input-block-level" placeholder="Write comments about the machine. Acquisition condition, special features, notes, etc..."><?php if (isset($comment)) {echo htmlentities($comment);} ?></textarea>
            <script>
			$('textarea#comment').maxlength({
				alwaysShow: false,
				threshold: 100,
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
        	<label for="acquisition_date">Acquisition Date</label>
            <div class='input-group date' id='acquisition_date'>
                <input class="form-control" type="text" name="acquisition_date" id="acquisition_date" maxlength="15" class="input-block-level" value="<?php if (isset($acquisition_date)) {echo htmlentities($acquisition_date);} ?>"/>
                    <span class="input-group-addon">
                        <span class="icon-calendar"></span>
                    </span>
                </div>
            </div>
            <script type="text/javascript">
                $(function () {
                        $('#acquisition_date').datetimepicker({
                        pickTime: false,	// set a maximum date
                    });
                });
            </script>
            </div>
        </div>
        <div class="form-group">
        	<input class="form-control" type="hidden" name="id" maxlength="15" value="<?php echo htmlentities($_GET['id']); ?>"/>
        </div>
        <div class="form-group">
            <input class="form-control" type="hidden" name="serial_num" placeholder="Required" maxlength="15" class="input-block-level" value="<?php if (isset($serial_num)) {echo htmlentities($serial_num);} ?>"/>
        </div>
        <input class="btn btn-block btn-success" type="submit" name="submit" placeholder="Required" value="Update Machine"/>
	</form>
</div>
</div>
<?php include_layout_template('admin_footer.php'); ?>