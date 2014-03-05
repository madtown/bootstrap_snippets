<?php require_once("../../includes/initialize.php"); ?>
<?php 
if (!$session->is_logged_in()) { redirect_to("login.php"); }
if ($_SESSION['rank'] > 3 ) { 
	$_SESSION['message'] = "You are not authorized to view machine documents.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']); 
}
?>
<?php
  // Find all the photos
  $serial_num = $_GET['serial_num'];
  $machine = $machineClass->find_by_serial_num($serial_num);
  $documents = $documentClass->find_by_serial_num($serial_num);
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="span12 offset0">
	<div class="center-text"><h2><i class="icon-copy"></i> Documents for <?php echo $machine->machine_num; ?></h2></div>
    <div class="center-text"><a class="btn btn-success" style='text-align:center;vertical-align:middle' href="edit_docs.php"><h4><i class="icon-plus"></i> New Document</h4></a></div>
    </br>

	<?php echo output_message($message); ?>
	<table class="table table-striped table-hover">
	  <tr>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-eye-open"></i> View</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-file"></i> Doc Name</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-time"></i> Created</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-user"></i> Author</th>
        <th style='text-align:center;vertical-align:middle'><i class="icon-cogs"></i> Machines</th>
		<th style='text-align:center;vertical-align:middle'><i class="icon-trash"></i> Delete</th>
	  </tr>
	<?php foreach($documents as $document):
	$id = $document->doc_id;
	$photo = array_shift($documentClass->find_by_id($id));
	?>
	  <tr>
		<td style='text-align:center;vertical-align:middle'><a href="<?php echo $photo->image_path(); ?>"><h1><i class="icon-file-text icon-2x"></i></h1></a></td>
	    <td style='text-align:center;vertical-align:middle'><?php echo $photo->doc_name; ?></td>
	    <td style='text-align:center;vertical-align:middle'><h6><?php echo $photo->timestamp; ?></h6></td>
	    <td style='text-align:center;vertical-align:middle'><?php echo $photo->username; ?></td>
		<td style='text-align:center;vertical-align:middle'><a class="btn btn-inverse" href="view_doc_machines.php?id=<?php echo htmlentities($photo->id); ?>"><i class="icon-cogs icon-4x"></i></a></td>
        <td style='text-align:center;vertical-align:middle'><a class="btn btn-danger" style='text-align:center;vertical-align:middle' href="delete_machine_from_doc.php?serial_num=<?php echo $machine->serial_num; ?>&doc_id=<?php echo htmlentities($id); ?>" onclick="return confirm('Are you sure you want to remove <?php echo htmlentities($machine->machine_num); ?> from the associated machines list for this document? This action CANNOT be undone. You can still go back and re-add the machine to the Document. Proceed with deletion?');"><i class="icon-trash icon-4x"> </i></a></p>
		</td>
	  </tr>
	<?php endforeach; ?>
	</table>
	<br />
    <a class="btn btn-info" style='text-align:center;vertical-align:middle' href="schedule_by_machine.php?serial_num=<?php echo htmlentities($machine->serial_num); ?>"><i class="icon-home"></i> Machine Home</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>
