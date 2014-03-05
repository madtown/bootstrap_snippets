<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container">
	<div class="center-text"><h1><i class="icon-file-text"></i> Manual and FAQ</h1></div> 
    <div class="row">
        <div class="col-sm-4 col-md-3 col-lg-2">
            <!-- Nav tabs category -->
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a class="lead" href="#get_start" data-toggle="tab"><i class="icon-info-sign"></i> Getting Started</a></li>
                <li><a class="lead" href="#proc" data-toggle="tab"><i class="icon-list-ol"></i> Procedures</a></li>
                <li><a class="lead" href="#req" data-toggle="tab"><i class="icon-edit"></i> Requests</a></li>
                <li><a class="lead" href="#equip" data-toggle="tab"><i class="icon-cogs"></i> Equipment</a></li>
                <li><a class="lead" href="#loc" data-toggle="tab"><i class="icon-map-marker"></i> Locator</a></li>
                <li><a class="lead" href="#users" data-toggle="tab"><i class="icon-group"></i> Users</a></li>
                <li><a class="lead" href="#noti" data-toggle="tab"><i class="custom-paper-plane"></i> Notifications</a></li>
                <li><a class="lead" href="#reports" data-toggle="tab"><i class="custom-chart-pie-1"></i> Reports</a></li>
            </ul>
    	</div>
        <div class="col-sm-8 col-md-9 col-lg-10">
            <!-- Tab panes -->
            <div class="tab-content faq-cat-content">
                <div class="tab-pane active in fade" id="get_start">
                    <?php include('../manual/tabs/01getting_started.php'); ?>
                </div>
                <div class="tab-pane in fade" id="proc">
                    <?php include('../manual/tabs/02procedure.php'); ?>
                </div>
                <div class="tab-pane in fade" id="req">
                    <?php include('../manual/tabs/03requests.php'); ?>
                </div>
                <div class="tab-pane in fade" id="equip">
                    <?php include('../manual/tabs/04equipment.php'); ?>
                </div>
                <div class="tab-pane in fade" id="loc">
                    <?php include('../manual/tabs/05locations.php'); ?>
                </div>
                <div class="tab-pane in fade" id="users">
                    <?php include('../manual/tabs/06users.php'); ?>
                </div>
                <div class="tab-pane in fade" id="noti">
                    <?php include('../manual/tabs/07notifications.php'); ?>
                </div>
                <div class="tab-pane in fade" id="reports">
                    <?php include('../manual/tabs/09reports.php'); ?>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>
<?php include_layout_template('admin_footer.php'); ?>
		
