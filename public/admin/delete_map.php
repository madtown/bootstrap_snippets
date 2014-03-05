<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php");
	} elseif ($_SESSION['rank'] > 2) { 
			$_SESSION['message'] = "You are not authorized to delete maps.";
			redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
			}
?>
<?php
	// must have an ID
  if(empty($_GET['id'])) {
  	$session->message("No photograph ID was provided.");
    redirect_to('view_maps.php');
  }

  $photo = $mapClass->find_by_id($_GET['id']);
  if($photo && $photo->destroy()) {
	//Log 
	$_POST['log_action'] = "Deleted map {$photo->map_name}";
	$_POST['log_db_action'] = "Deleted {$photo->id}";
	$_POST['log_table_affected'] = "maps";
	$_POST['log_serial_num'] = "";
	$_POST['log_maint_type'] = "";
	$logClass->newLog();
	
    $session->message("The map '{$photo->map_name}' was deleted.");
    redirect_to('view_maps.php');
  } else {
	$URL = $photo->URL;
	$URL = basename($URL);
    $session->message("The photo could not be deleted.".$URL);
    redirect_to('view_maps.php');
  }
  
?>
<?php if(isset($database)) { $database->close_connection(); } ?>
