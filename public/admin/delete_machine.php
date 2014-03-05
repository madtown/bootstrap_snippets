<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php");
	} elseif ($_SESSION['rank'] > 2) { 
			$_SESSION['message'] = "You are not authorized to delete machines.";
			redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
			}
?>
<?php
	// must have an ID
  if(empty($_GET['id'])) {
  	$session->message("No machine ID was provided.");
    redirect_to('view_all_machines.php');
  }

  $photo = $machineClass->find_by_id($_GET['id']);
  if($photo && $photo->delete()) {
	//Log 
	$_POST['log_action'] = "Deleted machine {$photo->machine_num}";
	$_POST['log_db_action'] = "Deleted {$photo->id}";
	$_POST['log_table_affected'] = "machines";
	$_POST['log_serial_num'] = "{$photo->serial_num}";
	$_POST['log_maint_type'] = "{$photo->type}";
	$logClass->newLog();
	
    $session->message("The Machine '{$photo->machine_num}' was utterly destroyed. May God have mercy on your soul.");
    redirect_to('view_all_machines.php');
  } else {
    $session->message("The machine could not be deleted.");
    redirect_to('view_all_machines.php');
  }
  
?>
<?php if(isset($database)) { $database->close_connection(); } ?>
