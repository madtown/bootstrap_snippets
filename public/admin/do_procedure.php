<?php require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 5 ) { 
	$_SESSION['message'] = "You are not authorized to perform work.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
global $machineClass;
$stepClass = new Step();
$procedure_id = $_GET['procedure_id'];
$serial_num = $_GET['serial_num'];
if (isset($_GET['step_num'])) {
	$step_num = $_GET['step_num'];
}
$machine = $machineClass->find_by_serial_num($serial_num);
$table = "maint_steps";
$sql = "SELECT * FROM maint_steps WHERE procedure_name='".$procedure_id."' ORDER BY step_num LIMIT 1";
$query = $database->query($sql);
$result = $query->fetch_assoc();
if(isset($_POST['submit'])) {
	if (isset($_FILES['file']['name']) and $_FILES['file']['name'] != "") {
		$file = basename($_FILES['file']['name']);
		//verify size  
		$size_maxi = 1048576*10;//10mb  
		$size = filesize($_FILES['file']['tmp_name']);  
		$extensions = array('.png', '.gif', '.jpg', '.jpeg', '.pdf', '.doc', '.docx', '.xls', '.xlsx', '.JPG', '.JPEG');  
		$extension = strrchr($_FILES['file']['name'], '.');  
		//Start the file type verification 
		//If the extension is not in table 
		if(!in_array($extension, $extensions)){  
			$error = 'You must make use of file in the following forma type png, gif, jpg, jpeg, txt ..etc...';  
		}  
		if($size>$size_maxi){  
			$error = 'File size is above allowed limitations...';  
		}  
		if (isset($error)) {
			return false;
		} else {
			//verify size of request photo
			$tmpname 		= $_FILES['file']['tmp_name'];
			$uploads_dir 	= '../../public/notes/';
			$filepath 		= $uploads_dir.date("YmdHis").$_FILES['file']['name'];
		if (move_uploaded_file($_FILES["file"]["tmp_name"], $uploads_dir.date("YmdHis").$_FILES["file"]["name"])) {
				return true;
			} else {
				return false;
			};
		}
	}
	if(isset($_POST['note']) and $_POST['note'] != "") {
		$_POST['log_note'] = $_POST['note'];
		$message = "Note was saved.";
	} else { $_POST['log_note'] = NULL; }
	if (isset($_FILES['file']['name']) and $_FILES['file']['name'] != "") {
		$_POST['log_note_URL'] = $filepath;
		$message = "Note was saved.";
	} else { $_POST['log_note_URL'] = NULL; }
	if($_POST['notify'] == "send") {
		$subject="Note regarding {$machine->machine_num}";
		$type="Alert Maint Note During Procedure";
		global $session;
		$body = "Note from {$session->username} about {$machine->machine_num}:</br>";
		if (isset($_POST['note'])) {$body .= "Text: {$_POST['note']} </br>"; }
		if (isset($filepath)) {
			$body .= "Image:</br>";
			$attachment = "{$filepath}"; 
		} else {$attachment = "";}
		$body .= "<strong>Link to Machine Page:</strong></br>";
		$body .= "<a>http://192.168.0.31/public/admin/schedule_by_machine.php?serial_num={$serial_num}</a></br>";
		$body .= "DO NOT REPLY TO THIS EMAIL";
		if (smtpmailer($type, $subject, $body, $attachment)) {
		}
		if (!empty($error)) {echo $error;}
	}
	$step_num 						= $_POST['step_num'];
	$_POST['log_action'] 			= "{$step_num}";
	$_POST['log_db_action']			= "{$procedure_id}";
	$_POST['log_table_affected'] 	= "maint_log";
	$_POST['log_serial_num'] 		= "{$serial_num}";
	$_POST['log_maint_type'] 		= "{$procedure_id}";
	$_POST['log_total_steps']		= $stepClass->count_procedure_steps($procedure_id);
	if ($logClass->newLog()){
		unset($_POST['log_total_steps']);
		unset($_POST['log_action']);
		unset($_POST['log_db_action']);
		unset($_POST['log_table_affected']);
		unset($_POST['log_serial_num']);
		unset($_POST['log_maint_type']);
		unset($_POST['log_note']);
		unset($_POST['log_note_URL']);
		unset($_POST['step_num']);
		unset($_POST['submit']);
	}	
}
if(isset($_POST['pause']) && isset($_POST['step_num'])) {
	$step_num = $_POST['step_num'];
	$_POST['log_action'] = "{$_POST['step_num']}";
	$_POST['log_db_action'] = "{$procedure_name}";
	$_POST['log_table_affected'] = "maint_log";
	$_POST['log_serial_num'] = "{$serial_num}";
	$_POST['log_maint_type'] = "{$procedure_id}";
	$_POST['log_note'] = "Paused";
	if ($logClass->newLog()){
		unset($_POST['log_action']);
		unset($_POST['log_db_action']);
		unset($_POST['log_table_affected']);
		unset($_POST['log_serial_num']);
		unset($_POST['log_maint_type']);
		unset($_POST['step_num']);
	} 
}
if(isset($_POST['unpause']) && isset($_POST['step_num'])) {
	$step_num = $_POST['step_num'];
	$_POST['log_action'] = "{$_POST['step_num']}";
	$_POST['log_db_action'] = "{$procedure_name}";
	$_POST['log_table_affected'] = "maint_log";
	$_POST['log_serial_num'] = "{$serial_num}";
	$_POST['log_maint_type'] = "{$procedure_id}";
	$_POST['log_note'] = "Unpause";
	if ($logClass->newLog()){
		unset($_POST['log_action']);
		unset($_POST['log_db_action']);
		unset($_POST['log_table_affected']);
		unset($_POST['log_serial_num']);
		unset($_POST['log_maint_type']);
		unset($_POST['step_num']);
		unset($_POST['unpause']);
	} 
}
?>
<?php include_layout_template('admin_header.php'); ?>
</br>
	<div class="container">
    	<?php 	echo output_message($message);
				unset($message);?>
		<?php $stepClass->carouselSteps(); ?>
	</div>
	<div class="container">
    <div>
		<form method="post" enctype="multipart/form-data" action="<?php $actual_link = "../admin/do_procedure.php?procedure_id={$_GET['procedure_id']}&serial_num={$_GET['serial_num']}";
		echo $actual_link;?>">
		<input type="hidden" name="procedure_name" id="procedure_name" method="post" value="<?php echo $_GET['procedure_name'];?>"</input>
		<input type="hidden" name="serial_num" id="serial_num" method="post" value="<?php echo $_GET['serial_num'];?>"</input>
		<input type="hidden" name="step_num" id="step_num" method="post" value="<?php echo $_POST['undone_step'];?>"</input>
        <input type="submit" name="submit" id="submit" id="nextStep" class="btn btn-large btn-block btn-success" value="NEXT STEP &#9658;" <?php if(isset($_POST['pause'])) { echo htmlentities('disabled'); }?>></br>        
        <input type="submit" name="<?php 
			
if(isset($_POST['pause'])) { 
	echo htmlentities('unpause'); 
} else {
	echo htmlentities('pause'); 
} 
?>" id="<?php 	
if(isset($_POST['pause'])) { 
	echo htmlentities('unpause'); 
} else {
	echo htmlentities('pause'); 
} 
?>" class="btn btn-large pull-right btn-danger" value="<?php 	
if(isset($_POST['pause'])) { 
echo htmlentities('UNPAUSE'); 
} else {echo htmlentities('PAUSE'); 
} 
?> &#9616; &#9612;"</input>	
    <div style='vertical-align:middle' class="col-md-6">
        	<div class="form-group">
                <div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
                    <div class="center-text">
                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;"></div>
                        <div>
                            <span class="btn btn-primary btn-block btn-file">
                                <span class="fileinput-new"><i class="icon-camera"></i> Picture</span>
                                <span class="fileinput-exists"><i class="icon-refresh"></i> Change</span>
                                <input type="file" name="file" id="file">
                            </span>
                            <a href="#" class="btn btn-danger btn-block fileinput-exists" data-dismiss="fileinput"><i class="icon-trash"></i> Remove</a>
                        </div>
                    </div>
                </div>
          	</div>
            <h3><i class="icon-edit-sign"></i> Record Note (No Notification)</h3>
          		<input class="form-control" type="radio" name="notify" value="nosend" checked="checked">
           	<h3><i class="icon-envelope"></i> Notify Maintenance</h3>
            	<input class="form-control" type="radio" name="notify" value="send">
	</div>
    	<textarea rows="4" type="text" name="note" id="note" maxlength="256" class="form-control" placeholder="Write a note about anything important...The note will be saved when you click NEXT STEP. This may take up to 5 seconds to post."></textarea>
        <script>
		$('textarea#note').maxlength({
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
    	</form>
	<a class="btn btn-info btn-small" href="schedule.php?user_id=<?php echo htmlentities($database->escape_value($_SESSION['user_id'])); ?>&type=0"><i class="icon-chevron-left"></i> Daily Schedule</a>
	</div>
</div>
<?php include_layout_template('admin_footer.php'); ?>