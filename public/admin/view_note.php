<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
  // Find all the procedures
	global $logClass;
	global $machineClass;
	$id 			= $_GET['id'];
	$serial_num 	= $_GET['serial_num'];
	$machine 		= $machineClass->find_by_serial_num($serial_num);
	$note 			= $logClass->find_note_by_id($id);
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>

<div class="span12 offset0 well">
	<div class="center-text">
    	<h1><i class="icon-edit"></i> Machine: <?php echo $machine->machine_num; ?> Note</h1>
	<?php echo output_message($message); ?>
    <?php 	if (isset($note['log_note_URL']) and $note['log_note_URL'] != NULL and $note['log_note_URL'] != "") {?>
			<a href="<?php echo $note['log_note_URL']; ?>"><img src="<?php echo $note['log_note_URL']; ?>"/></a>
			<?php } ?>
	</div>
    <table class="table table-striped table-hover">
        <tr>
        	<th style='text-align:center;vertical-align:middle'><i class="icon-align-justify"></i> Text</th>
	    	<td style='vertical-align:middle'><?php echo $note['log_note']; ?></td>
        </tr>
	    <tr>
        	<th style='text-align:center;vertical-align:middle'><i class="icon-user"></i> User</th>
	    	<td style='vertical-align:middle'><?php echo $note['log_username']; ?></td>
        </tr>
        <tr>
        	<th style='text-align:center;vertical-align:middle'><i class="icon-time"></i> Time</th>
	    	<td style='vertical-align:middle'><small><?php echo datetime_to_text($note['log_timestamp']);?></small></td>
        </tr>
	</table>
	<a class="btn btn-info" href="machine_log.php?serial_num=<?php echo $machine->serial_num; ?>"><i class="icon-chevron-left"></i> Back to Machine Log</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>