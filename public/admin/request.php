<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 6) { 
	$_SESSION['message'] = "You are not authorized to request maintenance.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
?>
<?php
//Set default fields
if(!isset($_POST['submit'])) {
	$short 			= NULL;//required
	$file		 	= NULL;
	$type		 	= NULL;
	$machine_serial	= NULL;
	$object 		= NULL;
	$details 		= NULL;
	$minutes		= NULL;
	$hours 			= NULL;
	$days 			= NULL;
	$weeks			= NULL;
	$emergency 		= NULL;
	$severity 		= NULL;//required
	$backdate		= NULL;
}
$errors_array = array();
if(isset($_POST['submit'])) {
	if(isset($_POST['submit']) && ($_POST['short'] == NULL or !isset($_POST['short']))) {
		$short_message = "Please add a Short Title. </br>";
	} else {$short_message = "";}
	if(isset($_POST['submit']) && (!isset($_POST['severity']) or $_POST['severity'] == NULL)) {
		$severity_message = "Please select a Severity Level. </br>";
	} else {$severity_message = "";}
	$message = "Error:</br> ".$short_message."".$severity_message."</br>";
}
//Create the request
if(isset($_POST['submit']) && isset($_POST['short']) && ($_POST['short'] != "") && isset($_POST['severity']) && ($_POST['severity'] != "")) {
	$request = new Request();
	if ($request->newRequest()) {
		$message = "The request was sent.";
		redirect_to('queue.php');
	}
}
?>
<script language="javascript">
	function getSerials() {
		var type = document.getElementById("type").value;
		$(document).ready(function() {
    		$("#getSerials").load("../scripts/getSerials.php?type=" + type);
		});
	}
</script>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<script language="javascript">
	$(document).ready(function() {
		$('#short').typeahead([
		{
		name: 'short',
		remote: '../scripts/typeahead_request.php?short=%QUERY',
		valueKey: "short",
		template: '<p>{{short}}</p>',
		limit: '10',
		engine: Hogan
		}]);
	});
</script>
</br>
<div class="container col-md-12 col-md-offset-0">
<div class="col-md-12 col-md-offset-0">
    <div class="center-text"><h2><i class="icon-plus-sign"></i> Request Maintenance</h2></div>
    <h5 class="text-danger"><?php echo output_error($message); ?></h5>
    </br>
    <form class="form-vertical" action="request.php" method="post" role="form" enctype="multipart/form-data">
    	<div class="row">
        	<div class="col-sm-7 col-sm-offset-0">		
                <div class="form-group has-error">
                    <label for="short">Short Description</label>
                        <input type="text" class="form-control typeahead" id="short" autocomplete="off" spellcheck="false" name="short" placeholder="Required-Ex: Door missing on Punch" maxlength="100" required/>
                        <script>
							$('input#short').maxlength({
								alwaysShow: false,
								threshold: 75,
								warningClass: "label label-success",
								limitReachedClass: "label label-danger",
								placement: 'top',
								preText: 'Used ',
								separator: ' of ',
								postText: ' chars.'
							});
						</script>
                </div>
         	</div>
            <div class="col-sm-4 col-sm-offset-0">
                <div class="form-group">
                    <div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
                        <div class="center-text">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 150px;"></div>
                            <div>
                                <span class="btn btn-primary btn-block btn-file">
                                    <span class="fileinput-new"><i class="icon-camera"></i> Picture</span>
                                    <span class="fileinput-exists"><i class="icon-refresh"></i> Change</span>
                                    <input type="file" name="file" id="file">
                                </span>
                                <a href="#" class="btn btn-danger btn-block fileinput-exists" data-dismiss="fileinput"><i class="icon-trash"></i> Remove</a>
                            </div>
                            <span class="help-block text-primary">Max size: 5Mb.</span>
                        </div>
                    </div>
                </div>
        	</div>
      	</div>
        <hr class="featurette-divider">
        <div class="center-text">
            <h3><p class="lead"><strong>Subject of Request</strong></br><small>Complete A OR B</small></p></h3>
        </div>
        <div class="row">
            <div class="col-sm-5 col-sm-offset-0">
            	<div class="center-text">
                    <h4><p class="lead">Option A</br><small>Machine(s)</small></p></h4>
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <p id="getTypes" onchange="getSerials()"></p>
                </div>
                <div class="form-group">
                    <label for="machine_serial">Machine Number</label>
                    <p change="type" id="getSerials"></p>
                </div>
            </div>
            <div class="col-sm-6 col-sm-offset-1">
            	<div class="center-text">
                    <h4><p class="lead">Option B</br><small>Other</small></p></h4>
                </div>
                <div class="form-group">
                    <label for="subject">Subject Description</label>
                        <textarea class="form-control" rows="7" type="text" name="subject" id="subject" maxlength="100" class="input-block-level" value="<?php if (isset($comment)) {echo htmlentities($comment);} ?>" placeholder="Ex: Air hose SW corner"></textarea>
                        <script>
							$('textarea#subject').maxlength({
								alwaysShow: false,
								threshold: 500,
								warningClass: "label label-success",
								limitReachedClass: "label label-danger",
								placement: 'top',
								preText: 'Used ',
								separator: ' of ',
								postText: ' chars.'
							});
						</script>
						<script language="javascript">
                            $(document).ready(function() {
                                $('#subject').typeahead([
                                {
                                name: 'subject',
                                remote: '../scripts/typeahead_request.php?subject=%QUERY',
                                valueKey: "subject",
                                template: '<p>{{subject}}</p>',
                                limit: '10',
                                engine: Hogan
                                }]);
                            });
                        </script>
                </div>
            </div>
        </div>
        <hr class="featurette-divider">
        <div class="center-text">
            <h3><p class="lead"><strong>Details</strong></br><small>Recommended</small></p></h3>
        </div>
        <div class="form-group">
            <label class="sr-only" for="details">Details of Request</label>
                <textarea class="form-control" rows="5" type="text" name="details" id="details" maxlength="500" class="input-block-level" value="" placeholder="Write details about the work being requested. Any details that you provide will speed up the resolution time. Parts missing, exact locations, description of cause, what parts may need to be ordered,...etc."></textarea>
				<script>
				$('textarea#details').maxlength({
					alwaysShow: false,
					threshold: 400,
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
        <div class="center-text">
        	<h3><p class="lead"><strong>Requested Turnaround</strong></br><small>Optional</small></p></h3>
        </div>        
		<div class="row">
            <div class="col-sm-3 col-sm-offset-0">	
                <div class="form-group">
                    <label for="minutes">Minutes</label>
                    <select class="form-control" name="minutes" id="minutes">
                        <option value="0" selected>Open</option> 
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                        <option value="30">30</option>
                        <option value="35">35</option>
                        <option value="40">40</option>
                        <option value="45">45</option>
                        <option value="50">50</option>
                        <option value="55">55</option>
                    </select>
                </div>
          	</div>
          	<div class="col-sm-3 col-sm-offset-0">	
                <div class="form-group">
                    <label for="hours">Hour(s)</label>
                    <select class="form-control" name="hours" id="hours">
                        <option value="0" selected>Open</option> 
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                </div>
          	</div>
          	<div class="col-sm-3 col-sm-offset-0">	
                <div class="form-group">
                    <label for="days">Day(s)</label>
                    <select class="form-control" name="days" id="days">
                        <option value="0" selected>Open</option> 
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
                </div>
          	</div>
            <div class="col-sm-3 col-sm-offset-0">	
                <div class="form-group">
                    <label for="weeks">Week(s)</label>
                    <select class="form-control" name="weeks" id="weeks">
                        <option value="0" selected>Open</option> 
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
                </div>
          	</div>
      	</div>
        <hr class="featurette-divider">
        <div class="center-text">
            <h3><p class="lead"><strong>Emergency</strong></br><small>Optional</small></p></h3>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-success">
                	<input type="radio" name="emergency" id="0" value="0"><i class="icon-thumbs-up"></i> Non-emergency
                </label>
                <label class="btn btn-danger">
                	<input type="radio" name="emergency" id="1" value="1"><i class="custom-lifebuoy"></i> Safety Hazard
                </label>
                <label class="btn btn-warning">
                	<input type="radio" name="emergency" id="2" value="2"><i class="icon-money"></i> Production
                </label>
                <label class="btn btn-primary">
                	<input type="radio" name="emergency" id="3" value="3"><i class="icon-time"></i> Time-Sensitive
                </label>
            </div>
        </div>
        </br>
        <div class="center-text">
        	<h3><p class="lead"><strong>Severity</strong></br><small>Helps to gauge priority. It's also <strong class="text-danger">REQUIRED</strong>.</small></p></h3>
        	<p class="lead text-primary pull-left">Minor</p>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-primary">
                    <input type="radio" name="severity" id="1" value="1"><p style="font-size:35;">1</p>
                </label>
                <label class="btn btn-primary">
                    <input type="radio" name="severity" id="2" value="2"><p style="font-size:35;">2</p>
                </label>
                <label class="btn btn-primary">
                    <input type="radio" name="severity" id="3" value="3"><p style="font-size:35;">3</p>
                </label>
                <label class="btn btn-success">
                    <input type="radio" name="severity" id="4" value="4"><p style="font-size:35;">4</p>
                </label>
                <label class="btn btn-success">
                    <input type="radio" name="severity" id="5" value="5"><p style="font-size:35;">5</p>
                </label>
                <label class="btn btn-success">
                    <input type="radio" name="severity" id="6" value="6"><p style="font-size:35;">6</p>
                </label>
                <label class="btn btn-warning">
                    <input type="radio" name="severity" id="7" value="7"><p style="font-size:35;">7</p>
                </label>
                <label class="btn btn-warning">
                    <input type="radio" name="severity" id="8" value="8"><p style="font-size:35;">8</p>
                </label>
                <label class="btn btn-warning">
                    <input type="radio" name="severity" id="9" value="9"><p style="font-size:35;">9</p>
                </label>
                <label class="btn btn-danger">
                    <input type="radio" name="severity" id="10" value="10"><p style="font-size:35;">10</p>
                </label>
                <label class="btn btn-danger">
                    <input type="radio" name="severity" id="11" value="11"><p style="font-size:35;">11</p>
                </label>
                <label class="btn btn-danger">
                    <input type="radio" name="severity" id="12" value="12"><p style="font-size:35;">12</p>
                </label>
            </div>
            <p class="lead text-danger pull-right">Major</p>
        </div>
        </br>
        <div class="form-group">
                        <label class="btn btn-inverse">
                            <input type="checkbox" data-related-item="backdate"> <i class="custom-back-in-time"></i> Backdate
                        </label>
                    </div>
                    <div id="backdate">
                        <div class="form-group">
                            <label class="control-label">Backdate</label>
                            <div class='input-group date' id='datetimepicker'>
                                <input type='text'id="backdate" name="backdate" data-format="YYYY-MM-DD HH:mm:ss" class="form-control" placeholder="Click Here to select date--->"/>
                                <span class="input-group-addon">
                                    <span class="icon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <script type="text/javascript">
						$('input[type="checkbox"]').click(evaluate).each(evaluate);
								$(function () {
									var today = new Date();
									var dd = today.getDate();
									var mm = today.getMonth()+1; //January is 0!
									var yyyy = today.getFullYear();
									if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = mm+'/'+dd+'/'+yyyy;
									$('#datetimepicker').datetimepicker({
										endDate: today,	// set a maximum date
									});
							});
						</script>
                 	</div>
                    </br>
        <div class="form-group">
        	<input class="btn btn-lg btn-block btn-success" type="submit" name="submit" placeholder="Required" value="Submit Request">
      	</div>
	</form>
</div>
</div>
<?php include_layout_template('admin_footer.php'); ?>