<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php");
	} elseif ($_SESSION['rank'] > 2) { 
		$_SESSION['message'] = "You are not authorized to delete locations.";
		redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
	}
?>
<?php
	global $locationClass;
	// PROCESS THE INPUT
	if(isset($_POST['submit'])) {
		if(isset($_GET['building_id'])) {
			if(!isset($_GET['section_id']) && !isset($_GET['cell_id'])) {
				$building_obj = $locationClass->find_building_by_id($_GET['building_id']);
				$building = $building_obj->fetch_assoc();
				$location = "builing ";
				$location .= $building['building_name'];
				$location_id = $building['building_id'];
				$location_table = 'building';
				$locationClass->edit_building($_GET['building_id']);
			}
			if(isset($_GET['section_id'])) {
				if(!isset($_GET['cell_id'])) {
					$section_obj = $locationClass->find_section_by_id($_GET['section_id']);
					$section = $section_obj->fetch_assoc();
					$location = "section ";
					$location .= $section['section_name'];
					$location_id = $section['section_id'];
					$location_table = 'section';
					$locationClass->edit_section($_GET['section_id']);
				}
				if(isset($_GET['cell_id'])) {
					$cell_obj = $locationClass->find_cell_by_id($_GET['cell_id']);
					$cell = $cell_obj->fetch_assoc();
					$location = "cell ";
					$location .= $cell['cell_name'];
					$location_id = $cell['cell_id'];
					$location_table = 'cell';
					$locationClass->edit_cell($_GET['cell_id']);
				}
			}
			//Log edition of the location
			$_POST['log_action'] = "Edited {$location}";
			$_POST['log_db_action'] = "Edited {$location_id}";
			$_POST['log_table_affected'] = "{$location_table}";
			$_POST['log_serial_num'] = "";
			$_POST['log_maint_type'] = "";
			$logClass->newLog();
			$session->message("The location was edited.");
			redirect_to('locations.php');
		} else {
			redirect_to('locations.php');
		}
	}
	//DISPLAY THE FORM
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<?php
	if(isset($_GET['building_id'])) {
		if(!isset($_GET['section_id']) && !isset($_GET['cell_id'])) {
			$building_obj = $locationClass->find_building_by_id($_GET['building_id']);
			$building = $building_obj->fetch_assoc();?>
			<div class="container col-md-12 col-md-offset-0">
				<div class="col-md-12 col-md-offset-0">
					<div class="center-text"><h1><i class="icon-edit"></i> Edit Building <i class="icon-building"></i></h1></div>
					<p class="text-error"><?php echo output_message($message); 
					unset($_SESSION['message']);
					?></p>
					<form class="form-horizontal" action="edit_location.php?building_id=<?php echo htmlentities($building['building_id']); ?>" method="post" enctype="multipart/form-data" role="form">
						<div class="form-group">
							<label for="building_name">Building Name</label>
							<input class="form-control" type="text" name="building_name" id="building_name" maxlength="30" placeholder="Required-Example: FabTech 1925, IGM Solutions..." value="<?php echo htmlentities($building['building_name']); ?>" required/>
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
							<button class="btn btn-success btn-block" type="submit" name="submit" id="submit" value="Submit"><i class="icon-edit"></i> Building</button>
						</div>
					</form>
				</div>
				<a class="btn btn-info" href="locations.php"><i class="icon-chevron-left"></i> Return to Locations</a>
			</div><?php
		}
		if(isset($_GET['section_id'])) {
			if(!isset($_GET['cell_id'])) {
				$section_obj = $locationClass->find_section_by_id($_GET['section_id']);
				$section = $section_obj->fetch_assoc();
				$building_obj = $locationClass->find_building_by_id($_GET['building_id']);
				$building = $building_obj->fetch_assoc();
				?>
				<div class="container col-md-12 col-md-offset-0">
					<div class="col-md-12 col-md-offset-0">
						<div class="center-text"><h1><i class="icon-edit"></i> Edit Section <i class="icon-th-large"></i></h1></div>
						<p class="text-error"><?php echo output_message($message); 
						unset($_SESSION['message']);
						?></p>
						<form class="form-horizontal" action="edit_location.php?building_id=<?php echo htmlentities($building['building_id']); ?>&section_id=<?php echo htmlentities($section['section_id']); ?>" method="post" enctype="multipart/form-data" role="form">
							<div class="form-group">
								<label for="section_name">Section Name</label>
								<input class="form-control" type="text" name="section_name" id="section_name" maxlength="30" placeholder="Required-Example: North Side, Paint..." value="<?php echo htmlentities($section['section_name']); ?>" required/>
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
								<button class="btn btn-success btn-block" type="submit" name="submit" id="submit" value="Submit"><i class="icon-edit"></i> Section</button>
							</div>
						</form>
					</div>
					<a class="btn btn-info" href="locations.php"><i class="icon-chevron-left"></i> Return to Locations</a>
				</div><?php
			}
			if(isset($_GET['cell_id'])) {
				$cell_obj = $locationClass->find_cell_by_id($_GET['cell_id']);
				$cell = $cell_obj->fetch_assoc();
				$section_obj = $locationClass->find_section_by_id($_GET['section_id']);
				$section = $section_obj->fetch_assoc();
				$building_obj = $locationClass->find_building_by_id($_GET['building_id']);
				$building = $building_obj->fetch_assoc();?>
				<div class="container col-md-12 col-md-offset-0">
					<div class="col-md-12 col-md-offset-0">
						<div class="center-text"><h1><i class="icon-edit"></i> Edit Cell <?php echo htmlentities($cell['cell_name']); ?> <i class="icon-th"></i></h1></div>
						<p class="text-error"><?php echo output_message($message); 
						unset($_SESSION['message']);
						?></p>
						<form class="form-horizontal" action="edit_location.php?building_id=<?php echo htmlentities($building['building_id']); ?>&section_id=<?php echo htmlentities($section['section_id']); ?>&cell_id=<?php echo htmlentities($cell['cell_id']); ?>" method="post" enctype="multipart/form-data" role="form">
							<div class="form-group">
								<label for="cell_name">Cell Name</label>
								<input class="form-control" type="text" name="cell_name" id="cell_name" maxlength="30" placeholder="Required-Example: NPI Cell, Cabinet Cell..." value="<?php echo htmlentities($cell['cell_name']); ?>" required/>
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
								<button class="btn btn-success btn-block" type="submit" name="submit" id="submit" value="Submit"><i class="icon-edit"></i> Cell</button>
							</div>
						</form>
					</div>
					<a class="btn btn-info" href="locations.php"><i class="icon-chevron-left"></i> Return to Locations</a>
				</div><?php
			}
		}
	}
?>
<?php include_layout_template('admin_footer.php'); ?>