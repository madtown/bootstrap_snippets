<?php require_once("../../includes/initialize.php"); ?>
<?php
//if any procedures exceed their time intervals at this time. Insert a note in the maint_log so it shows and so it can be reported to maintenance staff and to section or building leaders depending on the time it exceeded.
global $machineClass;
global $stepClass;
global $locationClass;
global $userClass;
global $session;
$procedureClass = new Procedure();
$procedures = $procedureClass->find_all_regularly_scheduled();
$machines_overdue_today = array();
$machines_super_overdue_today = array();
$machines_insanely_overdue_today = array();
$day_of_the_week = date("w");
if ($day_of_the_week != 6 && $day_of_the_week != 0 && $day_of_the_week != 1) {//not on Sat, Sun, or Mon
	foreach($procedures as $procedure): 
		$procedure_name = $procedure->procedure_name;
		$steps = $stepClass->find_all_steps_by_procedure($procedure_name);
		foreach($steps as $step) {
			$last_procedure_step = $step['step_num'];
		}
		$number_of_steps = $steps->num_rows;
		if ($number_of_steps = 0) {
			continue;//This procedure has no steps associated with it and should be exempt because it could not have been done. This is most likely a procedure in progress.
		}
		$machines = $procedureClass->find_serials($procedure_name);
		$number_of_machines = $machines->num_rows;
		if ($number_of_machines = 0) {
			continue;//This procedure has no machines associated with it and should be exempt because it could not have been done. This is most likely a procedure in progress.
		}
		switch ($procedure->type_maint) {
			case 36000://daily
				$interval = floor(86400*1.1);
				$tier_2 = 86400*2;
				$tier_3 = 86400*5;
				break;
			case 770400://weekly
				$interval = floor(604800*1.2);
				$tier_2 = 86400*1;
				$tier_3 = 86400*7;
				break;
			case 2593740://monthly
				$interval = floor(2592000*1.25);
				$tier_2 = 86400*7;
				$tier_3 = 86400*14;
				break;
			case 31524000://annual
				$interval = floor(31557600*1.33);
				$tier_2 = 2592000*4;
				$tier_3 = 2592000*6;
				break;
			default:
				$interval = floor(($procedure->type_maint)*1.15);
				$tier_2 = $procedure->type_maint*1.25;
				$tier_3 = $procedure->type_maint*1.5;
				break;
		}
		foreach($machines as $machine):
			$serial_num = $machine['machine_serial'];
			$last_step_done_in_log = $procedureClass->find_the_last_step_done($serial_num, $procedure_name);
			if ($last_step_done_in_log['log_action'] != $last_procedure_step && $last_step_done_in_log['log_timestamp'] != '1969-07-21 02:56:15') {
				if ($last_step_done_in_log['log_action'] = 0) {//it was already overdue
					$last_step_log_unix = strtotime($last_step_done_in_log['log_timestamp']);
					$time_elapsed = (time() - $last_step_log_unix);
					if ($time_elapsed >= ($interval+$tier_2) && $day_of_the_week = 1) {
						//It's super overdue
						if (!in_array($serial_num, $machines_super_overdue_today) && !in_array($serial_num, $machines_overdue_today)) {
							array_push($machines_super_overdue_today, $serial_num);
						}
					}
					if ($time_elapsed >= ($interval+$tier_3) && $day_of_the_week = 1) {
						//It's insanely overdue
						if (!in_array($serial_num, $machines_insanely_overdue_today) && !in_array($serial_num, $machines_overdue_today) && !in_array($serial_num, $machines_super_overdue_today)) {
							array_push($machines_insanely_overdue_today, $serial_num);
						}
						continue;
					} else {
						continue;//dunno whats up with that
					}
				} else {
					continue;//it is in progress
				}
			} else {//check if it is overdue on maintenance
				$last_step_log_unix = strtotime($last_step_done_in_log['log_timestamp']);
				$time_elapsed = (time() - $last_step_log_unix);
				if ($time_elapsed >= $interval) {
					//It's totally overdue
					$procedureClass->procedure_is_overdue($procedure_name, $serial_num, $last_step_log_unix);
					if (!in_array($serial_num, $machines_overdue_today)) {
						array_push($machines_overdue_today, $serial_num);
					}
					continue;
				} else {//nope it's not overdue continuing on
					continue;
				}
			}
			unset($machine);
		endforeach;
		unset($procedure);
	endforeach;
	$all_recipients = array();
	foreach ($machines_overdue_today as $serial_num) {
		$machine = $machineClass->find_by_serial_num($serial_num);
		$location_information = $locationClass->find_machine_location_information($machine->location_type, $machine->location_id);
		switch ($machine->location_type) {
			case 0:
				$recipient = search_array_keys_recursively('building_maint_user', $location_information);
				array_push($all_recipients, $recipient);
				break;
			case 1:
				$recipient = search_array_keys_recursively('section_maint_user', $location_information);
				array_push($all_recipients, $recipient);
				break;
			case 2:
				$recipient = search_array_keys_recursively('section_maint_user', $location_information);
				array_push($all_recipients, $recipient);
				break;
		}
	}
	foreach ($machines_super_overdue_today as $serial_num) {
		$machine = $machineClass->find_by_serial_num($serial_num);
		$location_information = $locationClass->find_machine_location_information($machine->location_type, $machine->location_id);
		switch ($machine->location_type) {
			case 0:
				$recipient = search_array_keys_recursively('building_lead_user', $location_information);
				array_push($all_recipients, $recipient);
				break;
			case 1:
				$recipient = search_array_keys_recursively('section_lead_user', $location_information);
				array_push($all_recipients, $recipient);
				break;
			case 2:
				$recipient = search_array_keys_recursively('section_lead_user', $location_information);
				array_push($all_recipients, $recipient);
				break;
		}
	}
	foreach ($machines_insanely_overdue_today as $serial_num) {
		$machine = $machineClass->find_by_serial_num($serial_num);
		$location_information = $locationClass->find_machine_location_information($machine->location_type, $machine->location_id);
		switch ($machine->location_type) {
			case 0:
				$recipient = search_array_keys_recursively('building_lead_user', $location_information);
				array_push($all_recipients, $recipient);
				break;
			case 1:
				$recipient = search_array_keys_recursively('building_lead_user', $location_information);
				array_push($all_recipients, $recipient);
				break;
			case 2:
				$recipient = search_array_keys_recursively('building_lead_user', $location_information);
				array_push($all_recipients, $recipient);
				break;
		}
	}
	array_push($all_recipients, 1);
	$unique_recipients = array_unique($all_recipients);
	foreach($unique_recipients as $id):
		//actually send the notification here
		if($user = $userClass->find_user_by_id($id)) {
			if (isset($user['email']) && $user['email'] != "") {
				$subject 	= "Maintenance Alert!";
				$type 		= $user['email'];
				$date_unix = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
				$body 		= "<strong>Maintenance Alert:</strong></br>";
				$body 		.= "You have received this email because a required maintenance procedure in your location has not been completed in the time allotted.</br></br>";
				$body .= "<a href='http://192.168.0.31/public/admin/report_daily.php?date={$date_unix}'><strong>Maintenance Alert</strong></a></br></br>";
				$body 		.= "If you think you have received this email in error inform someone of rank 2 or 1 and they can change your position on the Location page.</br>";
				if (isset($filepath)) {
					$attachment = ""; 
				} else {$attachment = "";}
				$body .= "DO NOT REPLY TO THIS EMAIL";
				if (smtpmailer($type, $subject, $body, $attachment)) {
				}
				if (!empty($error)) {
					$text_2 = $error;
				}
			} else {
				$text_2 = "Email not sent.";
			}
			//Done sending the email continue on
			if (isset($user['id']) && $user['id'] != "") {
				//notify assignee
				global $inboxClass;
				$date_unix = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
				$inbox_type 	= 3;
				$inbox_status 	= 0;
				$inbox_text = $database->escape_value('Daily Alert!');
				$notify_user 	= $database->escape_value(trim($user['id']));
				$inbox_link 	= 'report_daily.php?date='.$date_unix;
				$inboxClass->newInbox($notify_user, $inbox_type, $inbox_status, $inbox_text, $inbox_link);
			}
			unset($user);
		} else {
			$text_2 = "The user was not found.";
		}
	endforeach;
}
?>