<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); } 
elseif ($_SESSION['rank'] > 2) { 
	$message = "You do have a sufficient rank to add notfication recipients.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
	} ?>
<?php
if (isset($_POST['submit']) && ($_POST['type'] != NULL) && ($_POST['username'] != NULL)) {
	global $notificationClass;
	$notificationClass->add_user();
} else { //Form is unsubmitted
	$type = $_GET['type'];
}

?>

<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>

	<div class="center-text"><h2>Add Recipient:</br><?php echo htmlentities($type); ?></h2></div>

	<?php echo output_message($message); ?>
	<div class="container">
    	<div class="center-text">
		<div class="col-md-12 col-md-offset-0 well">
		<?php echo output_message($message); ?>
		<form action="add_notification.php" method="post">
		<table>
		    <tr>
		    	<td>Username: </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-user"></i></span>
                        <select class="form-control" name="username">
                        <?php
							global $connection;
							global $database;
							$input = $_GET['type'];
							$table = "equipment_log";
							$sql = "SELECT * FROM users ORDER BY username";
							$query = $database->query($sql);
							$result = $query->fetch_assoc();
						?>
						<option value="None Selected">None Selected</option> <?php
						do 
						{ if($result['username']){
							?><option value='<?php echo trim($result['username'], '"');?>'>"<?php echo trim($result['username'], '"');?>"</option><?php
						}
						}
							while ($result=$query->fetch_assoc());
						?>
						</select>
                    </div>
                </td>
		    </tr>
			<tr>
		      	<td>Type: </td>
		      	<td>				
                    <div class="input-group">
                    	<span class="input-group-addon"><i class="icon-warning-sign"></i></span>
                    	<input class="form-control" placeholder="None" type="text" name="type" maxlength="50" value="<?php echo htmlentities($type); ?>" disabled>
                    </div>
                    <input class="form-control" placeholder="None" type="hidden" name="type" maxlength="50" value="<?php echo htmlentities($type); ?>">
				</td>
		    </tr>
	
            <tr>
              <td colspan="2">
                <input class="btn btn-success btn-block" style='text-align:center;vertical-align:middle' type="submit" name="submit" value="Add User" />
              </td>
            </tr>
			</table>
		</form>
		</div>
        </div>
	</div>
	
<?php include_layout_template('admin_footer.php'); ?>
