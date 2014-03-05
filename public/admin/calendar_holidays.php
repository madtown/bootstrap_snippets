<?php
/*$year = date('Y');
	$month = date('m');

	echo json_encode(array(
	
		array(
			'id' => 111,
			'title' => "Event1",
			'start' => strtotime("$year-$month-10"),
			'url' => "http://yahoo.com/"
		),
		
		array(
			'id' => 222,
			'title' => "Event2",
			'start' => strtotime("$year-$month-20"),
			'end' => strtotime("$year-$month-22"),
			'url' => "http://yahoo.com/"
		),
	
	));*/
?>
[
{	title: 'Danger',
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
}]