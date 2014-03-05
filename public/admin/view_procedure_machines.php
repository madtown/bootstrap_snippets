<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
	global $database;
	$procedureClass = new Procedure();
	$machineClass = new Machine();
	$procedure_id = $database->escape_value($_GET['procedure_id']);
	$procedure = $procedureClass->find_by_id($procedure_id);
	$serials = $procedureClass->find_serials($procedure_id);
	$table = "procedure_serials";
	$sql = "SELECT * FROM procedure_serials WHERE procedure_name='".$procedure_id."' ORDER BY machine_num";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="col-md-12 col-md-offset-0">
	<div class="center-text"><h2><i class="icon-list"></i> <?php echo $procedure->procedure_name; ?> Machines</h2></div>
    <div class="center-text"><a class="btn btn-success" style='text-align:center;vertical-align:middle' href="edit_procedure_machines.php?procedure_id=<?php echo $procedure->id; ?>"><h4><i class="icon-plus"></i> Add Machine</h4></a></div>
	<?php echo output_message($message); ?>
	<table class="table table-striped table-hover">
	  <tr>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-cogs"></i> Machines</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-cog"></i> Type</th>
		<th style='text-align:center;vertical-align:middle'><i class="icon-trash"></i> Delete</td>
	  </tr>
	<?php do {
		if (isset($result['machine_serial'])) {
			$machine = $machineClass->find_by_serial_num($result['machine_serial']);
	?>
	  <tr>
	    <td style='text-align:center;'><h2><i class="icon-cog"></i> <?php echo $machine->machine_num; ?></h2><?php echo $machine->serial_num; ?></td>
	    <td style='text-align:center;'><h3><?php 
			$type_id = $machine->type_id; 
			$machine_type = $machineClass->find_type_by_id($type_id); 
			$type_array= $machine_type->fetch_assoc(); 
			echo $type_array['name']; 
			?></h3><?php echo $machine->machine_desc; ?></td>
		<td style='text-align:center;vertical-align:middle'><a class="btn btn-danger" style='text-align:center;vertical-align:middle' href="delete_machine_from_procedure.php?serial_num=<?php echo $machine->serial_num; ?>&procedure_id=<?php echo htmlentities($procedure_id); ?>" onclick="return confirm('Are you sure you want to delete <?php echo htmlentities($machine->machine_num); ?>? This action CANNOT be undone. You can still go back and re-add the machine to the procedure. This will NOT, however, affect the maintenance log. Proceed with deletion?');"><i class="icon-trash icon-4x"> </i></a></p>
		</td>
	  </tr>
	<?php }} while ($result=$query->fetch_assoc()); ?>
	</table>
    <a class="btn btn-info" style='text-align:center;vertical-align:middle' href="view_procedures.php"><i class="icon-chevron-left"></i> Return to View Procedures</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>
