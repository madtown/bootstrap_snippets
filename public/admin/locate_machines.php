<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
if (isset($_POST['redirect_URL']) and $_POST['redirect_URL'] != "") {
	$URL = $_POST['redirect_URL'];
	redirect_to($URL);
}
?>
<?php
	$procedureClass = new Procedure();
	$photos=$procedureClass->find_in_progress();

?>

<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="col-md-12 well">
	<div class="center-text"><h1><i class="icon-wrench"></i> Machines Down for Maintenance</h1></div>
	<?php echo output_message($message); ?>
	<table class="table table-striped table-hover">
		<tr>
		    <th>Do Procedure</th>
		    <th>Machine</th>
		    <th>Machine Type</th>
		    <th>Serial Number</th>
		</tr>
	<?php foreach($photos as $photo): 
	$procedure_name = $photo->procedure_name;
	$query = $procedureClass->find_serials($procedure_name);
	$result = $query->fetch_assoc();
	$procedure_name = $photo->procedure_name;
			$serial_num = $result['machine_serial'];
			$sql = "SELECT * FROM maint_steps WHERE procedure_name='".$procedure_name."' AND step_num>'0' ORDER BY step_num";
			$query2 = $database->query($sql);
			$result2 = $query2->fetch_assoc();
			$undone_steps = array();
	do {
		$serial_num = $result['machine_serial'];
		$machine_query = $procedureClass->find_machine_num_by_serial($serial_num);
		$machine_result = $machine_query->fetch_assoc();
		$procedure_name = $photo->procedure_name;
		$serial_num = $result['machine_serial'];
		$sql = "SELECT * FROM maint_steps WHERE procedure_name='".$procedure_name."' AND step_num>'0' ORDER BY step_num";
		$query2 = $database->query($sql);
		$result2 = $query2->fetch_assoc();
		$undone_steps = array();
		//Create array of undone steps. 
		do {
			if (isset($result2['step_num'])) {
				$sql_last_step_time = "SELECT * FROM maint_log WHERE log_serial_num='".$serial_num."' AND log_action='".$result2['step_num']."' AND log_db_action='".$procedure_name."' ORDER BY log_timestamp DESC LIMIT 1";
				$query_last_step_time = $database->query($sql_last_step_time);
				$result_last_step_time = $query_last_step_time->fetch_assoc();
				$type_maint = $photo->type_maint;
				if (strtotime(current_time()) > (strtotime($result_last_step_time['log_timestamp'])+$type_maint)) {
					array_push($undone_steps, $result2);
					if ($undone_steps['0']['step_num'] != 1) {
						$onstep = $undone_steps['0']['step_num'];
					} else { $onstep = 0; }
				global $stepClass;
				$totalSteps = $stepClass->count_procedure_steps($procedure_name);
				$percent_undone = (($onstep/$totalSteps)*100);
				} else {
				}
				} else { 
			}
		} while ($result2=$query2->fetch_assoc());
		if (isset($_SESSION['no_step']) and $_SESSION['no_step'] = "") {
				$no_step = $_SESSION['no_step'];
			} 
			if (empty($undone_steps)) {
				continue 2;
			} elseif (!empty($undone_steps) and $onstep != 0 and ($totalSteps-$onstep != 0)) {
				$type_bar = "progress-warning progress-striped active";
			} elseif (!empty($undone_steps)) {
				$type_bar = "progress-warning progress-striped active";
				continue 2;
			} elseif (empty($undone_steps) and $no_step = true) { 
				continue 2;
			} 
	?>
		<tr class="<?php 
			$procedure_name = $photo->procedure_name;
			$serial_num = $result['machine_serial'];
			$sql = "SELECT * FROM maint_steps WHERE procedure_name='".$procedure_name."' AND step_num>'0' ORDER BY step_num";
			$query2 = $database->query($sql);
			$result2 = $query2->fetch_assoc();
			$undone_steps = array();
		//Create array of undone steps. 
			do {
				if (isset($result2['step_num'])) {
				$sql_last_step_time = "SELECT * FROM maint_log WHERE log_serial_num='".$serial_num."' AND log_action='".$result2['step_num']."' AND log_db_action='".$procedure_name."' ORDER BY log_timestamp DESC LIMIT 1";
				$query_last_step_time = $database->query($sql_last_step_time);
				$result_last_step_time = $query_last_step_time->fetch_assoc();
				$type_maint = $photo->type_maint;
				if (strtotime(current_time()) > (strtotime($result_last_step_time['log_timestamp'])+$type_maint)) {
					array_push($undone_steps, $result2);
					if ($undone_steps['0']['step_num'] != 1) {
						$onstep = $undone_steps['0']['step_num'];
					} else {$onstep = 0;}
					global $stepClass;
					$totalSteps = $stepClass->count_procedure_steps($procedure_name);
					$percent_undone = (($onstep/$totalSteps)*100);
					} else {
					}
				} else { 
				}
			} while ($result2=$query2->fetch_assoc());
			if (isset($_SESSION['no_step']) and $_SESSION['no_step'] = "") {
				$no_step = $_SESSION['no_step'];
			} 
			if (empty($undone_steps)) {
				echo "success";
				$type_bar = "progress-success";
				$percent_undone = "100";
			} elseif (!empty($undone_steps)) {
				echo "warning";
				$type_bar = "progress-warning progress-striped active";
			} elseif (empty($undone_steps) and $no_step = true) { 
				echo "info";
				$type_bar = "progress-info progress-striped";
			} 
			unset($_SESSION['no_step']);
				?>">
			<td><a  class="btn btn-inverse" style='text-align:center;vertical-align:middle' href="do_procedure.php?procedure_name=<?php echo htmlentities($photo->procedure_name); ?>&serial_num=<?php echo htmlentities($result['machine_serial']); ?>&type_maint=<?php echo $photo->type_maint; ?>"><img src="<?php echo htmlentities($photo->procedure_URL); ?>" width=200></br><h4><?php echo $procedure_name; ?></h4></a></td>
		    <td style='vertical-align:middle'><h2><?php echo $machine_result['machine_num']; ?></h2></td>
		    <td style='vertical-align:middle'><h4><?php echo $photo->machine_type; ?></h4></td>
		    <td style='vertical-align:middle'><h4><?php echo $result['machine_serial']; ?></h4></td>
		</tr>
		<tr>
			<td colspan="4">							
				<div class="progress <?php echo htmlentities($type_bar); ?>">
				  <div class="bar" style="width: <?php echo htmlentities($percent_undone); ?>%;"></div>
				</div>
		<?php 
	} while ($result=$query->fetch_assoc());
	endforeach;
	?>
	</table>
</div>
<?php include_layout_template('admin_footer.php'); ?>