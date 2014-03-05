<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
   // Find all the photos
  $photos = $documentClass->find_all();
?>
<?php include_layout_template('navbar.php'); ?>
<?php include_layout_template('admin_header.php'); ?>
<div class="span12 offset0 well">
	<div class="center-text"><h2><i class="icon-copy"></i> All Documents</h2></div>
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
	<?php foreach($photos as $photo): ?>
	  <tr>
		<td style='text-align:center;vertical-align:middle'><a href="<?php echo $photo->image_path(); ?>"><h1><i class="icon-file-text icon-2x"></i></h1></a></td>
	    <td style='text-align:center;vertical-align:middle'><?php echo $photo->doc_name; ?></td>
	    <td style='text-align:center;vertical-align:middle'><h6><?php echo $photo->timestamp; ?></h6></td>
	    <td style='text-align:center;vertical-align:middle'><?php echo $photo->username; ?></td>
		<td style='text-align:center;vertical-align:middle'><a class="btn btn-inverse" href="view_doc_machines.php?id=<?php echo htmlentities($photo->id); ?>"><i class="icon-cogs icon-4x"></i></a></td>
        <td style='text-align:center;vertical-align:middle'><a class="btn btn-danger" style='text-align:center;vertical-align:middle' href="delete_doc.php?id=<?php echo $photo->id; ?>&doc_id=<?php echo basename($photo->doc_path); ?>" onclick="return confirm('Are you sure you want to delete <?php echo $photo->doc_name; ?>? This action CANNOT be undone. Proceed with deletion? Last warning.');"><i class="icon-trash icon-4x"> </i></a></p>
		</td>
	  </tr>
	<?php endforeach; ?>
	</table>
	<br />
</div>
<?php include_layout_template('admin_footer.php'); ?>
