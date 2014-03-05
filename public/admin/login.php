<?php
require_once("../../includes/initialize.php");
if($session->is_logged_in()) {
  redirect_to("index.php");
}


// Remember to give your form's submit tag a name="submit" attribute!
if (isset($_POST['submit'])) { // Form has been submitted.

	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
 
	// Check database to see if username/password exist.
	$found_user = $userClass->authenticate($username, $password);

	if ($found_user) {
		$session->login($found_user);
		$_POST['log_action'] = "{$found_user->username} logged in.";
		$_POST['log_db_action'] = "";
		$_POST['log_table_affected'] = "maint_log";
		$_POST['log_serial_num'] = "";
		$_POST['log_maint_type'] = "";
		$logClass->newLog();
		if ($_SESSION['rank'] == 5) {
			redirect_to("schedule.php?user_id=".$database->escape_value($_SESSION['user_id'])."&type=0");
		} elseif ($_SESSION['rank'] == 6) {
			redirect_to("request.php");
		} else { redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);}
	} else {
	// username/password combo was not found in the database
	$message = "Username/password combination incorrect.";
	} 
} else { // Form has not been submitted.
	$username = "";
	$password = "";
	$rank = "";
}
?>
<?php include_layout_template('header.php'); ?>
<body>
	<br/>
	<div class="span6 offset4 well">
		<form class="form-vertical" action="login.php" method="post">
		<fieldset>
		    <div id="legend">
		      <div class="center-text"><legend class="">Login <?php echo output_message($message); ?></legend></div>
		    </div>
			<div class="control-group">
				<label class="control-label"  for="username">Username</label>
				<div class="controls">
					<input type="text" name="username" id="username" method="post" placeholder="Username" class="input-xlarge" value="<?php echo htmlentities($username); ?>"autofocus />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="password">Password</label>
				<div class="controls">
					<input type="password" name="password" id="password" method="post" placeholder="Password" class="input-xlarge" value="<?php echo htmlentities($password); ?>" />
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
                	<span class="text-success">
                    	<input type="submit" name="submit" id="submit" method="post" value="Login" class="btn btn-lg btn-primary" />
                        <i class="custom-firefox pull-right icon-2x"></i><i class="custom-opera pull-right icon-2x"></i><i class="icon-compass pull-right icon-2x"></i><i class="custom-chrome pull-right icon-2x"></i>
                        <p class="pull-right"><small>Supported desktop browsers.</small></p>
             		</span>
				</div>
			</div>
		</fieldset>
		</form>
	</div>
<?php include_layout_template('footer.php'); ?>
</body>