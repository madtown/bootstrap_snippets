<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 2 ) { 
	$_SESSION['message'] = "You are not authorized to edit maps.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
	}
?>
<?php
	$max_file_size = 10485760;   // expressed in bytes
	                            //     10240 =  10 KB
	                            //    102400 = 100 KB
	                            //   1048576 =   1 MB
	                            //  10485760 =  10 MB

	if(isset($_POST['submit'])) {
		$photo = new Map();
		$photo->uploadFile($_FILES["file"]);
		$message = "The file was successfully uploaded.";
		$_POST['log_action'] = "Added Map";
		$_POST['log_db_action'] = "Added {$_POST['map_name']}";
		$_POST['log_table_affected'] = "maps";
		$_POST['log_serial_num'] = "";
		$_POST['log_maint_type'] = "";
		$logClass->newLog();
		unset($_POST['submit']);
		unset($_FILES["file"]["name"]) ;
		unset($_POST['location']);
		unset($_POST['map_name']);
		redirect_to('view_maps.php');
	 } else {
		$message = "Select a file.";
	}
	
?>

<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">
        <div class="center-text"><h1><i class="icon-plus-sign"></i> Map</h1></div>
        <div class="center-text"><p class="text-danger"><?php echo $message; ?></p></div>
        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" role="form">
        	<div class="col-sm-12 col-sm-offset-0">
        	<div class="form-group">
                <div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
                    <div class="center-text">
                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;"></div>
                        <div>
                            <span class="btn btn-primary btn-block btn-file">
                                <span class="fileinput-new"><i class="icon-file"></i> Select Map</span>
                                <span class="fileinput-exists"><i class="icon-refresh"></i> Change</span>
                                <input type="file" name="file" id="file">
                            </span>
                            <a href="#" class="btn btn-danger btn-block fileinput-exists" data-dismiss="fileinput"><i class="icon-trash"></i> Remove</a>
                        </div>
                        <span class="help-block text-primary">Max size: 5Mb.</span>
                    </div>
                </div>
            </div>
            </div>
            <div class="form-group">
            	<label for="map_name">Map Title</label>
            	<input class="form-control" placeholder="Example: Punches, All Machines, Floor Plan...etc" type="text" name="map_name" id="map_name" maxlength="255">
                <script>
				$('input#map_name').maxlength({
					alwaysShow: false,
					threshold: 250,
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
            	<label for="location">Location</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-map-marker"></i></span>
                	<input class="form-control" placeholder="Building, cell, area...etc" type="text" name="location" id="location" maxlength="255">
                    <script>
                    $('input#location').maxlength({
                        alwaysShow: false,
                        threshold: 250,
                        warningClass: "label label-success",
                        limitReachedClass: "label label-danger",
                        placement: 'top',
                        preText: 'Used ',
                        separator: ' of ',
                        postText: ' chars.'
                    });
					</script>
              	</div>
            </div>
            <div class="form-group">
            	<button class="btn btn-success btn-block btn-lg" type="submit" name="submit" value="Submit"><i class="icon-plus-sign"></i> Map</button>
         	</div>
        </form>
    </div>
</div>
<?php unset ($_SESSION['message']); ?>
<?php include_layout_template('admin_footer.php'); ?>