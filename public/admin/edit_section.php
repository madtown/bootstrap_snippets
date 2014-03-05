<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 2 ) { 
	$_SESSION['message'] = "You are not authorized to edit maintenance procedures.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
if(isset($_POST['submit']) && $_POST['section_name'] != "" && $_POST['building_id'] != "") {
	if ($location = new Location()) {
		$location->newSection();
		$message = "The Section was successfully created.";
		unset($_POST['submit']);
		unset ($_SESSION['message']);
		unset($_POST['section_name']);
		unset($_POST['building_id']);
		redirect_to('locations.php');
	 }
} else {
	$_SESSION['message'] = "Please select all fields.";
}
if(isset($_GET['building_id'])) {
	global $locationClass;
	$building = $locationClass->find_building_by_id($_GET['building_id']);
	$building_array = mysqli_fetch_array($building);
}
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">
        <div class="center-text"><h1><i class="icon-plus-sign"></i> New Section <i class="icon-th-large"></i></h1><h2><i class="icon-building"></i> <?php echo $building_array['building_name']; ?> </h2></div>
        <p class="text-error"><?php echo output_message($message); 
        unset($_SESSION['message']);
        ?></p>
        <form class="form-horizontal" action="edit_section.php?building_id=<?php echo $building_array['building_id']; ?>" method="post" enctype="multipart/form-data" role="form">
            <div class="form-group">
                <label for="section_name">Section Name</label>
                <input class="form-control" type="text" name="section_name" id="section_name" maxlength="30" placeholder="Required-Example: North Side, Paint..." required/>
                <script>
				$('input#section_name').maxlength({
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
                <label for="section_lead_user">Section Lead</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-user"></i></span>
                    <select class="form-control" name="section_lead_user" id="section_lead_user"> 
                        <?php include('../scripts/userSelect.php');?>
                    </select>
                    <script>
                    $("#section_lead_user").select2({
                        placeholder: "Select Lead",
                        allowClear: true
                    });
                    </script>
                </div>
            </div>
            <div class="form-group">
                <label for="section_maint_user">Section Maintenance Lead</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="custom-wrench"></i></span>
                    <select class="form-control" name="section_maint_user" id="section_maint_user"> 
                        <?php getUsers();?>
                    </select>
                    <script>
                    $("#section_maint_user").select2({
                        placeholder: "Select Maintenance Lead",
                        allowClear: true
                    });
                    </script>
                </div>
            </div>
            <div class="form-group">
                <input class="form-control" type="hidden" name="building_id" id="building_id" maxlength="6" value="<?php echo htmlentities($building_array['building_id']); ?>" required/>
          	</div>
          	<div class="form-group">
            	<button class="btn btn-success btn-block" type="submit" name="submit" value="Submit"><i class="icon-plus-sign"></i> Section</button>
            </div>
        </form>
    </div>
	<a class="btn btn-info" href="locations.php"><i class="icon-chevron-left"></i> Return to Locations</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>