<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<section id=schedules>
    <div class="panel panel-warning">
        <div class="panel-heading">
            <div class="center-text"><h2><i class="icon-tasks"></i> Master Schedules</h2></div>
        </div>
        <div class="panel-body">
            <div class="row">
            	<div class="col-sm-3">
                	<a class="btn btn-primary btn-block" href="schedule.php?type=0"><h3><i class="icon-sun"></i> Daily</h3></a>
                </div>
                <div class="col-sm-3">
                	<a class="btn btn-success btn-block" href="schedule.php?type=1"><h3><i class="icon-list"></i> Weekly</h3></a>	
             	</div>
                <div class="col-sm-3">
                	<a class="btn btn-warning btn-block" href="schedule.php?type=2"><h3><i class="icon-moon"></i> Monthly</h3></a>
              	</div>
                <div class="col-sm-3">
                	<a class="btn btn-danger btn-block" href="schedule.php?type=3"><h3><i class="icon-globe"></i> Annual</h3></a>
              	</div>
                <div class="col-sm-3">
                	<a class="btn btn-default btn-block" href="schedule.php?type=4"><h3><i class="icon-warning-sign"></i> Emergency</h3></a>
            	</div>
             	<div class="col-sm-3">
                    <a class="btn btn-default btn-block" href="schedule.php?type=5"><h3><i class="icon-star"></i> Special</h3></a>
            	</div>
            	<div class="col-sm-3">
                    <a class="btn btn-default btn-block" href="schedule.php?type=6"><h3><i class="icon-question-sign"></i> Other</h3></a>
             	</div>
            </div>
        </div>
    </div>
</section>
<?php include_layout_template('admin_footer.php'); ?>
		
