<?php require_once("../../includes/initialize.php"); ?>
<?php 
if (!$session->is_logged_in()) { redirect_to("login.php"); } 
elseif ($_SESSION['rank'] > 3 ) { 
	$_SESSION['message'] = "You are not authorized to view notifications.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
?>
<?php
  // Find all the procedures
	global $notificationClass;
 	$photos = $notificationClass->find_all();
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="col-md-12">
	<div class="center-text">
    	<h2><i class="icon-envelope"></i> Notification Center
    	<a class="btn btn-success pull-right" style='text-align:center;vertical-align:middle' href="send_notifications.php"><i class="icon-envelope"></i> Send All</a></h2>
	</div>    
	<?php echo output_message($message); ?>
	<table class="table table-striped table-hover">
	  <tr>
	    <th style='text-align:center;vertical-align:middle'>Recipients</th>
	    <th></th>
	    <th>Type</th>
	    <th><div class="center-text"><i class="icon-time"></i> Time</div></th>
	  </tr>
	<?php foreach($photos as $photo): ?>
	  <tr>
        <td class="center-text">
        	<a href="change_notification.php?type=<?php echo htmlentities($photo->type); ?>">
          		<h2><i class="custom-users icon-large icon-stack-base"></i></h2>
        	</a>
        </td>
        <td></td>
	    <td style='vertical-align:middle'><?php echo $photo->type; ?></td>
	    <td style='text-align:center;vertical-align:middle'><small><?php if($photo->at_time != NULL) {echo $photo->at_time;} else {echo "Unscheduled";} ?></small></td>
	  </tr>
	<?php endforeach; ?>
	</table>
</div>
<?php include_layout_template('admin_footer.php'); ?>
