<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
  // Find all the procedures
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
 	//$edits = $logClass->find_machine_edits();
?>
<?php include_layout_template('navbar.php'); ?>
<?php include_layout_template('admin_header.php'); ?>
<div class="span12" data-spy="affix">
        <div class="btn-group">
                <a href="machine_log.php?serial_num=<?php echo htmlentities($serial_num); ?><?php if ($page==0) {} else {echo htmlentities("&page="); echo htmlentities($page-1);} ?>" class="btn btn-inverse"<?php 
										if ($page == 0) {
											echo " disabled";
										}?>><i class="icon-chevron-left"></i> Prev</a>
                <a class="btn btn-inverse"><?php 
										if ($page == 0) {
											echo "1-10";
										} else {
											$min = ($page*10)+1;
											$max = ($page*10)+10;
											echo $min."-".$max;
										}
											?></a>
                <a href="machine_log.php?serial_num=<?php echo htmlentities($serial_num); ?>&page=<?php echo htmlentities($page+1); ?>" class="btn btn-inverse">Next <i class="icon-chevron-right"></i></a>
        </div>
    </div>
    <div class="span12 offset0 well">
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
	<table class="table table-striped table-hover">
	  <tr>
	    <th><i class="icon-search"></i> Details</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-list"></i> Procedure</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-cog"></i> Maintenance Type</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-user"></i> User</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-time"></i> Time</th>
	  </tr>
	<?php 	if (isset($procedures)) {?>
	<?php 		foreach($procedures as $procedure): ?>
	  <tr>
	    <td style='text-align:center;vertical-align:middle'><i class="icon-bar-chart icon-2x"> </i></td>
	    <td style='text-align:center;vertical-align:middle'><small><?php if (!is_object($procedure)) {
				echo $procedure['log_db_action']; 
			} else { echo $procedure->log_db_action; }?></small>
		</td>
	    <td style='text-align:center;vertical-align:middle'><h4><?php if (!is_object($procedure)) {	
					switch($procedure['log_maint_type']) {
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
		} else {
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
		}
						?></h4></td>
	    <td style='text-align:center;vertical-align:middle'><h4><?php if (!is_object($procedure)) {	
			echo $procedure['log_username']; 
			} else {
				echo $procedure->log_username; 
			}?></h4>
		</td>
	    <td style='text-align:center;vertical-align:middle'><small><?php if (!is_object($procedure)) {	
			echo datetime_to_text($procedure['log_timestamp']); 
			} else {
				echo datetime_to_text($procedure->log_timestamp);
			}?></small>
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
	    <th style='text-align:center;vertical-align:middle'><i class="icon-list"></i> Procedure</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-user"></i> User</th>
	    <th style='text-align:center;vertical-align:middle'><i class="icon-time"></i> Time</th>
	  </tr>
	<?php 	if (isset($notes)) {?>
	<?php 		foreach($notes as $note): ?>
	  <tr>
	    <td style='text-align:center;vertical-align:middle'><i class="icon-file-text icon-2x"> </i></td>
	    <td style='text-align:center;vertical-align:middle'><small><?php if (!is_object($note)) {
				echo $note['log_db_action']; 
			} else { echo $note->log_db_action; }?></small>
		</td>
	    <td style='text-align:center;vertical-align:middle'><h4><?php if (!is_object($note)) {	
			echo $note['log_username']; 
			} else {
				echo $note->log_username; 
			}?></h4>
		</td>
	    <td style='text-align:center;vertical-align:middle'><small><?php if (!is_object($note)) {	
			echo datetime_to_text($note['log_timestamp']); 
			} else {
				echo datetime_to_text($note->log_timestamp);
			}?></small>
        </td>
	  </tr>
	<?php endforeach; ?>
    <?php	} ?>
	</table>
	<a class="btn btn-info" href="search_machine_log.php"><i class="icon-chevron-left"></i> Back to Choose Machine</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>
