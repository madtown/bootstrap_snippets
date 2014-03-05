<?php
require_once('../../includes/initialize.php');

function getTypes(){
	global $connection;
	global $database;

	$sql = "SELECT * FROM equipment_type ORDER BY name";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
	?><select class="form-control" name="type" id="type" required>
	<option value="None Selected">None Selected</option> <?php
	do 
	{ if($result['id']){
		?><option value='<?php echo trim($result['id'], '"');?>'<?php if (isset($type_id)) {echo htmlentities(" selected");}?>>"<?php echo trim($result['name'], '"');?>"</option><?php
	}
	} while ($result=$query->fetch_assoc());
}
getTypes();
?>
