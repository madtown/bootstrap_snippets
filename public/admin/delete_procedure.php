<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php");}
	elseif ($_SESSION['rank'] > 3 ) { 
		$_SESSION['message'] = "You are not authorized to delete maintenance procedures.";
		redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
} 
?>
<?php
$procedureClass = new Procedure();
	// must have an ID
  if(empty($_GET['id'])) {
  	$session->message("No Procedure ID was provided.");
    redirect_to('view_procedures.php');
  }

  $photo = $procedureClass->find_by_id($_GET['id']);
  if($photo && $photo->destroy()) {
    $session->message("The procedure '{$photo->procedure_name}' was deleted.");
	//Log 
	$_POST['log_action'] = "Deleted Procedure {$photo->procedure_name}";
	$_POST['log_db_action'] = "Deleted {$photo->id}";
	$_POST['log_table_affected'] = "procedures";
	$_POST['log_serial_num'] = "";
	$_POST['log_maint_type'] = "{$photo->type_maint}";
	$logClass->newLog();
	redirect_to('view_procedures.php');
  } else {
	$URL = $photo->procedure_URL;
	$URL = basename($URL);
    $session->message("The procedure could not be deleted. ".$URL);
    redirect_to('view_procedures.php');
  }
  
?>
<?php if(isset($database)) { $database->close_connection(); } ?>
