<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container">
	<div class="center-text"><h1><i class="icon-wrench"></i> Unscheduled Maintenace Mockups</h1></div>
	<div class="panel panel-danger">
    	<div class="panel-heading">
            <div class="center-text"><h2><i class="icon-plus-sign"></i> Request</h2></div>
      	</div>
        <div class="panel-body">
            <h3>
                <ul>
                    <a href="request.php"><i class="icon-edit"></i> Request Form</a></br>
                </ul>
            </h3>
    	</div>
 	</div>
    <div class="panel panel-warning">
    	<div class="panel-heading">
			<div class="center-text"><h2><i class="icon-wrench"></i> Respond</h2></div>
      	</div>
        <div class="panel-body">
            <h3>
                <ul>
                    <a href="queue.php"><i class="icon-list-ol"></i> Maintenance Queue</a></br>
             		<a href="work.php"><i class="icon-wrench"></i> Work Form</a></br>	
 					<a href="order_parts.php"><i class="icon-shopping-cart"></i> Order Parts</a></br>
                </ul>
            </h3>
        </div>
 	</div>
    <div class="panel panel-success">
    	<div class="panel-heading">
            <div class="center-text"><h2><i class="icon-archive"></i> Report</h2></div>
      	</div>
        <div class="panel-body">
            <h3>
                <ul>
                    <a href="resolution.php"><i class="icon-check"></i> Resolution Form</a></br>	
 					<a href=""><i class="icon-list"></i> Inventory</a></br>
                </ul>
            </h3>
    	</div>
 	</div>
</div>
<div class="container">   
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <!-- Nav tabs category -->
            <ul class="nav nav-tabs faq-cat-tabs">
                <li class="active"><a href="#faq-cat-1" data-toggle="tab">Category #1</a></li>
                <li><a href="#faq-cat-2" data-toggle="tab">Category #2</a></li>
            </ul>
    
            <!-- Tab panes -->
            <div class="tab-content faq-cat-content">
                <div class="tab-pane active in fade" id="faq-cat-1">
                    <div class="panel-group" id="accordion-cat-1">
                        <div class="panel panel-default panel-faq">
                            <div class="panel-heading">
                                <a data-toggle="collapse" data-parent="#accordion-cat-1" href="#faq-cat-1-sub-1">
                                    <h4 class="panel-title">
                                        FAQ Item Category #1
                                        <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                    </h4>
                                </a>
                            </div>
                            <div id="faq-cat-1-sub-1" class="panel-collapse collapse">
                                <div class="panel-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default panel-faq">
                            <div class="panel-heading">
                                <a data-toggle="collapse" data-parent="#accordion-cat-1" href="#faq-cat-1-sub-2">
                                    <h4 class="panel-title">
                                        FAQ Item Category #1
                                        <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                    </h4>
                                </a>
                            </div>
                            <div id="faq-cat-1-sub-2" class="panel-collapse collapse">
                                <div class="panel-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="faq-cat-2">
                    <div class="panel-group" id="accordion-cat-2">
                        <div class="panel panel-default panel-faq">
                            <div class="panel-heading">
                                <a data-toggle="collapse" data-parent="#accordion-cat-2" href="#faq-cat-2-sub-1">
                                    <h4 class="panel-title">
                                        FAQ Item Category #2
                                        <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                    </h4>
                                </a>
                            </div>
                            <div id="faq-cat-2-sub-1" class="panel-collapse collapse">
                                <div class="panel-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default panel-faq">
                            <div class="panel-heading">
                                <a data-toggle="collapse" data-parent="#accordion-cat-2" href="#faq-cat-2-sub-2">
                                    <h4 class="panel-title">
                                        FAQ Item Category #2
                                        <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                                    </h4>
                                </a>
                            </div>
                            <div id="faq-cat-2-sub-2" class="panel-collapse collapse">
                                <div class="panel-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
    <div class="container">
    <h3>jQuery Checkbox Buttons<br />
        <small>Buttons that change the state of their own hidden checkboxes, and vice-versa!</small>
    </h3>
    <br />

    <span class="button-checkbox">
        <button type="button" class="btn" data-color="primary">Unchecked</button>
        <input type="checkbox" class="hidden" />
    </span>
    <span class="button-checkbox">
        <button type="button" class="btn" data-color="primary">Checked</button>
        <input type="checkbox" class="hidden" checked />
    </span>
    
    <hr />
    
    <!-- All colors -->
    <span class="button-checkbox">
        <button type="button" class="btn" data-color="default">Default</button>
        <input type="checkbox" class="hidden" checked />
    </span>
    <span class="button-checkbox">
        <button type="button" class="btn" data-color="primary">Primary</button>
        <input type="checkbox" class="hidden" checked />
    </span>
    <span class="button-checkbox">
        <button type="button" class="btn" data-color="success">Success</button>
        <input type="checkbox" class="hidden" checked />
    </span>
    <span class="button-checkbox">
        <button type="button" class="btn" data-color="info">Info</button>
        <input type="checkbox" class="hidden" checked />
    </span>
    <span class="button-checkbox">
        <button type="button" class="btn" data-color="warning">Warning</button>
        <input type="checkbox" class="hidden" checked />
    </span>
    <span class="button-checkbox">
        <button type="button" class="btn" data-color="danger">Danger</button>
        <input type="checkbox" class="hidden" checked />
    </span>
    <span class="button-checkbox">
        <button type="button" class="btn" data-color="link">Link</button>
        <input type="checkbox" class="hidden" checked />
    </span>
    
    <hr />
    
    <!-- All sizes -->
    <span class="button-checkbox">
        <button type="button" class="btn btn-xs" data-color="primary">Primary</button>
        <input type="checkbox" class="hidden" checked />
    </span>
    <span class="button-checkbox">
        <button type="button" class="btn btn-sm" data-color="primary">Primary</button>
        <input type="checkbox" class="hidden" checked />
    </span>
    <span class="button-checkbox">
        <button type="button" class="btn" data-color="primary">Primary</button>
        <input type="checkbox" class="hidden" checked />
    </span>
    <span class="button-checkbox">
        <button type="button" class="btn btn-lg" data-color="primary">Primary</button>
        <input type="checkbox" class="hidden" checked />
    </span>
    
    <hr />
    
    <!-- Icons -->
    <span class="button-checkbox">
        <button type="button" class="btn" data-color="primary"><i class="glyphicon glyphicon-envelope"></i></button>
        <input type="checkbox" class="hidden" checked />
    </span>
    <span class="button-checkbox">
        <button type="button" class="btn" data-color="primary"><i class="glyphicon glyphicon-phone"></i></button>
        <input type="checkbox" class="hidden" />
    </span>
</div>
<?php include_layout_template('admin_footer.php'); ?>
		
