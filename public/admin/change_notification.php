<?php require_once("../../includes/initialize.php"); ?>
<?php 
if (!$session->is_logged_in()) { redirect_to("login.php"); } 
elseif ($_SESSION['rank'] > 2 ) { 
	$_SESSION['message'] = "You are not authorized to edit notifications.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}?>
<?php
  // Find all the procedures
	global $notificationClass;
	$type = $_GET['type'];
 	$photos = $notificationClass->find_all_recipients($type);
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="span12 offset0 well">
	<div class="center-text"><h2><i class="icon-envelope"></i> <?php echo htmlentities($type); ?></h2></div>
    <div class="center-text"><a class="btn btn-small btn-success" style='text-align:center;vertical-align:middle' href="add_notification.php?type=<?php echo htmlentities($type); ?>"><h4><i class="icon-plus"></i> Add Recipient</h4></a></div>
    </br>
	<?php echo output_message($message); ?>
	<table class="table table-striped table-hover">
	  <tr>
      	<th></th>
        <th></th>
	    <th><div class="center-text">Recipient</div></th>
	    <th>Remove Recipient</th>
	  </tr>
	<?php foreach($photos as $photo): ?>
	  <tr>
        <td>
        	<div class="center-text" style="padding-top: 10px;">
                <span class="icon-stack center-text">
                  <h2>
                  	<i class="icon-user icon-large icon-stack-base"></i>
                  	<i class="icon-envelope icon-light" style="padding-left: 16px; padding-top: 17px;"></i>
                  </h2>
                </span>
        	</div>
        </td>
        <td></td>
	    <td style='text-align:center;vertical-align:middle'><div class="center-text"><h2><?php echo $photo->username; ?></h2></div></td>
		<td><a class="btn btn-danger" style='text-align:center;vertical-align:middle' href="delete_notification.php?username=<?php echo htmlentities($photo->username); ?>&type=<?php echo htmlentities($type); ?>"><i class="icon-trash icon-4x"> </i></a>
		</td>
	  </tr>
	<?php endforeach; ?>
	</table>
	<br />
    <div class="center-text"><a class="btn btn-small btn-info" style='text-align:center;vertical-align:middle' href="edit_notifications.php"><h4><i class="icon-chevron-left"></i> View All Notifications</h4></a></div>
</div>
<?php include_layout_template('admin_footer.php'); ?>
