<?php
require_once('../../includes/initialize.php');

function getProcedures(){
	global $connection;
	global $database;
	$table = "procedures";
	$selected_procedure = $_GET['id'];

$sql = "SELECT DISTINCT procedure_name FROM procedures ORDER BY procedure_name";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
?><select class="form-control" name="procedure" id="procedure" multiple>
	<option value="None Selected">None Selected</option> <?php
	do 
	{ if($result['procedure_name']) {
		?><option value='<?php echo trim($result['procedure_name'], '"');?>' <?php 
		if ($selected_procedure == $result['procedure_name']) echo 'selected="selected"'; ?>>"<?php 
		echo trim($result['procedure_name'], '"');?>"</option><?php
}
}
	while ($result=$query->fetch_assoc());
}
getProcedures();
?>
</select>
