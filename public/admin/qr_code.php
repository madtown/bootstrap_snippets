<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
global $QRClass;
if (isset($_GET['serial_num'])) {
	$serial_num = $_GET['serial_num'];
}
if (isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
}
if (isset($_GET['request_id'])) {
	$request_id = $_GET['request_id'];
}
if (isset($serial_num)) {
	$QRClass->qr_code_serial($serial_num);
}
if (isset($user_id)) {
	$QRClass->qr_code_contact($user_id);
}
if (isset($request_id)) {
	$QRClass->qr_code_request($request_id);
}
?>