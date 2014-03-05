<?php
require_once('../../includes/initialize.php');
require_once('../../public/layouts/admin_header.php');

function getMachines(){
	global $connection;
	global $database;
	$input = $_GET['type'];
	$table = "equipment_log";

$sql = "SELECT * FROM equipment_log WHERE type='".$input."' ORDER BY machine_num";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
?><select class="form-control" name="machine_num" id="machine_num" multiple="multiple">
	<option value="None Selected">None Selected</option> <?php
	do 
	{ if($result['machine_num']){
		?><option value='<?php echo trim($result['machine_num'], '"');?>'>"<?php echo trim($result['machine_num'], '"');?>"</option><?php
	}
	}
		while ($result=$query->fetch_assoc());
	?></select><?php
}
getMachines();

?>