<?php require_once("../../includes/initialize.php"); ?>
<?php	
	$_POST['log_action'] 			= "Logged out {$session->username}";
	$_POST['log_db_action']			= "";
	$_POST['log_table_affected'] 	= "maint_log";
	$_POST['log_serial_num'] 		= "";
	$_POST['log_maint_type'] 		= "";
	$_POST['log_total_steps']		= "";
	if ($logClass->newLog()){
		unset($_POST['log_total_steps']);
		unset($_POST['log_action']);
		unset($_POST['log_db_action']);
		unset($_POST['log_table_affected']);
		unset($_POST['log_serial_num']);
		unset($_POST['log_maint_type']);
		unset($_POST['log_note']);
		unset($_POST['log_note_URL']);
		unset($_POST['step_num']);
		unset($_POST['submit']);
	}
	$session->logout();	
    redirect_to("login.php");
?>
