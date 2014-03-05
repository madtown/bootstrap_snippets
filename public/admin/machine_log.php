<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } 
elseif ($_SESSION['rank'] > 4 ) { 
	$_SESSION['message'] = "You are not authorized to view maintenance logs.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}?>
<?php
	global $logClass;
	$machineClass = new Machine();
	if (isset($_POST['page'])) {
		$page = trim($_POST['page']);
	} else {
		$page = 0;
	}
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	} else { $page = 0; }
	$serial_num = $_GET['serial_num'];
	$machine = $machineClass->find_by_serial_num($serial_num);
 	$procedures = $logClass->find_machine_procedures($page, $serial_num);
	$notes = $logClass->find_machine_notes($page, $serial_num);
 	$edits = $logClass->find_machine_else($page, $serial_num);
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
    <div class="container col-md-12 col-md-offset-0">
	<div class="col-md-12 col-md-offset-0">
    <div class="span12" data-spy="affix">
	<table>
    	<tr><td>
            <div class="btn-group">
                    <a href="machine_log.php?serial_num=<?php echo htmlentities($serial_num); ?><?php if ($page==0) {} else {echo htmlentities("&page="); echo htmlentities($page-1);} ?>" class="btn btn-default"<?php 
                                            if ($page == 0) {
                                                echo " disabled";
                                            }?>><i class="icon-chevron-left"></i> Prev</a>
                    <a class="btn btn-default"><?php 
                                            if ($page == 0) {
                                                echo "1-10";
                                            } else {
                                                $min = ($page*10)+1;
                                                $max = ($page*10)+10;
                                                echo $min."-".$max;
                                            }
                                                ?></a>
                    <a href="machine_log.php?serial_num=<?php echo htmlentities($serial_num); ?>&page=<?php echo htmlentities($page+1); ?>" class="btn btn-default">Next <i class="icon-chevron-right"></i></a>
            </div>
		</td></tr>
        <tr><td>
        	<a class="btn btn-primary btn-block" href="view_all_machines.php"><i class="icon-cogs"></i> View All Machines</a>
		</td></tr>
        <tr><td>
        	<a class="btn btn-info btn-block" href="search_machine.php"><i class="icon-search"></i> Search Machines</a>
		</td></tr>
	</table>
