<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 4 ) { 
	$_SESSION['message'] = "You are not authorized to edit machines.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
?>
<?php
//Move the machine
global $machineClass;
if(isset($_POST['submit']) && ($_POST['current_location'] != "")) {
	if ($machineClass) {
		$machineClass->moveMachine();
		$machine = $machineClass->find_by_serial_num($_GET['serial_num']);
		unset($_POST['submit']);
		unset($_SESSION['message']);
		unset($_POST['location_id']);
		$message = "The machine was successfully moved.";
		redirect_to('view_all_machines.php');
	}
}
$machine = $machineClass->find_by_serial_num($_GET['serial_num']);
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container col-md-12 col-md-offset-0">
<div class="col-md-12 col-md-offset-0">
		   	<div class="center-text"><h2><i class="icon-cog"></i> Move <?php echo $machine->machine_num; ?> </h2><h5><?php echo output_error($message); ?></h5>
       		</div>
 	<form class="form-horizontal" action="move_machine.php?serial_num=<?php echo htmlentities($_GET['serial_num']); ?>" method="post">
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
        <input class="btn btn-block btn-success" type="submit" name="submit" placeholder="Required" value="Update Machine"/>
	</form>
</div>
</div>
<?php include_layout_template('admin_footer.php'); ?>