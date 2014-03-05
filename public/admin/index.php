<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<script type='text/javascript'>

	$(document).ready(function() {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			selectable: false,
			eventSources: [
				{ events:<?php include('calendar_events.php'); ?>},
			]
	});
		
	});

	</script>
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">
        <div class="center-text"><h2><i class="icon-warning-sign"></i> Pardon My Dust</h2></div>
        <div class="center-text"><p class="text-danger"><?php echo output_message($message); ?></p></div>
        <div>
        <div id='loading' style='display:none'>Loading...</div>
        <div id='calendar' style='margin:3em 0;font-size:13px'></div>
        </div>
 	</div>
</div>
<?php include_layout_template('admin_footer.php'); ?>