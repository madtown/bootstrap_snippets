<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); 
	} elseif ($_SESSION['rank'] > 4) { 
		$message = "You do have a sufficient rank to delete steps.";
		redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']); 
		}
?>
<?php
	// must have an ID
  if(empty($_GET['id'])) {
  	$session->message("No Step ID was provided.");
    redirect_to('view_procedures.php');
  }

  $photo = $stepClass->find_by_id($_GET['id']);
  if($photo && $photo->destroyStep()) {
    $session->message("Step Number '{$photo->step_num}' was deleted from '{$photo->procedure_name}'.");
    //Log 
	$_POST['log_action'] = "Deleted Step {$photo->step_num} from {$photo->procedure_name}";
	$_POST['log_db_action'] = "Deleted Step {$photo->id}";
	$_POST['log_table_affected'] = "procedures";
	$_POST['log_serial_num'] = "";
	$_POST['log_maint_type'] = "{$photo->type_maint}";
	$logClass->newLog();
	$procedure_id = $_GET['procedure_id'];
	redirect_to('view_steps.php?procedure_id='.$procedure_id);
  } else {
	$URL = $photo->URL;
	$URL = basename($URL);
    $session->message("The photo could not be deleted.".$URL);
    redirect_to('view_procedures.php');
  }
  
?>
<?php if(isset($database)) { $database->close_connection(); } ?>
