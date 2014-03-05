<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
global $inboxClass;
$dismiss = $inboxClass->updateStatus();
?>