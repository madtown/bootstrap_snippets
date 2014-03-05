<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 5 ) { 
	$_SESSION['message'] = "You are not authorized to perform work.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
?>
<?php
global $inboxClass;
if (isset($_POST['submit']) && $_POST['user_id'] != "") {
	$inboxClass->sendMessage();
	unset($_POST['file']);
	unset($_POST['text']);
	unset($_POST['user_id']);
	unset($_POST['submit']);
	unset($_POST['inbox_submit']);
}
if (isset($_POST['inbox_submit']) && $_POST['user_id'] != "") {
	$inboxClass->sendMessage();
	unset($_POST['file']);
	unset($_POST['text']);
	unset($_POST['user_id']);
	unset($_POST['inbox_submit']);
	unset($_POST['submit']);
}
$posts = $inboxClass->find_notifications_for_last_3_weeks();
$posts_array = $posts->fetch_array();
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container">
    <div class="col-sm-12">
        <div class="center-text">
            <h1><a class="btn btn-primary pull-left" href="#send" data-toggle="modal"><h4><i class="custom-paper-plane"></i> Message</h4></a><i class="icon-inbox"></i> My Inbox<a class="btn btn-info pull-right" href="my_homepage.php?user_id=<?php echo $_SESSION['user_id']; ?>"><h4><i class="icon-list-ol"></i> My Homepage</h4></a></h1>
            <h5><p class="lead text-danger"><?php if (isset($_SESSION['message'])) {echo $_SESSION['message']; unset($_SESSION['message']);} ?></p></h5>
        </div>
    </div>
</div>
<div class="container">
    <div class="col-sm-12">
        <ul class="timeline">
			<?php $inboxClass->formatThread($posts); ?>
        </ul>
    </div>
</div>
    <script language="javascript">
        jQuery(document).ready(function() {
          jQuery("div.timeago").timeago();
          jQuery("span.timeago").timeago();
        });
    </script>
<!-- Modal -->
<div class="modal fade" id="send" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1><i class="custom-paper-plane"></i> Message</h1>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="my_inbox.php" method="post" role="form" enctype="multipart/form-data">		
                    <div class="form-group">
                        <label for="user_id">Users</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-user"></i></span>
                            <select class="form-control" name="user_id" id="user_id"> 
                                <?php 
                                    global $userClass;
                                    $users = $userClass->userSelect();
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="text">Text</label>
                        <textarea class="form-control" rows="4" type="text" id="text" name="text" maxlength="256" class="input-block-level" value="" placeholder="Hey, I think you should be aware that...."></textarea>
                        <script>
                        $('textarea#text').maxlength({
                            alwaysShow: false,
                            threshold: 100,
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
                        <div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
                            <div class="center-text">
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;">
                          	</div>
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
                    <div class="form-group">
                        <input class="btn btn-lg btn-primary btn-block btn-success" id="submit" type="submit" name="submit" placeholder="Required" value="Send Message">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse pull-left" data-dismiss="modal"><i class="icon-reply"></i> Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Modal -->
<?php include_layout_template('admin_footer.php'); ?>