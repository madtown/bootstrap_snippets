<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<script language="javascript"> 
setTimeout("self.close();",10000) 
</script>
<?php
redirect_to("zxing://scan/");
?>