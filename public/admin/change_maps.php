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
		$message = "No file selected.";
	}
	
?>

<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container">
	<div class="span5 offset0 well">
	<div class="center-text"><h2><i class="custom-map"></i> Map Upload</h2></div>
	<?php echo $message; 
	$mapClass->showform() ;
	unset ($_SESSION['message']);
	?>
	</div>
</div>
<?php include_layout_template('admin_footer.php'); ?>