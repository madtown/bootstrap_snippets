<?php require_once('../../includes/initialize.php'); ?>
<div id="my_notifications"></div>
<script>
	function updateNotifications(){
		$('#my_notifications').load('notifications.php').slideDown('slow');
	}
	setInterval("updateNotifications()", 5000);
</script>
<hr class="featurette-divider">
	<footer class="footer" data-spy="affix-bottom">
		<div class="container">
			<table class="col-md-12">  	
				<tr>
					<td class="col-md-4">
						<h3>
							<div class="center-text"><i class="icon-time"></i> <span id="clock">&nbsp;</span></div>
						</h3>
					</td>
					<td class="col-md-4">
						<h5><div class="center-text"><p class="text-muted">&copy; <?php echo date("Y", time()); ?> Fabrication Technologies Incorporated, All Rights Reserved.</p></div></h5>
					</td>
					<td class="col-md-4 push-right">
						<div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,es,pt', multilanguagePage: true}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
					</td>
				</tr>
			</table>
		</div>
		<script>
		$('body').on('touchstart.dropdown', '.dropdown-menu', function (e) {
			e.stopPropagation();
		})
		</script>
	</footer>
</hr>
</body>
</html>
<?php if(isset($database)) { $database->close_connection(); } 
//THIS SHEET MUST BE EDITED UPON TRANSFER TO NEW FILESYSTEM
?>