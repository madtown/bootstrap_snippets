<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 3 ) { 
	$_SESSION['message'] = "You are not authorized to edit maintenance procedures.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
if(isset($_POST['submit']) && $_POST['name'] != "") {
	if ($machine = new Machine()) {
		$machine->newEquipmentType();
		$message = "The procedure was successfully created.";
		unset($_POST['submit']);
		unset ($_SESSION['message']);
		unset($_POST['name']);
		unset($_POST['type']);
		redirect_to('manage_machines.php');
	 }} else {
		$_SESSION['message'] = "Please select all fields.";
		}
?>

<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<script language="javascript">
	function getTypes() {
		$(document).ready(function() {
    		$("#getTypes").load("../scripts/getTypes.php");
});

}
</script>
<div class="container col-md-6 col-md-offset-0">
    <div class="col-md-6 col-md-offset-0">
        <div class="center-text"><h2><i class="icon-plus-sign"></i> New Machine Type</h2></div>
        <p class="text-error"><?php echo output_message($message); 
        unset($_SESSION['message']);
        ?></p>
        <form class="form-horizontal" action="edit_equipment_type.php" method="post" enctype="multipart/form-data" role="form">
            <div class="form-group">
                <label for="name">Type Name</br><small class="text-danger"><strong>Cannot be changed later and should be unique.</strong></small></label>
                <input class="form-control" type="text" name="name" id="name" maxlength="30" placeholder="Required-Example: Punch, Brake, Laser..." onchange="getTypes()" required>
                <script>
				$('input#name').maxlength({
					alwaysShow: false,
					threshold: 25,
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
                <label for="type">Reference Current Types:</label>
                <p change="name" id="getTypes"></p>
            </div>
          	<div class="form-group">
            	<button class="btn btn-success btn-block" type="submit" name="submit" value="Submit"><i class="icon-plus-sign"></i> Equipment Type</button>
            </div>
        </form>
    </div>
	<a class="btn btn-info" href="manage_machines.php"><i class="icon-chevron-left"></i> Return to Machine Dashboard</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>