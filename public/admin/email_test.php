<?php
require_once('../../includes/initialize.php');
require_once('../../includes/functions.php');
$subject="Maintenance Required";
$to="skelly@fabtech.com";
$body="Is it time to do some work !";
if (smtpmailer($to, $subject, $body)) {
	echo "Email Sent!";
	}
if (!empty($error)) {echo $error;}
?>