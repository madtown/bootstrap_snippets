<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 4 ) { 
	$_SESSION['message'] = "You are not authorized to edit documents.";
	redirect_to("index.php");
	}
?>
<?php
	if (isset($_GET['serial_num'])) {
		$serial_num = $_GET['serial_num'];
		$machine = $machineClass->find_by_serial_num($serial_num);
	}
	if(isset($_POST['submit'])) {
		$photo = new Request();
		$photo->uploadFile($_FILES["file"]);
		$message = "The file was successfully uploaded.";
		$_POST['log_action'] = "Added Document to {$_POST['doc_name']}";
		$_POST['log_db_action'] = "Added {$_POST['doc_name']} to {$_POST['serial_num']}";
		$_POST['log_table_affected'] = "machine_docs";
		$_POST['log_serial_num'] = "";
		$_POST['log_maint_type'] = "";
		$logClass->newLog();
		unset($_POST['submit']);
		unset($_FILES["file"]["name"]) ;
		unset($_POST['serial_num']);
		unset($_POST['doc_name']);
		$url = "view_docs.php?serial_num=".$serial_num;
		redirect_to($url);
	 } else {
		$message = "No file selected.";
	}
	
?>

<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container">
	<div class="span5 offset0 well">
	<div class="center-text"><h2>Document Upload</h2></div>
		<?php echo $message; ?> 
        <form action="" method="post" enctype="multipart/form-data">
            <label for="file">Filename:</label>
                <input type="file" name="file" id="file"><br>
            <label for="doc_name">Document Name:</label>
                <input class="input-block-level" placeholder="Parts-list, Manual, Wiring Diagram, etc..." type="text" name="doc_name" id="doc_name" maxlength="255">
            <input type="hidden" name="serial_num" id="serial_num" maxlength="255" value="<?php echo htmlentities($serial_num)?>"><br>
            <button class="btn btn-success btn-block" type="submit" name="submit" value="Submit">Upload Document</button>
        </form>
	</div>
</div>
<?php include_layout_template('admin_footer.php'); 
	unset ($_SESSION['message']);
?>