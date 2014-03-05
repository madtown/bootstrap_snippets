<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
elseif ($_SESSION['rank'] > 4 ) { 
	$_SESSION['message'] = "You are not authorized to perform work.";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
?>
<?php
//Create the machine
if(isset($_POST['work_note_submit']) or isset($_POST['notify_submit']) or isset($_POST['part_submit']) or isset($_POST['external_submit']) or isset($_POST['submit_status'])) {
	if ($work = new Work()) {
		$work->newWork();
		//unset some stuff just in case
		unset($_FILES["file"]["name"]);
		unset($_SESSION['message']);
		unset($_POST["work_note_text"]);
		unset($_POST["work_note_procedure"]);
		unset($_POST["work_note_backdate"]);
		unset($_POST["work_note_submit"]);
		unset($_POST["notify_user"]);
		unset($_POST["notify_text"]);
		unset($_POST["notify_submit"]);
		unset($_POST["part_action"]);
		unset($_POST["part_id"]);
		unset($_POST["part_vendor"]);
		unset($_POST["part_comment"]);
		unset($_POST["part_backdate"]);
		unset($_POST["part_submit"]);
		unset($_POST["external_vendor"]);
		unset($_POST["external_comment"]);
		unset($_POST["external_doc_name"]);
		unset($_POST["external_backdate"]);
		unset($_POST["external_submit"]);
		if (isset($_POST["status"]) && $_POST["status"] = "1") {
			redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
		}
		unset($_POST["status"]);
		unset($_POST["status_summary"]);
		unset($_POST["status_cause"]);
		unset($_POST["cause_radio"]);
		unset($_POST["post_backdate"]);
		unset($_POST["submit_status"]);
		redirect_to("work.php?request_id=".$_POST['request_id']);
	}
} else {
	unset($_FILES["file"]["name"]);
	unset($_SESSION['message']);
	unset($_POST["work_note_text"]);
	unset($_POST["work_note_procedure"]);
	unset($_POST["work_note_backdate"]);
	unset($_POST["work_note_submit"]);
	unset($_POST["notify_user"]);
	unset($_POST["notify_text"]);
	unset($_POST["notify_submit"]);
	unset($_POST["part_action"]);
	unset($_POST["part_id"]);
	unset($_POST["part_vendor"]);
	unset($_POST["part_comment"]);
	unset($_POST["part_backdate"]);
	unset($_POST["part_submit"]);
	unset($_POST["external_vendor"]);
	unset($_POST["external_comment"]);
	unset($_POST["external_doc_name"]);
	unset($_POST["external_backdate"]);
	unset($_POST["external_submit"]);
	unset($_POST["status"]);
	unset($_POST["status_summary"]);
	unset($_POST["status_cause"]);
	unset($_POST["cause_radio"]);
	unset($_POST["post_backdate"]);
	unset($_POST["submit_status"]);
}
global $requestClass;
$requestObject = $requestClass->find_by_id($_GET['request_id']);
$request = $requestObject->fetch_array();
$most_recent_post = $workClass->find_most_recent_post($request['request_id']);
$most_recent_post_array = $most_recent_post->fetch_array();
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container">
    <div class="row">
        <div class="col-sm-10">
            <div class="center-text">
                <h1><strong><i class="icon-wrench"></i> #<?php echo $request['request_id']; ?></strong> <?php echo $request['short']; ?></h1>
                <h5><p class="lead text-danger"><?php if (isset($_SESSION['message'])) {echo $_SESSION['message']; unset($_SESSION['message']);} ?></p></h5>
                <h5>Requested <span class="timeago" title="<?php echo $request['request_timestamp']; ?>"></span></h5>
            </div>
        </div><a class="btn btn-info pull-right" href="my_homepage.php?user_id=<?php echo $_SESSION['user_id']; ?>"><h4><i class="icon-home"></i> My Homepage</h4></a>
    </div>
</div>
<script language="javascript">
	jQuery(document).ready(function() {
	  jQuery("div.timeago").timeago();
	  jQuery("span.timeago").timeago();
	});
</script>
<div class="container">
    <div class="row">
        <div class="col-sm-5"><!-- Input area ------------------------------------------->
            <!-- Nav tabs ---------------------------------------------------------------->
            <ul class="nav nav-tabs">
              <li class="active"><a href="#note" data-toggle="tab"><i class="icon-home"></i> In-house</a></li>
              <li><a href="#notify" data-toggle="tab"><i class="icon-envelope"></i> Notify</a></li>
              <li><a href="#parts" data-toggle="tab"><i class="icon-cogs"></i> Parts</a></li>
              <li><a href="#external" data-toggle="tab"><i class="icon-external-link"></i> External</a></li>
              <li><a href="#status" data-toggle="tab"><i class="icon-check"></i> Status</a></li>
              <li><a href="#qrcode" data-toggle="tab"><i class="icon-qrcode"></i> QR</a></li>
            </ul> 
            <!-- Tab panes ------------------------------------------------------------------------------>
            <div class="tab-content col-sm-11 col-sm-offset-1">
                <div class="tab-pane fade in active" id="note"><!-- Tab Note -------------------------------->
                    <div class="center-text">
                    </div>
                    <form class="form-horizontal" action="work.php?request_id=<?php echo htmlentities($request['request_id'])?>" method="post" role="form" enctype="multipart/form-data">		
                        <div class="form-group">
                            <label for="work_note_text">Text</label>
                            <textarea class="form-control" rows="6" type="text" name="work_note_text" id="work_note_text" maxlength="1000" class="input-block-level" value="" placeholder="Write notes about the work being done, make a reminder for next time, methods used, or really anything noteworthy..."></textarea>
                        </div>
                        <script>
                        $('textarea#work_note_text').maxlength({
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
                        <div class="form-group">
                            <label for="work_note_procedure">Procedure Performed</label>
                                <select class="form-control" name="work_note_procedure" id="work_note_procedure"> 
                                    <?php include('../scripts/procedureSelect.php');?>
                                </select>
                            <script>
                                function format(work_note_procedure) {
                                    var originalOption = work_note_procedure.element;
                                    return "<img src='" + $(originalOption).data('foo') + "' alt='' height='17'/>" + "" + work_note_procedure.text + "";
                                }
                                $("#work_note_procedure").select2({
                                    placeholder: "Select Procedure",
                                    allowClear: true,
                                    formatResult: format,
                                    formatSelection: format,
                                    escapeMarkup: function(m) { return m; }
                                });
                            </script>
                        </div>
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
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="btn btn-inverse btn-block">
                                <input type="checkbox" data-related-item="work_note_backdate"> <i class="custom-back-in-time"></i> Backdate
                            </label>
                        </div>
                        <div id="work_note_backdate">
                            <div class="form-group">
                                <label class="control-label">Backdate</label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' data-format="YYYY-MM-DD HH:mm:ss" placeholder="Click Here to select date--->" id="work_note_backdate" name="work_note_backdate" class="form-control" />
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
                                $('#datetimepicker1').datetimepicker({
                                    autoclose: true,
                                    endDate: today,	// set a maximum date
                                });
                            });
                            </script>
                        </div>
                        </br>
                        <div class="input-group">
                            <input class="form-control" type="hidden" id="request_id" name="request_id" autocomplete="off" spellcheck="false" name="part_id" value="<?php echo htmlentities($_GET['request_id']); ?>" required/>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-lg btn-block btn-success" type="submit" name="work_note_submit" id="work_note_submit" placeholder="Required" value="Post Note">
                        </div>
                    </form>
                </div><!-- End Tab Note ------------------------------------------------------------>
                <div class="tab-pane fade" id="notify"><!-- Tab Notify -------------------------------------->
                    <div class="center-text">
                        <h4><p class="text-info">Invite some one to join the thread.</p></h4>
                    </div>
                    <form class="form-horizontal" action="work.php?request_id=<?php echo htmlentities($request['request_id'])?>" method="post" role="form" enctype="multipart/form-data">		
                        <div class="form-group">
                            <label for="notify_user">Users</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-user"></i></span>
                                <select class="form-control" name="notify_user" id="notify_user"> 
                                    <?php 
                                        global $userClass;
                                        $users = $userClass->userSelect();
                                    ?>
                                </select>
                                <script>
                                $("#notify_user").select2({
                                    placeholder: "Select Recipient",
                                    allowClear: true
                                });
                                </script>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="notify_type">Send Email</label>
                            <div class="input-group">
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="notify_type" id="notify_type" value="nothing" checked>
                                    <i class="custom-paper-plane"></i> Notification Only
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="notify_type" id="notify_type" value="1">
                                    <i class="icon-envelope"></i> Email and <i class="custom-paper-plane"></i> Notification
                                  </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="notify_text">Notification Text</label>
                            <textarea class="form-control" rows="4" type="text" id="notify_text" name="notify_text" maxlength="256" class="input-block-level" value="" placeholder="Hey, I think you should be aware that...."></textarea>
                            <script>
                            $('textarea#notify_text').maxlength({
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
                        <div class="input-group">
                            <input class="form-control" type="hidden" id="request_id" name="request_id" autocomplete="off" spellcheck="false" name="part_id" value="<?php echo htmlentities($_GET['request_id']); ?>" required/>
                        </div>
                        <div class="form-group">
                        <input class="btn btn-lg btn-block btn-success" type="submit" name="notify_submit" id="notify_submit" placeholder="Required" value="Post Notification">
                        </div>
                    </form>
                </div><!-- End Tab Notify -------------------------------------------------------------------->
                <div class="tab-pane fade" id="parts"><!-- Tab Parts ---------------------------------------------->
                    <div class="center-text">
                        <h4><p class="text-info">Make note of parts used or ordered.</p></h4>
                    </div>
                    <form class="form-horizontal" action="work.php?request_id=<?php echo htmlentities($request['request_id'])?>" method="post" role="form" enctype="multipart/form-data">		
                        <div class="form-group">
                            <label for="part_action">Action</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-list"></i></span>
                                <select class="form-control" name="part_action" id="part_action" placeholder="Select Action" required>                     	<option value="">Select Action</option>
                                    <option value="0">I used this part from our inventory.</option>
                                    <option value="1">I think we need this part.</option>
                                    <option value="2">I request this part be ordered.</option>
                                    <option value="3">I ordered this part.</option>
                                </select>
                            </div>
                        </div>
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
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="part_vendor">Vendor</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-truck"></i></span>
                                <input class="form-control" type="text" id="part_vendor" name="part_vendor" autocomplete="off" spellcheck="false" name="part_vendor" placeholder="Amada, McMaster-Carr,..." maxlength="50" class="input-block-level" value=""/>
                                <script>
                                $('input#part_vendor').maxlength({
                                    alwaysShow: false,
                                    threshold: 25,
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
										$('#part_vendor').typeahead([
										{
										name: 'part_vendor',
										remote: '../scripts/typeahead_request.php?part_vendor=%QUERY',
										valueKey: "part_vendor",
										template: '<p>{{part_vendor}}</p>',
										limit: '10',
										engine: Hogan
										}]);
									});
								</script>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="part_id">Part ID</label>
                            <div class="input-group">
                                <span class="input-group-addon">#</span>
                                <input class="form-control" type="text" id="part_id" name="part_id" autocomplete="off" spellcheck="false" name="part_id" placeholder="Part No., SKU, UPC, ISO...etc" maxlength="15" class="input-block-level" value="" required/>
                                <script>
                                $('input#part_id').maxlength({
                                    alwaysShow: false,
                                    threshold: 10,
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
										$('#part_id').typeahead([
										{
										name: 'part_id',
										remote: '../scripts/typeahead_request.php?part_id=%QUERY',
										valueKey: "part_id",
										template: '<p>{{part_id}}</p>',
										limit: '10',
										engine: Hogan
										}]);
									});
								</script>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="part_comment">Comment</label>
                            <textarea class="form-control" rows="4" type="text" name="part_comment" id="part_comment" maxlength="500" class="input-block-level" value="" placeholder="Write comments about the part used. Condition, special features, notes, etc..."></textarea>
                            <script>
                            $('textarea#part_comment').maxlength({
                                alwaysShow: false,
                                threshold: 250,
                                warningClass: "label label-success",
                                limitReachedClass: "label label-danger",
                                placement: 'top',
                                preText: 'Used ',
                                separator: ' of ',
                                postText: ' chars.'
                            });
                            </script>
                        </div>
                        <div class="input-group">
                            <input class="form-control" type="hidden" id="request_id" name="request_id" autocomplete="off" spellcheck="false" name="part_id" value="<?php echo htmlentities($_GET['request_id']); ?>" required/>
                        </div>
                        <div class="form-group">
                            <label class="btn btn-inverse btn-block">
                                <input type="checkbox" data-related-item="part_backdate"> <i class="custom-back-in-time"></i> Backdate
                            </label>
                        </div>
                        <div id="part_backdate">
                            <div class="form-group">
                                <label class="control-label">Backdate</label>
                                <div class='input-group date' id='datetimepicker2'>
                                    <input id="part_backdate" name="part_backdate" type='text' data-format="YYYY-MM-DD HH:mm:ss" placeholder="Click Here to select date--->" class="form-control" />
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
                                        $('#datetimepicker2').datetimepicker({
                                        endDate: today,	// set a maximum date
                                    });
                                });
                            </script>
                        </div>
                        </br>
                        <div class="form-group">
                        <input class="btn btn-lg btn-block btn-success" type="submit" name="part_submit" id="part_submit" placeholder="Required" value="Post Part">
                        </div>
                    </form>
                </div><!-- End Tab Parts--------------------------------------------------------->
                <div class="tab-pane fade" id="external"><!-- Tab External ------------------------------>
                    <div class="center-text">
                        <h4><p class="text-info">Add info about work done externally.</p></h4>
                    </div>
                    <form class="form-horizontal" action="work.php?request_id=<?php echo htmlentities($request['request_id'])?>" method="post" role="form" enctype="multipart/form-data">		
                        <div class="form-group">
                            <label for="external_vendor">Vendor</label>
                            <input class="form-control" type="text" id="external_vendor" autocomplete="off" spellcheck="false" name="external_vendor" placeholder="Amada, McMaster-Carr,..." maxlength="50" class="input-block-level" value="" required/>
                            <script>
                            $('input#external_vendor').maxlength({
                                alwaysShow: false,
                                threshold: 25,
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
                                    $('#external_vendor').typeahead([
                                    {
                                    name: 'external_vendor',
                                    remote: '../scripts/typeahead_request.php?external_vendor=%QUERY',
                                    valueKey: "external_vendor",
                                    template: '<p>{{external_vendor}}</p>',
                                    limit: '10',
                                    engine: Hogan
                                    }]);
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <label for="external_comment">Comment</label>
                            <textarea class="form-control" rows="4" type="text" name="external_comment" id="external_comment" maxlength="500" class="input-block-level" value="" placeholder="Write comments about the work performed or the service used. Any additional details that are not in the invoice or documents uploaded....."></textarea>
                            <script>
                            $('textarea#external_comment').maxlength({
                                alwaysShow: false,
                                threshold: 250,
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
                            <label for="external_doc_name">Document Name</label>
                            <input class="form-control" type="text" id="external_doc_name" autocomplete="off" spellcheck="false" name="external_doc_name" placeholder="Service Report, Invoice, ...etc" maxlength="15" class="input-block-level" value=""/>
                            <script>
                            $('input#external_doc_name').maxlength({
                                alwaysShow: false,
                                threshold: 10,
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
                                    $('#external_doc_name').typeahead([
                                    {
                                    name: 'external_doc_name',
                                    remote: '../scripts/typeahead_request.php?external_doc_name=%QUERY',
                                    valueKey: "external_doc_name",
                                    template: '<p>{{external_doc_name}}</p>',
                                    limit: '10',
                                    engine: Hogan
                                    }]);
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <label for="file">Document</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-file-text"></i></span>
                                <input class="form-control" type="file" name="file" id="file" class="input-block-level" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="btn btn-inverse btn-block">
                                <input type="checkbox" data-related-item="external_backdate"> <i class="custom-back-in-time"></i> Backdate
                            </label>
                        </div>
                        <div id="external_backdate">
                            <div class="form-group">
                                <label class="control-label">Backdate</label>
                                <div class='input-group date' id='datetimepicker3'>
                                    <input type='text' data-format="YYYY-MM-DD HH:mm:ss" placeholder="Click Here to select date--->" class="form-control" id="external_backdate" name="external_backdate" />
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
                                    $('#datetimepicker3').datetimepicker({
                                    endDate: today,	// set a maximum date
                                });
                            });
                            </script>
                        </div>
                        </br>
                        <div class="input-group">
                            <input class="form-control" type="hidden" id="request_id" name="request_id" autocomplete="off" spellcheck="false" name="part_id" value="<?php echo htmlentities($_GET['request_id']); ?>" required/>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-lg btn-block btn-success" type="submit" name="external_submit" id="external_submit" placeholder="Required" value="Post External Work">
                        </div>
                    </form>
                </div><!-- End Tab External--------------------------------------------------------->
                <div class="tab-pane fade" id="status"><!-- Tab Status ------------------------------>
                    <div class="center-text">
                        <h4><p class="text-info">Update this request's status.</p></h4>
                    </div>
                    <form class="form-horizontal" action="work.php?request_id=<?php echo htmlentities($request['request_id'])?>" method="post" role="form" enctype="multipart/form-data">		
                        <div class="form-group">
                            <label for="status">Status</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-check"></i></span>
                                <select class="form-control" onchange="showDiv(this)" name="status" id="status"> 
                                    <option value="0">Unresponded</option>
                                    <option value="1">Resolved</option>
                                    <option value="2">Working</option>
                                    <option value="3">Pending Labor</option>
                                    <option value="4">Pending Parts</option>
                                    <option value="5">Pending Outside Service</option>
                                    <option value="6">Pending Outside Quote</option>
                                    <option value="7">Pending Outside Technician</option>
                                </select>
                                <script>
                                    function showDiv(elem){
                                        if(elem.value == 1) {
                                            document.getElementById('completion_form').style.display = "block";
                                        } else {
                                            document.getElementById('completion_form').style.display = "none";
                                        }
                                    }                        
                                </script>
                            </div>
                            <span class="help-block"><p class="text-danger">When setting status as resolved, fill-in the form as completely as possible.</p></span>
                        </div>
                        <!-- Start Completion Form--------------------------------------------->
                        <div id="completion_form" style="display: none;">
                            <div class="form-group">
                                <label for="status_summary">Work Summary</label>
                                <textarea class="form-control" rows="4" type="text" name="status_summary" id="status_summary" maxlength="1000" class="input-block-level" value="" placeholder="Summarize work completed...and include any additional notes."></textarea>
                                <script>
                                $('textarea#status_summary').maxlength({
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
                            </div>
                            <div class="form-group">
                                <label for="status_cause">Suspected Cause</label>
                                <textarea class="form-control" rows="2" type="text" id="status_cause" autocomplete="off" spellcheck="false" name="status_cause" maxlength="256" class="input-block-level" value="" placeholder=""></textarea>
                                <script>
                                $('textarea#status_cause').maxlength({
                                    alwaysShow: false,
                                    threshold: 128,
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
                                        $('#status_cause').typeahead([
                                        {
                                        name: 'status_cause',
                                        remote: '../scripts/typeahead_request.php?status_cause=%QUERY',
                                        valueKey: "status_cause",
                                        template: '<p>{{status_cause}}</p>',
                                        limit: '10',
                                        engine: Hogan
                                        }]);
                                    });
                                </script>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="checkbox center-text">
                                        <label>
                                            <input type="radio" name="cause_radio" id="1" value="1"><p class="lead text-success"><small>Unpreventable</small></p>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="checkbox center-text">
                                        <label>
                                            <input type="radio" name="cause_radio" id="2" value="2"><p class="lead text-warning"><small>Preventable</small></p>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="checkbox center-text">
                                        <label>
                                            <input type="radio" name="cause_radio" id="3" value="3"><p class="lead text-danger"><small>Corrective Action</small></p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="btn btn-inverse btn-block">
                                    <input type="checkbox" name="cause_backdate" id="cause_backdate" data-related-item="backdate4"> <i class="custom-back-in-time"></i> Backdate
                                </label>
                            </div>
                            <div id="backdate4">
                                <div class="form-group">
                                    <label class="control-label">Backdate</label>
                                    <div class='input-group date' id='datetimepicker4'>
                                        <input type='text' id="post_backdate" name="post_backdate" placeholder="Click Here to select date--->" data-format="YYYY-MM-DD HH:mm:ss" class="form-control" />
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
                                        $('#datetimepicker4').datetimepicker({
                                    endDate: today,	// set a maximum date
                                    });
                                });
                                </script>
                            </div>
                        </div><!-- End Completion Form--------------------------------------------->
                        </br>
                        <div class="input-group">
                            <input class="form-control" type="hidden" id="request_id" name="request_id" autocomplete="off" spellcheck="false" name="part_id" value="<?php echo htmlentities($_GET['request_id']); ?>" required/>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-lg btn-primary btn-block btn-success" id="submit_status" type="submit" name="submit_status" placeholder="Required" value="Post Status">
                        </div>
                    </form>
                </div><!-- End Tab Status--------------------------------------------------------->
                <div class="tab-pane fade" id="qrcode"><!-- Tab Status ------------------------------>
                    <div class="center-text">
                        <h4><p class="text-info">Scan to get to this work page.</p></h4>
                    </div>
					<img style='display: block;margin-left: auto;margin-right: auto;' src="../admin/qr_code.php?request_id=<?php echo htmlentities($request['request_id']);?>" class="img-responsive" alt="There was a problem dude."/>                   
                </div><!-- End Tab QR CODE--------------------------------------------------------->
            </div><!-- End Tab Content --------------------------------------------------------->
        </div><!-- End Tab panes ------------------------------------------------------------------------------>
        <div class="col-sm-7">
            <ul class="timeline">
            <div class="center-text"><h2 style="padding:0;"><i class="icon-comments"></i> Job Timeline</h2></div>
                <ul class="timeline" id="thread">
                </ul>
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
                        $('#thread').load(url).slideDown('slow',hideLoader());
                    }
                    setInterval("updateThread()", 5000);
                </script>
         	</ul>
                <div class="panel panel-danger" ><!--start-request-panel------------------------------->
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="icon-warning-sign"></i> Request</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-xs-12">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <strong>Subject</strong>
                                </div>
                                <div class="col-xs-6">
                                        <?php 
                                        if(isset($request['serial_num']) && $request['serial_num'] != "") {
                                            global $machineClass;
                                            $machine = $machineClass->find_by_serial_num($request['serial_num']);
											if (is_object($machine)) {
                                            	echo $machine->machine_num;
											}
                                        } else { 
											if(isset($request['subject']) && $request['subject'] != "") {
                                            	echo $request['subject'];
											}
                                        }
                                        ?>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <strong>Details</strong>
                                </div>
                                <div class="col-xs-6">
                                        <?php 
                                        if(isset($request['details']) && $request['details'] != "") {
                                            echo $request['details']; 
                                        } else {
                                            echo "There are no details for this request.";
                                        }
                                        ?>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <strong>Requested</strong></br><strong>Turnaround</strong>
                                </div>
                                <div class="col-xs-6">
                                    <?php 
                                    if($request['turnaround_req'] != "") {
                                        $total_time = $request['turnaround_req'];
                                        if ($total_time < 1) {
                                            echo "Open Turnaround";
                                        } else {
                                            unset($weeks);
                                            unset($days);
                                            unset($hours);
                                            unset($minutes);
                                            if ($total_time > 604800) {
                                                $weeks = floor($total_time / (7 * 60 * 60 * 24));
                                                $total_time -= $weeks * (7 * 60 * 60 * 24);
                                                echo "{$weeks} Weeks ";
                                            }
                                            if ($total_time > 86400) {
                                                $days = floor($total_time / (60 * 60 * 24));
                                                $total_time -= $days * (60 * 60 * 24);
                                                echo "{$days} Days ";
                                            }
                                            if ($total_time > 3600) {
                                                $hours = floor($total_time / (60 * 60));
                                                $total_time -= $hours * (60 * 60);
                                                echo "{$hours}h ";
                                            }
                                            if ($total_time > 60) {
                                                $minutes = floor($total_time / 60);
                                                $total_time -= $minutes * 60;
                                                echo "{$minutes}m";
                                            }
                                            unset($weeks);
                                            unset($days);
                                            unset($hours);
                                            unset($minutes);
                                        } 
                                        unset($total_time);
                                    } else {
                                        echo "There was no turnaround request time.";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <strong>Emergency</strong>
                                </div>
                                <div class="col-xs-6">
                                    <?php 
                                    switch ($request['emergency']) {
                                        case 0:
                                            ?><i class="icon-thumbs-up"></i> <?php
                                            echo "Non-emergency";
                                            break;
                                        case 1:
                                            ?><i class="custom-lifebuoy"></i> <?php
                                            echo "Safety Hazard";
                                            break;
                                        case 2:
                                            ?><i class="icon-money"></i> <?php
                                            echo "Production";
                                            break;
                                        case 3:
                                            ?><i class="icon-time"></i> <?php
                                            echo "Time-Sensitive";
                                            break;
                                        default:
                                            echo "This was not marked as an emergency.";
                                            break;
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <strong>Severity</strong>
                                </div>
                                <div class="col-xs-6">
                                    <h2>
                                        <?php 
                                        if($request['severity'] != "") {
                                            echo $request['severity']; 
                                        } else {
                                            echo "There was no severity indicated.";
                                        }
                                        ?>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <?php if(isset($request['doc_URL']) && $request['doc_URL'] != ""): ?>
                        <a class="pull-left" href="<?php echo htmlentities($request['doc_URL']); ?>">
                            <img class="img-responsive img-thumbnail" src="<?php echo htmlentities($request['doc_URL']); ?>" alt="../../public/stock_images/navbar_icon.gif">
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="panel-footer"><?php if (is_numeric($request['request_username'])) {
						global $userClass;
						$requestor = $userClass->find_user_by_id($request['request_username']);
						echo $requestor['username'];
					} else {
						echo $request['request_username']; 
					}?><div class="timeago pull-right" title="<?php if (isset($request['backdate']) && $request['backdate'] != "0000-00-00 00:00:00") {
                    echo htmlentities($request['backdate']);
                } else {
                    echo htmlentities($request['request_timestamp']);
                }
                ?>"></div>
                </div><!--end-request-panel------------------------------------------------------->
        </div>
    </div>
</div>
<?php include_layout_template('admin_footer.php'); ?>