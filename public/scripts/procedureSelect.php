<option value="" data-foo=""></option>
<?php
require_once('../../includes/initialize.php');

function getProcedures(){
	global $connection;
	global $database;
	$table = "procedures";
	$sql = "SELECT * FROM procedures ORDER BY procedure_name";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
	
	do { 
		if($result['procedure_name']) {
			?>
            <option value="<?php echo trim($result['id'], '"');?>" data-foo="<?php echo trim($result['procedure_URL'], '"'); ?>">
				<?php echo trim($result['procedure_name'], '"');?>
            </option>
			<?php
		}
	}
	while ($result=$query->fetch_assoc());
}
getProcedures();
?>
