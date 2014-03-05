<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
if (isset($_POST['redirect_URL']) and $_POST['redirect_URL'] != "") {
	$URL = $_POST['redirect_URL'];
	redirect_to($URL);
}
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
<?php
	global $machineClass;
	$serial_num = $_GET['serial_num'];
	$procedureClass = new Procedure();
	if (isset($_GET['serial_num'])) {
		$procedures = $procedureClass->find_all();
		$header = "";
		$my_machines = array();
		array_push($my_machines, $_GET['serial_num']);
	}
	$machine = $machineClass->find_by_serial_num($serial_num);
	
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container jumbotron col-md-12">
    <div class="center-text">
        <h1><i class="icon-home"></i> <?php echo $machine->machine_num; ?> Home Page</h1>
        <div class="container col-md-12">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                    <a class="btn btn-inverse btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="#proc">
                        <h4><i class="icon-list-ol"></i> <?php echo $machine->machine_num; ?> Queue</h4>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                    <a class="btn btn-success btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="#proc">
                        <h4><i class="icon-wrench"></i> <?php echo $machine->machine_num; ?> Procedures</h4>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                    <a class="btn btn-info btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="#info">
                        <h4><i class="icon-info-sign"></i> <?php echo $machine->machine_num; ?> Information</h4>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                    <a class="btn btn-primary btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="view_docs.php?serial_num=<?php echo htmlentities($machine->serial_num); ?>">
                        <h4><i class="icon-copy"></i> <?php echo $machine->machine_num; ?> Documents</h4>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                    <a class="btn btn-default btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="machine_log.php?serial_num=<?php echo htmlentities($machine->serial_num); ?>">
                        <h4><i class="icon-archive"></i> <?php echo $machine->machine_num; ?> Logs</h4>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                    <a class="btn btn-danger btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="#qrcode">
                        <h4><i class="icon-qrcode"></i> <?php echo $machine->machine_num; ?> QR Code</h4>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                    <a class="btn btn-warning btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="view_all_machines.php">
                        <h4><i class="icon-cogs"></i> All Machines</h4>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                    <a class="btn btn-maint btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="move_machine.php?serial_num=<?php echo $machine->serial_num; ?>">
                        <h4><i class="icon-move"></i> Move <?php echo $machine->machine_num; ?></h4>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <section id=queue>
    	<div class="panel panel-primary">
			<?php
                if (isset($_GET['request_id']) && isset($_POST['submit_assignments'])) {
                    $requestClass->assign();
                }
                global $requestClass;
                $requestObject = $requestClass->find_machine_unresolved_requests();	//returns all requests in a object but need a better order. 
                $requests = $requestClass->orderQueue($requestObject);
            ?>
            <div class="panel-heading center-text">
                <h1><i class="icon-list-ol"></i> <?php echo $machine->machine_num; ?> Queue</h1>
            </div>
            <div class="panel-body">
                <?php 
                    global $workClass;
                    $workClass->displayQueue($requests); 
                ?>
            </div>
            <div class="panel-footer">
                <div class="media">
                    <a class="btn btn-warning pull-left" href="request.php"><h5><i class="icon-edit"></i> Request</h5></a>
                    <a class="btn btn-success pull-right" href="resolved_queue.php?serial_num=<?php echo $serial_num; ?>"><h5><i class="icon-check"></i> Resolved Requests</h5></a>
                </div>
        	</div>
        </div>
    </section>
    <section id=proc>
    	<div class="panel panel-success"> 
            <div class="panel-heading center-text">
            	<h1><i class="icon-wrench"></i> <?php echo $machine->machine_num; ?> Procedures</h1>
           	</div>
            <div class="panel-body">
            	<?php $procedureClass->displayProcedurelist($procedures, $header, $my_machines); ?>
          	</div>
     	</div>
    </section>
    <section id=info>
    	<div class="panel panel-info">
        	<div class="panel-heading center-text">
            	<h1><i class="icon-info-sign"></i> <?php echo $machine->machine_num; ?> Information</h1>
           	</div>
    		<table class="table table-striped table-hover">
            	<tr>
                    <td style='text-align:right;vertical-align:middle'><strong>Type: </strong></td>
                    <td><?php 				
                            if(isset($machine->type_id)) { 
                                $type_id = $machine->type_id; 
                                $machine_type = $machineClass->find_type_by_id($type_id);
                                $type_array= $machine_type->fetch_assoc(); 
                                echo $type_array['name'];
                            } else { ""; }
                    ?></td>
                </tr>
                <tr>
                	<td style='text-align:right;vertical-align:middle'><strong>Manufacturer: </strong></td>
                    <td><?php 		
							if(isset($machine->manufacturer)) { 
								echo $machine->manufacturer;
							} else { ""; } ?>
                 	</td>
             	</tr>
                <tr>
                	<td style='text-align:right;vertical-align:middle'><strong>Machine Description: </strong></td>
                    <td><?php	
							if(isset($machine->machine_desc)) { 
								echo $machine->machine_desc;
							} else { ""; } ?>
                	</td>
            	</tr>
                <tr>
                	<td style='text-align:right;vertical-align:middle'><strong>Serial Number: </strong></td>
                    <td><?php 		
							if(isset($machine->serial_num)) { 
								echo $machine->serial_num;
							} else { ""; } ?>
                 	</td>
          		</tr>
                <tr>
                	<td style='text-align:right;vertical-align:middle'><strong>Manufacture Date: </strong></td>
                    <td><?php 		
							if(isset($machine->mfg_date)) { 
								echo $machine->mfg_date;
							} else { ""; } ?>
                 	</td>
            	</tr>
                <tr>
                	<td style='text-align:right;vertical-align:middle'><strong>Weight (lbs): </strong></td>
                    <td><?php 			
							if(isset($machine->weight_lbs)) { 
								echo $machine->weight_lbs;
							} else { ""; } ?>
                	</td>
           		</tr>
                <tr>
                	<td style='text-align:right;vertical-align:middle'><strong>Voltage: </strong></td><td><?php 				
						if(isset($machine->voltage)) { 
							echo $machine->voltage;
						} else { ""; } ?>
               		</td>
             	</tr>
                <tr>
                	<td style='text-align:right;vertical-align:middle'><strong>Amperage: </strong></td>
                    <td><?php 				
							if(isset($machine->amp)) { 
								echo $machine->amp;
							} else { ""; } ?>
                 	</td>
            	</tr>
                <tr>
                	<td style='text-align:right;vertical-align:middle'><strong>Phase: </strong></td>
                    <td><?php 				
							if(isset($machine->phase)) { 
								echo $machine->phase;
							} else { ""; } ?>
                 	</td>
             	</tr>
                <tr>
                	<td style='text-align:right;vertical-align:middle'><strong>Data Connection: </strong></td>
                    <td><?php 		
							if(isset($machine->data_conn)) { 
								echo $machine->data_conn;
							} else { ""; } ?>
               		</td>
           		</tr>
                <tr>
                	<td style='text-align:right;vertical-align:middle'><strong>Compressed Air: </strong></td>
                    <td><?php 		
							if(isset($machine->comp_air)) { 
								echo $machine->comp_air;
							} else { ""; } ?>
                 	</td>
              	</tr>
                <tr>
                	<td style='text-align:right;vertical-align:middle'><strong>Current Location: </strong></td>
                    <td><?php 		
							global $locationClass;
							$location_type 		= $machine->location_type; 
							$location_id 		= $machine->location_id; 
							$location_string 	= $locationClass->find_machine_location($location_type, $location_id);
							echo $location_string; ?>
                 	</td>
            	</tr>
                <tr>
                	<td style='text-align:right;vertical-align:middle'><strong>Machine Number: </strong></td>
                    <td><?php		
						if(isset($machine->machine_num)) { 
							echo $machine->machine_num;
						} else { ""; } ?>
                  	</td>
             	</tr>
                <tr>
                	<td style='text-align:right;vertical-align:middle'><strong>Rig Required: </strong></td>
                    <td><?php 			
							if(isset($machine->rig_req)) { 
								echo $machine->rig_req;
							} else { ""; } ?>
                 	</td>
           		</tr>
                <tr>
                	<td style='text-align:right;vertical-align:middle'><strong>Comment: </strong></td>
                    <td><?php 				
							if(isset($machine->comment)) { 
								echo $machine->comment;
							} else { ""; } ?>
                  	</td>
             	</tr>
                <tr>
                	<td style='text-align:right;vertical-align:middle'><strong>Acquisition Date: </strong></td>
                    <td><?php 		
							if(isset($machine->acquisition_date)) { 
								echo $machine->acquisition_date;
							} else { ""; } ?>
                 	</td>
             	</tr>
       		</table>
            <div class="panel-footer">
                <div class="media">
                    <a class="btn btn-warning pull-left" data-target="#medit<?php echo $machine->id; ?>" data-toggle="modal"><h5><i class="icon-edit"></i> Edit Information</h5></a>
                    <a class="btn btn-maint pull-right" href="move_machine.php?serial_num=<?php echo $serial_num; ?>"><h5><i class="icon-move icon-large"></i> Move <?php echo $machine->machine_num; ?></h5></a>
                    <?php
                        $id 			= 'medit'.$machine->id;
                        $type 			= 0;
                        $header_text	= '<strong>Warning!</strong> You are about to edit '.$machine->machine_num.'!';
                        $body 			= '<p>Are you sure you want to PERMANENTLY edit Machine: '.$machine->machine_num.'?</p></br>
                                            <p><i class="icon-warning-sign"></i> <strong>Consequences</strong></p>
                                            <ul>
                                                <li>This action CANNOT be undone.</li>
                                                <li>All pages will reflect these changes.</li>
                                                <li>You cannot change the serial number this way. So do not even bother. If you do need to change the serial number you must RE-ADD the machine and RE-CONNECT all procedures and documents. This should be a last resort. </li>
                                                <li>If you change the Machine Number note: this is what the machine will be called throughout the system. This is what the name will look like for everyone. Notify those, who need to be aware, that the common name has changed.</li>
                                            </ul>   
                                            <p><i class="icon-cloud"></i> <strong>Silver Lining</strong></p>
                                            <ul>             
                                                <li>This will NOT affect the maintenance log.</li> 
                                                <li>This will NOT delete documents if they are connected to other machines.</li>
                                                <li>This will affect virtually nothing other than described above.</li>
                                            </ul>
                                            <p>To proceed with deletion, check the box below, and click <button class="btn btn-warning"><i class="icon-edit"></i></button>.</p>';
                        $link 			= 'change_machines.php?id='.$machine->id;
                        warningModal($id, $type, $header_text, $body, $link); 
                    ?>
                </div>
            </div>
        </div>
        </section>
        <section id=qrcode>
            <div class="panel panel-danger">
        		<div class="panel-heading center-text">
                	<h1><i class="icon-qrcode"></i> <?php echo $machine->machine_num; ?> QR Code</h1>
             	</div>
                <div class="panel-body">
                 	<img style='display: block;margin-left: auto;margin-right: auto;' src="../admin/qr_code.php?serial_num=<?php echo htmlentities($serial_num);?>" class="img-responsive" alt="There was a problem dude."/>
                </div>
            </div>
        </section>
    </div>
</div>
<?php include_layout_template('admin_footer.php'); ?>