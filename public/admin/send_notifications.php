<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); } 
elseif ($_SESSION['rank'] > 1) { 
	$message = "You do not have a sufficient rank to trigger daily notifications.";
	redirect_to("edit_notifications.php");
	}
if (isset($_POST['submit']) && ($_POST['type'] != NULL) && ($_POST['username'] != NULL)) {
	global $logClass;
	global $machineClass;
	global $notificationClass;
	$notificationClass->add_user();
} else { //Form is unsubmitted
	$type = $_GET['type'];
}
?>
<?php include_layout_template('admin_footer.php'); ?>
