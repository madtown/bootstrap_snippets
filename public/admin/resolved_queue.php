<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } 
elseif ($_SESSION['rank'] > 4 ) { 
	$_SESSION['message'] = "You are not authorized to view maintenance queue details.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
?>
<?php
	if (isset($_GET['request_id']) && isset($_POST['submit_assignments'])) {
		$requestClass->assign();
		redirect_to("resolved_queue.php" );
	}
	global $requestClass;
	if (isset($_GET['serial_num'])) {
		$requests = $requestClass->find_machine_resolved_requests(); 
	} elseif(isset($_GET['user'])) {
		$requests = $requestClass->find_my_resolved_requests(); 
	} else {
		$requests = $requestClass->find_resolved_requests(); 
	}
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<script>
	jQuery(document).ready(function() {
	  jQuery("div.timeago").timeago();
	});
</script>
<div class="container col-md-12 col-md-offset-0">
    <div class="center-text">
        <h1><i class="icon-check"></i> Resolved Maintenance Queue<a class="btn btn-warning pull-right" href="request.php"><h5><i class="icon-edit"></i> Request</h5></a><a class="btn btn-danger pull-right" href="queue.php"><h5><i class="icon-list-ol"></i> Queue</h5></a></h1>
    </div>     
<?php echo output_message($message); ?>
</div>
        <?php 
		global $workClass;
		$workClass->displayQueue($requests); 
	?> 
<?php include_layout_template('admin_footer.php'); ?>