<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
if (isset($_POST['redirect_URL']) and $_POST['redirect_URL'] != "") {
	$URL = $_POST['redirect_URL'];
	redirect_to($URL);
}
?>
<?php
	global $machineClass;
	global $stepClass;
	$procedureClass = new Procedure();
	if (isset($_GET['user_id'])) {
		$my_machines = $machineClass->find_my_machines();
	} else {
		$my_machines = $machineClass->find_every_single_machine();
	}
	if (isset($_GET['type'])) {
		switch ($_GET['type']) {
			case 0://daily maintenance
				$procedures = $procedureClass->find_daily();
				$header = "<i class='icon-sun text-warning'></i> Daily Maintenance";
				break;
			case 1://weekly maintenance
				$procedures = $procedureClass->find_weekly();
				$header = "<i class='icon-list'></i> Weekly Maintenance";
				break;
			case 2://monthly maintenance
				$procedures = $procedureClass->find_monthly();
				$header = "<i class='icon-moon'></i> Monthly Maintenance";
				break;
			case 3://annual maintenance
				$procedures = $procedureClass->find_annual();
				$header = "<i class='icon-globe'></i> Annual Maintenance";
				break;
			case 4://emergency maintenance
				$procedures = $procedureClass->find_emergency();
				$header = "<i class='icon-warning-sign'></i> Emergency Maintenance";
				break;
			case 5://special maintenance
				$procedures = $procedureClass->find_special();
				$header = "<i class='icon-star text-warning'></i> Special Maintenance";
				break;
			case 6://other maintenance
				$procedures = $procedureClass->find_other();
				$header = "<i class='icon-question-sign text-warning'></i> Other Maintenance";
				break;
			case 7://other maintenance
				$procedures = $procedureClass->find_all();
				$header = "<i class='icon-globe'></i> All Maintenance";
				break;	
		}
	}
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
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
<?php $procedureClass->displayProcedurelist($procedures, $header, $my_machines); ?>
<?php include_layout_template('admin_footer.php'); ?>