<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 3 ) { 
	$_SESSION['message'] = "You are not authorized to edit maintenance procedures.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
if (isset($_GET['id']) and $_GET['id'] != "") {
	$machineClass = new Machine;
	$id = $_GET['id'];
	$machine_type = $machineClass->find_type_by_id($id);
}
if (isset($_POST['submit']) && $_POST['id'] != "") {
	if ($machine_type_updated = new Machine()) {
		$machine_type_updated->updateEquipmentType();
		unset($_POST['submit']);
		unset($_POST['name']);
		unset($_POST['description']);
		$_SESSION['message'] = "The machine type was successfully updated.";
		redirect_to('manage_machines.php');
	 }} else {
		$_SESSION['message'] = "Please select all fields.";
		}
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container col-md-6 col-md-offset-0">
    <div class="col-md-6 col-md-offset-0">
        <div class="center-text"><h2><i class="icon-plus-sign"></i> New Machine Type</h2></div>
        <p class="text-error"><?php echo output_message($message); 
        unset($_SESSION['message']);
        $machine_type_array = $machine_type->fetch_assoc();
		?></p>
        <form class="form-horizontal" action="change_machine_type.php?id=<?php echo htmlentities($machine_type_array['id']); ?>" method="post" enctype="multipart/form-data" role="form">
            <div class="form-group">
                <label for="oldname">Current Type Name</label></br>
                <input class="form-control" type="text" name="oldname" id="oldname" value="<?php echo htmlentities($machine_type_array['name'])?>" disabled>
          	</div>
            <div class="form-group">
                <label for="name">New Type Name</label></br>
                <input class="form-control" type="text" name="name" id="name" maxlength="15" placeholder="Required-Example: Punch, Brake, Laser..." value="<?php echo htmlentities($machine_type_array['name'])?>" required>
                <script>
							$('input#name').maxlength({
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
                <input class="form-control" type="hidden" name="id" id="id" value="<?php echo htmlentities($machine_type_array['id'])?>" required>
          	</div>
          	<div class="form-group">
            	<button class="btn btn-success btn-block" type="submit" name="submit" value="Submit">Update Equipment Type</button>
            </div>
        </form>
        <a class="btn btn-info" href="manage_machines.php"><i class="icon-chevron-left"></i> Return to Machine Dashboard</a>
    </div>
</div>
<?php include_layout_template('admin_footer.php'); ?>