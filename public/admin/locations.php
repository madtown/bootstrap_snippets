<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); 
} elseif ($_SESSION['rank'] > 2) { 
	$_SESSION['message'] = "You are not authorized to use the dashboard";
	redirect_to("my_homepage.php?user_id=".$_SESSION['user_id']);
}
$buildings = $locationClass->find_all_buildings();
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">
    	<div class="center-text"><h1><i class="icon-compass"></i> Locations</h1></div>
		<div class="center-text"><p class="text-danger"><?php echo output_message($message); ?></p></div>
<!------------------------------------------------- Start Buildings ----------------------------------------------->
<?php 
	$machineClass = new Machine();
	foreach ($buildings as $building):
	$sections = $locationClass->find_building_sections($building['building_id']);
	unset($all);
	$collapse_id_modifier = "";
	$collapse_id_modifier = "b".$building['building_id'];
 	$all = $machineClass->find_building_machines($building['building_id']);
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">
        	<a data-toggle="collapse" data-parent="#b<?php echo htmlentities($building['building_id']); ?>" href="#b<?php echo htmlentities($building['building_id']); ?>"><h1><i class="icon-building"></i> <?php echo $building['building_name']; ?><span class="label label-info pull-right"><i class="icon-th-large"></i> <?php echo $sections->num_rows; ?></span></h1></a>
        </div>
    </div><!-- /.panel-heading -->
    <div id="b<?php echo htmlentities($building['building_id']); ?>" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
            	<div class="col-sm-6">
                	<p class="lead">
                    	<strong>Building Lead</strong><small> |</small>
						<?php 
							global $userClass; 
							$lead_user = $userClass->find_by_user_id($building['building_lead_user']); 
							echo $lead_user->username;
							unset($lead_user);
						?>
                	</p>
            	</div>
                <div class="col-sm-6">
                	<p class="lead">
                    	<strong>Maintenance Lead</strong><small> |</small>
						<?php 
                            global $userClass; 
                            $maint_user = $userClass->find_by_user_id($building['building_maint_user']); 
                            echo $maint_user->username;
                            unset($maint_user);
                        ?>
                	</p>
            	</div>
                <?php if($all->num_rows > 0): ?>
                    <div class="col-xs-12 center-text">
                        <p class="lead">
                            <h1><i class="icon-cogs"></i> <?php echo $building['building_name']; ?> Machines <i class="icon-cogs icon-rotate-180"></i><span class="label label-default pull-right"><?php echo $all->num_rows; ?></span></h1>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
      	</div><!-- /.panel-body -->
        <?php if($all->num_rows > 0): ?>
			<?php $machineClass->displayMachines($all, $collapse_id_modifier); ?>
        <?php endif; ?>
        <?php if($sections->num_rows > 0): ?>
      	<div>
            <h1 class="center-text">
                <i class="icon-th-large"></i> <?php echo $building['building_name']; ?> Sections <i class="icon-th-large icon-rotate-180"></i><span class="label label-info pull-right"><i class="icon-th-large"></i> <?php echo $sections->num_rows; ?></span>
            </h1>
        <!-------------------------------------------- Start Sections ------------------------------------->
        <?php 	foreach ($sections as $section):
				$section = $locationClass->find_section_by_id($section['section_id']);
				while ($section_assoc = $section->fetch_assoc()) {
					$cells = $locationClass->find_section_cells($section_assoc['section_id']);
					unset($all);
					$collapse_id_modifier = "";
					$collapse_id_modifier = "s".$section_assoc['section_id'];
					$all = $machineClass->find_section_machines($section_assoc['section_id']);
		?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <a data-toggle="collapse" data-parent="#s<?php echo htmlentities($section_assoc['section_id']); ?>" href="#s<?php echo htmlentities($section_assoc['section_id']); ?>"><h1><i class="icon-th-large"></i> <?php echo $section_assoc['section_name']; ?><span class="label label-default pull-right"><i class="icon-th"></i> <?php echo $cells->num_rows; ?></span></h1></a>
                </div>
            </div>
            <div id="s<?php echo htmlentities($section_assoc['section_id']); ?>" class="panel-collapse collapse">
                <div class="panel-body">
                	<div class="row">
                        <div class="col-sm-6">
                            <p class="lead">
                                <strong>Section Lead</strong><small> |</small>
                                <?php 
                                    global $userClass; 
                                    $lead_user = $userClass->find_by_user_id($section_assoc['section_lead_user']); 
                                    echo $lead_user->username;
                                    unset($lead_user);
                                ?>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p class="lead">
                                <strong>Maintenance Lead</strong><small> |</small>
                                <?php 
                                    global $userClass; 
                                    $maint_user = $userClass->find_by_user_id($section_assoc['section_maint_user']); 
                                    echo $maint_user->username;
                                    unset($maint_user);
                                ?>
                            </p>
                        </div>
                        <?php if($all->num_rows > 0): ?>
                        <div class="col-sm-12 center-text">
                            <p class="lead">
                                <h1><i class="icon-cogs"></i> <?php echo $section_assoc['section_name']; ?> Machines <i class="icon-cogs icon-rotate-180"></i><span class="label label-default pull-right"><i class="icon-th"></i> <?php echo $all->num_rows; ?></span></h1>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
             	<?php if($all->num_rows > 0): ?>
                	<?php $machineClass->displayMachines($all, $collapse_id_modifier); ?>
                <?php endif; ?>
                <?php if($cells->num_rows > 0): ?>
                    <div class="col-sm-12 center-text">
                        <p class="lead">
                            <h1><i class="icon-th"></i> <?php echo $section_assoc['section_name']; ?> Cells <i class="icon-th icon-rotate-180"></i><span class="label label-default pull-right"><i class="icon-th"></i> <?php echo $cells->num_rows; ?></span></h1>
                        </p>
                    </div>
                <?php endif; ?>
				<?php 	foreach ($cells as $cell):
                        $cell = $locationClass->find_cell_by_id($cell['cell_id']);
                        while ($cell_assoc = $cell->fetch_assoc()) {
							unset($all);
							$collapse_id_modifier = "";
							$collapse_id_modifier = "c".$cell_assoc['cell_id'];
							$all = $machineClass->find_cell_machines($cell_assoc['cell_id']);
                ?>
                </div><!-- /.panel-body-sections -->
                <!--------------------------------- Start Cells --------------------------------->
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	<div class="panel-title">
                        	<h2>
                                <a data-toggle="collapse" data-parent="#c<?php echo htmlentities($cell_assoc['cell_id']); ?>" href="#c<?php echo htmlentities($cell_assoc['cell_id']); ?>">
                                <i class="icon-th"></i> <?php echo $cell_assoc['cell_name']; ?> <i class="icon-caret-down"></i>
                                <span class="label label-default pull-right"><i class="icon-cogs icon-large"></i> <?php echo $all->num_rows; ?></span>
                                </a>
                            </h2>
                     	</div>
                    </div>
                    <div id="c<?php echo htmlentities($cell_assoc['cell_id']); ?>" class="panel-collapse collapse">
                        <div class="panel-body">
                        	<?php if($all->num_rows > 0) { ?>
                            <div class="col-sm-12 center-text">
                                <p class="lead">
                                    <h1><i class="icon-cogs"></i> <?php echo $cell_assoc['cell_name']; ?> Machines <i class="icon-cogs icon-rotate-180"></i><span class="label label-default pull-right"><?php echo $all->num_rows; ?></span></h1>
                                </p>
                            </div>
                            <?php } else { ?>
                            <div class="col-sm-12 center-text">
                                <p class="lead">
                                    <h1>That's weird, there are no machines in this cell...</h1>
                                </p>
                            </div>
                            <?php } ?>
                        </div>
                        <?php if($all->num_rows > 0): ?>
                        	<?php $machineClass->displayMachines($all, $collapse_id_modifier); ?>
                        <?php endif; ?>
                        <div class="panel-footer">
                            <div class="media">
                                <div class="media-object col-xs-12 col-md-12 col-lg-12">
                                    <a type="button" class="btn btn-info" href="view_all_machines.php">Machines <i class="icon-cogs"></i></a>
                                    <a type="button" class="btn btn-warning" data-target="#cedit<?php echo htmlentities($cell_assoc['cell_id']); ?>" data-toggle="modal"><i class="icon-edit"></i> Edit <?php echo htmlentities($cell_assoc['cell_name']); ?></a>
                                    <a type="button" class="btn btn-danger" data-target="#cdelete<?php echo htmlentities($cell_assoc['cell_id']); ?>" data-toggle="modal"><i class="icon-trash"></i> Delete <?php echo htmlentities($cell_assoc['cell_name']); ?></a>
                                    <!-- Delete Warning Modal -->
                                    <div id="cdelete<?php echo htmlentities($cell_assoc['cell_id']); ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header modal-header-danger">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h3 id="myModalLabel"><strong>Warning!</strong> You are about to delete <?php ?>!</h3>
                                                </div><!-- /.modal-header -->
                                                <div class="modal-body">
                                                    <div class="alert alert-danger"> 
                                                        <p>Are you sure you want to PERMANENTLY delete Cell: <?php echo htmlentities($cell_assoc['cell_name']); ?></p></br>
                                                        <p><strong>YOU MUST UNDERSTAND THE CONSEQUENCES THIS IS A SYSTEMIC CHANGE THAT WILL AFFECT EVERYONE THAT USES THE SYSTEM.</strong> The best thing to do so it does not disrupt anyone using the system, is to move all personnel and machines out of the location prior to deprecation.</p>
                                                        <p><i class="icon-warning-sign"></i> <strong>Consequences</strong></p>
                                                        <ul>
                                                            <li>This action CANNOT be undone.</li>
                                                            <li>All machines and personnel in this location will be "homeless" and will need to be moved ASAP.</li>
                                                            <li>This may distrupt the homepages of the people in this location.</li>
                                                        </ul>   
                                                        <p><i class="icon-cloud"></i> <strong>Silver Lining</strong></p>
                                                        <ul>             
                                                            <li>This will NOT delete anything from the database. It will set the location as "inactive." But it is a pain to change so consider yourself warned.</li>
                                                        </ul>
                                                        <p>To proceed with deletion, check the box below, and click <button class="btn btn-danger"><i class="icon-trash"></i></button>.</p>
                                                    </div><!-- /.alert-danger -->
                                                </div><!-- /.modal-body -->
                                                <div class="modal-footer">
                                                    <button class="btn btn-default btn-small pull-left" data-dismiss="modal" aria-hidden="true"><h4><i class="icon-reply"></i> Nevermind</h4></button>
                                                    <form onClick="proceed(this);" action="delete_location.php?building_id=<?php echo htmlentities($building['building_id']); ?>&section_id=<?php echo htmlentities($section_assoc['section_id']); ?>&cell_id=<?php echo htmlentities($cell_assoc['cell_id']); ?>" method="post">
                                                        <span class="button-checkbox">
                                                            <button type="button" class="btn btn-lg" data-color="danger">I Understand The Consequences</button>
                                                            <input type="checkbox" class="hidden" name="understood" value="understood"/>
                                                        </span>
                                                      <button class="btn btn-danger" type="submit" name="proceedButton" id="proceedButton" disabled><i class="icon-trash icon-3x"></i></button>
                                                    </form>
                                                </div><!-- /.modal-footer -->
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                                    <!-- End Delete Modal -->
                                    <!-- Edit Warning Modal -->
                                    <div id="cedit<?php echo htmlentities($cell_assoc['cell_id']); ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header modal-header-warning">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h3 id="myModalLabel"><strong>Warning!</strong> You are about to edit <?php echo htmlentities($cell_assoc['cell_name']); ?>!</h3>
                                                </div><!-- /.modal-header -->
                                                <div class="modal-body">
                                                    <div class="alert alert-warning"> 
                                                        <p>Are you sure you want to edit Cell: <?php echo htmlentities($cell_assoc['cell_name']); ?>?</p></br>
                                                        <p><i class="icon-warning-sign"></i> <strong>Consequences</strong></p>
                                                        <ul>
                                                            <li>This change will be seen by everyone in the system.</li>
                                                            <li>Make sure they are aware of the changes so there isn't any confusion about where the location is.</li>
                                                        </ul>   
                                                        <p><i class="icon-cloud"></i> <strong>Silver Lining</strong></p>
                                                        <ul>             
                                                            <li>This will NOT affect the maintenance logs.</li>
                                                            <li>You can change again later.</li> 
                                                        </ul>
                                                        <p>To proceed with edit, check the box below, and click <button class="btn btn-warning"><i class="icon-edit"></i></button>.</p>
                                                    </div><!-- /.alert-warning -->
                                                </div><!-- /.modal-body -->
                                                <div class="modal-footer">
                                                    <button class="btn btn-default btn-small pull-left" data-dismiss="modal" aria-hidden="true"><h4><i class="icon-reply"></i> Nevermind</h4></button>
                                                    <form onClick="proceed(this);" action="edit_location.php?building_id=<?php echo htmlentities($building['building_id']); ?>&section_id=<?php echo htmlentities($section_assoc['section_id']); ?>&cell_id=<?php echo htmlentities($cell_assoc['cell_id']); ?>" method="post">
                                                        <span class="button-checkbox">
                                                            <button type="button" class="btn btn-lg" data-color="warning">I Understand The Consequences</button>
                                                            <input type="checkbox" class="hidden" name="understood" value="understood"/>
                                                        </span>
                                                      <button class="btn btn-warning" type="submit" name="proceedButton" id="proceedButton" disabled><i class="icon-edit icon-3x"></i></button>
                                                    </form>
                                                </div><!-- /.modal-footer -->
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                                    <!-- End Warning Modal -->
                                    <a type="button" class="btn btn-primary pull-right" data-toggle="collapse" data-parent="#c<?php echo htmlentities($cell_assoc['cell_id']); ?>" href="#c<?php echo htmlentities($cell_assoc['cell_id']); ?>"><i class="icon-caret-up"></i> Cell <i class="icon-th"></i></a>
                                </div>
                            </div>
                            <div class="media">
                                <div class="media-object col-xs-12 col-md-12 col-lg-12">
                                    <ol class="breadcrumb center-text">
                                        <li><a data-toggle="collapse" data-parent="#b<?php echo htmlentities($building['building_id']); ?>" href="#b<?php echo htmlentities($building['building_id']); ?>"><i class="icon-building"></i> <?php echo $building['building_name']; ?></a></li>
                                        <li><a data-toggle="collapse" data-parent="#s<?php echo htmlentities($section_assoc['section_id']); ?>" href="#s<?php echo htmlentities($section_assoc['section_id']); ?>"><i class="icon-th-large"></i> <?php echo $section_assoc['section_name']; ?></a></li>
                                        <li class="active"><i class="icon-th"></i> <?php echo $cell_assoc['cell_name']; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                 	</div>
					<?php 
                    	}
                    	endforeach; 
					?>
                </div><!-- /.panel-cells -->
            	<!------------------------------------- End Cells ------------------------------------>
                <div class="panel-footer">
                	<div class="media">
                    	<div class="media-object col-xs-12 col-md-12 col-lg-12">
                        	<a type="button" class="btn btn-success" href="edit_cell.php?building_id=<?php echo htmlentities($building['building_id']); ?>&section_id=<?php echo htmlentities($section_assoc['section_id']); ?>"><i class="icon-plus-sign"></i> Add Cell</a>
                            <a type="button" class="btn btn-warning" data-target="#sedit<?php echo htmlentities($section_assoc['section_id']); ?>" data-toggle="modal"><i class="icon-edit"></i> Edit <?php echo htmlentities($section_assoc['section_name']); ?></a>
                            <a type="button" class="btn btn-danger" data-target="#sdelete<?php echo htmlentities($section_assoc['section_id']); ?>" data-toggle="modal"><i class="icon-trash"></i> Delete <?php echo htmlentities($section_assoc['section_name']); ?></a>
                            <?php 
							$id 			= 'sdelete'.$section_assoc['section_id'];
							$type 			= 1;
							$header_text	= '<strong>Warning!</strong> You are about to delete '.$section_assoc['section_name'].'!';
							$body 			= '<p>Are you sure you want to PERMANENTLY delete Section: '.$section_assoc['section_name'].'</p></br>
                                                <p><strong>YOU MUST UNDERSTAND THE CONSEQUENCES THIS IS A SYSTEMIC CHANGE THAT WILL AFFECT EVERYONE THAT USES THE SYSTEM.</strong> The best thing to do so it does not disrupt anyone using the system, is to move all personnel and machines out of the location prior to deprecation.</p>
                                                <p><i class="icon-warning-sign"></i> <strong>Consequences</strong></p>
                                                <ul>
                                                    <li>This action CANNOT be undone.</li>
                                                    <li>All machines and personnel in this location will be "homeless" and will need to be moved ASAP.</li>
                                                    <li>This may distrupt the homepages of the people in this location.</li>
                                                </ul>   
                                                <p><i class="icon-cloud"></i> <strong>Silver Lining</strong></p>
                                                <ul>             
                                                    <li>This will NOT delete anything from the database. It will set the location as "inactive." But it is a pain to change so consider yourself warned.</li>
                                                </ul>
                                                <p>To proceed with deletion, check the box below, and click <button class="btn btn-danger"><i class="icon-trash"></i></button>.</p>';
							$link 			= 'delete_location.php?building_id='.$building['building_id'].'&section_id='.$section_assoc['section_id'];
							warningModal($id, $type, $header_text, $body, $link); ?>
							<?php 
							$id 			= 'sedit'.$section_assoc['section_id'];
							$type 			= 0;
							$header_text	= '<strong>Warning!</strong> You are about to edit '.$section_assoc['section_name'].'!';
							$body 			= '<p>Are you sure you want to edit Section: '.$section_assoc['section_name'].'?</p></br>
                                                <p><i class="icon-warning-sign"></i> <strong>Consequences</strong></p>
                                                <ul>
                                                    <li>This change will be seen by everyone in the system.</li>
                                                    <li>Make sure they are aware of the changes so there is no confusion about where the location is.</li>
                                                </ul>   
                                                <p><i class="icon-cloud"></i> <strong>Silver Lining</strong></p>
                                                <ul>             
                                                    <li>This will NOT affect the maintenance logs.</li>
                                                    <li>You can change again later.</li> 
                                                </ul>
                                                <p>To proceed with edit, check the box below, and click <button class="btn btn-warning"><i class="icon-edit"></i></button>.</p>';
							$link 			= 'edit_location.php?building_id='.$building['building_id'].'&section_id='.$section_assoc['section_id'];
							warningModal($id, $type, $header_text, $body, $link); ?>
                            <a type="button" class="btn btn-primary pull-right" data-toggle="collapse" data-parent="#s<?php echo htmlentities($section_assoc['section_id']); ?>" href="#s<?php echo htmlentities($section_assoc['section_id']); ?>"><i class="icon-caret-up"></i> Section <i class="icon-th-large"></i></a>
                     	</div>
                  	</div>
                  	<div class="media">
                        <div class="col-xs-12 col-md-12 col-lg-12">
                            <ol class="breadcrumb center-text">
                                <li><a data-toggle="collapse" data-parent="#b<?php echo htmlentities($building['building_id']); ?>" href="#b<?php echo htmlentities($building['building_id']); ?>"><i class="icon-building"></i> <?php echo $building['building_name']; ?></a></li>
                                <li class="active"><i class="icon-th-large"></i> <?php echo $section_assoc['section_name']; ?></li>
                            </ol>
                        </div>            			
                   	</div>
                </div>
           	</div><!-- /.panel-collapse-cells -->
        </div><!-- /.panel-sections -->
                    <?php 
					}
					endforeach; ?>
                    </div>
                    <?php endif; ?>
        <!----------------------------------------- End Sections ----------------------------------------->
        <div class="panel-footer">
        	<div class="media">
                <div class="media-object col-xs-12 col-md-12 col-lg-12">
                    <a type="button" class="btn btn-success" href="edit_section.php?building_id=<?php echo htmlentities($building['building_id']); ?>"><i class="icon-plus-sign"></i> Add Section</a>
                    <a type="button" class="btn btn-warning center-text" data-target="#bedit<?php echo htmlentities($building['building_id']); ?>" data-toggle="modal"><i class="icon-edit"></i> Edit <?php echo htmlentities($building['building_name']); ?></a>
                    <a type="button" class="btn btn-danger center-text" data-target="#bdelete<?php echo htmlentities($building['building_id']); ?>" data-toggle="modal"><i class="icon-trash"></i> Delete <?php echo htmlentities($building['building_name']); ?></a>
                    <?php 
						$id 			= 'bdelete'.$building['building_id'];
						$type 			= 1;
						$header_text	= '<strong>Warning!</strong> You are about to delete '.$building['building_name'].'!';
						$body 			= '<p>Are you sure you want to PERMANENTLY delete Section: '.$building['building_name'].'</p></br>
                                        <p><strong>YOU MUST UNDERSTAND THE CONSEQUENCES THIS IS A SYSTEMIC CHANGE THAT WILL AFFECT EVERYONE THAT USES THE SYSTEM.</strong> The best thing to do so it does not disrupt anyone using the system, is to move all personnel and machines out of the location prior to deprecation.</p>
                                        <p><i class="icon-warning-sign"></i> <strong>Consequences</strong></p>
                                        <ul>
                                            <li>This action CANNOT be undone.</li>
                                            <li>All machines and personnel in this location will be "homeless" and will need to be moved ASAP.</li>
                                            <li>This may distrupt the homepages of the people in this location.</li>
                                        </ul>   
                                        <p><i class="icon-cloud"></i> <strong>Silver Lining</strong></p>
                                        <ul>             
                                            <li>This will NOT delete anything from the database. It will set the location as "inactive." But it is a pain to change so consider yourself warned.</li>
                                        </ul>
                                        <p>To proceed with deletion, check the box below, and click <button class="btn btn-danger"><i class="icon-trash"></i></button>.</p>';
						$link 			= 'delete_location.php?building_id='.$building['building_id'];
						warningModal($id, $type, $header_text, $body, $link); 
					?>
					<?php 
						$id 			= 'bedit'.$building['building_id'];
						$type 			= 0;
						$header_text	= '<strong>Warning!</strong> You are about to edit '.$building['building_name'].'!';
						$body 			= '<p>Are you sure you want to edit Building: '.$building['building_name'].'?</p></br>
                                        <p><i class="icon-warning-sign"></i> <strong>Consequences</strong></p>
                                        <ul>
                                            <li>This change will be seen by everyone in the system.</li>
                                            <li>Make sure they are aware of the changes so there is no confusion about where the location is.</li>
                                        </ul>   
                                        <p><i class="icon-cloud"></i> <strong>Silver Lining</strong></p>
                                        <ul>             
                                            <li>This will NOT affect the maintenance logs.</li>
                                            <li>You can change again later.</li> 
                                        </ul>
                                        <p>To proceed with edit, check the box below, and click <button class="btn btn-warning"><i class="icon-edit"></i></button>.</p>';
						$link 			= 'edit_location.php?building_id='.$building['building_id'];
						warningModal($id, $type, $header_text, $body, $link); 
					?>
                    <a type="button" class="btn btn-primary pull-right" data-toggle="collapse" data-parent="#b<?php echo htmlentities($building['building_id']); ?>" href="#b<?php echo htmlentities($building['building_id']); ?>"><i class="icon-caret-up"></i> Building <i class="icon-building"></i></a>
                </div>
         	</div>
          	<div class="media">
                <div class="media-object col-xs-12 col-md-12 col-lg-12">
                    <ol class="breadcrumb center-text">
                        <li><i class="icon-building"></i> <?php echo $building['building_name']; ?></li>
                    </ol>
                </div>
         	</div>
            </div><!-- /.panel-collapse-sections -->
        </div><!-- /.panel-footer ------------------------------------------------------------------------>
    </div><!-- /.panel-collapse-buildings ------------------------------------------------------------------->
    <?php 
        endforeach;
    ?>
<!------------------------------------------------- End Buildings ------------------------------------------------>
	<a type="button" class="btn btn-success btn-block btn-lg" href="edit_building.php"><i class="icon-plus-sign"></i> Add Building <i class="icon-building"></i></a>
    </div><!-- /.col-md-12 -->
</div><!-- /.container -->
<?php include_layout_template('admin_footer.php'); ?>
		
