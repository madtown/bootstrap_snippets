<?php require_once('../../includes/initialize.php'); ?>
<hr class="featurette-divider">
	<footer class="footer">
		<div class="container">
			<table class="col-md-12">  	
				<tr>
					<td class="col-md-4">
						<h2>
							<div class="center-text"><i class="icon-time"></i> <span id="clock">&nbsp;</span></div>
						</h2>
					</td>
					<td class="col-md-4">
						<h5><div class="center-text"><p class="text-muted">&copy; <?php echo date("Y", time()); ?> Fabrication Technologies Incorporated, All Rights Reserved.</p></div></h5>
					</td>
					<td class="col-md-4 push-right">
						<p class="text-info"><div id="google_translate_element"></div>
							<script type="text/javascript">
								function googleTranslateElementInit() {
								  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'es', layout: google.translate.TranslateElement.FloatPosition.BOTTOM_RIGHT}, 'google_translate_element');
								}
							</script>
							<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
						</p>
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