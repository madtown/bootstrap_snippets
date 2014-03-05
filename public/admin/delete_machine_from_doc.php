<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php");}
	elseif ($_SESSION['rank'] > 3 ) { 
		$_SESSION['message'] = "You are not authorized to delete machines from a document.";
		redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
} 
?>
<?php
$documentClass = new Document();
	// must have an ID
  if(empty($_GET['doc_id'])) {
  	$session->message("No Doc ID was provided.");
    redirect_to('view_all_documents.php');
  } else { $doc_id = $_GET['doc_id']; }
  if(empty($_GET['serial_num'])) {
  	$session->message("No Serial Number was provided.");
	$URL = "view_doc_machines.php?id=".$doc_id;
    redirect_to($URL);
  } else { $serial_num = $_GET['serial_num']; }
  $URL = "view_doc_machines.php?id=".$doc_id;
  if($documentClass->delete_doc_machine()) {
    $session->message("The machine '{$serial_num}' was deleted from Document '{$doc_id}'.");
	$_POST['log_action'] = "Deleted machine from Document {$doc_id}";
	$_POST['log_db_action'] = "Deleted {$serial_num}";
	$_POST['log_table_affected'] = "procedures";
	$_POST['log_serial_num'] = "";
	$_POST['log_maint_type'] = "";
	$logClass->newLog();
	redirect_to($URL);
  } else {
    $session->message("The machine could not be deleted. {$serial_num}");
    redirect_to($URL);
  }
  
?>
<?php if(isset($database)) { $database->close_connection(); } ?>
