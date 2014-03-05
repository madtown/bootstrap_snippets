<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
  // Find all the procedures
  	global $machineClass;
 	$photos = $machineClass->find_all_machine_types();
?>
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
	<?php foreach($photos as $photo): ?>
	  <tr>
		<td style='text-align:center;vertical-align:middle'><div class="center-text"><a class="btn btn-primary" href="view_steps.php?procedure_name=<?php echo htmlentities($photo->procedure_name); ?>"><img src="<?php echo $photo->image_path(); ?>" width="100"/></br><?php 
		$steps = new Step;
		$table = "maint_steps";
		$sql = "SELECT * FROM ".$table." WHERE procedure_name='".$photo->procedure_name."'";		
		$procedure_steps = $steps->find_by_sql($sql); 
		echo htmlentities(count($procedure_steps));
		?> Steps</a></div></td>
	    <td style='text-align:center;vertical-align:middle'><div class="center-text"><a href="view_steps.php?procedure_name=<?php echo htmlentities($photo->procedure_name); ?>"><?php echo $photo->procedure_name; ?></a></div></td>
	    <td style='text-align:center;vertical-align:middle'><h4><?php 	switch($photo->type_maint) {
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
	    <td style='text-align:center;vertical-align:middle'><h4><?php echo $photo->machine_type; ?></h4></td>
	    <td style='text-align:center;vertical-align:middle'><a class="btn btn-default" href="view_procedure_machines.php?procedure_name=<?php echo htmlentities($photo->procedure_name); ?>"><i class="icon-cogs icon-4x"></i></a></td>
		<td style='text-align:center;vertical-align:middle'><p><a class="btn btn-warning" style='text-align:center;vertical-align:middle' href="change_procedure.php?id=<?php echo $photo->id; ?>"><i class="icon-edit icon-4x"> </i></a>
		</td>
		<td style='text-align:center;vertical-align:middle'><a class="btn btn-danger" data-target="#<?php echo $photo->id; ?>" data-toggle="modal" style='text-align:center;vertical-align:middle'><i class="icon-trash icon-4x"> </i></a></p>
        <!-- Delete Warning Modal -->
<div id="<?php echo $photo->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <div class="alert alert-error">
            <h3 id="myModalLabel"><strong>Warning!</strong> You are about to delete <?php echo $photo->procedure_name; ?>!</h3>
        </div>
    </div>
    <div class="modal-body">
    	<div class="alert alert-error">        
        	<p>Are you sure you want to PERMANENTLY delete <?php echo $photo->procedure_name; ?>?</p></br>
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
            <p>To proceed with deletion, check the box below, and click <button class="btn btn-danger"><i class="icon-trash"></i></button>.</p>
    	</div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-small pull-left" data-dismiss="modal" aria-hidden="true"><h4><i class="icon-reply"></i> Nevermind</h4></button>
        <form onclick="proceed(this);" action="delete_procedure.php?id=<?php echo $photo->id; ?>" method="post">
          <input type="checkbox" name="understood" value="understood">I understand the consequences</input>
          <button class="btn btn-danger" type="submit" name="proceedButton" id="proceedButton" disabled><i class="icon-trash icon-3x"></i></button>
		</form>
    </div>
</div>
<!-- End Warning Modal -->
		</td>
	  </tr>
	<?php endforeach; ?>
	</table>
	<br />
</div>
<?php include_layout_template('admin_footer.php'); ?>
