<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } 
elseif ($_SESSION['rank'] > 4 ) { 
	$_SESSION['message'] = "You are not authorized to view maintenance log details.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}?>
<?php
	global $logClass;
	global $stepClass;
	global $machineClass;
	$last_step_id = $_GET['id'];
	$serial_num = $_GET['serial_num'];
	$last_step = $logClass->find_note_by_id($last_step_id);					//returns assoc array of last step in log
	$machine = $machineClass->find_by_serial_num($serial_num);				//returns machine object
	$procedure = $logClass->find_procedure_info($last_step_id);				//returns procedure array
	$procedure_name = $procedure['procedure_name'];			 				//returns procedure name
	$procedure_logs = $logClass->find_procedure_log($last_step, $procedure, $serial_num); //returns all log steps
	$procedure_standard = $stepClass->find_by_procedure($procedure_name);	//returns all steps
	$total_time = 0;
	foreach($procedure_logs as $procedure_log) {
		global $stepClass;
		$step_num = $procedure_log->log_action;
		$array_num = ($procedure_log->log_action)-1;
		$step_standard = $procedure_standard[$array_num];
		$step_details = $stepClass->find_procedure_step($procedure_name, $step_num);
		$step_finish = strtotime($procedure_log->log_timestamp);
		if ($step_num == 1) {
			$step_began = strtotime($procedure_log->log_timestamp);
		} else {
			$prev_step_num = ($procedure_log->log_action) - 2;
			$previous_step_object = ($procedure_logs[$prev_step_num]);
			$step_began = strtotime($previous_step_object->log_timestamp) ;  
		}  
		$time_diff = floor($step_finish - $step_began);
		$total_time = $total_time + $time_diff;
	}
	//$procedure_log_averages = $logClass->find_procedure_log_average($last_step, $procedure, $serial_num); //returns averages
	if ($total_time <= 300) {
		$error = "Click-thru";
	}
	if ($procedure_log->log_note_URL == "SKIP") {
		$error = $procedure_log->log_note;
	}
	if ($total_time > 86400) {
		$days = floor($total_time / (60 * 60 * 24));
		$total_time -= $days * (60 * 60 * 24);
	}
	if ($total_time > 3600) {
		$hours = floor($total_time / (60 * 60));
		$total_time -= $hours * (60 * 60);
	}
	if ($total_time > 60) {
		$minutes = floor($total_time / 60);
		$total_time -= $minutes * 60;
	}
	if (isset($total_time) and $total_time != 0) {
		$seconds = floor($total_time);
		$total_time -= $seconds;
	}	
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container col-md-12 col-md-offset-0">
<div class="col-md-12 col-md-offset-0">	
<div class="center-text"><h1>
    	<i class="icon-list-ol"></i> <?php echo $machine->machine_num; ?> Procedure Details</h1></div>
	<?php echo output_message($message); ?>
    <div class="center-text">
        <h2>Completion Time: 
        <?php //TOTAL TIME
        if (isset($days)) {
            echo "{$days} Days ";
        }
        if (isset($hours)) {
            echo "{$hours}h ";
        }
        if (isset($minutes)) {
            echo "{$minutes}m ";
        }
        if (isset($seconds)) {
            echo "{$seconds}s";
        }
        unset($days);
        unset($hours);
        unset($minutes);
        unset($seconds);
        ?><p class="text-danger"><?php 
        if (isset($error)) {
            echo "{$error}";
            unset($error);
        }?>
        </h2>
        <h4>
    		<i class="icon-wrench"></i> <?php echo $procedure_name; ?> completed <?php echo datetime_to_text($last_step['log_timestamp']); ?>
		</h4>
	</div>
	<table class="table table-striped table-hover">
	  <tr>
	    <th style='text-align:center;vertical-align:middle'>Step &#35 </th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-time"></i> Step Time</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-tasks icon-rotate-270"></i> Average Time</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-edit"></i> Note</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-book"></i> Step Instructions</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-user"></i> User</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-time"></i> Completed</th>
	  </tr>
	<?php 	if (isset($procedure_logs)) {?>
	<?php 	foreach($procedure_logs as $procedure_log): ?>
    <?php
			if ($procedure_log->log_note_URL == "SKIP") {
				continue;
			}
			global $stepClass;
			$step_num = $procedure_log->log_action;
			$array_num = ($procedure_log->log_action)-1;
			$step_standard = $procedure_standard[$array_num];
			$step_details = $stepClass->find_procedure_step($procedure_name, $step_num);
			$step_finish = strtotime($procedure_log->log_timestamp);
			if ($step_num == 1) {
				$step_began = strtotime($procedure_log->log_timestamp);
			} else {
				$prev_step_num = ($procedure_log->log_action) - 2;
				$previous_step_object = ($procedure_logs[$prev_step_num]);
				$step_began = strtotime($previous_step_object->log_timestamp) ;  
			}  
			$time_diff = floor($step_finish - $step_began);
			$time = $step_finish - $step_began; // time duration in seconds
			if ($time > 86400) {
				$days = floor($time / (60 * 60 * 24));
				$time -= $days * (60 * 60 * 24);
			}
			if ($time > 3600) {
				$hours = floor($time / (60 * 60));
				$time -= $hours * (60 * 60);
			}
			if ($time > 60) {
				$minutes = floor($time / 60);
				$time -= $minutes * 60;
			}
			if (isset($time) and $time != 0) {
				$seconds = floor($time);
				$time -= $seconds;
			}
			if ($time_diff <= 10 and $time_diff != 0) {
				$error = "Click-thru";
			} elseif ($time_diff == 0) {
				$error = "Start";
			} 
			
	?>
	  <tr<?php $class = " class='danger'";
	  if(isset($error) and $error != "Start") {echo htmlentities($class);} ?>>
	    <td style='text-align:center;vertical-align:middle'><h1><?php echo $procedure_log->log_action; ?></h1></td>
	    <td style='text-align:center;vertical-align:middle'><h4><?php 
			if (isset($days)) {
				echo "{$days} Days ";
			}
			if (isset($hours)) {
				echo "{$hours}h ";
			}
			if (isset($minutes)) {
				echo "{$minutes}m ";
			}
			if (isset($seconds)) {
				echo "{$seconds}s";
			}
			unset($days);
			unset($hours);
			unset($minutes);
			unset($seconds);
			?></h4><h4><p class="text-error"><?php 
			if (isset($error)) {
				echo "{$error}";
				unset($error);
			}?></p></h4></td>
	    <td style='text-align:center;vertical-align:middle'><?php
				echo "Data Insufficient"; 
			?></td>
      	<td style='text-align:center;vertical-align:middle'><?php
				if ($procedure_log->log_note_URL != "") {
					?><a href="view_note.php?id=<?php echo htmlentities($procedure_log->id);?>&serial_num=<?php echo htmlentities($serial_num);?>"><img src="<?php echo htmlentities($procedure_log->log_note_URL);?>" alt="Image Not Found"></a><?php
				}
				echo $procedure_log->log_note; 
			?></td>
        <td width="25%" style='text-align:left;vertical-align:middle'><small><?php
				echo $step_standard->instruction; 
			?></small></td>
   		<td style='text-align:center;vertical-align:middle'><?php
				echo $procedure_log->log_username; 
			?></td>
	    <td style='text-align:center;vertical-align:middle'><small><?php echo datetime_to_text_date($procedure_log->log_timestamp); ?></small>
        </td>
	  </tr>
	<?php endforeach; ?>
    <?php	} ?>
	</table>
	<a class="btn btn-info" href="machine_log.php?serial_num=<?php echo $machine->serial_num; ?>"><i class="icon-chevron-left"></i> Back to <?php echo $machine->machine_num; ?> Log</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>