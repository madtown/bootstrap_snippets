<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
  // Find all the procedures
  	$procedureClass = new Procedure();
	global $stepClass;
	unset($_POST['submit']);
	$stepClass = new Step();
	$procedure_id = $_GET['procedure_id'];
	$procedure = $procedureClass->find_procedure_by_id($procedure_id);
 	$steps = $stepClass->find_by_procedure($procedure_id);
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="col-md-12 col-md-offset-0">
	<div class="center-text"><h1><?php echo $procedure['procedure_name']; ?> Steps</h1></div>

	<?php echo output_message($message); ?>
	<table class="table table-striped table-hover">
	  <tr>
	    <th>Preview</th>
	    <th>Step Number</th>
	    <th>Instruction</th>
	    <th>Author</th>
	    <th>Edit</th>
	    <th>Delete</th>
	  </tr>
	<?php foreach($steps as $step): ?>
	  <tr>
	    <td><a href="<?php echo $step->image_path_step(); ?>"><img src="<?php echo $step->image_path_step(); ?>" width="100"/></a></td>
	    <td><div class="center-text"><h1><?php echo $step->step_num; ?></h1></div></td>
	    <td><?php echo $step->instruction; ?></td>
	    <td><h4><?php echo $step->username_step; ?></h4></td>
		<td><p><a class="btn btn-warning" style='text-align:center;vertical-align:middle' href="change_step.php?id=<?php echo $step->id; ?>&procedure_id=<?php echo $_GET['procedure_id']; ?>"><i class="icon-edit icon-4x"> </i></a></p>
		</td>
		<td><p><a class="btn btn-danger" style='text-align:center;vertical-align:middle' href="delete_step.php?id=<?php echo $step->id; ?>&procedure_id=<?php echo $_GET['procedure_id']; ?>" onclick="return confirm('Are you sure you want to delete <?php echo $step->step_num; ?>? This action CANNOT be undone. This will NOT, however, affect the maintenance log. Proceed with deletion?');"><i class="icon-trash icon-4x"> </i></a></p>
		</td>
	  </tr>
	<?php endforeach; ?>
	</table>
	<br />
	<a class="btn btn-success" style='text-align:center;vertical-align:middle' href="edit_maintenance_steps.php?procedure_id=<?php echo $_GET['procedure_id']; ?>"><i class="icon-plus"></i> New Step</a>
 	<a class="btn btn-info" style='text-align:center;vertical-align:middle' href="view_procedures.php?procedure_id=<?php echo $_GET['procedure_id']; ?>"><i class="icon-eye-open"></i> View Procedures</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>