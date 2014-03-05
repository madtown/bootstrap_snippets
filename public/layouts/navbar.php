<?php
require_once('../../includes/initialize.php');
?>
<div data-spy="affix-top">
	<nav class="navbar navbar-inverse" role="navigation">
		<div class="navbar-inner">
			<div class="navbar-header">
<!-- Collapse Button -->
<!-- Navbar Brand -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-xs-12 col-sm-8 col-lg-8">
                        <div class="center-text">
                            <a class="navbar-brand" data-toggle="offcanvas" data-target="#offCanvasmenu" data-canvas="body">
                                <h3><img src="../../public/stock_images/navbar_icon.gif" width="120px"> Maintenance</h3>
                            </a>
                        </div>
                    </div>
                    <div class="col-xs-0 col-sm-offset-8 col-lg-offset-8">
                    	<div class="center-text">
                    		<h4><p class="navbar-text navbar-right"><i class="icon-user icon-white"></i> <a class="navbar-link" href=../../public/admin/logout.php>Logout: </a><?php echo $_SESSION['username'] ; ?>&nbsp;</p></h4>
                     	</div>
                    </div>
                    <div class="navbar-right">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#maint">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                </div>
            </div>
<!-- Collapsed Menu -->
				<div class="collapse navbar-collapse" id="maint">
					<ul class="nav navbar-nav">
                    	<?php 
							if ($_SESSION['rank'] < 6) : 
						?>
                        <li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><h4><i class="icon-calendar icon-white"></i> Schedule <i class="icon-caret-down"></i></h4></a>
							<ul class="dropdown-menu">
								<li><a href="../../public/admin/index.php"><h4><i class="icon-calendar icon-white"></i> Calendar</h4></a></li>
								<li><a href="./select_maint.php"><h4><i class="icon-tasks icon-white"></i> Master Schedules</h4></a></li>
							</ul>
						</li>
						<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><h4><i class="icon-wrench icon-white"></i> Maintenance <i class="icon-caret-down"></i></h4></a>
						<ul class="dropdown-menu">
							<li><a href="./request.php"><h4><i class="icon-edit icon-white"></i>  Request</h4></a></li>
                            <li><a href="./queue.php"><h4><i class="icon-list-ol icon-white"></i> Queue</h4></a></li>
                            <li><a href="./view_procedures.php"><h4><i class="icon-tasks icon-white"></i> Procedures</h4></a></li>
                            <li><a href="./part_posts.php"><h4><i class="custom-wrench icon-white"></i> Parts</h4></a></li>
                            <li><a href="./live_queue.php"><h4><i class="custom-spin3 icon-white icon-spin"></i> Live Queue</h4></a></li>
						</ul>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><h4><i class="icon-cogs icon-white"></i> Machines <i class="icon-caret-down"></i></h4></a>
							<ul class="dropdown-menu">
								<li><a href="./search_machine.php"><h4><i class="icon-search icon-white"></i> Search</h4></a></li>
                                <li><a href="./scan_barcode.php"><h4><i class="icon-qrcode icon-white"></i> Scan</h4></a></li>								
                                <li><a href="./view_all_machines.php"><h4><i class="icon-cogs icon-white"></i> Machines</h4></a></li>
                                <?php 
								if ($_SESSION['rank'] < 3 ) : 
								?>
                              	<li><a href="./view_all_documents.php"><h4><i class="icon-copy icon-white"></i> Documents</h4></a></li>
                                <li><a href="./manage_machines.php"><h4><i class="icon-dashboard icon-white"></i> Manage</h4></a></li>
								<?php
								endif;
								?>
							</ul>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><h4><i class="icon-map-marker icon-white"></i> Locator <i class="icon-caret-down"></i></h4></a>
							<ul class="dropdown-menu">
                            	<li><a href="./view_maps.php"><h4><i class="icon-globe icon-white"></i> Maps</h4></a></li>
								<li><a href="./locate_users.php"><h4><i class="icon-warning-sign icon-white"></i> Find Staff</h4></a></li>
								<li><a href="./locate_machines.php"><h4><i class="icon-lock icon-white"></i> Machines Down for Maintenance</h4></a></li>
                                <?php 
								if ($_SESSION['rank'] < 3 ) : 
								?>
                                <li><a href="./locations.php"><h4><i class="icon-building icon-white"></i> Locations</h4></a></li>
                                <?php
								endif;
								?>
							</ul>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><h4><i class="icon-user icon-lg icon-white"></i> User <b class="caret"></b></h4></a>
							<ul class="dropdown-menu">
								<li><a href="./view_users.php"><h4><i class="icon-group icon-white"></i> View Users</h4></a></li>
                                <?php 
								if ($_SESSION['rank'] < 3 ) : 
								?>
                                <li><a href="./edit_notifications.php"><h4><i class="icon-envelope icon-white"></i> Manage Notifications</h4></a></li>
                                <?php
								endif;
								?>
							</ul>
						</li>
                        <?php
							endif;
						?>
                        <?php 
							if ($_SESSION['rank'] == 6) : 
						?>
                        <li class="dropdown">
							<a href="./request.php"><h4><i class="icon-edit icon-white"></i>  Request</h4></a>
						</li>
                        <?php
							endif;
						?>
		            </ul><!--/.nav -->
			</div><!-- /.container-fluid -->
		</div><!-- /.navbar-inner -->
	</nav><!-- /.navbar navbar-inverse -->
