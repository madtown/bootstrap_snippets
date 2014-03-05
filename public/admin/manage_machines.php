<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); 
} elseif ($_SESSION['rank'] > 2) { 
	$_SESSION['message'] = "You are not authorized to use the dashboard";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
$types = $machineClass->find_all_machine_types();
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">
    	<div class="center-text"><h1><i class="icon-dashboard"></i> Management Dashboard</h1></div>
		<div class="center-text"><p class="text-danger"><?php echo output_message($message); ?></p></div>
<!--------------------------------------------------- Start Types -------------------------------------------------->
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><a data-toggle="collapse" data-parent="#machinetype" href="#machinetype"><h1><i class="icon-cogs"></i> Machine Types</h1></a></div>
            </div>
            <div id="machinetype" class="panel-collapse collapse">
                <table class="table table-striped table-hover table-condensed">
                    <tr>
                        <th style='text-align:center;vertical-align:middle'>Type Name</th>
                        <th style='text-align:center;vertical-align:middle'><i class="icon-edit"></i> Edit</th>
                    </tr>
                    <?php 
                    do{
                        foreach ($types as $type):
                    ?>
                    <tr>
                        <td><h3><i class="icon-cog"></i> <?php echo $type['name'] ;?></h3></td>
                        <td style='text-align:center;vertical-align:middle'><a class="btn btn-warning" href="change_machine_type.php?id=<?php echo htmlentities($type['id']);?>"><i class="icon-edit icon-2x"></i></a></td>
                    </tr>
                    <?php endforeach;
                    } while ($types->fetch_assoc());?>
                </table>
                <div class="panel-footer"><a class="btn btn-success" href="edit_equipment_type.php"><h4><i class="icon-plus-sign"></i> Add Machine Type</h4></a></div>
            </div><!-- /.panel-collapse-types --------------------------------------------------------------------->
        </div><!-- /.panel-types ---------------------------------------------------------------------------------->
<!---------------------------------------------------- End Types -------------------------------------------------->
    </div><!-- /.col-md-12 -->
</div><!-- /.container -->
<?php include_layout_template('admin_footer.php'); ?>
		
