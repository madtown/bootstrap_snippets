<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
  // Find all the procedures
	$logClass = new Log();
 	$logs = $logClass->find_all();
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="span12 offset0 well">
	<div class="center-text"><h2>All Machines</h2></div>

	<?php echo output_message($message); ?>
	<table class="table table-striped table-hover">
	  <tr>
	    <th></th>
	    <th>Action</th>
	    <th>Username</th>
	    <th>Timestamp</th>
	    <th>Serial Number</th>
	    <th>Maintenance Type</th>
	    <th>IP Address</th>
	    <th>Database Action</th>
	    <th>Referring Page</th>
	    <th>User Rank</th>
	    <th>User Agent</th>
	    <th>PHP Self</th>
	    <th>Last Error</th>
	    <th>Table Affected</th>
	  </tr>
	<?php foreach($logs as $log): ?>
	  <tr>
	    <td><i class="icon-book icon-3x"> </i></td>
	    <td><?php echo $photo->machine_num; ?></td>
	    <td><?php echo $photo->machine_desc; ?></td>
		<td><?php //show serial numbers
			//{
			global $database;
			$table = "procedure_serials";
			$sql = "SELECT * FROM procedure_serials WHERE serial_num='".$photo->serial_num."' ORDER BY procedure_name";
			$query = $database->query($sql);
			$result = $query->fetch_assoc();
			do {
			echo trim($result['machine_serial'])."<br>";
			}
			while ($result=$query->fetch_assoc());
			//}
			?>
			</td>
	    <td><?php echo $photo->procedure_name; ?></td>
	    <td><?php echo $photo->type_maint; ?></td>
	    <td><?php echo $photo->machine_type; ?></td>
	    <td><p><a href="change_procedure.php?id=<?php echo $photo->id; ?>"><i class="icon-edit icon-3x"> </i></a><a href="delete_procedure.php?id=<?php echo $photo->id; ?>"><i class="icon-trash icon-3x"> </i></a></p>
		</td>
	  </tr>
	<?php endforeach; ?>
	</table>
	<br />
	<a href="edit_maintenance_procedure.php"><i class="icon-plus"></i> New Procedure</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>
