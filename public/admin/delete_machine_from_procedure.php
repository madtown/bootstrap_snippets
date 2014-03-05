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
  if(empty($_GET['procedure_id'])) {
  	$session->message("No Procedure Name was provided.");
    redirect_to('view_procedures.php');
  } else { 
  	$procedure_id = $_GET['procedure_id']; 
  }
  if(empty($_GET['serial_num'])) {
  	$session->message("No Procedure Name was provided.");
    redirect_to('view_procedure_machines.php?procedure_id='.$_GET['procedure_id']);
  } else { $serial_num = $_GET['serial_num']; }
  $URL = "view_procedure_machines.php?procedure_id=".$procedure_id;
  $photo = $procedureClass->find_procedure_serial($procedure_id, $serial_num);
  if($photo && $procedureClass->delete_procedure_machine()) {
    $session->message("The machine '{$serial_num}' was deleted from procedure '{$procedure_id}' was deleted.");
	$_POST['log_action'] = "Deleted machine from Procedure {$procedure_id}";
	$_POST['log_db_action'] = "Deleted {$serial_num}";
	$_POST['log_table_affected'] = "procedures";
	$_POST['log_serial_num'] = "";
	$_POST['log_maint_type'] = "";
	$logClass->newLog();
	redirect_to($URL);
  } else {
    $session->message("The procedure could not be deleted. {$procedure_id}");
    redirect_to($URL);
  }
  
?>
<?php if(isset($database)) { $database->close_connection(); } ?>
