<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>

<div class="container col-md-6 col-md-offset-0">
<div class="col-md-6 col-md-offset-0">	
<div class="center-text"><h2>View Machine Log</h2></div>
	<?php echo output_message($message); ?>
	<?php $machineClass->get_machine_log(); ?>
	</br>
	<a class="btn btn-success" href="edit_machines.php">Add Machines</a>
	</br>
	<a class="btn btn-info" href="view_all_machines.php">View All Machines</a>
</div>
</div>
<?php include_layout_template('admin_footer.php'); ?>
