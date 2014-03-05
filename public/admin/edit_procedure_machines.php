<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 3 ) { 
	$_SESSION['message'] = "You are not authorized to edit maintenance procedures.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
if(isset($_POST['submit']) && $_POST['procedure_id'] != "") {
	if ($procedure = new Procedure()) {
		$procedure->add_procedure_machine();
		$message = "The machine was successfully added.";
		unset($_POST['submit']);
		unset ($_SESSION['message']);
		unset($_POST['procedure']);
		unset($_POST['type']);
		unset($_POST['machine_serial']);
		redirect_to('view_procedures.php');
	 }} else {
		$_SESSION['message'] = "Please select all fields.";
		}
$procedure_id = $_GET['procedure_id'];
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
    	<div class="center-text"><h2><i class="icon-plus-sign"></i> Add Machine</h2></div>
			<?php echo output_message($message); 
			unset($_SESSION['message']);
			?>
		<form class="form-horizontal" action="edit_procedure_machines.php?procedure_id=<?php echo htmlentities($procedure_id);?>" method="post" enctype="multipart/form-data" role="form">
            <input class="form-control" type="hidden" name="procedure_id" id="procedure_id" value="<?php echo htmlentities($procedure_id);?>" required>
          	<div class="form-group">
                    <label for="type">Type</label>
                    <p id="getTypes" onchange="getSerials()"></p>
                </div>
                <div class="form-group">
                    <label for="machine_serial">Machine Number</label>
                    <p change="type" mutliple="multiple" id="getSerials"></p>
                </div>
          	<div class="form-group">
            	<button class="btn btn-success btn-block" type="submit" name="submit" value="Submit"><i class="icon-plus-sign"></i> Add Machine</button>
            </div>
		</form>
	</div>
	<a class="btn btn-info" href="view_procedures.php"><i class="icon-chevron-left"></i> Return to View Procedures</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>