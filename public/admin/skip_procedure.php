<?php require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
global $database;
global $machineClass;
global $stepClass;
$procedure_id = $database->escape_value($_GET['procedure_id']);
$serial_num = $_GET['serial_num'];
$table = "maint_steps";
$sql = "SELECT * FROM maint_steps WHERE procedure_name='".$procedure_id."' ORDER BY step_num";
$query = $database->query($sql);
$result = $query->fetch_assoc();
do {
	$step_num 						= $result['step_num'];
	$_POST['log_action'] 			= "{$step_num}";
	$_POST['log_db_action']			= "{$procedure_name}";
	$_POST['log_table_affected'] 	= "maint_log";
	$_POST['log_serial_num'] 		= "{$serial_num}";
	$_POST['log_maint_type'] 		= "{$procedure_id}";
	$_POST['log_total_steps']		= $stepClass->count_procedure_steps($procedure_id);
	$_POST['log_note']				="{$_POST['reason']}";
	$_POST['log_note_URL']			="SKIP";
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
} while ($result = $query->fetch_assoc());
redirect_to("select_maint.php");
?>