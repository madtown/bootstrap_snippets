<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } 
elseif ($_SESSION['rank'] > 4 ) { 
	$_SESSION['message'] = "You are not authorized to view maintenance logs.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}?>
<?php
	//Find the current page 
	global $logClass;
	global $requestClass;
	$procedureClass = new Procedure();
	$machineClass = new Machine();
	if (isset($_GET['page'])) {
		$page = trim($_GET['page']);
		$date_unix = mktime(0, 0, 0, date("m"), date("d")+((7)*$page), date("Y"));
		$date_unix_end = mktime(0, 0, 0, date("m"), date("d")+((7)*$page)+7, date("Y"));
		$date_text = strftime('%B %d, %Y', $date_unix);
		$date_text_end = strftime('%B %d, %Y', $date_unix_end);
	} elseif (isset($_GET['date'])) {
		$date_unix = $_GET['date'];
		$date_unix_end = $date_unix + (60*60*24*7);//one week
		$weeks = floor((((time() - $date_unix)/60)/60)/24/7);
		$page = $weeks*(-1);
		$date_text = strftime('%B %d, %Y', $date_unix);
		$date_text_end = strftime('%B %d, %Y', $date_unix_end);
	} else {
		$day_of_the_week = date("w");
		if ($day_of_the_week = 1) {
			$page = 0;
			$date_unix = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
			$date_unix_end = mktime(0, 0, 0, date("m"), date("d")+7, date("Y"));
			$date_text = date('F jS, Y');
			$date_text_end = strftime('%B %d, %Y', $date_unix_end);
		} else {
			$page = 0;
			if ($day_of_the_week = 0) {
				$day_multiplier = -6;
			} else {
				$day_multiplier = $day_of_the_week-1;
			}
			$date_unix = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
			$date_unix_end = mktime(0, 0, 0, date("m"), date("d")+7, date("Y"));
			$date_text = date('F jS, Y');
			$date_text_end = strftime('%B %d, %Y', $date_unix_end);
		}
			
	}
	//Find this day's requests
	$requests = $requestClass->find_my_locations_resolved_requests($date_unix, $date_unix_end);
	$undone_procedures = $procedureClass->find_my_overdue_in_time($date_unix, $date_unix_end);
	//Find undone procedures 
	global $machineClass;
	if (isset($_GET['user_id'])) {
		$my_machines = $machineClass->find_my_machines();
	}
	$procedureClass = new Procedure();
	$procedures = $procedureClass->find_daily();
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container">
	<div class="row">
    	<div class="col-md-6">
            <h1 class="center-text"><i class="icon-sun"></i> My Weekly Report</h1>
      	</div>
     	<div class="col-md-6 center-text">
            <a href="report_weekly.php" class="btn btn-default pull-right"><i class="icon-calendar"></i> Today</a>
            <div class="btn-group">
                <a href="report_weekly.php?page=<?php echo htmlentities($page-1); ?>" class="btn btn-inverse"><i class="icon-chevron-left"></i> Prev</a>
                <a class="btn btn-inverse"><?php 
                        echo $date_text."-".$date_text_end;
                        ?></a>
                <a href="report_weekly.php?page=<?php echo htmlentities($page+1); ?>" class="btn btn-inverse"<?php if ($page == 0) {
                        echo " disabled";
                    }?>>Next <i class="icon-chevron-right"></i></a>
            </div>	
        </div>
  	</div>
    <div class="row">
    	<div class="col-md-6 panel-group" id="requests">
        	<div class="panel panel-primary">
            	<div class="panel-heading center-text"><h4>Overdue Procedures<span class="label label-info pull-right"><?php 
                        if (isset($undone_procedures) && count($undone_procedures) != 0) {
                        	echo count($undone_procedures);
						} else {
							echo 0;
						}
                            ?>
                        </span></h4></div>
                <?php if (isset($undone_procedures) && count($undone_procedures) != 0) {  ?>
                <div class="table">
                    <table class="table table-hover table-condensed">
                        <thead>
                          <tr>
                            <th style='text-align:center;vertical-align:middle'><i class="icon-list-ol"></i> Procedure</th>
                            <th style='text-align:center;vertical-align:middle'>Machine &#35 </th>
                            <th style='text-align:center;vertical-align:middle'>Completed Last <i class="icon-time"></i></th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php 	if (isset($undone_procedures)) {?>
                            <?php 	foreach($undone_procedures as $procedure): 
                            ?>
                              <tr>
                                <td style='text-align:center;vertical-align:middle;'><h4>#<?php echo $procedure['log_db_action']; ?></h4></td>
                                <td style='text-align:center;vertical-align:middle;'><h2><?php $machine = $machineClass->find_by_serial_num($procedure['log_serial_num']); echo $machine->machine_num; ?></h2></td>
                           		<td style='text-align:center;vertical-align:middle;'><small><?php $most_recent = strftime('%A, %B %d, %Y', $procedure['log_maint_type']); echo $most_recent; ?></small></td>

                            <?php 
                            endforeach; ?>
                            <?php } ?>
                            </tr>	
                        </tbody>
                    </table>
                </div>
                <?php } ?>
            </div>
            <div class="panel panel-danger">
                <div class="panel-heading center-text">
                	<a data-toggle="collapse" data-parent="#requests" href="#ca">
                        <h4><i class="icon-warning-sign"></i> Corrective Actions<span class="label label-danger pull-right"><?php 
                        $number_of_ca = 0;
                        foreach($requests as $request): 
                            $resolution = $requestClass->find_request_resolution($request['request_id']);
                            if ($resolution['status_radio'] == 3) {
                                $number_of_ca++;
                            }
                        endforeach;
                        echo $number_of_ca;
                            ?>
                        </span></h4>
                	</a>
             	</div>
                <div id="ca" class="panel-collapse collapse<?php if ($number_of_ca != 0) { echo " in";}?>">
                    <div class="table">
                        <table class="table table-hover table-condensed">
                            <thead>
                              <tr>
                                <th style='text-align:center;vertical-align:middle'></th>
                                <th style='text-align:center;vertical-align:middle'>&#35 ID</th>
                                <th style='text-align:center;vertical-align:middle'>Short</th>
                                <th style='text-align:center;vertical-align:middle'>Cause</th>
                                <th style='text-align:center;vertical-align:middle'>Summary</th>
                             	<th style='text-align:center;vertical-align:middle'>Completion <i class="icon-time"></i></th>

                              </tr>
                            </thead>
                            <tbody data-link="row" class="rowlink">
                                <?php 	if (isset($requests)) {?>
                                <?php 	foreach($requests as $request): 
                                    $resolution = $requestClass->find_request_resolution($request['request_id']);
                                    if ($resolution['status_radio'] == 3) {
										$completion_time = secs_to_h(strtotime($resolution['post_timestamp']) - strtotime($request['request_timestamp']));

                                ?>
                                  <tr>
                                    <td style='text-align:center;vertical-align:middle;'>
                                        <a href="./work.php?request_id=<?php echo htmlentities($request['request_id'])?>"></a>
                                    </td>
                                    <td style='text-align:center;vertical-align:middle;'><h2>#<?php echo $request['request_id']?></h2></td>
                                    <td style='text-align:center;vertical-align:middle;' width="10%"><small><?php echo $request['short']?></small></td>
                                    <td style='text-align:center;vertical-align:middle;'><small><?php echo $resolution['text_2']?></small></td>
                                    <td style='text-align:left;vertical-align:middle' width="35%"><small><?php echo $resolution['text']?></small></td>
                               		<td style='text-align:left;vertical-align:middle'><small><?php echo $completion_time; ?></small></td>

                                <?php 
                                    }
                                endforeach; ?>
                                <?php } ?>
                                </tr>	
                            </tbody>
                        </table>
                    </div>
            	</div>
            </div>
            <div class="panel panel-warning">
                <div class="panel-heading center-text">
                	<a data-toggle="collapse" data-parent="#requests" href="#preventative">
                		<h4><i class="icon-check-empty"></i> Preventable<span class="label label-warning pull-right"><?php
					$number_of_p = 0;
					foreach($requests as $request): 
						$resolution = $requestClass->find_request_resolution($request['request_id']);
						if ($resolution['status_radio'] == 2) {
							$number_of_p++;
						}
					endforeach;
					echo $number_of_p;
						?>
                	</span></h4>
                 	</a>
            	</div>
                <div id="preventative" class="panel-collapse collapse<?php if ($number_of_p != 0) { echo " in";}?>">
                    <div class="table">
                        <table class="table table-hover table-condensed">
                            <thead>
                              <tr>
                                <th style='text-align:center;vertical-align:middle'></th>
                                <th style='text-align:center;vertical-align:middle'>&#35 ID</th>
                                <th style='text-align:center;vertical-align:middle'>Short</th>
                                <th style='text-align:center;vertical-align:middle'>Cause</th>
                                <th style='text-align:center;vertical-align:middle'>Summary</th>
                                <th style='text-align:center;vertical-align:middle'>Completion <i class="icon-time"></i></th>
                              </tr>
                            </thead>
                            <tbody data-link="row" class="rowlink">
                                <?php 	if (isset($requests)) {?>
                                <?php 	foreach($requests as $request): 
                                    $resolution = $requestClass->find_request_resolution($request['request_id']);
                                    if ($resolution['status_radio'] == 2) {
										$completion_time = secs_to_h(strtotime($resolution['post_timestamp']) - strtotime($request['request_timestamp']));
                                ?>
                                  <tr>
                                    <td style='text-align:center;vertical-align:middle;'>
                                        <a href="./work.php?request_id=<?php echo htmlentities($request['request_id'])?>"></a>
                                    </td>
                                    <td style='text-align:center;vertical-align:middle;'><h2>#<?php echo $request['request_id']?></h2></td>
                                    <td style='text-align:center;vertical-align:middle;' width="10%"><small><?php echo $request['short']?></small></td>
                                    <td style='text-align:center;vertical-align:middle;'><small><?php echo $resolution['text_2']?></small></td>
                                    <td style='text-align:left;vertical-align:middle' width="35%"><small><?php echo $resolution['text']?></small></td>
                                    <td style='text-align:left;vertical-align:middle'><small><?php echo $completion_time; ?></small></td>
                                <?php 
                                    }
                                endforeach; ?>
                                <?php } ?>
                                </tr>	
                            </tbody>
                        </table>
                    </div>
             	</div>
            </div>
            <div class="panel panel-success">
                <div class="panel-heading center-text">
                	<a data-toggle="collapse" data-parent="#requests" href="#unavoidable">
                    	<h4><i class="icon-check"></i> Unavoidable<span class="label label-success pull-right"><?php
					$number_of_u = 0;
					foreach($requests as $request): 
						$resolution = $requestClass->find_request_resolution($request['request_id']);
						if ($resolution['status_radio'] != 2 and $resolution['status_radio'] != 3) {
							$number_of_u++;
						}
					endforeach;
					echo $number_of_u;
						?>
                	</span></h4>
                	</a>
            	</div>
                <div id="unavoidable" class="panel-collapse collapse">
                    <div class="table">
                        <table class="table table-hover table-condensed">
                            <thead>
                              <tr>
                                <th style='text-align:center;vertical-align:middle'></th>
                                <th style='text-align:center;vertical-align:middle'>&#35 ID</th>
                                <th style='text-align:center;vertical-align:middle'>Short</th>
                                <th style='text-align:center;vertical-align:middle'>Cause</th>
                                <th style='text-align:center;vertical-align:middle'>Summary</th>
                                <th style='text-align:center;vertical-align:middle'>Completion <i class="icon-time"></i></th>
                              </tr>
                            </thead>
                            <tbody data-link="row" class="rowlink">
                                <?php 	if (isset($requests)) {?>
                                <?php 	foreach($requests as $request): 
                                    $resolution = $requestClass->find_request_resolution($request['request_id']);
                                    if ($resolution['status_radio'] != 2 and $resolution['status_radio'] != 3) {
										$completion_time = secs_to_h(strtotime($resolution['post_timestamp']) - strtotime($request['request_timestamp']));
                                ?>
                                  <tr>
                                    <td style='text-align:center;vertical-align:middle;'>
                                        <a href="./work.php?request_id=<?php echo htmlentities($request['request_id'])?>"></a>
                                    </td>
                                    <td style='text-align:center;vertical-align:middle;'><h2>#<?php echo $request['request_id']?></h2></td>
                                    <td style='text-align:center;vertical-align:middle;' width="10%"><small><?php echo $request['short']?></small></td>
                                    <td style='text-align:center;vertical-align:middle;'><small><?php echo $resolution['text_2']?></small></td>
                                    <td style='text-align:left;vertical-align:middle' width="35%"><small><?php echo $resolution['text']?></small></td>
                                    <td style='text-align:left;vertical-align:middle'><small><?php echo $completion_time; ?></small></td>
                                <?php 
                                    }
                                endforeach; ?>
                                <?php } ?>
                                </tr>	
                            </tbody>
                        </table>
                    </div>
             	</div>
            </div>
        </div>
        <div class="col-md-6 center-text">
        	<div class="center-text caption">
                <h3>Requests <?php if (isset($requests)) {echo '('.count($requests).')';} ?></h3>
            </div>
        	<div class="thumbnail">
                <canvas id="request_breakdown" width="400" height="400"></canvas>
                <script>
                    <?php 
                    $total = count($requests);
                    if ($total != 0) {
                        $percent_ca = floor(($number_of_ca/$total)*100);
                        $percent_p = floor(($number_of_p/$total)*100);
                        $percent_u = floor(($number_of_u/$total)*100);
                    } else {
                        $percent_ca = 0;
                        $percent_p = 0;
                        $percent_u = 100;
                    }
                    ?>
                    var data = [
                        {
                            value : <?php echo htmlentities($percent_u); ?>,
                            color : "#5cb85c"//green
                        },
                        {
                            value : <?php echo htmlentities($percent_p); ?>,
                            color : "#f0ad4e"//yellow
                        },
                        {
                            value : <?php echo htmlentities($percent_ca); ?>,
                            color : "#d9534f"//red
                        }
                     
                    ];
                    var canvas = document.getElementById("request_breakdown");
                    var ctx = canvas.getContext("2d");
                    new Chart(ctx).Doughnut(data);
                </script>
                <h3>
                    <span class="label label-success"><?php echo htmlentities($percent_u); ?>&#37; Unavoidable</span>
                    <span class="label label-warning"><?php echo htmlentities($percent_p); ?>&#37; Preventable</span>
                    <span class="label label-danger"><?php echo htmlentities($percent_ca); ?>&#37; Corrective Action</span>
                </h3>
            </div><!---End Chart---->
      	</div>
    </div><!---End Row---->
</div><!---End Container---->
<?php include_layout_template('admin_footer.php'); ?>