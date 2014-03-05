<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); } 
elseif ($_SESSION['rank'] > 4) { 
	$message = "You do have a sufficient rank to edit users.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
	} ?>
<?php
if (isset($_POST['submit']) && isset($_POST['password']) && isset($_POST['username'])) {
	$username1 = $_GET['username'];
	$password1 = $_POST['password'];
	$found_user = $userClass->reauthenticate($username1, $password1);
	if (isset($found_user) and $_POST['password_new'] == $_POST['password2_new']) {
		$fields = array('username', 'password', 'rank', 'email', 'location_type', 'location_id');
		$table = "users";
		$username 		= trim($_POST['username']);
		if (isset($_POST['password_new']) and $_POST['password_new'] == $_POST['password2_new']) {  	
			$password = trim($_POST['password_new']);
		} else {
			$password = trim($_POST['password']);
		}
		$rank			= trim($_POST['rank']);
		$email 			= trim($_POST['email']);
		$current_location = $database->escape_value(trim($_POST['current_location']));
		$location_array = explode(',' , $current_location);	

		$fields['username'] 		= $username;
		$fields['password']			= $password;
		$fields['rank'] 			= $rank;
		$fields['email']			= $email;
		$fields['location_type'] 	= $location_array['0'];
		$fields['location_id']		= $location_array['1'];
		
		global $connection;
		$sql = "UPDATE ".$table." SET ";
		$sql .= "username='{$fields['username']}', ";
		$sql .= "password='{$fields['password']}', ";
		$sql .= "rank='{$fields['rank']}', ";
		$sql .= "location_type='{$fields['location_type']}', ";
		$sql .= "location_id='{$fields['location_id']}'";
		$sql .= ", email='{$fields['email']}'";
		$sql .= " WHERE username='{$username}'";
		$database->query($sql);
		
		global $logClass;
		/*1*/ 	$_POST['log_action'] = "Edit User";
		/*2*/	$_POST['log_db_action'] = "Edited: {$username}";
		/*3*/	$_POST['log_serial_num'] = "{$username}";
		/*4*/	$_POST['log_maint_type'] = "";
		/*5*/	$_POST['log_table_affected'] = "users";	
		$logClass->newLog();
		$_SESSION['message'] = "{$username} edited successfully";
		redirect_to("view_users.php");
	} else {
		$message = "Old Username/Password did not check out try again"; 
	}
} else { //Form is unsubmitted
	global $userClass;
	$user = $userClass->find_by_id($_GET['username']);
	if(isset($user)) {
		$username 		= $user->username;
		$password 		= "";
		$password_new 	= "";
		$password2_new 	= "";
		$rank 			= $user->rank;
		$email 			= $user->email;
	} else {
		redirect_to("view_users.php");
	}
}

?>

<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="center-text"><h2>Edit User</h2></div>
<?php echo output_message($message); ?>
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">
        <div class="center-text"><?php echo output_message($message); ?></div>
        <form class="form-horizontal" action="change_users.php?username=<?php echo $_GET['username']; ?>" method="post" role="form">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-user"></i></span>
                    <input class="form-control" placeholder="First Name Last Name" type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>" required disabled>
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
                </div>
                <span class="text-danger"><small>Provide a Location if possible</small></span>
            </div>
            <div class="form-group">
                <label for="password">Old Password</br><small class="text-danger">(required)</small></label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-key"></i></span>
                    <input class="form-control" placeholder="Password" type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password_new">New Password</br><small class="text-danger">(required)</small></label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-key"></i></span>
                    <input class="form-control" placeholder="Password" type="password" name="password_new" id="password_new" maxlength="30" value="<?php echo htmlentities($password_new); ?>">
              	</div>
            </div>
            <div class="form-group">
                <label for="password2_new">Confirm New Password</br><small class="text-danger">(required)</small></label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-key"></i></span>
                    <input class="form-control" placeholder="Retype Password" type="password" name="password2_new" id="password2_new" maxlength="30" value="<?php echo htmlentities($password2_new); ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="rank">Permission Level:</label>
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
                    <input class="form-control" placeholder="Example@FabTech.com" type="text" name="email" maxlength="30" value="<?php echo htmlentities($email); ?>" />
                </div>
            </div>
            <input class="hidden" placeholder="First Name Last Name" type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>" required>
            <div class="form-group">
                <input class="btn btn-success btn-block" style='text-align:center;vertical-align:middle' type="submit" name="submit" value="Edit User" />
            </div>
        </form>
        </div>
    </div>
</div>
	
<?php include_layout_template('admin_footer.php'); ?>
