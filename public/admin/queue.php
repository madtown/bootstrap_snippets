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
		redirect_to("queue.php" );
	}
	global $requestClass;
	$requestObject = $requestClass->find_unresolved_requests();	//returns all requests in a object but need a better order. 
	$requests = $requestClass->orderQueue($requestObject);
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container col-md-12 col-md-offset-0">
    <div class="center-text">
        <h1><i class="icon-list-ol"></i> Maintenance Queue<a class="btn btn-warning pull-left" href="request.php"><h5><i class="icon-edit"></i> Request</h5></a><a class="btn btn-success pull-right" href="resolved_queue.php"><h5><i class="icon-check"></i> Resolved Requests</h5></a></h1>
        <?php echo output_message($message); ?>
    </div>    
    <?php 
		global $workClass;
		$workClass->displayQueue($requests); 
	?>  
<?php include_layout_template('admin_footer.php'); ?>