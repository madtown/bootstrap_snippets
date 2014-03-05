<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); 
	} elseif ($_SESSION['rank'] > 2) { 
		$message = "You do have a sufficient rank to delete notifications.";
		redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']); 
		}
?>
<?php
global $notificationClass;

// must have an ID
	if(empty($_GET['username'])) {
		$session->message("No username was provided.");
		redirect_to('edit_notifications.php');
	}
	if(empty($_GET['type'])) {
		$session->message("No type was provided.");
		redirect_to('edit_notifications.php');
	}
	$username = $_GET['username'];
	$type = $_GET['type'];
	$deleted = $notificationClass->delete($username, $type);
	if($username && $type && $deleted) {
	$session->message("The user '{$username}' was removed from the recipient list.");
	//Log 
	$_POST['log_action'] = "Removed recipient: {$username} from notifications";
	$_POST['log_db_action'] = "Removed recipient: {$username}";
	$_POST['log_table_affected'] = "notifications";
	$_POST['log_serial_num'] = "{$username}";
	$_POST['log_maint_type'] = "";
	$logClass->newLog();
	$session->message("The recipient: {$username} was removed.");
	$type = htmlentities($type);
	redirect_to("change_notification.php?type={$type}");
	} else {
	$session->message("The recipient: {$username} could not be removed.");
	$type = htmlentities($type);
	redirect_to("change_notification.php?type={$type}");
	}
  
?>
<?php if(isset($database)) { $database->close_connection(); } ?>
