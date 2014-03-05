<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
  // Find all the procedures
	$machineClass = new Machine();
 	$all = $machineClass->find_all();
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<?php echo output_message($message); ?>
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">	
        <div class="center-text">
        	<h1><i class="icon-cogs"></i> All Machines <i class="icon-cogs icon-rotate-180"></i><span class="label label-default pull-right"><?php echo $all->num_rows; ?></span></h1>
      	</div> 
        <?php $machineClass->displayMachines($all); ?>
        <a class="btn btn-success" style='text-align:center;vertical-align:middle' href="edit_machines.php"><i class="icon-plus"></i> New Machine</a>
	</div>
</div>
<?php include_layout_template('admin_footer.php'); ?>
