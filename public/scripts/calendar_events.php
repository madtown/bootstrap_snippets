<?php
require_once('../../includes/initialize.php');
require_once('../../public/layouts/admin_header.php');

function getEvents(){
	global $connection;
	global $database;
	$sql = "SELECT * FROM maint_log WHERE type='".$input."' ORDER BY machine_num";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
	?><select class="form-control" name="machine_num" id="machine_num" multiple="multiple">
	<option value="None Selected">None Selected</option> <?php
	do 
	{ if($result['machine_num']){
		?><option value='<?php echo trim($result['machine_num'], '"');?>'>"<?php echo trim($result['machine_num'], '"');?>"</option><?php
	}
	$a = json_encode($a, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
	}
		while ($result=$query->fetch_assoc());
	?></select><?php
}
getEvents();
?>

{"title": 'Danger',
	start: '2013-11-15',
	end: '2013-11-16',
	url: 'http://google.com/',
	color: '#d9534f'
},
{
	title: 'Default',
	start: new Date(y, m, d-5),
	end: new Date(y, m, d-2),
	url: 'http://google.com/',
	color: '#333333'
},
{
	title: 'Primary',
	start: new Date(y, m, d-3, 16, 0),
	end: new Date(y, m, d-2),
	allDay: false,
	url: 'http://google.com/',
	color: '#3276b1'
},
{
	title: 'Primary',
	start: new Date(y, m, d-3, 16, 0),
	end: new Date(y, m, d-2),
	allDay: false,
	url: 'http://google.com/',
	color: '#3276b1'
},
{
	title: 'Primary',
	start: new Date(y, m, d-3, 16, 0),
	end: new Date(y, m, d-2),
	allDay: false,
	url: 'http://google.com/',
	color: '#3276b1'
},
{
	title: 'Primary',
	start: new Date(y, m, d-3, 16, 0),
	end: new Date(y, m, d-2),
	allDay: false,
	url: 'http://google.com/',
	color: '#3276b1'
},
{
	title: 'Primary',
	start: new Date(y, m, d-3, 16, 0),
	end: new Date(y, m, d-2),
	allDay: false,
	url: 'http://google.com/',
	color: '#3276b1'
},
{
	title: 'Primary',
	start: new Date(y, m, d-3, 16, 0),
	end: new Date(y, m, d-2),
	allDay: false,
	url: 'http://google.com/',
	color: '#3276b1'
},
{
	title: 'Primary',
	start: new Date(y, m, d-3, 16, 0),
	end: new Date(y, m, d-2),
	allDay: false,
	url: 'http://google.com/',
	color: '#3276b1'
},
{
	title: 'Primary',
	start: new Date(y, m, d-3, 16, 0),
	end: new Date(y, m, d-2),
	allDay: false,
	url: 'http://google.com/',
	color: '#3276b1'
},
{
	title: 'Primary',
	start: new Date(y, m, d-3, 16, 0),
	end: new Date(y, m, d-2),
	allDay: false,
	url: 'http://google.com/',
	color: '#3276b1'
},
{
	title: 'Warning',
	start: new Date(y, m, d+4, 16, 0),
	end: new Date(y, m, d-2),
	allDay: false,
	url: 'http://google.com/',
	color: '#f0ad4e'
},
{
	title: 'Success',
	start: new Date(y, m, d, 10, 30),
	end: new Date(y, m, d-2),
	allDay: false,
	url: 'http://google.com/',
	color: '#47a447'
},
{
	title: 'Info',
	start: new Date(y, m, d, 12, 0),
	end: new Date(y, m, d, 14, 0),
	allDay: false,
	url: 'http://google.com/',
	color: '#5bc0de'
},
{
	title: 'Transparent',
	start: new Date(y, m, d+1, 19, 0),
	end: new Date(y, m, d+1, 22, 30),
	allDay: false,
	url: 'http://google.com/',
	color: 'transparent'
},
{
	title: 'Click for Google',
	start: new Date(y, m, 28),
	end: new Date(y, m, 29),
	url: 'http://google.com/',
	color: '#333333'
}
