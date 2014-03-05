<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 3 ) { 
	$_SESSION['message'] = "You are not authorized to edit maintenance procedures.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
if (isset($_GET['id']) and $_GET['id'] != "") {
	$procedureClass = new Procedure;
	$id = $_GET['id'];
	$procedure = $procedureClass->find_by_id($id);
}
if (isset($_POST['submit']) && $_POST['id'] != "") {
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>
<script language="javascript">
	function getSerials() {
		var type = document.getElementById("type").value;
		$(document).ready(function() {
    		$("#getSerials").load("../scripts/getSerials.php?type=" + type);
});
}
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.multiselect').multiselect({
			width: 'auto',
		});
	});
</script>
<div class="container">
	<table class="table"><tr><td>
	<div class="span6 offset0 well">
		<div class="center-text"><h2>Edit Procedure</h2></div>
			<?php echo output_message($message); 
			unset($_SESSION['message']);
			?>
		<form action="" method="post" enctype="multipart/form-data">
			<label for="procedure_name">Cannot Edit Procedure Name:</label>
				<input type="text" name="procedure_name" id="procedure_name" placeholder="Required" value="<?php echo htmlentities($procedure->procedure_name)?>" disabled>
				<input type="hidden" name="procedure_name" id="procedure_name" value="<?php echo htmlentities($procedure->procedure_name)?>">
				<br>
			<label for="type_maint">Maintenance Type:</label>
				<table><td>
					<select name="type_maint"> 
					<option value="36000">Daily</option>
					<option value="2592000">Monthly</option>
					<option value="31556926">Annual</option>
					<option value="1">Emergency</option>
					<option value="2">Special</option>
					<option value="3">Other</option>			
					</select>
				</td></table>				
				<br>
			<label for="type">Machine Type:</label>
				<table><td><select name="type" id="type" onchange="getSerials()" required>
					<option value="None Selected">None Selected</option> 
					<option<?php
					$machine_type = $procedure->machine_type;
					if ($machine_type == "Brake") {	
						echo " selected='";
						echo htmlentities($machine_type);
						echo "'";
						} ?> 
					value="Brake">Brake</option>
					<option<?php
					$machine_type = $procedure->machine_type;
					if ($machine_type == "Punch") {	
						echo " selected='";
						echo htmlentities($machine_type);
						echo "'";
						} ?> 
					value="Punch">Punch</option>
					<option<?php
					$machine_type = $procedure->machine_type;
					if ($machine_type == "Insertion") {	
						echo " selected=";
						echo htmlentities($machine_type);
						echo "'";
						} ?> 
					value="Insertion">Insertion</option>
					<option<?php
					$machine_type = $procedure->machine_type;
					if ($machine_type == "Laser") {	
						echo " selected='";
						echo htmlentities($machine_type);
						echo "'";
						} ?> 
					value="Laser">Laser</option>
					<option<?php
					$machine_type = $procedure->machine_type;
					if ($machine_type == "BM") {	
						echo " selected='";
						echo htmlentities($machine_type);
						echo "'";
						} ?> 
					value="BM">BM</option>
					<option<?php
					$machine_type = $procedure->machine_type;
					if ($machine_type == "LUL") {	
						echo " selected='";
						echo htmlentities($machine_type);
						echo "'";
						} ?> 
					value="LUL">LUL</option>
					<option<?php
					$machine_type = $procedure->machine_type;
					if ($machine_type == "Other") {	
						echo " selected='";
						echo htmlentities($machine_type);
						echo "'";
						} ?> 
					value="Other">Other</option>			
					</select>
				</td></table>
				<br>
				<table><td>
			<label for="machine_serial">ADD a Machine Serial <a href="view_procedure_machines.php?procedure_name=<?php echo htmlentities($procedure->procedure_name); ?>">(Click here to delete a serial)</a>:</label>
				<p change="type" mutliple="multiple" id="getSerials"></p>
				</td></table>
				<br>
			<label for="file">Change Procedure Image: </br>(if unchosen the image will stay the same)</label><br/>
				<table><td>
					<input type="file" name="file" id="file">
				</td></table>
				<br>
				<input type="hidden" name="id" id="id" value="<?php echo htmlentities($_GET['id']); ?>">
			<button class="btn btn-success btn-block" type="submit" name="submit" value="Submit">Update Procedure</button>
		</form>
	</div>
	</td><td>
	<div class="span7 offset0 well pull-left">
		<?php $machineClass->get_machine_info(); ?>	
	</div>
	</td></tr>
	</table>
	<a class="btn btn-info" style='text-align:center;vertical-align:middle' href="view_procedures.php"> Return to View Procedures</a>
</div>
<br>
</br>	
</br>
<?php include_layout_template('admin_footer.php'); ?>