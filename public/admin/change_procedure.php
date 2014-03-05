<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 3 ) { 
	$_SESSION['message'] = "You are not authorized to edit maintenance procedures.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
if (isset($_GET['procedure_id']) and $_GET['procedure_id'] != "") {
	$procedureClass = new Procedure;
	$procedure_id = $_GET['procedure_id'];
	$procedure = $procedureClass->find_by_id($procedure_id);
}
if (isset($_POST['submit']) && $_POST['procedure_id'] != "") {
	if ($procedure_updated = new Procedure()) {
		$procedure_updated->updateProcedure();
		unset($_POST['submit']);
		unset($_POST['procedure']);
		unset($_POST['type']);
		unset($_FILES["file"]["name"]);
		$_SESSION['message'] = "The procedure was successfully updated.";
		redirect_to('view_procedures.php');
	 }} else {
		$_SESSION['message'] = "Please select all fields.";
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
<div class="container">
	<div class="col-md-12 col-md-offset-0">
		<div class="center-text"><h2>Edit Procedure</h2></div>
			<?php echo output_message($message); 
			unset($_SESSION['message']);
			?>
		<form action="change_procedure.php?procedure_id=<?php echo htmlentities($procedure_id)?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="control-label" for="procedure_name">Cannot Edit Procedure Name</label>
                <div class="input-group">
                	<input type="text" name="procedure_names" id="procedure_names" placeholder="Required" value="<?php echo htmlentities($procedure['procedure_name'])?>" disabled>
                </div>
            </div>
            <div class="form-group">
				<input type="hidden" name="procedure_name" id="procedure_name" value="<?php echo htmlentities($procedure['procedure_name'])?>">
         	</div>
			<div class="form-group">
                <label for="type">Type</label>
                <p id="getTypes" onchange="getSerials()"></p>
            </div>
            <div class="form-group">
                <label for="machine_serial">Machine Number</label>
                <p change="type" mutliple="multiple" id="getSerials"></p>
                <span class="help-block">ADD a Machine Serial <a href="view_procedure_machines.php?procedure_id=<?php echo htmlentities($procedure_id); ?>">(Click here to delete a serial)</a></span>
            </div>
            <div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
            <span class="help-block">Change Procedure Image(if unchosen the image will stay the same)</span>
                <div class="center-text">
                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;"></div>
                    <div>
                        <span class="btn btn-primary btn-block btn-file">
                            <span class="fileinput-new"><i class="icon-camera"></i> Change Picture</span>
                            <span class="fileinput-exists"><i class="icon-refresh"></i> Change Again</span>
                            <input type="file" name="file" id="file">
                        </span>
                        <a href="#" class="btn btn-danger btn-block fileinput-exists" data-dismiss="fileinput"><i class="icon-trash"></i> Remove</a>
                    </div>
                </div>
            </div>
                    <input type="hidden" name="procedure_id" id="procedure_id" value="<?php echo htmlentities($_GET['procedure_id']); ?>">
          	<div class="form-group">
                <button class="btn btn-success btn-block" type="submit" name="submit" value="Submit">Update Procedure</button>
          	</div>
		</form>
	<a class="btn btn-info" style='text-align:center;vertical-align:middle' href="view_procedures.php"><i class="icon-chevron-left"></i> Return to View Procedures</a>
    </div>
</div>
<?php include_layout_template('admin_footer.php'); ?>