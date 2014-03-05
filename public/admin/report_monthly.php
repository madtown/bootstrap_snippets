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
	if (isset($_GET['page']) && !isset($_GET['date'])) {
		$page 			= trim($_GET['page']);
		$date_unix 		= mktime(0, 0, 0, date("m")-1+$page, 1, date("Y"));
		$date_unix_end 	= mktime(0, 0, 0, (date("m")+$page), 1, date("Y"));
		$date_text 		= strftime('%B %d, %Y', $date_unix);
		$date_text_end 	= strftime('%B %d, %Y', $date_unix_end);
	} elseif (isset($_GET['date'])) {
		$date_unix 		= mktime(0, 0, 0, date('m', $_GET['date']), 1, date('y', $_GET['date']));
		$date_unix_end 	= mktime(0, 0, 0, date('m', $_GET['date'])+1, 1, date('y', $_GET['date']));
		
		$months_ago = floor((((time() - $date_unix)/60)/60)/24/30.4167)-1;
		$page = $months_ago*(-1);
		
		$date_text 		= strftime('%B %d, %Y', $date_unix);
		$date_text_end 	= strftime('%B %d, %Y', $date_unix_end);
	} else {
		$page 			= 0;
		$date_unix 		= mktime(0, 0, 0, date("m")-1, 1, date("Y"));
		$date_unix_end 	= mktime(0, 0, 0, date("m"), 1, date("Y"));
		$date_text 		= strftime('%B %d, %Y', $date_unix);
		$date_text_end 	= strftime('%B %d, %Y', $date_unix_end);
	}
	//Find this months's info
	$requests 			= $requestClass->find_my_locations_resolved_requests($date_unix, $date_unix_end);
	$undone_procedures 	= $procedureClass->find_my_overdue_in_time($date_unix, $date_unix_end);
	$posts 				= $workClass->find_all_my_posts_in_time($date_unix, $date_unix_end);
	//Find undone procedures 
	global $machineClass;
	$my_machines = $machineClass->find_my_machines();
	$procedures = $procedureClass->find_all_regularly_scheduled();
	$procedure_arrays = array();
	foreach($procedures as $procedure): 
		$procedure_name = $procedure->procedure_name;
		$steps = $stepClass->find_all_steps_by_procedure($procedure_name);
		foreach($steps as $step) {
			$last_procedure_step = $step['step_num'];
		}
		$number_of_steps = $steps->num_rows;
		if ($number_of_steps = 0) {
			continue;//This procedure has no steps associated with it and should be exempt because it could not have been done. This is most likely a procedure in progress.
		}
		$machines = $procedureClass->find_serials($procedure_name);
		$number_of_machines = $machines->num_rows;
		if ($number_of_machines = 0) {
			continue;//This procedure has no machines associated with it and should be exempt because it could not have been done. This is most likely a procedure in progress.
		}
		$machine_array = array();
		foreach($machines as $machine):
			$serial_num = $machine['machine_serial'];
			if (isset($my_machines)) {//see if it is one of my machines otherwise continue
				$key = recursive_array_search($machine['machine_serial'], $my_machines);
				if ($key != true) {
					continue 1;
				}
			}
			$machine_steps_arrays = $logClass->find_procedure_logs_by_serial_num_in_time($procedure_name, $serial_num, $date_unix, $date_unix_end);
			$procedure_time = 0;
			$previous_step = NULL;
			foreach ($machine_steps_arrays as $step) {
				if ($step->log_action == 1) {
					$previous_step = $step;
					continue;
				}
				if ($step->log_action == $previous_step->log_action) {
					$previous_step = $step;
					continue;
				}
				$end_time 		= strtotime($step->log_timestamp);
				$start_time 	= strtotime($previous_step->log_timestamp);
				$end_step 		= next($machine_steps_arrays);
				$total_time 	= $end_time - $start_time;
				$procedure_time = $procedure_time + $total_time;
				$previous_step 	= $step;
				unset($step);
			}
			$machine_array[$serial_num] = $procedure_time;
			unset($machine);
			unset($machine_steps_arrays);
		endforeach;
		$procedure_arrays[$procedure->id] = $machine_array;
		unset($procedure);
	endforeach;
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container">
	<div class="row">
    	<div class="col-md-6">
            <h1 class="center-text"><i class="icon-sun"></i> My Monthly Report</h1>
      	</div>
     	<div class="col-md-6 center-text">
            <a href="report_monthly.php" class="btn btn-default pull-right"><i class="icon-calendar"></i> Today</a>
            <div class="btn-group">
                <a href="report_monthly.php?page=<?php echo htmlentities($page-1); ?>" class="btn btn-inverse"><i class="icon-chevron-left"></i> Prev</a>
                <a class="btn btn-inverse"><?php 
                        echo $date_text."-".$date_text_end;
                        ?></a>
                <a href="report_monthly.php?page=<?php echo htmlentities($page+1); ?>" class="btn btn-inverse"<?php if ($page == 0) {
                        echo " disabled";
                    }?>>Next <i class="icon-chevron-right"></i></a>
            </div>	
        </div>
  	</div>
    <div class="row">
    	<div class="col-md-6 panel-group" id="requests">
        	<div class="panel panel-info">
            	<div class="panel-heading center-text">
                	<a data-toggle="collapse" data-parent="#requests" href="#op"><h4>Overdue Procedures<span class="label label-info pull-right"><?php 
                        if (isset($undone_procedures) && count($undone_procedures) != 0) {
                        	echo count($undone_procedures);
						} else {
							echo 0;
						}
                            ?>
                        </span></h4></a>
              	</div>
         	<div id="op" class="panel-collapse collapse">
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
            </div>
            <div class="panel panel-danger">
                <div class="panel-heading center-text">
                	<a data-toggle="collapse" data-parent="#requests" href="#ca">
                        <h4><i class="icon-warning-sign"></i> Corrective Actions<span class="label label-danger pull-right"><?php 
                        $number_of_ca = 0;
                        $ca_array = array();
                        foreach($requests as $request): 
                            $resolution = $requestClass->find_request_resolution($request['request_id']);
                            if ($resolution['status_radio'] == 3) {
								array_push($ca_array, $request);
                                $number_of_ca++;
                            }
                        endforeach;
                        echo $number_of_ca;
                            ?>
                        </span></h4>
                	</a>
             	</div>
                <div id="ca" class="panel-collapse collapse">
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
                		<h4><i class="icon-check-empty"></i> Preventable<span class="label label-warning pull-right"><?php				$p_array = array();
					$number_of_p = 0;
					foreach($requests as $request): 
						$resolution = $requestClass->find_request_resolution($request['request_id']);
						if ($resolution['status_radio'] == 2) {
							array_push($p_array, $request);
							$number_of_p++;
						}
					endforeach;
					echo $number_of_p;
						?>
                	</span></h4>
                 	</a>
            	</div>
                <div id="preventative" class="panel-collapse collapse">
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
							$u_array = array();
							foreach($requests as $request): 
								$resolution = $requestClass->find_request_resolution($request['request_id']);
								if ($resolution['status_radio'] != 2 and $resolution['status_radio'] != 3) {
									array_push($u_array, $request);
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
            <div class="panel panel-primary center-text">
                <div class="panel-heading center-text">
                	<a style="color: #fff; background: #ff0;" data-toggle="collapse" data-parent="#requests" href="#dt"><h4><i class="icon-time"></i> Down Time Breakdown</h4></a>
            	</div>
             	<div id="dt" class="panel-collapse collapse in">
                	<?php 	if (isset($procedure_arrays)) {?>
					<?php 	foreach($procedure_arrays as $procedure_id => $procedure_array): 
								if (!empty($procedure_array)) {
                                $procedure = $procedureClass->find_procedure_by_id($procedure_id);
                    ?>
                    <h2><?php echo $procedure['procedure_name']; ?></h2>
                    <div class="table">
                        <table class="table table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th style='text-align:center;vertical-align:middle'></th>
                                    <th style='text-align:center;vertical-align:middle'>Machine &#35 </th>
                                    <th style='text-align:center;vertical-align:middle'>Total Down <i class="icon-time"></i></th>
                                  </tr>
                            </thead>
                            <tbody data-link="row" class="rowlink">
                                <?php 	foreach($procedure_array as $machine => $time): 
                                    
                                        $machine_array = $machineClass->find_by_serial_num($machine);
                                ?>
                                <tr>
                                    <td style='text-align:center;vertical-align:middle;'>
                                        <a href="./schedule_by_machine.php?serial_num=<?php echo htmlentities($machine)?>"></a>
                                    </td>
                                    <td style='text-align:center;vertical-align:middle;'><h3><?php echo $machine_array->machine_num; ?></h3></td>
                                    <td style='text-align:left;vertical-align:middle'><small><?php echo secs_to_h($time); ?></small></td>
                                </tr>
                                <?php 
                                    endforeach;
                                ?>
                            </tbody>
                        </table>
                        </div>
                        <?php 
								}
						endforeach; ?>
                        <?php } ?>
                	</div>
          		</div>
        	</div>
        <?php //request percentage doughnut graph info
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
        <?php //weekly breakdown bar graph info
			$total = count($requests);
			$interval_sec = $date_unix_end - $date_unix;
			$graph_interval = floor($interval_sec/4);
			$counter_weeks = 0;
			$ca_w1 	= array();
			$ca_w2 	= array();
			$ca_w3 	= array();
			$ca_w4 	= array();
			$p_w1 	= array();
			$p_w2 	= array();
			$p_w3 	= array();
			$p_w4 	= array();
			$u_w1 	= array();
			$u_w2 	= array();
			$u_w3 	= array();
			$u_w4 	= array();
			do {
				$begin = 0;
				$end = 0;
				$begin = $date_unix+($graph_interval*$counter_weeks);
				$end = $begin+$graph_interval;
				foreach($ca_array as $request) {
					$request_timestamp = strtotime($request['request_timestamp']);
					if ($request_timestamp > $begin && $request_timestamp < $end) {
						switch($counter_weeks) {
							case 0:
								array_push($ca_w1, $request);
								break;
							case 1:
								array_push($ca_w2, $request);
								break;
							case 2:
								array_push($ca_w3, $request);
								break;
							case 3:
								array_push($ca_w4, $request);
								break;
						}
					}
				}
				foreach($p_array as $request) {
					$request_timestamp = strtotime($request['request_timestamp']);
					if ($request_timestamp > $begin && $request_timestamp < $end) {
						switch($counter_weeks) {
							case 0:
								array_push($p_w1, $request);
								break;
							case 1:
								array_push($p_w2, $request);
								break;
							case 2:
								array_push($p_w3, $request);
								break;
							case 3:
								array_push($p_w4, $request);
								break;
						}
					}
				}
				foreach($u_array as $request) {
					$request_timestamp = strtotime($request['request_timestamp']);
					if ($request_timestamp > $begin && $request_timestamp < $end) {
						switch($counter_weeks) {
							case 0:
								array_push($u_w1, $request);
								break;
							case 1:
								array_push($u_w2, $request);
								break;
							case 2:
								array_push($u_w3, $request);
								break;
							case 3:
								array_push($u_w4, $request);
								break;
						}
					}
				}
				$counter_weeks++;
			} while ($counter_weeks <= 3)
		?>
        <?php 
			$post_users = array();
			foreach ($posts as $post) {
				array_push($post_users, $post['post_username']);
			}
			$unique_users = array_unique($post_users);
			$user_number_posts = array();
			foreach ($unique_users as $user) {
				$usercount = 0;
				foreach ($posts as $post) {
					if ($post['post_username'] == $user) {
						$usercount++;
					}
				}
				$user_number_posts[$user] = $usercount;
			}
			arsort($user_number_posts);
		?>
        <div class="col-md-6 center-text">
        	<div class="thumbnail">
            	<div class="center-text caption">
                	<h3>Requests <?php if (isset($requests)) {echo '('.count($requests).')';} ?></h3>
                </div>
                <canvas id="request_breakdown" width="400" height="400"></canvas>
                <script>					
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
					var context = canvas.getContext('2d');
					var x = canvas.width / 2;
					var y = canvas.height / 2;
				
					context.font = '30pt Calibri';
					context.textAlign = 'center';
					context.fillStyle = 'blue';
					context.fillText('Hello World!', x, y);
                </script>
                <h4>
                    <span class="label label-success"><?php echo htmlentities($percent_u); ?>&#37; Unavoidable</span>
                    <span class="label label-warning"><?php echo htmlentities($percent_p); ?>&#37; Preventable</span>
                    <span class="label label-danger"><?php echo htmlentities($percent_ca); ?>&#37; Corrective Action</span>
                </h4>
            </div><!---End Chart---->
      	</div>
        <div class="col-md-6 center-text">
            <div class="thumbnail">
            	<div class="center-text caption">
                	<h3>Post Distribution</h3>
                </div>
                <div class="canvas-container">
                    <canvas id="post_dist" width="400" height="400"></canvas>
                </div>
                <?php 
					$number_of_post_users = count($user_number_posts);
					$counter = 0;
					$color_num = 0;
					$color = "";
					while ($this_array = current($user_number_posts)) {
						if ($number_of_post_users % 9 === 0 && $number_of_post_users == $counter+1) {
							$color_num++;
						}
						switch ($color_num) {
							case 0:
								$color = "#534fd8";//purple
								$color_num++;
								break;
							case 1:
								$color = "#5cb85c";//green
								$color_num++;
								break;
							case 2:
								$color = "#f0ad4e";//yellow
								$color_num++;
								break;
							case 3:
								$color = "#d9534f";//red
								$color_num++;
								break;
							case 4:
								$color = "#428bca";//blue
								$color_num++;
								break;
							case 5:
								$color = "#5bc0de";//light-blue
								$color_num++;
								break;
							case 6:
								$color = "#FFEA88";//pale-yellow
								$color_num++;
								break;
							case 7:
								$color = "#333";//grey
								$color_num = 0;
								break;
						}?>
						<span class="label label-default" style="background-color:<?php echo $color; ?>;">(<?php echo $this_array; ?>) <?php echo key($user_number_posts); ?></span>
						<?php 
						$counter++;
                       	next($user_number_posts);
					}
					?>
            </div>
            <script>
                var pieData = [
					<?php 
					$number_of_post_users = count($user_number_posts);
					$counter = 0;
					$color_num = 0;
					$color = "";
					foreach ($user_number_posts as $user) :
						if ($number_of_post_users % 9 === 0 && $number_of_post_users == $counter+1) {
							$color_num++;
						}
						switch ($color_num) {
							case 0:
								$color = "#534fd8";//purple
								$color_num++;
								break;
							case 1:
								$color = "#5cb85c";//green
								$color_num++;
								break;
							case 2:
								$color = "#f0ad4e";//yellow
								$color_num++;
								break;
							case 3:
								$color = "#d9534f";//red
								$color_num++;
								break;
							case 4:
								$color = "#428bca";//blue
								$color_num++;
								break;
							case 5:
								$color = "#5bc0de";//light-blue
								$color_num++;
								break;
							case 6:
								$color = "#FFEA88";//pale-yellow
								$color_num++;
								break;
							case 7:
								$color = "#333";//grey
								$color_num = 0;
								break;
						}?>
						{
							value: <?php echo $user; ?>,
							color:"<?php echo $color; ?>"//purple
						}
						<?php 
						if ($number_of_post_users > $counter) {
							echo ",";
						}

						$counter++;
                    endforeach; 
					?>
                ];
                var pieOptions = {
                    segmentShowStroke : false,
                    animateScale : true
                }
                var post_dist= document.getElementById("post_dist").getContext("2d");
                new Chart(post_dist).Pie(pieData, pieOptions);
                
            </script>
        </div>
        <div class="col-md-12">
        	<div class="thumbnail">
            	<div class="center-text caption">
                	<h3>Causes Weekly Breakdown</h3>
                </div>
                <div class="canvas-container center-text col-md-12">
                    <canvas id="requests_over_interval" width="900" height="550"></canvas>
                </div>
                <div class="center-text caption">
                    <h4>
                        <span class="label label-success">Unavoidable</span>
                        <span class="label label-warning">Preventable</span>
                        <span class="label label-danger">Corrective Action</span>
                    </h4>
             	</div>
        	</div>
         	<script>
				var bar1Data = {
					labels : ["Week 1","Week 2","Week 3","Week 4"],
					datasets : [
						{
							fillColor : "#5cb85c",//green,
							barShowStroke : false,
							data : [<?php 
								echo count($u_w1).',';
								echo count($u_w2).',';
								echo count($u_w3).',';
								echo count($u_w4); ?>]
						},
						{
							fillColor : "#f0ad4e",//yellow,
							barShowStroke : false,
							data : [<?php 
								echo count($p_w1).',';
								echo count($p_w2).',';
								echo count($p_w3).',';
								echo count($p_w4); ?>]
						},
						{
							fillColor : "#d9534f",//red
							barShowStroke : false,
							data : [<?php 
								echo count($ca_w1).',';
								echo count($ca_w2).',';
								echo count($ca_w3).',';
								echo count($ca_w4); ?>]
						}
				
					]
				}
				var requests_over_interval = document.getElementById("requests_over_interval").getContext("2d");
				new Chart(requests_over_interval).Bar(bar1Data);
			</script>
   		</div>
        <div class="col-md-12 center-text">
            <div class="thumbnail">
            	<div class="center-text caption">
                	<h3>Requests by Week</h3>
                </div>
                <div class="canvas-container">
                    <canvas id="buyers" width="900" height="550"></canvas>
                </div>
                <div class="center-text caption">
                    <h4>
                        <span class="label label-primary">Total Requests</span>
                        <span class="label label-success">Unavoidable</span>
                        <span class="label label-warning">Preventable</span>
                        <span class="label label-danger">Corrective Action</span>
                    </h4>
             	</div>
            </div>
            <script>
                 var buyerData = {
                labels : ["Week 1","Week 2","Week 3","Week 4"],
                datasets : [
                    {
                        fillColor : "rgba(151,187,205,0.15)",
                        strokeColor : "rgba(151,187,205,0.15)",
                        pointColor : "rgba(151,187,205,1)",
                        pointStrokeColor : "rgba(151,187,205,1)",
                        data : [<?php 
								echo count($p_w1)+count($ca_w1)+count($u_w1).',';
								echo count($p_w2)+count($ca_w2)+count($u_w2).',';
								echo count($p_w3)+count($ca_w3)+count($u_w3).',';
								echo count($p_w4)+count($ca_w4)+count($u_w4); ?>]
                    },
					{
						fillColor : "rgba(217, 83, 79, 0.25)",
						strokeColor : "rgba(217, 83, 79, 0.25)",
						pointColor : "rgba(217, 83, 79, 1)",
						pointStrokeColor : "rgba(217, 83, 79, 1)",
						data : [<?php 
								echo count($ca_w1).',';
								echo count($ca_w2).',';
								echo count($ca_w3).',';
								echo count($ca_w4); ?>]
					},
					{
						fillColor : "rgba(92, 184, 92, 0.25)",
						strokeColor : "rgba(92, 184, 92, 0.25)",
						pointColor : "rgba(92, 184, 92, 1)",
						pointStrokeColor : "rgba(92, 184, 92, 1)",
						data : [<?php 
								echo count($u_w1).',';
								echo count($u_w2).',';
								echo count($u_w3).',';
								echo count($u_w4); ?>]
					},
					{
						fillColor : "rgba(240, 173, 78, 0.25)",
						strokeColor : "rgba(240, 173, 78, 0.25)",
						pointColor : "rgba(240, 173, 78, 1)",
						pointStrokeColor : "rgba(240, 173, 78, 1)",
						data : [<?php 
								echo count($p_w1).',';
								echo count($p_w2).',';
								echo count($p_w3).',';
								echo count($p_w4); ?>]
					}
                ]
                }
                var buyers = document.getElementById('buyers').getContext('2d');
                new Chart(buyers).Line(buyerData);
            </script>
        </div>
    </div><!---End Row---->
</div><!---End Container---->
<?php include_layout_template('admin_footer.php'); ?>