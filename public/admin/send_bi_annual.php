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
$day_of_the_week = date("w");
$all_recipients = array();
//find all recipients
$buildings = $locationClass->find_all_buildings();
foreach ($buildings as $building):
	array_push($all_recipients, $building['building_lead_user']);
	array_push($all_recipients, $building['building_maint_user']);
	$sections = $locationClass->find_building_sections($building['building_id']);
	foreach ($sections as $section):
		$section = $locationClass->find_section_by_id($section['section_id']);
		$section_assoc = $section->fetch_assoc();
		array_push($all_recipients, $section_assoc['section_lead_user']);
		array_push($all_recipients, $section_assoc['section_maint_user']);
	endforeach;
endforeach; 

array_push($all_recipients, 1);
$unique_recipients = array_unique($all_recipients);
foreach($unique_recipients as $id):
	//actually send the notification here
	if($user = $userClass->find_user_by_id($id)) {
		if (isset($user['email']) && $user['email'] != "") {
			$subject 	= "Bi-annual Maintenance Report";
			$type 		= $user['email'];
			$date_unix = mktime(0, 0, 0, date("m")-6, date("d"), date("Y"));
			$body 		= "<strong>Bi-annual Maintenance Report:</strong></br>";
			$body 		.= "You have received this email because you are designated as either a maintenance or lead person for a building or section. The link below will bring you to the monthly maintenance report.</br></br>";
			$body .= "<a href='http://192.168.0.31/public/admin/report_bi_annual.php?date={$date_unix}'><strong>Bi-annual Maintenance Report</strong></a></br></br>";
			$body 		.= "If you think you have received this email in error inform someone of rank 2 or 1 and they can change your position on the Location page or just find Sean Kelly.</br>skelly@fabtech.com</br>";
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
			$date_unix = mktime(0, 0, 0, date("m")-6, date("d"), date("Y"));
			$inbox_type 	= 6;
			$inbox_status 	= 0;
			$inbox_text 	= $database->escape_value('Bi-annual Report');
			$notify_user 	= $database->escape_value(trim($user['id']));
			$inbox_link 	= 'report_bi_annual.php?date='.$date_unix;
			$inboxClass->newInbox($notify_user, $inbox_type, $inbox_status, $inbox_text, $inbox_link);
		}
		unset($user);
	} else {
		$text_2 = "The user was not found.";
	}
endforeach;
?>