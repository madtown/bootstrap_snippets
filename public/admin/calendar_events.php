<?php
	/*$event_array = array();
	foreach($scheduled_maints as $maint) {	
		$type = $maint['type'];
		switch ($type) {
			case "Default":
				$color = "#333333";
				break;
			case "Danger":
				$color = "#d9534f";
				break;
			case "Primary":
				$color = "#3276b1";
				break;
			case "Success":
				$color = "#47a447";
				break;
			case "Info":
				$color = "#5bc0de";
				break;
			case "Warning":
				$color = "#f0ad4e";
				break;
			default;
				$color = "#333333";
				break;
		}
		$temp_array = array(
			'id' 		=> "{$maint['procedure_name']}{$result['machine_serial']}",
			'title' 	=> "{$maint['procedure_name']} on {$result['machine_serial']}",
			'start' 	=> "{$start}",
			'end' 		=> "{$end}",
			'url' 		=> "do_procedure.php?procedure_name={$photo->procedure_name}&serial_num={$result['machine_serial']}&type_maint={$photo->type_maint}",
			'color' 	=> "{$color}"
		);
		array_push($event_array, $temp_array);
	}
	echo json_encode($event_array);*/
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
	color: '#f0ad4e'
},
{
	title: 'Test',
	start: new Date(y, m, d+30, 19, 0),
	end: new Date(y, m, d+31, 22, 30),
	allDay: false,
	url: 'http://google.com/',
	color: '#47a447'
},
{
	title: 'Click for Google',
	start: new Date(y, m, 28),
	end: new Date(y, m, 29),
	url: 'http://google.com/',
	color: '#333333'
}]