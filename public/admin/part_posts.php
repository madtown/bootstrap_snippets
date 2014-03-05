<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 4 ) { 
	$_SESSION['message'] = "You are not authorized to perform work.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
?>
<?php
global $requestClass;
$most_recent_post = $workClass->find_most_recent_post();
$most_recent_post_array = $most_recent_post->fetch_array();
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container">
    <div class="col-sm-12">
    	<div>
            <div class="center-text">
                <h1><i class="icon-wrench"></i> Part Posts</h1>
                <h5><p class="lead text-danger"><?php if (isset($_SESSION['message'])) {echo $_SESSION['message']; unset($_SESSION['message']);} ?></p></h5>
            </div>
        </div><a class="btn btn-info pull-right" href="queue.php"><h4><i class="icon-list-ol"></i> Queue</h4></a>
    </div>
</div>
<div class="container">
    <div class="col-sm-12">
        <ul class="timeline">
			<?php include('../admin/pre_thread.php'); ?>
            <script>
                function updateThread(){
                    function geturlparam(name){
                       if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
                          return decodeURIComponent(name[1]);
                    }
                    var post_id = <?php echo htmlentities($most_recent_post_array['post_id'])?>;
                    var page = geturlparam('request_id');
                    var url = 'pre_thread.php?request_id=' + page + '&post_id=' + post_id;
                    $('#thread').load(url);
                }
                setInterval("updateThread()", 5000);
            </script>
        </ul>
    </div>
</div>
    <script language="javascript">
        jQuery(document).ready(function() {
          jQuery("div.timeago").timeago();
          jQuery("span.timeago").timeago();
        });
    </script>
<?php include_layout_template('admin_footer.php'); ?>