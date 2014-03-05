<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
  // Find all the procedures
	$procedureClass = new Procedure();
 	$photos = $procedureClass->find_all();
?>
<?php include_layout_template('navbar.php'); ?>
<?php include_layout_template('admin_header.php'); ?>
<div class="span12 offset0 well">
	<div class="center-text"><h2><i class="icon-envelope"></i> Notification Center</h2></div>
    <div class="center-text"><a class="btn btn-success" style='text-align:center;vertical-align:middle' href="edit_maintenance_procedure.php"><h4><i class="icon-plus"></i> New Procedure</h4></a></div>
    </br>

	<?php echo output_message($message); ?>
	<table class="table table-striped table-hover">
	  <tr>
	    <th>Preview</th>
	    <th>Procedure Name</th>
	    <th>Interval</th>
	    <th>Machine Type</th>
	    <th>Do Machine</th>
	    <th>Edit</th>
		<th>Delete</td>
	  </tr>
	<?php foreach($photos as $photo): ?>
	  <tr>
		<td><div class="center-text"><a href="view_steps.php?procedure_name=<?php echo htmlentities($photo->procedure_name); ?>"><img src="<?php echo $photo->image_path(); ?>" width="100"/></br><?php 
		$steps = new Step;
		$table = "maint_steps";
		$sql = "SELECT * FROM ".$table." WHERE procedure_name='".$photo->procedure_name."'";		
		$procedure_steps = $steps->find_by_sql($sql); 
		echo htmlentities(count($procedure_steps));
		?> Steps</a></div></td>
	    <td><div class="center-text"><a href="view_steps.php?procedure_name=<?php echo htmlentities($photo->procedure_name); ?>"><?php echo $photo->procedure_name; ?></a></div></td>
	    <td><?php 	switch($photo->type_maint) {
						case "36000":
							echo "Daily";
							break;
						case "770400":
							echo "Weekly";
							break;						
						case "2593740":
							echo "Monthly";
							break;
						case "31524000":
							echo "Annual";
							break;
						case "10":
							echo "Emergency";
							break;
						case "20":
							echo "Special";
							break;
						case "30":
							echo "Other";
							break;
						default:
							echo "Custom";
							break;
					}
						?></td>
	    <td><?php echo $photo->machine_type; ?></td>
	    <td><?php //show serial numbers
			//{
			global $connection;
			global $database;
			$table = "procedure_serials";
			$sql = "SELECT * FROM procedure_serials WHERE procedure_name='".$photo->procedure_name."' ORDER BY machine_serial";
			$query = $database->query($sql);
			$result = $query->fetch_assoc();
			do {
				global $connection;
				global $database;
				$sql2 = "SELECT * FROM equipment_log WHERE serial_num='".$result['machine_serial']."' ORDER BY machine_num";
				$query2 = $database->query($sql2);
				$result2 = $query2->fetch_assoc();
				?><p><a href="do_procedure.php?procedure_name=<?php echo $photo->procedure_name; ?>&serial_num=<?php echo $result['machine_serial']; ?>&type_maint=<?php echo $photo->type_maint; ?>"><i class="icon-wrench"></i> <?php echo trim($result2['machine_num'])?></a><br><?php
			}
			while ($result=$query->fetch_assoc());
			//}
			?>
		<td><p><a class="btn btn-warning" style='text-align:center;vertical-align:middle' href="change_procedure.php?id=<?php echo $photo->id; ?>"><i class="icon-edit icon-4x"> </i></a>
		</td>
		<td><a class="btn btn-danger" style='text-align:center;vertical-align:middle' href="delete_procedure.php?id=<?php echo $photo->id; ?>" onclick="return confirm('Are you sure you want to delete <?php echo $photo->procedure_name; ?>? This action CANNOT be undone. All steps will also be deleted permanently. This will NOT, however, affect the maintenance log. Proceed with deletion?');"><i class="icon-trash icon-4x"> </i></a></p>
		</td>
	  </tr>
	<?php endforeach; ?>
	</table>
	<br />
	<script>
	$(document).ready(function(){
	  $(":button").click(function(){
	   	$("#div1").load("demo_test.txt");
		  });
	  });
	});
	</script>
</div>
<?php include_layout_template('admin_footer.php'); ?>
