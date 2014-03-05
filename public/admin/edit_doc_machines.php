<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 3 ) { 
	$_SESSION['message'] = "You are not authorized to edit maintenance procedures.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
$id = $_GET['id'];
if(isset($_POST['submit']) && $_POST['machine_serial'] != "") {
	if ($document = new Document()) {
		$document->add_doc_machine();
		$message = "The machine was successfully added.";
		unset($_POST['submit']);
		unset ($_SESSION['message']);
		unset($_POST['procedure']);
		unset($_POST['type']);
		unset($_POST['machine_serial']);
		$url = "view_doc_machines.php?id=".$id;
		redirect_to($url);
	 }
	 } else {
		$_SESSION['message'] = "Please select all fields.";
		}
$document = $documentClass->find_by_id($id);
?>

<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>
<script language="javascript">
	function getSerials() {
		var type = document.getElementById("type").value;
		$(document).ready(function() {
    		$("#getSerials").load("../scripts/getSerials.php?type=" + type);
});

}
</script>
<div class="container col-md-6 col-md-offset-0">
	<div class="col-md-6 col-md-offset-0">
		<div class="center-text"><h1><i class="icon-link"></i> Machine to <i class="icon-copy"></i> <?php echo $document['0']->doc_name; ?></h1></div>
		<div><?php echo output_message($message); 
        unset($_SESSION['message']);
        ?></div>
		<form class="form-horizontal" action="edit_doc_machines.php?id=<?php echo htmlentities($id);?>" method="post" enctype="multipart/form-data" role="form">
			<input class="form-control" type="hidden" name="doc_id" id="doc_id" value="<?php echo htmlentities($id);?>" required>
			<div class="form-group">
                <label for="type">Type</label>
                <p id="getTypes" onchange="getSerials()"></p>
            </div>
            <div class="form-group">
                <label for="machine_serial">Machine Number</label>
                <p change="type" id="getSerials"></p>
            </div>
            <div class="form-group">
			<button class="btn btn-success btn-block" type="submit" name="submit" value="Submit"><i class="icon-plus-sign"></i> Add Machine to Document</button>
            </div>
		</form>
	</div>
    <a class="btn btn-info" href="view_doc_machines.phpid=<?php echo htmlentities($id);?>"><i class="icon-chevron-left"></i> Return to View Document Machines</a>
</div>	
<?php include_layout_template('admin_footer.php'); ?>