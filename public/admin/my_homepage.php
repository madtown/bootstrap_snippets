<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php
	if (isset($_GET['request_id']) && isset($_POST['submit_assignments'])) {
		$requestClass->assign();
	}
	global $userClass;
 	$user = $userClass->find_user_by_id($database->escape_value($_SESSION['user_id'])); 
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container jumbotron col-md-12">
    <div class="center-text">
        <h1><i class="icon-home"></i> My Home Page</h1>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
            <a class="btn btn-danger btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="#assignments">
                <h4><i class="icon-list-ol"></i> My Assignments</h4>
            </a>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
            <a class="btn btn-primary btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="#queue">
                <h4><i class="icon-map-marker"></i> My Location Queue</h4>
            </a>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
            <a class="btn btn-warning btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="#schedules">
                <h4><i class="icon-calendar"></i> My Schedules</h4>
            </a>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
            <a class="btn btn-success btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="my_inbox.php">
                <h4><i class="icon-inbox"></i> My Inbox</h4>
            </a>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
            <a class="btn btn-default btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="my_machines.php">
                <h4><i class="icon-cogs"></i> My Machines</h4>
            </a>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
            <a class="btn btn-info btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="#info">
                <h4><i class="icon-info-sign"></i> My Information</h4>
            </a>
        </div>        
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
            <a class="btn btn-inverse btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="#reports">
                <h4><i class="custom-chart-pie-alt"></i> My Reports</h4>
            </a>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
            <a class="btn btn-maint btn-block" style='text-align:center;vertical-align:middle;padding:4px' href="manual.php">
                <h4><i class="icon-warning-sign"></i> Manual</h4>
            </a>
        </div>
    </div>
</div>
<section id=assignments>
    <div class="panel panel-danger">
        <div class="panel-heading center-text">
            <h1><i class="icon-list-ol"></i> My Assignments</h1>
            <?php 
            echo output_message($message); 
            global $requestClass;
            $requestObject = $requestClass->find_my_unresolved_requests();	//returns all requests in a object but need a better order. 
            $requests = $requestClass->orderQueue($requestObject);?>
        </div>  
        <div class="panel-body">  
        <?php 
            global $workClass;
            $workClass->displayQueue($requests); 
        ?>  
        </div>
    </div>
</section>
<section id=queue>
    <div class="panel panel-primary">
        <div class="panel-heading center-text">
            <h1><i class="icon-list-ol"></i> My Location Queue</h1>
            <?php 
            $requestObject2 = $requestClass->find_my_locations_unresolved_requests();
            $requests2 = $requestClass->orderQueue($requestObject2); ?>
        </div>  
        <div class="panel-body">  
        <?php 
            global $workClass;
            $workClass->displayQueue($requests2); 
        ?>  
        </div>
        <div class="panel-footer">
        	<div class="media">
                <a class="btn btn-warning pull-left" href="request.php"><h5><i class="icon-edit"></i> Request</h5></a>
                <a class="btn btn-success pull-right" href="resolved_queue.php?user_id=<?php echo $_SESSION['user_id']; ?>"><h5><i class="icon-check"></i> Resolved Requests</h5></a>
          	</div>
        </div>
    </div>
</section>
<section id=schedules>
    <div class="panel panel-warning">
        <div class="panel-heading">
            <div class="center-text"><h2><i class="icon-tasks"></i> My Schedules</h2></div>
        </div>
        <div class="panel-body">
            <div class="row">
            	<div class="col-sm-3">
                	<a class="btn btn-primary btn-block" href="schedule.php?user_id=<?php echo htmlentities($database->escape_value($_SESSION['user_id'])); ?>&type=0"><h3><i class="icon-sun"></i> Daily</h3></a>
                </div>
                <div class="col-sm-3">
                	<a class="btn btn-success btn-block" href="schedule.php?user_id=<?php echo htmlentities($database->escape_value($_SESSION['user_id'])); ?>&type=1"><h3><i class="icon-list"></i> Weekly</h3></a>	
             	</div>
                <div class="col-sm-3">
                	<a class="btn btn-warning btn-block" href="schedule.php?user_id=<?php echo htmlentities($database->escape_value($_SESSION['user_id'])); ?>&type=2"><h3><i class="icon-moon"></i> Monthly</h3></a>
              	</div>
                <div class="col-sm-3">
                	<a class="btn btn-danger btn-block" href="schedule.php?user_id=<?php echo htmlentities($database->escape_value($_SESSION['user_id'])); ?>&type=3"><h3><i class="icon-globe"></i> Annual</h3></a>
              	</div>
                <div class="col-sm-3">
                	<a class="btn btn-default btn-block" href="schedule.php?user_id=<?php echo htmlentities($database->escape_value($_SESSION['user_id'])); ?>&type=4"><h3><i class="icon-warning-sign"></i> Emergency</h3></a>
            	</div>
             	<div class="col-sm-3">
                    <a class="btn btn-default btn-block" href="schedule.php?user_id=<?php echo htmlentities($database->escape_value($_SESSION['user_id'])); ?>&type=5"><h3><i class="icon-star"></i> Special</h3></a>
            	</div>
            	<div class="col-sm-3">
                    <a class="btn btn-default btn-block" href="schedule.php?user_id=<?php echo htmlentities($database->escape_value($_SESSION['user_id'])); ?>&type=6"><h3><i class="icon-question-sign"></i> Other</h3></a>
             	</div>
            </div>
        </div>
    </div>
</section>
<section id=info>
    <div class="panel panel-info"> 
        <div class="panel-heading center-text">
            <h1><i class="icon-user"></i> My Information</h1>
        </div>  
        <table class="table table-hover table-striped">
            <tr>
                <th><h3><i class='icon-user'></i> Username</h3></th>
                <th><h3><i class='icon-envelope'></i> Email</h3></th>
                <th><h3><i class='icon-mapmarker'></i> Location</h3></th>
            </tr>
            <tr>
                <td><h3><?php echo $_SESSION['username']; ?></h3></td>
                <td><h3><?php echo htmlentities($user['email']);?></h3></td>
                <td><h3><?php if ($user['location_id'] != 0) {
                            $location_type 		= $user['location_type']; 
                            $location_id 		= $user['location_id']; 
                            $location_string 	= $locationClass->find_machine_location($location_type, $location_id);
                            echo $location_string;
                        } else {
                            echo 'User has no Location';
                        }?></h3></td>
            </tr>                    
        </table>
    </div>
</section>
<section id=reports>
    <div class="panel panel-success"> 
        <div class="panel-heading center-text">
            <h1><i class="icon-user"></i> My Reports</h1>
        </div>  
        <div class="panel-body">
            <div class="row">
            	<div class="col-sm-3">
                	<a class="btn btn-primary btn-block" href="report_daily.php"><h3><i class="icon-sun"></i> Daily Report</h3></a>
                </div>
                <div class="col-sm-3">
                	<a class="btn btn-success btn-block" href="report_weekly.php"><h3><i class="icon-list"></i> Weekly Report</h3></a>	
             	</div>
                <div class="col-sm-3">
                	<a class="btn btn-warning btn-block" href="report_monthly.php"><h3><i class="icon-moon"></i> Monthly Report</h3></a>
              	</div>
                <div class="col-sm-3">
                	<a class="btn btn-danger btn-block" href="report_bi_annual.php"><h3><i class="icon-globe"></i> Bi-Annual Report</h3></a>
              	</div>
            </div>
        </div>
    </div>
</section>
<?php include_layout_template('admin_footer.php'); ?>