</div><!-- /.navbar navbar-fixed-top -->
<?php
global $inboxClass;
$inbox_posts = $inboxClass->find_notifications_for_last_3_weeks();
$inbox_posts_array = $inbox_posts->fetch_array();
?>
<script language="javascript">
    jQuery(document).ready(function() {
      jQuery("div.timeago").timeago();
      jQuery("span.timeago").timeago();
    });
</script>
<nav id="offCanvasmenu" class="navmenu navmenu-inverse navmenu-fixed-left offcanvas" role="navigation">
	<h2 class="center-text"><a data-toggle="offcanvas" data-target="#offCanvasmenu" data-canvas="body" style="color: #999999;"><i class="icon-dashboard icon-white"></i> My Dashboard</a></h2>
	<ul class="nav navmenu-nav">
		<li><a href="./my_homepage.php?user_id=<?php echo $_SESSION['user_id']; ?>"><h4><i class="icon-home icon-white"></i> Homepage</h4></a></li>
		<li><a href="./my_machines.php?user_id=<?php echo $_SESSION['user_id']; ?>"><h4><i class="icon-cogs icon-white"></i> My Machines</h4></a></li>
        <li><a href="./my_inbox.php?user_id=<?php echo $_SESSION['user_id']; ?>"><h4><i class="icon-inbox icon-white"></i> My Inbox</h4></a></li>
	</ul>
	<div>
    	<h3 class="center-text"><a class="btn btn-primary btn-block" href="#inbox_send" data-toggle="modal"><i class="custom-paper-plane"></i> Message</a></h3>
	</div>
	<ul class="timeline">
		<?php $inboxClass->formatThread($inbox_posts); ?>
    </ul>
</nav>
<script type="text/javascript">
idleTime = 0;
$(document).ready(function () {
    //Increment the idle time counter every minute.
    var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

    //Zero the idle timer on mouse movement.
    $(this).mousemove(function (e) {
        idleTime = 0;
    });
    $(this).keypress(function (e) {
        idleTime = 0;
    });
});

function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > 4) { // 5 minutes
        window.location.reload();
    }
}
</script>  
<body onLoad="updateClock(); setInterval('updateClock()', 1000 ); getTypes()" style='padding-top:0px'>
<!-- Modal -->
	<div class="modal fade" id="inbox_send" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
	                        <input class="btn btn-lg btn-primary btn-block btn-success" id="inbox_submit" type="submit" name="inbox_submit" placeholder="Required" value="Send Message">
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