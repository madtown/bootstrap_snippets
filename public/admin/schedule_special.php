<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php
	global $machineClass;
	if (isset($_GET['user_id'])) {
		$my_machines = $machineClass->find_my_machines();
	}
	$procedureClass = new Procedure();
	$photos=$procedureClass->find_special();
?>
<script type="text/javascript">

function proceed(form) {
  var el, els = form.getElementsByTagName('input');
  var i = els.length;
  while (i--) {
    el = els[i];

    if (el.type == 'checkbox' && !el.checked) {
      form.proceedButton.disabled = true;
      return; 
     }
  }
  form.proceedButton.disabled = false;
}

</script>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="col-md-12 col-md-offset-0">
	<div class="center-text"><h1><i class="icon-star text-warning"></i> Special Maintenance</h1></div>
	<?php echo output_message($message); ?>
	<table class="table table-striped table-hover">
		<tr>
		    <th>Do Procedure</th>
		    <th>Machine</th>
		    <th>Average</th>
		    <th>Machine Type</th>
		    <th>Skip</th>
		</tr>
	<?php foreach($photos as $photo): 
	$procedure_name = $photo->procedure_name;
	$query = $procedureClass->find_serials($procedure_name);
	$result = $query->fetch_assoc();
	do {
		if (isset($my_machines)) {
			$key = recursive_array_search($result['machine_serial'], $my_machines);
			if ($key != true) {
				continue 1;
			}
		}
		$serial_num = $result['machine_serial'];
		$machine_query = $procedureClass->find_machine_num_by_serial($serial_num);
		$machine_result = $machine_query->fetch_assoc();
		$procedure_detail = array("procedure_name" => $procedure_name, "type_maint" => $photo->type_maint);
		$atotal_time = $logClass->find_procedure_log_average($procedure_detail, $serial_num); //returns average
		if ($atotal_time <= 300) {
			$aerror = "Not Set";
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
		<tr class="<?php 
			$procedure_name = $photo->procedure_name;
			$serial_num = $result['machine_serial'];
			$string = $procedure_name.$serial_num;
			$procedure_nameserial_num = str_replace(' ', '', $string);
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
				$type_bar = " progress-success";
				$type_color = " progress-bar-success";
				$percent_undone = "100";
			} elseif (!empty($undone_steps)) {
				echo "warning";
				$type_bar = " progress-warning progress-striped active";
				$type_color = " progress-bar-warning";
			} elseif (empty($undone_steps) and $no_step = true) { 
				echo "info";
				$type_bar = " progress-info progress-striped active";
				$type_color = " progress-bar-info";
			} 
			unset($_SESSION['no_step']);
				?>">
			<td><a  class="btn btn-primary" style='text-align:center;vertical-align:middle' data-target="#start<?php echo $procedure_nameserial_num; ?><?php if($type_bar != " progress-warning progress-striped active") { echo htmlentities("nottoday");}?>" data-toggle="modal"<?php if($type_bar != " progress-warning progress-striped active") { echo htmlentities(" disabled");}?>><img src="<?php echo htmlentities($photo->procedure_URL); ?>" width=200></br><h4><?php echo $procedure_name; ?></h4></a>
            <!-- Start Warning Modal -->
            <div id="start<?php echo $procedure_nameserial_num; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            	<div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3 class="modal-title"><strong>Procedure: <?php echo htmlentities($photo->procedure_name); ?> for <?php echo $machine_result['machine_num']; ?></strong></h3>
                        </div><!-- /.modal-header -->
                        <div class="modal-body">
                            <p><strong>Are you sure you are ready to begin the procedure?</strong></p></br>
                            <ul>
                                <li><i class="icon-wrench"></i> Do you have all the tools you need to begin?</p>  
                                <li><i class="icon-user"></i> Are you logged in as yourself? If not log out and log back in before you proceed. When you are finished using the program remember to log out! You are responsible for what is done on your account.</p>
                                <li><i class="icon-time"></i> Do you know how long the procedure should take? Have you set the timer and alerted the operator and manager that you are beginning?</li>
                                <li><i class="icon-warning-sign"></i> Do you understand all of the safety features of the machine? If not, or if you have a question, please ask your supervisor before you begin. Safety is the number one priority!</li> 
                            </ul>
                            <p>To begin the Procedure click <button class="btn btn-success">Begin Procedure <i class="icon-play"></i></button> below.</p>
                        </div><!-- /.modal-body -->
                        <div class="modal-footer">
                            <button class="btn btn-sm pull-left" data-dismiss="modal" aria-hidden="true"><h4><i class="icon-reply"></i> Nevermind</h4></button>
                            <form action="do_procedure.php?procedure_name=<?php echo htmlentities($photo->procedure_name); ?>&serial_num=<?php echo htmlentities($result['machine_serial']); ?>&type_maint=<?php echo $photo->type_maint; ?>" method="post">
                          	<button class="btn btn-success" type="submit" name="start"><h4>Begin Procedure <i class="icon-play"></i></h4></button>
                            </form>
                        </div><!-- /.modal-footer -->
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Warning Modal -->
            </td>
		    <td style='vertical-align:middle'><h2><?php echo $machine_result['machine_num']; ?></h2></td>
		    <td style='text-align:center;vertical-align:middle'>
                <h3> 
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
                </h3>
            </td>
		    <td style='text-align:center;vertical-align:middle'><h4><?php 
				if (is_numeric($photo->machine_type)){
					$type_id = $photo->machine_type;
					$machine_type = $machineClass->find_type_by_id($type_id);
					$type_array= $machine_type->fetch_assoc(); 
					echo $type_array['name'];
				} else {
				echo $photo->machine_type;} ?></h4>
         	</td>
		    <td style='vertical-align:middle'>
            <h4>
			<a class="btn btn-primary" style='text-align:center;vertical-align:middle' data-target="#<?php echo $procedure_nameserial_num; ?><?php if($type_bar != " progress-warning progress-striped active") { echo htmlentities("nottoday");}?>" data-toggle="modal"<?php if($type_bar != " progress-warning progress-striped active") { echo htmlentities(" disabled");}?>><i class="icon-check icon-5x"> </i></a>
            </h4>
            <!-- Delete Warning Modal -->
            <div id="<?php echo $procedure_nameserial_num; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            	<div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <div class="alert alert-danger">
                                <h3 class="modal-title"><strong>Warning!</strong> You are about to SKIP <?php $machine_result['machine_num']?>!</h3>
                            </div><!-- /.alert-danger -->
                        </div><!-- /.modal-header -->
                        <div class="modal-body">
                            <div class="alert alert-danger">        
                                <p>Are you sure you want to SKIP for the current time period Machine: <?php echo $machine_result['machine_num']; ?>?</p></br>
                                <p><i class="icon-warning-sign"></i> <strong>Consequences</strong></p>
                                <ul>
                                    <li>This action CANNOT be undone.</li>
                                    <li>You will not be able to do this procedure on this machine until the end of the time interval.</li>
                                    <li>Be especially sure if you are skipping a procedure with a long interval like Monthly or Annual.</li>
                                    <li>This will be noted as a skip.</li>
                                </ul>   
                                <p><i class="icon-cloud"></i> <strong>Silver Lining</strong></p>
                                <ul>             
                                    <li>This will NOT affect the other machines.</li> 
                                </ul>
                                <p>To proceed with the SKIP, select a reason check the box below, and click <button class="btn btn-danger"><i class="icon-check"></i></button>.</p>
                            </div><!-- /.alert-danger -->
                        </div><!-- /.modal-body -->
                        <div class="modal-footer">
                            <button class="btn btn-sm pull-left" data-dismiss="modal" aria-hidden="true"><h4><i class="icon-reply"></i> Nevermind</h4></button>
                            <form onclick="proceed(this);" action="skip_procedure.php?procedure_name=<?php echo htmlentities($procedure_name); ?>&serial_num=<?php echo htmlentities($serial_num); ?>&type_maint=<?php echo htmlentities($type_maint); ?>" method="post">
                            <table><td>
                                <select class="form-control" name="reason"> 
                                    <option value="">None Selected</option>
                                    <option value="Machine Not in Service">Machine Not in Service</option>
                                    <option value="Machine Being Moved">Machine Being Moved</option>
                                    <option value="Machine is Broken">Machine is Broken</option>
                                    <option value="Machine is undergoing other maintenance">Machine is undergoing other maintenance</option>
                                    <option value="This Maintenance was already done without the program">This Maintenance was already done without the program</option>
                                    <option value="Other">Other</option>
                                </select>
                            </td></table>
                              <input type="checkbox" name="understood" value="understood">I understand the consequences</input>
                              <button class="btn btn-danger" type="submit" name="proceedButton" id="proceedButton" disabled><i class="icon-check icon-3x"></i></button>
                            </form>
                        </div><!-- /.modal-footer -->
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Warning Modal -->
            </td>
		</tr>
		<tr>
			<td colspan="5">	
            <div class="progress<?php echo htmlentities($type_bar); ?>">
                <div class="progress-bar<?php echo htmlentities($type_color); ?>" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo htmlentities($percent_undone); ?>%">
                </div>
            </div>
		<?php 
	} while ($result=$query->fetch_assoc());
	endforeach;
	?>
	</table>
    </div>
<?php include_layout_template('admin_footer.php'); ?>