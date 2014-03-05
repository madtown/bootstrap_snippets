<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
  // Find all the procedures
	$procedureClass = new Procedure();
 	$procedures = $procedureClass->find_all();
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="col-md-12 col-md-offset-0">
	<div class="center-text"><h2><i class="icon-list"></i> All Procedures</h2></div>
    <div class="center-text"><a class="btn btn-success" style='text-align:center;vertical-align:middle' href="edit_maintenance_procedure.php"><h4><i class="icon-plus"></i> New Procedure</h4></a></div>
    </br>

	<?php echo output_message($message); ?>
	<table class="table table-striped table-hover">
	  <tr>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-eye-open"></i> Preview</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-list"></i> Procedure</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-time"></i> Interval</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-cog"></i> Type</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-cogs"></i> Machines</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-edit"></i> Edit</th>
		<th style='text-align:center;vertical-align:middle'><i class="icon-trash"></i> Delete</td>
	  </tr>
	<?php foreach($procedures as $procedure): ?>
	  <tr>
		<td style='text-align:center;vertical-align:middle'><div class="center-text"><a class="btn btn-primary" href="view_steps.php?procedure_id=<?php echo htmlentities($procedure->id); ?>"><img src="<?php echo $procedure->image_path(); ?>" width="100"/></br><?php 
		$steps = new Step;
		$table = "maint_steps";
		$sql = "SELECT * FROM ".$table." WHERE procedure_name='".$procedure->id."'";		
		$procedure_steps = $steps->find_by_sql($sql); 
		echo htmlentities(count($procedure_steps));
		?> Steps</a></div></td>
	    <td style='text-align:center;vertical-align:middle'><div class="center-text"><p class="lead"><a href="view_steps.php?procedure_id=<?php echo htmlentities($procedure->id); ?>"><?php echo $procedure->procedure_name; ?></a></p></div></td>
	    <td style='text-align:center;vertical-align:middle'><h4><?php 	
					switch($procedure->type_maint) {
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
						case "1":
							echo "Emergency";
							break;
						case "2":
							echo "Special";
							break;
						case "3":
							echo "Other";
							break;
						default:
							echo "Custom";
							break;
					}
						?></h4></td>
	    <td style='text-align:center;vertical-align:middle'><h4><?php 
			$type_id = $procedure->machine_type; 
			$machine_type = $machineClass->find_type_by_id($type_id); 
			$type_array= $machine_type->fetch_assoc(); 
			echo $type_array['name']; 
			?></h4>
		</td>
	    <td style='text-align:center;vertical-align:middle'><a class="btn btn-default" href="view_procedure_machines.php?procedure_id=<?php echo htmlentities($procedure->id); ?>"><i class="icon-cogs icon-4x"></i></a></td>
		<td style='text-align:center;vertical-align:middle'><p><a class="btn btn-warning" style='text-align:center;vertical-align:middle' href="change_procedure.php?procedure_id=<?php echo $procedure->id; ?>"><i class="icon-edit icon-4x"> </i></a>
		</td>
		<td style='text-align:center;vertical-align:middle'><a class="btn btn-danger" data-target="#d<?php echo $procedure->id; ?>" data-toggle="modal" style='text-align:center;vertical-align:middle'><i class="icon-trash icon-4x"> </i></a></p>
        <?php //modal warning for deletion
			$id 			= 'd'.$procedure->id;
			$type 			= 1;
			$header_text	= '<strong>Warning!</strong> You are about to delete '.$procedure->procedure_name.'!';
			$body 			= '<p>Are you sure you want to PERMANENTLY delete '.$procedure->procedure_name.'?</p></br>
								<p><i class="icon-warning-sign"></i> <strong>Consequences</strong></p>
								<ul>
									<li>This action CANNOT be undone.</li>
									<li>All procedure connections will also be severed permanently.</li>
									<li>All associated photos and steps will be deleted permanently.</li>
									<li>When viewing the procedure details in the Maintenance Log you will not be able to see the instructions of the steps.</li>
								</ul>   
								<p><i class="icon-cloud"></i> <strong>Silver Lining</strong></p>
								<ul>             
									<li>This will NOT affect the maintenance log.</li> 
									<li>This will NOT delete notes if they are connected to this procedure.</li>
								</ul>
								<p>To proceed with deletion, check the box below, and click <button class="btn btn-danger"><i class="icon-trash"></i></button>.</p>';
			$link 			= 'delete_procedure.php?id='.$procedure->id;
			warningModal($id, $type, $header_text, $body, $link); 
		?>
        </td>
	  </tr>
	<?php endforeach; ?>
	</table>
    <script type="text/javascript">
	function proceed(form) {
	  var el, els = form.getElementsByTagName('input');
	  var i = els.length;
	  while (i--) {
		el = els[i];
	
		if (el.type == 'checkbox' && !el.checked) {
		  form.proceedButton.disabled = true;
		  return; 
		 }
	  }
	  form.proceedButton.disabled = false;
	}
	</script>
</div>
<?php include_layout_template('admin_footer.php'); ?>
