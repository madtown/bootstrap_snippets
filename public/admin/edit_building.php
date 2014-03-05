<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 2 ) { 
	$_SESSION['message'] = "You are not authorized to edit maintenance procedures.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
if(isset($_POST['submit']) && $_POST['building_name'] != "") {
	if ($location = new Location()) {
		$location->newBuilding();
		$message = "The Building was successfully created.";
		unset($_POST['submit']);
		unset ($_SESSION['message']);
		unset($_POST['building_name']);
		redirect_to('locations.php');
	 }
} else {
	$_SESSION['message'] = "Please fill in all fields.";
}
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">
        <div class="center-text"><h1><i class="icon-plus-sign"></i> New Building <i class="icon-building"></i></h1></div>
        <p class="text-error"><?php echo output_message($message); 
        unset($_SESSION['message']);
        ?></p>
        <form class="form-horizontal" action="edit_building.php" method="post" enctype="multipart/form-data" role="form">
            <div class="form-group">
                <label for="building_name">Building Name</label>
                <input class="form-control" type="text" name="building_name" id="building_name" maxlength="30" placeholder="Required-Example: FabTech 1925, IGM Solutions..." required/>
                <script>
				$('input#building_name').maxlength({
					alwaysShow: false,
					threshold: 25,
					warningClass: "label label-success",
					limitReachedClass: "label label-danger",
					placement: 'top',
					preText: 'Used ',
					separator: ' of ',
					postText: ' chars.'
				});
				</script>
          	</div>
            <div class="form-group">
                <label for="building_lead_user">Building Lead</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-user"></i></span>
                    <select class="form-control" name="building_lead_user" id="building_lead_user" required> 
                        <?php include('../scripts/userSelect.php');?>
                    </select>
                    <script>
                    $("#building_lead_user").select2({
                        placeholder: "Select Lead",
                        allowClear: true
                    });
                    </script>
                </div>
            </div>
            <div class="form-group">
                <label for="building_maint_user">Building Maintenance Lead</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="custom-wrench"></i></span>
                    <select class="form-control" name="building_maint_user" id="building_maint_user" required> 
                        <?php getUsers();?>
                    </select>
                    <script>
                    $("#building_maint_user").select2({
                        placeholder: "Select Maintenance Lead",
                        allowClear: true
                    });
                    </script>
                </div>
            </div>
          	<div class="form-group">
            	<button class="btn btn-success btn-block" type="submit" name="submit" value="Submit"><i class="icon-plus-sign"></i> Building</button>
            </div>
        </form>
    </div>
	<a class="btn btn-info" href="locations.php"><i class="icon-chevron-left"></i> Return to Locations</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>