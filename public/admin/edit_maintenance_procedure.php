<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 3 ) { 
	$_SESSION['message'] = "You are not authorized to edit maintenance procedures.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
if(isset($_POST['submit']) && $_POST['procedure_name'] != "") {
	if ($procedure = new Procedure()) {
		$procedure->newProcedure();
		$message = "The procedure was successfully created.";
		unset($_POST['submit']);
		unset ($_SESSION['message']);
		unset($_POST['procedure']);
		unset($_POST['type']);
		unset($_FILES["file"]["name"]);
		redirect_to('view_procedures.php');
	 }} else {
		$_SESSION['message'] = "Please select all fields.";
		}
?>

<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<script language="javascript">
	function getSerials() {
		var type = document.getElementById("type").value;
		$(document).ready(function() {
    		$("#getSerials").load("../scripts/getSerials.php?type=" + type);
});

}

</script>
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">
        <div class="center-text"><h2><i class="icon-plus-sign"></i> New Procedure</h2></div>
        <p class="text-error"><?php echo output_message($message); 
        unset($_SESSION['message']);
        ?></p>
        <form class="form-horizontal" action="edit_maintenance_procedure.php" method="post" enctype="multipart/form-data" role="form">
            <div class="form-group">
                <label for="procedure_name">Procedure Name:</br><p class="text-danger"><strong>Cannot be changed later and must be unique.</strong></p></label></br>
                <input class="form-control" type="text" name="procedure_name" id="procedure_name" maxlength="30" placeholder="Required-Example: Fly Wheel Replace AE2510NT " onchange="getTypes()" required>
                <script>
				$('input#procedure_name').maxlength({
					alwaysShow: false,
					threshold: 15,
					warningClass: "label label-success",
					limitReachedClass: "label label-danger",
					placement: 'top',
					preText: 'Used ',
					separator: ' of ',
					postText: ' chars.'
				});
				</script>
          	</div>
            <hr class="featurette-divider">
          	<div class="form-group" id="standard" style="display: block;">
            	<label for="type_maint">Select Standard Interval</br><small>Or create a custom interval below</small></label>
                <select class="form-control" name="type_maint" id="type_maint" onchange="showDiv(this)"> 
                    <option value="0">No Standard Interval</option>
                    <option value="36000">Daily</option>
                    <option value="770400">Weekly</option>
                    <option value="2593740">Monthly</option>
                    <option value="31524000">Annual</option>
                    <option value="1">Emergency (Unscheduled)</option>
                    <option value="2">Special (Unscheduled)</option>
                    <option value="3">Other (Unscheduled)</option>			
                </select>
                <script>
					function showDiv(elem){
						if(elem.value != 0) {
							document.getElementById('custom').style.display = "none";
						} else {
							document.getElementById('custom').style.display = "block";
						}
					}                        
				</script>
            </div>
            <div class="well" id="custom" style="display: block;">
            	<div class="center-text"><h3><i class="icon-time"></i> Custom Interval</br><small>(Each selection adds together)</small></h3></div>
                <div class="form-group">
                    <label for="hours">Hours</label>
                    <input class="form-control" type="text" name="hours" id="hours" onchange="showDiv2(this)" placeholder="(No Commas)">
                </div>
                <div class="form-group">
                    <label for="days">Days</label>
                    <select class="form-control" name="days" id="days" onchange="showDiv2(this)"> 
                        <option value="0">0 Days</option>
                        <option value="36000">1 Day</option>
                        <option value="158400">2 Days</option>
                        <option value="280800">3 Days</option>
                        <option value="403200">4 Days</option>
                        <option value="525600">5 Days</option>
                        <option value="648000">6 Days</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="weeks">Weeks</label>
                    <select class="form-control" id="weeks" name="weeks" onchange="showDiv2(this)"> 
                        <option value="0">0 Weeks</option>
                        <option value="770400">1 Week</option>
                        <option value="1627200">2 Weeks</option>
                        <option value="2484000">3 Weeks</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="months">Months</label>
                    <select class="form-control" name="months" id="months" onchange="showDiv2(this)"> 
                        <option value="0">0 Months</option>
                        <option value="2620000">1 Month</option>
                        <option value="5240000">2 Months</option>
                        <option value="7860000">3 Months</option>
                        <option value="10480000">4 Months</option>
                        <option value="13100000">5 Months</option>
                        <option value="15720000">6 Months</option>
                        <option value="18340000">7 Months</option>
                        <option value="20960000">8 Months</option>
                        <option value="23580000">9 Months</option>
                        <option value="26200000">10 Months</option>
                        <option value="28820000">11 Months</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="years">Years</label>
                    <select class="form-control" name="years" id="years" onchange="showDiv2(this)"> 
                        <option value="0">0 Years</option>
                        <option value="30786526">1 Year</option>
                        <option value="63032960">2 Years</option>
                        <option value="93899600">3 Years</option>
                        <option value="125429600">4 Years</option>
                        <option value="157029600">5 Years</option>
                        <option value="188529600">6 Years</option>
                        <option value="220129600">7 Years</option>
                        <option value="251729600">8 Years</option>
                        <option value="283229600">9 Years</option>
                        <option value="314829600">10 Years</option>
                    </select>
                </div>
                <script>
					function showDiv2(elem){
						if(elem.value != 0) {
							document.getElementById('standard').style.display = "none";
						} else {
							document.getElementById('standard').style.display = "block";
						}
					}                        
				</script>
            </div>
            <hr class="featurette-divider">
          	<div class="form-group">
                <label for="type">Type (required)</label>
                <p id="getTypes" onchange="getSerials()"></p>
            </div>
            <div class="form-group">
                <label for="machine_serial">Machine Number</label>
                <p change="type" mutliple="multiple" id="getSerials"></p>
            </div>
            <hr class="featurette-divider">
          	<div class="form-group">
                <div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
                    <div class="center-text">
                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;"></div>
                        <div>
                            <span class="btn btn-primary btn-block btn-file">
                                <span class="fileinput-new"><i class="icon-camera"></i> Procedure Image</span>
                                <span class="fileinput-exists"><i class="icon-refresh"></i> Change</span>
                                <input type="file" name="file" id="file">
                            </span>
                            <a href="#" class="btn btn-danger btn-block fileinput-exists" data-dismiss="fileinput"><i class="icon-trash"></i> Remove</a>
                        </div>
                    </div>
                </div>
            </div>
          	<div class="form-group">
            	<button class="btn btn-success btn-block" type="submit" name="submit" value="Submit">Create Procedure</button>
            </div>
        </form>
 	</div>
        <a class="btn btn-info" href="view_procedures.php"><i class="icon-chevron-left"></i> Return to View Procedures</a>
</div>
<?php include_layout_template('admin_footer.php'); ?>