<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); } 
elseif ($_SESSION['rank'] > 4) { 
	$message = "You do have a sufficient rank to create users.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
	} ?>
<?php
if (isset($_POST['submit']) && ($_POST['password']==$_POST['password2'])) {
	$fields = array('username', 'password', 'rank', 'email', 'location_type', 'location_id');
	$table = "users";
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$password2 = trim($_POST['password2']);
	$rank = trim($_POST['rank']);
	$email = trim($_POST['email']);
	$current_location = $database->escape_value(trim($_POST['current_location']));
	$location_array = explode(',' , $current_location);	
	$location_type = $location_array['0'];
	$location_id = $location_array['1'];
	$user_values = array($username, $password, $rank, $email, $location_type, $location_id);
	global $connection;
	$sql = "INSERT INTO ".$table." (";
	$sql .= join(", ", $fields);
	$sql .= ") VALUES ('";
	$sql .= join("', '", $user_values);
	$sql .= "')";
	$database->query($sql);
	global $logClass;
	/*1*/ 	$_POST['log_action'] = "New User";
	/*2*/	$_POST['log_db_action'] = "Added: {$username}";
	/*3*/	$_POST['log_serial_num'] = "{$username}";
	/*4*/	$_POST['log_maint_type'] = "";
	/*5*/	$_POST['log_table_affected'] = "users";	
	$logClass->newLog();
	$_SESSION['message'] = "New user created successfully";
	redirect_to("view_users.php");
	
} else { //Form is unsubmitted
	$username = "";
	$password = "";
	$password2 = "";
	$rank = "";
	$email = "";
}
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container col-md-12 col-md-offset-0">
<div class="col-md-12 col-md-offset-0">
	<div class="center-text"><h2><i class="icon-plus-sign"></i> User</h2></div>
    	<div class="center-text"><?php echo output_message($message); ?></div>
		<form class="form-horizontal" action="edit_users.php" method="post" role="form">
		<div class="form-group">
            <label for="username">Username</label>
				<div class="input-group">
                    <span class="input-group-addon"><i class="icon-user"></i></span>
					<input class='form-control' placeholder="First Name Last Name" type="text" name="username" id="username" maxlength="30" value="<?php echo htmlentities($username); ?>" required>
                    <script>
                    $('input#username').maxlength({
                        alwaysShow: false,
                        threshold: 20,
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
        	<label for="serial_num">Password</label>
				<div class="input-group">
                    <span class="input-group-addon"><i class="icon-key"></i></span>
					<input class='form-control' placeholder="Password" type="password" name="password" id="password" maxlength="30" value="<?php echo htmlentities($password); ?>" required>
                    <script>
                    $('input#password').maxlength({
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
      	</div>
		<div class="form-group">
            <label for="serial_num">Confirm Password:</label>
				<div class="input-group">
                    <span class="input-group-addon"><i class="icon-key"></i></span>
			  		<input class='form-control' placeholder="Retype Password" type="password" name="password2" id="password2" maxlength="30" value="<?php echo htmlentities($password2); ?>" required>
                    <script>
                    $('input#password2').maxlength({
                        alwaysShow: false,
                        threshold: 500,
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
            <label for="current_location">Current Location</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-map-marker"></i></span>
                <select class="form-control" type="text" name="current_location" id="current_location" class="input-block-level">
                    <?php include('../scripts/locationSelect.php');?>
                </select>
                <script>
                    function format(current_location) {
                        var originalOption = current_location.element;
                        return "" + current_location.text + "";
                    }
                    $("#current_location").select2({
                        placeholder: "Select Location",
                        allowClear: true,
                        formatResult: format,
                        formatSelection: format,
                        escapeMarkup: function(m) { return m; }
                    });
                </script>
            </div><span class="text-danger"><small>Provide a Location if possible</small></span>
        </div>
		<div class="form-group">
            <label for="rank">Rank</label>
			<?php 
			if (isset($_SESSION['rank'])){		
				switch ($_SESSION['rank']) {
					case 4: 
						echo "<select class='form-control' name='rank'>";
						echo "<option value='6'>#6 Request Only</option>";
						echo "<option value='5'>#5 Daily Maintenance</option>";
						echo "</select>";
						break;
					case 3: 
						echo "<select class='form-control' name='rank'>"; 
						echo "<option value='6'>#6 Request Only</option>";
						echo "<option value='5'>#5 Daily Maintenance</option>";
						echo "<option value='4'>#4 Maintenance</option>";
						echo "</select>";
						break;
					case 2: 
						echo "<select class='form-control' name='rank'>";
						echo "<option value='6'>#6 Request Only</option>";
						echo "<option value='5'>#5 Daily Maintenance</option>";
						echo "<option value='4'>#4 Maintenance</option>";
						echo "<option value='3'>#3 Manager</option>";
						echo "<option value='2'>#2 Administrator</option>";
						echo "</select>";
						break;
					case 1: 
						echo "<select class='form-control' name='rank'>";
						echo "<option value='6'>#6 Request Only</option>";
						echo "<option value='5'>#5 Daily Maintenance</option>";
						echo "<option value='4'>#4 Maintenance</option>";
						echo "<option value='3'>#3 Manager</option>";
						echo "<option value='2'>#2 Administrator</option>";
						echo "<option value='1'>#1 Supreme Commander</option>";
						echo "</select>";
						break;
					}
				} else {
					echo "You have no rank and, therefore, cannot assign a rank to others.";
				}
		
					?>
      	</div>
		<div class="form-group">
            <label for="email">Email</label>
				<div class="input-group">
                    <span class="input-group-addon"><i class="icon-envelope"></i></span>
					<input class='form-control' placeholder="Example@FabTech.com" type="text" name="email" id="email" maxlength="30" value="<?php echo htmlentities($email); ?>" />
                    <script>
                    $('input#email').maxlength({
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
		</div>
		<div class="form-group">
			<input class="btn btn-success btn-block btn-lg" style='text-align:center;vertical-align:middle' type="submit" name="submit" value="Add User">
		</form>
        </div>
	</div>
</div>
<?php include_layout_template('admin_footer.php'); ?>
