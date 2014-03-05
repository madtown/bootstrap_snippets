<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php 
	if (isset($_POST['submit']) and $_POST['type'] != "" and $_POST['machine_serial'] != "") {
		global $database;
		$type = trim($_POST['type']);
		$machine_serial = $_POST['machine_serial'];
		$URL = "schedule_by_machine.php?serial_num=".$machine_serial['0'];
		redirect_to($URL);
	} else { //FORM IS UNSUBMITTED
		if (isset($_POST['submit'])) {
			$_SESSION['message'] = "Must select from BOTH fields.";
			/*01*/ $type = "";
			/*02*/ $machine_num = "";
		}
	}
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<script language="javascript">
	function getSerials() {
		var type = document.getElementById("type").value;
		$(document).ready(function() {
    		$("#getSerials").load("../scripts/getSerials.php?type=" + type);
});
}
</script>
<div class="container col-md-12 col-md-offset-0">
<div class="col-md-12 col-md-offset-0">
	<div class="center-text"><h2><i class="icon-search"></i> Machine Search</h2></div>
	<?php echo output_message($message); ?>
	<form class="form-horizontal" action="search_procedure.php" method="post" role="form">
		<div class="form-group">
            <label for="type">Type</label>
            <p id="getTypes" onchange="getSerials()"></p>
        </div>
		<div class="form-group">
            <label for="machine_serial">Machine Number</label>
            <p change="type" mutliple="multiple" id="getSerials"></p>
        </div>
		<div class="form-group">
			<button class="btn btn-success btn-block" type="submit" name="submit" value="Submit">Display Machine Procedures</button>
		</div>
	</form>
</div>
<div>	
<a class="btn btn-info" href="edit_machines.php"><i class="icon-plus-sign"></i> Add Machines</a>
</br>
<a class="btn btn-default" href="view_all_machines.php"><i class="icon-cogs"></i> View All Machines</a>
</div>
</div>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
		</script>
		<script language="javascript">
			function getMachines() {
				var type = document.getElementById("machine_type").value;
				$(document).ready(function() {
		    		$("#getMachines").load("../../public/scripts/getMachines.php?type=" + type);
		});

		}
		</script>
<?php include_layout_template('admin_footer.php'); ?>
