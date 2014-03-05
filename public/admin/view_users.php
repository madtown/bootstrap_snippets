<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
  // Find all the procedures
	$userClass = new User();
 	$photos = $userClass->find_all_sub_user($_SESSION['rank']);
?>
<script type="text/javascript">

function proceed(form) {
  var el, els = form.getElementsByTagName('input');
  var i = els.length;
  while (i--) {
    el = els[i];

    if (el.type == 'checkbox' && !el.checked) {
      form.proceedButton.disabled = true;
      return; 
     }
  }
  form.proceedButton.disabled = false;
}

</script>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">	
        <div class="center-text"><h2><i class="icon-user"></i> Users</h2><a class="btn btn-success pull-right" style='text-align:center;vertical-align:middle' href="edit_users.php"><i class="custom-user-add"></i> Add User</a></div>
        <?php echo output_message($message); ?>
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th></th>
                <th style='text-align:center;vertical-align:middle'>Username</th>
                <th style='text-align:center;vertical-align:middle'>Rank</th>
                <th style='text-align:center;vertical-align:middle'>Email</th>
                <th style='text-align:center;vertical-align:middle'>Domain</th>
                <th style='text-align:center;vertical-align:middle'>Edit</th>
                <th style='text-align:center;vertical-align:middle'>Delete</th>
              </tr>
            </thead>
        <?php foreach($photos as $photo): ?>
          <tr>
            <td style='text-align:center;vertical-align:middle'><i class="icon-user icon-3x"></i></td>
            <td style='vertical-align:middle'><h3><?php echo $photo->username; ?></h3></td>
            <td style='text-align:center;vertical-align:middle'><h1><?php echo $photo->rank; ?></h1></td>
            <td style='text-align:center;vertical-align:middle'><small><?php echo $photo->email; ?></small></td>
            <td style='text-align:center;vertical-align:middle'><small>
                <?php 	if(isset($photo->location_id)) {
                            global $locationClass;
                            $location_type 		= $photo->location_type; 
                            $location_id 		= $photo->location_id; 
                            $location_string 	= $locationClass->find_machine_location($location_type, $location_id);
                            echo $location_string; 
                        } else { 
                            echo 'No location selected.';
                        }
                ?></small>
            </td>
            <td style='text-align:center;vertical-align:middle'><p><a class="btn btn-warning" style='text-align:center;vertical-align:middle' href="change_users.php?username=<?php global $photo; echo $photo->username; ?>"><i class="icon-edit icon-4x"> </i></a></p>
            </td>
            <td style='text-align:center;vertical-align:middle'><p><a class="btn btn-danger" style='text-align:center;vertical-align:middle' data-target="#<?php echo $photo->id; ?>" data-toggle="modal"><i class="icon-trash icon-4x"> </i></a></p>
            <?php //modal warning for deletion
                $id 			= $photo->id;
                $type 			= 1;
                $header_text	= '<strong>Warning!</strong> You are about to delete '.$photo->username.'!';
                $body 			= '<p>Are you sure you want to PERMANENTLY delete '.$photo->username.'?</p></br>
									<p><i class="icon-warning-sign"></i> <strong>Consequences</strong></p>
									<ul>
										<li>This action CANNOT be undone.</li>
										<li>All notifications will be severed permanently.</li>
										<li>They obviously will not be able to log in or do work.</li>
									</ul>   
									<p><i class="icon-cloud"></i> <strong>Silver Lining</strong></p>
									<ul>             
										<li>This will NOT affect the maintenance log.</li> 
										<li>This will NOT delete notes if they are connected to this person.</li>
										<li>It is pretty easy to re-add someone but be sure that you can add a user of that permission level.</li>
									</ul>
									<p>To proceed with deletion, check the box below, and click <button class="btn btn-danger"><i class="icon-trash"></i></button>.</p>';
                $link 			= 'delete_user.php?id='.$photo->id;
                warningModal($id, $type, $header_text, $body, $link); 
            ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </table>
    </div>
</div>
<?php include_layout_template('admin_footer.php'); ?>
