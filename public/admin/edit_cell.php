<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 2 ) { 
	$_SESSION['message'] = "You are not authorized to edit cells.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
if(isset($_POST['submit']) && $_POST['cell_name'] != "" && $_POST['section_id'] != "") {
	if ($location = new Location()) {
		$location->newCell();
		$message = "The Section was successfully created.";
		unset($_POST['submit']);
		unset ($_SESSION['message']);
		unset($_POST['cell_name']);
		unset($_POST['building_id']);
		unset($_POST['section_id']);
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
if(isset($_GET['section_id'])) {
	global $locationClass;
	$section = $locationClass->find_section_by_id($_GET['section_id']);
	$section_array = mysqli_fetch_array($section);
} 
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">
        <div class="center-text"><h1><i class="icon-plus-sign"></i> New Cell <i class="icon-th"></i></h1><h2>Within <i class="icon-th-large"></i> <?php echo $section_array['section_name']; ?> of <i class="icon-building"></i> <?php echo $building_array['building_name']; ?> </h2></div>
        <p class="text-error"><?php echo output_message($message); 
        unset($_SESSION['message']);
        ?></p>
        <form class="form-horizontal" action="edit_cell.php?building_id=<?php echo $building_array['building_id']; ?>&section_id=<?php echo $section_array['section_id']; ?>" method="post" enctype="multipart/form-data" role="form">
            <div class="form-group">
                <label for="cell_name">Cell Name</label>
                <input class="form-control" type="text" name="cell_name" id="cell_name" maxlength="30" placeholder="Required-Example: NPI Cell, Cabinet Cell..." required/>
                <script>
				$('input#cell_name').maxlength({
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
                <input class="form-control" type="hidden" name="section_id" id="section_id" maxlength="6" value="<?php echo htmlentities($section_array['section_id']); ?>" required/>
          	</div>
          	<div class="form-group">
            	<button class="btn btn-success btn-block" type="submit" name="submit" value="Submit"><i class="icon-plus-sign"></i> Cell</button>
            </div>
        </form>
    </div>
	<a class="btn btn-info" href="locations.php"><i class="icon-chevron-left"></i> Return to Locations</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>