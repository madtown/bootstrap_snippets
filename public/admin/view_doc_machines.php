<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
	global $database;
	$documentClass = new Document();
	$id = $_GET['id'];
	$document = $documentClass->find_by_id($id);
	$results = $documentClass->find_serials($id);
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="col-md-12 col-md-offset-0">
	<div class="center-text"><h2><i class="icon-copy"></i> <?php echo $document['0']->doc_name; ?> linked Machines</h2></div>
    <div class="center-text"><a class="btn btn-success" style='text-align:center;vertical-align:middle' href="edit_doc_machines.php?id=<?php echo $id; ?>"><h4><i class="icon-plus"></i> Add Machine</h4></a></div>
    </br>

	<?php echo output_message($message); ?>
	<table class="table table-striped table-hover">
	  <tr>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-cogs"></i> Machines</th>
		<th style='text-align:center;vertical-align:middle'><i class="icon-unlink"></i> Unlink</td>
	  </tr>
	<?php foreach($results as $result):
		global $machineClass;
		$serial_num = $result->machine_serial;
		$machine = $machineClass->find_by_serial_num($serial_num);
	?>
	  <tr>
	    <td style='text-align:center;'><h2><i class="icon-cog"></i> <?php echo $machine->machine_num; ?></h2><?php echo $machine->serial_num; ?></td>
		<td style='text-align:center;vertical-align:middle'><a class="btn btn-danger" style='text-align:center;vertical-align:middle' href="delete_machine_from_doc.php?serial_num=<?php echo $machine->serial_num; ?>&doc_id=<?php echo htmlentities($id); ?>" onclick="return confirm('Are you sure you want to unlink <?php echo htmlentities($machine->machine_num); ?>? This action CANNOT be undone. You can still go back and re-add the machine to the document. This will NOT, however, affect the maintenance log. Proceed with unlink?');"><i class="icon-unlink icon-4x"> </i></a></p>
		</td>
	  </tr>
	<?php endforeach; ?>
	</table>
    <a class="btn btn-info" style='text-align:center;vertical-align:middle' href="view_all_documents.php"><i class="icon-chevron-left"></i> Return to View All Documents</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>
