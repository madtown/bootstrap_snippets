<?php
require_once('../../includes/initialize.php');

function getUsers(){
	global $connection;
	global $database;
	$table = "users";
	$sql = "SELECT * FROM users ORDER BY username";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
	echo"<option value=''></option>";
	
	do { 
		if($result['username']) {
			?>
            <option value="<?php echo trim($result['id'], '"');?>">
				<?php echo trim($result['username'], '"');?>
            </option>
			<?php
		}
	}
	while ($result=$query->fetch_assoc());
}
getUsers();
?>
