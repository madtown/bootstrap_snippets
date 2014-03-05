<?php
require_once('../../includes/initialize.php');

function getSerials(){
	global $connection;
	global $database;
	$input = $_GET['type'];
	$sql = "SELECT * FROM equipment WHERE type_id='".$input."' ORDER BY machine_num";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
	?>
    <select class="form-control" name="machine_serial[ ]" id="machine_serial[ ]" multiple="multiple">
        <option value="None Selected">None Selected</option> <?php
        do 
        { if($result['serial_num']){
            ?><option value='<?php echo trim($result['serial_num'], '"');?>'>"<?php echo trim($result['machine_num'], '"');?>"
      	</option>
		<?php
	}
	}
	while ($result=$query->fetch_assoc());
}
getSerials();
?>
