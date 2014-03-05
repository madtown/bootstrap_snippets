<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php");
	} elseif ($_SESSION['rank'] > 4) { 
			$_SESSION['message'] = "You are not authorized to delete maps.";
			redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
			}
?>
<?php
	// must have an ID
  if(empty($_GET['id'])) {
  	$session->message("No photograph ID was provided.");
    redirect_to('view_all_docs.php');
  }

  $photo = $documentClass->find_by_id($_GET['id']);
  if($photo && $photo[0]->destroy()) {
	//Log 
	$doc_name = $photo[0]->doc_name;
	$id = $photo[0]->id;
	$serial_num = $photo[0]->serial_num;
	$_POST['log_action'] = "Deleted document {$doc_name}";
	$_POST['log_db_action'] = "Deleted {$id}";
	$_POST['log_table_affected'] = "machine_docs";
	$_POST['log_serial_num'] = "";
	$_POST['log_maint_type'] = "";
	$logClass->newLog();
    $session->message("The document '{$photo[0]->doc_name}' was deleted from machine {$photo[0]->serial_num}.");
    $url = "view_all_documents.php";
    redirect_to($url);
  } else {
	$URL = $photo->doc_path;
	$URL = basename($URL);
    $session->message("The document could not be deleted.".$URL);
	$url = "view_all_documents.php";
    redirect_to($url);
  }
  
?>
<?php if(isset($database)) { $database->close_connection(); } ?>