</div>
	<div class="center-text"><h1>
    	<span class="icon-stack" style="font-size:17px;vertical-align:middle;">
            <i class="icon-archive icon-stack-base"></i>
            <i class="icon-light icon-cog icon-small" style="font-size:12px;padding-left: 0px; padding-top: 7px;"></i>
		</span> <?php echo $machine->machine_num; ?> Maintenance Log</h1></div>
	<?php echo output_message($message); ?>
    <div class="center-text">
    	<h3>
    		<i class="icon-check"></i> Completed Procedures
		</h3>
	</div>
	<table class="table table-hover">
	  <tr>
	    <th><i class="icon-search"></i> Details</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-list"></i> Procedure</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-time"></i> Completion Time</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-globe"></i> Average Time</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-cog"></i> Maintenance Type</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-user"></i> User</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-time"></i> Time</th>
	  </tr>
	<?php 	if (isset($procedures)) {?>
	<?php 		foreach($procedures as $procedure): ?>
    <?php
	global $logClass;
	global $stepClass;
	global $machineClass;
	
	$last_step_id = $procedure->id;
	$last_step = $logClass->find_note_by_id($last_step_id);					//returns assoc array of last step in log
	$machine = $machineClass->find_by_serial_num($serial_num);				//returns machine object
	$procedure_detail = $logClass->find_procedure_info($last_step_id);		//returns procedure array
	$procedure_name = $procedure_detail['procedure_name'];			 		//returns procedure name
	$procedure_logs = $logClass->find_procedure_log($last_step, $procedure_detail, $serial_num); //returns all log steps
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
		if ($procedure_log->log_note_URL == "SKIP") {
			$skip = $procedure_log->log_note;
		}
		$time_diff = floor($step_finish - $step_began);
		$total_time = $total_time + $time_diff;
	}
	if ($total_time <= 300) {
		$error = "Click-thru";
	} 
	if (isset($skip)) {
		$error = $skip;
		unset($skip);
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
	$atotal_time = $logClass->find_procedure_log_average($procedure_detail, $serial_num); //returns average
	if ($atotal_time <= 300) {
		$aerror = "Click-thru";
	}
	if ($atotal_time > 86400) {
		$adays = floor($atotal_time / (60 * 60 * 24));
		$atotal_time -= $adays * (60 * 60 * 24);
	}
	if ($atotal_time > 3600) {
		$ahours = floor($atotal_time / (60 * 60));
		$atotal_time -= $ahours * (60 * 60);
	}
	if ($atotal_time > 60) {
		$aminutes = floor($atotal_time / 60);
		$atotal_time -= $aminutes * 60;
	}
	if (isset($atotal_time) and $atotal_time != 0) {
		$aseconds = floor($atotal_time);
		$atotal_time -= $aseconds;
	}	
?>
	  <tr<?php $class = " class='invalid'";
	  if(isset($error) and $error != "Start") {echo htmlentities($class);} ?>>
	    <td style='text-align:center;vertical-align:middle'><a href="view_procedure_detail.php?id=<?php echo htmlentities($procedure->id);?>&serial_num=<?php echo htmlentities($serial_num); ?>"><i class="icon-bar-chart icon-3x"> </i></td>
	    <td style='text-align:center;vertical-align:middle'><small><?php echo $procedure->log_db_action; ?></small>
		</td>
        <td style='text-align:center;vertical-align:middle'>
        <h4> 
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
        ?>
        <p class="text-error"><?php 
        if (isset($error)) {
            echo "{$error}";
            unset($error);
        }?>
        </p>
        </h4>
        </td>
        <td style='text-align:center;vertical-align:middle'>
        <h4> 
        <?php //TOTAL TIME
        if (isset($adays)) {
            echo "{$adays} Days ";
        }
        if (isset($ahours)) {
            echo "{$ahours}h ";
        }
        if (isset($aminutes)) {
            echo "{$aminutes}m ";
        }
        if (isset($aseconds)) {
            echo "{$aseconds}s";
        }
        unset($adays);
        unset($ahours);
        unset($aminutes);
        unset($aseconds);
        ?>
        <p class="text-error"><?php 
        if (isset($aerror)) {
            echo "{$aerror}";
            unset($aerror);
        }?>
        </p>
        </h4>
        </td>
	    <td style='text-align:center;vertical-align:middle'><h4><?php
			switch($procedure->log_maint_type) {
				case "36000":
					echo "Daily";
					break;
				case "770400":
					echo "Weekly";
					break;						
				case "2593740":
					echo "Monthly";
					break;
				case "31524000":
					echo "Annual";
					break;
				case "1":
					echo "Emergency";
					break;
				case "2":
					echo "Special";
					break;
				case "3":
					echo "Other";
					break;
				default:
					echo "Custom";
					break;
			}
						?></h4></td>
	    <td style='text-align:center;vertical-align:middle'><h4><?php
				echo $procedure->log_username; 
			?></h4>
		</td>
	    <td style='text-align:center;vertical-align:middle'>
        	<strong><?php echo datetime_day_of_week($procedure->log_timestamp); ?></strong></br>
			<small><?php echo datetime_to_text($procedure->log_timestamp); ?></small>
        </td>
	  </tr>
	<?php endforeach; ?>
    <?php	} ?>
	</table>
	<br />
    <div class="center-text">
    	<h3>
    		<i class="icon-edit"></i> Machine Notes
		</h3>
	</div>
    <table class="table table-striped table-hover">
	  <tr>
	    <th><i class="icon-search"></i> Details</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-align-justify"></i> Text</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-user"></i> User</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-time"></i> Time</th>
	  </tr>
	<?php 	if (isset($notes)) {?>
	<?php 		foreach($notes as $note): 
	?>
	  <tr>
	    <td style='text-align:center;vertical-align:middle'><a href="view_note.php?id=<?php echo htmlentities($note->id);?>&serial_num=<?php echo htmlentities($serial_num); ?>"><?php if ($note->log_note_URL != NULL and $note->log_note_URL != "") {?>
			<img src="<?php echo htmlentities($note->log_note_URL); ?>" width="75"/>
			<?php } else { ?>
				<i class="icon-file-text icon-4x"></i>
				<?php } ?></a></td>
	    <td style='text-align:center;vertical-align:middle'><small><?php echo $note->log_note; ?></small>
		</td>
	    <td style='text-align:center;vertical-align:middle'><h4><?php
				echo $note->log_username; 
			?></h4>
		</td>
	    <td style='text-align:center;vertical-align:middle'>
        	<strong><?php echo datetime_day_of_week($note->log_timestamp); ?></strong></br>
			<small><?php echo datetime_to_text($note->log_timestamp); ?></small>
        </td>
	  </tr>
	<?php endforeach; ?>
    <?php	}?>
	</table>
    <div class="center-text">
    	<h3>
    		<i class="icon-list"></i> Changelog
		</h3>
	</div>
    <table class="table table-striped table-hover">
	  <tr>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-wrench"></i> Action</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-user"></i> User</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-time"></i> Time</th>
	  </tr>
	<?php 	if (isset($edits)) {?>
	<?php 		foreach($edits as $edit): ?>
	  <tr>
	    <td style='text-align:center;vertical-align:middle'><?php echo $edit->log_action; ?>
		</td>
	    <td style='text-align:center;vertical-align:middle'><h4><?php echo $edit->log_username; ?></h4>
		</td>
	    <td style='text-align:center;vertical-align:middle'>
			<strong><?php echo datetime_day_of_week($edit->log_timestamp); ?></strong></br>
			<small><?php echo datetime_to_text($edit->log_timestamp); ?></small>
        </td>
	  </tr>
	<?php endforeach; ?>
    <?php	} ?>
	</table>
	<a class="btn btn-info" href="search_machine_log.php"><i class="icon-chevron-left"></i> Back to Choose Machine</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>