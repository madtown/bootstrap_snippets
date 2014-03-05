<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); 
	} elseif ($_SESSION['rank'] > 4) { 
		$message = "You do have a sufficient rank to delete steps.";
		redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']); 
		}
?>
<?php
$user = new User();

	// must have an ID
  if(empty($_GET['id'])) {
  	$session->message("No user ID was provided.");
    redirect_to('view_users.php');
  }

  $photo = $user->find_by_user_id($_GET['id']);
  $deleted = $photo->delete();
  if($photo && $deleted) {
    $session->message("The user '{$photo->username}'.");
    //Log 
	$_POST['log_action'] = "Deleted User {$photo->username}";
	$_POST['log_db_action'] = "Deleted User";
	$_POST['log_table_affected'] = "users";
	$_POST['log_serial_num'] = "{$photo->username}";
	$_POST['log_maint_type'] = "";
	$logClass->newLog();
	$session->message("The User {$photo->username} was deleted.");
	redirect_to('view_users.php');
  } else {
    $session->message("The User {$photo->username} could not be deleted.");
    redirect_to('view_users.php');
  }
  
?>
<?php if(isset($database)) { $database->close_connection(); } ?>
