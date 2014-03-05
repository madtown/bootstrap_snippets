<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
global $inboxClass;
$notifications = $inboxClass->find_all_my_unresponded_notifications();
?><div id="notifications" class='notifications bottom-left'></div><?php
foreach ($notifications as $notification) {
	if ($notification['type'] == 0) {
		?>
		<script>
		$('.bottom-left').notify({
			id: '<?php echo htmlentities($notification['id']); ?>',
			type: 'warning',//default success
			fadeOut: { enabled: false },
			message: { html: true, html: '<a class="btn btn-success pull-left" href="<?php echo htmlentities($notification['link']); ?>"><i class="icon-external-link"></i></a><p class="lead center-text">Assignment</p><?php echo htmlentities($notification['text']); ?> <p><small class="text-muted"><i class="icon-time"></i> <?php 
					global $userClass;
					$user = $userClass->find_user_by_id($notification['sender_id']); 
					echo htmlentities($user['username']); 
					?>-<span class="timeago" title="<?php 
			if(isset($notification['timestamp']) && $notification['timestamp'] != '0000-00-00 00:00:00') {
				echo $notification['timestamp'];
			}
			?>"></span></small></p>' },
			onClose: function () {$( "#<?php echo htmlentities($notification['id']); ?>" ).load( "dismiss_notification.php?id=<?php echo htmlentities($notification['id']); ?>" );}
		}).show();
		</script><?php
	}
	if ($notification['type'] == 1) {
		?>
		<script>
		$('.bottom-left').notify({
			id: '<?php echo htmlentities($notification['id']); ?>',
			type: 'info',//default success
			fadeOut: { enabled: false },
			message: { html: true, html: '<a class="btn btn-success pull-left" href="<?php echo htmlentities($notification['link']); ?>"><i class="icon-external-link"></i></a><p class="lead center-text"><i class="custom-paper-plane"></i> Notification</p><?php echo htmlentities($notification['text']); ?> <p><small class="text-muted"><i class="icon-time"></i> <?php 
					global $userClass;
					$user = $userClass->find_user_by_id($notification['sender_id']); 
					echo htmlentities($user['username']); 
					?>-<span class="timeago" title="<?php 
			if(isset($notification['timestamp']) && $notification['timestamp'] != '0000-00-00 00:00:00') {
				echo $notification['timestamp'];
			}
			?>"></span></small></p>' },
			onClose: function () {$( "#<?php echo htmlentities($notification['id']); ?>" ).load( "dismiss_notification.php?id=<?php echo htmlentities($notification['id']); ?>" );}
		}).show();
		</script><?php
	}
	if ($notification['type'] == 2) {
		?>
		<script>
		$('.bottom-left').notify({
			id: '<?php echo htmlentities($notification['id']); ?>',
			type: 'success',//default success
			fadeOut: { enabled: false },
			message: { html: true, html: '<a class="btn btn-success pull-left" href="<?php echo htmlentities($notification['link']); ?>"><i class="icon-external-link"></i></a><p class="lead center-text"><i class="custom-paper-plane"></i> Message</p><?php echo htmlentities($notification['text']); ?> <p><small class="text-muted"><i class="icon-time"></i> <?php 
					global $userClass;
					$user = $userClass->find_user_by_id($notification['sender_id']); 
					echo htmlentities($user['username']); 
					?>-<span class="timeago" title="<?php 
			if(isset($notification['timestamp']) && $notification['timestamp'] != '0000-00-00 00:00:00') {
				echo $notification['timestamp'];
			}
			?>"></span></small></p>' },
			onClose: function () {$( "#<?php echo htmlentities($notification['id']); ?>" ).load( "dismiss_notification.php?id=<?php echo htmlentities($notification['id']); ?>" );}
		}).show();
		</script><?php
	}
	if ($notification['type'] == 3) {
		?>
		<script>
		$('.bottom-left').notify({
			id: '<?php echo htmlentities($notification['id']); ?>',
			type: 'danger',//default success
			fadeOut: { enabled: false },
			message: { html: true, html: '<a class="btn btn-success pull-left" href="<?php echo htmlentities($notification['link']); ?>"><i class="icon-external-link"></i></a><p class="lead center-text"><i class="icon-warning-sign"></i> Daily Report</p><?php echo htmlentities($notification['text']); ?> <p><small class="text-muted"><i class="icon-time"></i> <?php 
					global $userClass;
					if (is_numeric($notification['sender_id'])) {
						$user = $userClass->find_user_by_id($notification['sender_id']); 
						echo htmlentities($user['username']); 
					} else {
						echo $notification['sender_id'];
					} 
					?>-<span class="timeago" title="<?php 
			if(isset($notification['timestamp']) && $notification['timestamp'] != '0000-00-00 00:00:00') {
				echo $notification['timestamp'];
			}
			?>"></span></small></p>' },
			onClose: function () {$( "#<?php echo htmlentities($notification['id']); ?>" ).load( "dismiss_notification.php?id=<?php echo htmlentities($notification['id']); ?>" );}
		}).show();
		</script><?php
	}
	if ($notification['type'] == 4) {
		?>
		<script>
		$('.bottom-left').notify({
			id: '<?php echo htmlentities($notification['id']); ?>',
			type: 'danger',//default success
			fadeOut: { enabled: false },
			message: { html: true, html: '<a class="btn btn-success pull-left" href="<?php echo htmlentities($notification['link']); ?>"><i class="icon-external-link"></i></a><p class="lead center-text"><i class="icon-list"></i> Weekly Report</p><?php echo htmlentities($notification['text']); ?> <p><small class="text-muted"><i class="icon-time"></i> <?php 
					global $userClass;
					if (is_numeric($notification['sender_id'])) {
						$user = $userClass->find_user_by_id($notification['sender_id']); 
						echo htmlentities($user['username']); 
					} else {
						echo $notification['sender_id'];
					} 
					?>-<span class="timeago" title="<?php 
			if(isset($notification['timestamp']) && $notification['timestamp'] != '0000-00-00 00:00:00') {
				echo $notification['timestamp'];
			}
			?>"></span></small></p>' },
			onClose: function () {$( "#<?php echo htmlentities($notification['id']); ?>" ).load( "dismiss_notification.php?id=<?php echo htmlentities($notification['id']); ?>" );}
		}).show();
		</script><?php
	}
	if ($notification['type'] == 5) {
		?>
		<script>
		$('.bottom-left').notify({
			id: '<?php echo htmlentities($notification['id']); ?>',
			type: 'danger',//default success
			fadeOut: { enabled: false },
			message: { html: true, html: '<a class="btn btn-success pull-left" href="<?php echo htmlentities($notification['link']); ?>"><i class="icon-external-link"></i></a><p class="lead center-text"><i class="icon-moon"></i> Monthly Report</p><?php echo htmlentities($notification['text']); ?> <p><small class="text-muted"><i class="icon-time"></i> <?php 
					global $userClass;
					if (is_numeric($notification['sender_id'])) {
						$user = $userClass->find_user_by_id($notification['sender_id']); 
						echo htmlentities($user['username']); 
					} else {
						echo $notification['sender_id'];
					} 
					?>-<span class="timeago" title="<?php 
			if(isset($notification['timestamp']) && $notification['timestamp'] != '0000-00-00 00:00:00') {
				echo $notification['timestamp'];
			}
			?>"></span></small></p>' },
			onClose: function () {$( "#<?php echo htmlentities($notification['id']); ?>" ).load( "dismiss_notification.php?id=<?php echo htmlentities($notification['id']); ?>" );}
		}).show();
		</script><?php
	}
	if ($notification['type'] == 6) {
		?>
		<script>
		$('.bottom-left').notify({
			id: '<?php echo htmlentities($notification['id']); ?>',
			type: 'danger',//default success
			fadeOut: { enabled: false },
			message: { html: true, html: '<a class="btn btn-success pull-left" href="<?php echo htmlentities($notification['link']); ?>"><i class="icon-external-link"></i></a><p class="lead center-text"><i class="icon-globe"></i> Bi-Annual Report</p><?php echo htmlentities($notification['text']); ?> <p><small class="text-muted"><i class="icon-time"></i> <?php 
					global $userClass;
					if (is_numeric($notification['sender_id'])) {
						$user = $userClass->find_user_by_id($notification['sender_id']); 
						echo htmlentities($user['username']); 
					} else {
						echo $notification['sender_id'];
					}
					?>-<span class="timeago" title="<?php 
			if(isset($notification['timestamp']) && $notification['timestamp'] != '0000-00-00 00:00:00') {
				echo $notification['timestamp'];
			}
			?>"></span></small></p>' },
			onClose: function () {$( "#<?php echo htmlentities($notification['id']); ?>" ).load( "dismiss_notification.php?id=<?php echo htmlentities($notification['id']); ?>" );}
		}).show();
		</script><?php
	}
}
?>
<script language="javascript">
	jQuery(document).ready(function() {
	  jQuery("div.timeago").timeago();
	  jQuery("span.timeago").timeago();
	});
</script>