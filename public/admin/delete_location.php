<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php");
	} elseif ($_SESSION['rank'] > 2) { 
		$_SESSION['message'] = "You are not authorized to delete locations.";
		redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
	}
?>
<?php
	global $locationClass;
	// must have an ID
	if(isset($_GET['building_id'])) {
		if(!isset($_GET['section_id']) && !isset($_GET['cell_id'])) {
			$building_obj = $locationClass->find_building_by_id($_GET['building_id']);
			$building = $building_obj->fetch_assoc();
			$location = "builing ";
			$location .= $building['building_name'];
			$location_id = $building['building_id'];
			$location_table = 'building';
			$locationClass->delete_building($_GET['building_id']);
		}
		if(isset($_GET['section_id'])) {
			if(!isset($_GET['cell_id'])) {
				$section_obj = $locationClass->find_section_by_id($_GET['section_id']);
				$section = $section_obj->fetch_assoc();
				$location = "section ";
				$location .= $section['section_name'];
				$location_id = $section['section_id'];
				$location_table = 'section';
				$locationClass->delete_section($_GET['section_id']);
			}
			if(isset($_GET['cell_id'])) {
				$cell_obj = $locationClass->find_cell_by_id($_GET['cell_id']);
				$cell = $cell_obj->fetch_assoc();
				$location = "cell ";
				$location .= $cell['cell_name'];
				$location_id = $cell['cell_id'];
				$location_table = 'cell';
				$locationClass->delete_cell($_GET['cell_id']);
			}
		}
		//Log deletion of the location
		$_POST['log_action'] = "Deleted {$location}";
		$_POST['log_db_action'] = "Deleted {$location_id}";
		$_POST['log_table_affected'] = "{$location_table}";
		$_POST['log_serial_num'] = "";
		$_POST['log_maint_type'] = "";
		$logClass->newLog();
		$session->message("The location was deleted. You have become death, destroyer of locations.");
		redirect_to('locations.php');
	} else {
		redirect_to('locations.php');
	}
?>
<?php if(isset($database)) { $database->close_connection(); } ?>