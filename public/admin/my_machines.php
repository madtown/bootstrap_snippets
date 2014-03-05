<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
  // Find all the procedures
	$machineClass = new Machine();
 	$all = $machineClass->find_my_machines();
	$types = $machineClass->find_all_machine_types();
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
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">	
        <div class="center-text">
        	<h1><i class="icon-cogs"></i> My Machines <i class="icon-cogs icon-rotate-180"></i><span class="label label-default pull-right"><?php if (is_object($all)) {
				echo $all->num_rows;
			} else {
				echo count($all);
			}?></span></h1>
      	</div> 
        <?php $machineClass->displayMachines($all); ?>
        <a class="btn btn-success" style='text-align:center;vertical-align:middle' href="edit_machines.php"><i class="icon-plus"></i> New Machine</a>
	</div>
</div>
<?php include_layout_template('admin_footer.php'); ?>
