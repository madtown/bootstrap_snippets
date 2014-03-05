<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 4 ) { 
	$_SESSION['message'] = "You are not authorized to edit documents.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
	}
?>
<?php
	$max_file_size = 10485760;  // expressed in bytes
	                            //     10240 =  10 KB
	                            //    102400 = 100 KB
	                            //   1048576 =   1 MB
	                            //  10485760 =  10 MB

	if(isset($_POST['submit'])) {
		$photo = new Document();
		if ($photo->uploadFile($_FILES["file"])) {
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
			$url = "view_all_docs.php";
			redirect_to($url);
		} else {
		$message = "There was a problem uploading the file. Please double-check that the file size does not exceed 20mb.";
		}
	 } else {
		$message = "No file selected.";
	}
?>
<script language="javascript">
	function getSerials() {
		var type = document.getElementById("type").value;
		$(document).ready(function() {
    		$("#getSerials").load("../scripts/getSerials.php?type=" + type);
});

}
</script>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">	
        <div class="center-text"><h2>Document Upload</h2></div>
        <div class="center-text"><p class="text-danger"><?php echo $message; ?></p></div> 
        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" role="form">
        <div class="form-group">
            <div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
                <div class="center-text">
                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;"></div>
                    <div>
                        <span class="btn btn-primary btn-block btn-file">
                            <span class="fileinput-new"><i class="icon-file-text"></i> Select File</span>
                            <span class="fileinput-exists"><i class="icon-refresh"></i> Change</span>
                            <input type="file" name="file" id="file">
                        </span>
                        <a href="#" class="btn btn-danger btn-block fileinput-exists" data-dismiss="fileinput"><i class="icon-trash"></i> Remove</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
         	<label for="doc_name">Document Name</label>
        	<input class="form-control" placeholder="Parts-list, Manual, Wiring Diagram, etc..." type="text" name="doc_name" id="doc_name" maxlength="255">
            <script>
			$('input#doc_name').maxlength({
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
            <label for="type">Machine Type</label>
        	<p id="getTypes" onchange="getSerials()"></p>
      	</div>
        <div class="form-group">
            <label for="machine_serial">Machine Serial</label>
        	<p change="type" mutliple="multiple" id="getSerials"></p>
      	</div>
        <div class="form-group">
            <button class="btn btn-success btn-block" type="submit" name="submit" value="Submit">Upload Document</button>
      	</div>
        </form>
    </div>
</div>
<?php include_layout_template('admin_footer.php'); 
	unset ($_SESSION['message']);
?>