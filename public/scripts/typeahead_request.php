<?php
require_once('../../includes/initialize.php');

if(isset($_GET['username'])) {
	$username = '%'.$_GET['username'].'%';
	global $connection;
	global $database;
	$sql = "SELECT username FROM users WHERE username LIKE '".$username."'ORDER BY username";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
	$data = array();
	do { 
		$subarray = $result;
		array_push($data, $subarray);
	}
	while ($result=$query->fetch_assoc());
	echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
}

if(isset($_GET['part_vendor'])) {
	$part_vendor = '%'.$_GET['part_vendor'].'%';
	global $connection;
	global $database;
	$sql = "SELECT part_vendor FROM posts WHERE part_vendor LIKE '".$part_vendor."'ORDER BY part_vendor";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
	$data = array();
	do { 
		$subarray = $result;
		array_push($data, $subarray);
	}
	while ($result=$query->fetch_assoc());
	echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
}

if(isset($_GET['part_id'])) {
	$part_id = '%'.$_GET['part_id'].'%';
	global $connection;
	global $database;
	$sql = "SELECT part_id FROM posts WHERE part_id LIKE '".$part_id."'ORDER BY part_id";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
	$data = array();
	do { 
		$subarray = $result;
		array_push($data, $subarray);
	}
	while ($result=$query->fetch_assoc());
	echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
}

if(isset($_GET['external_vendor'])) {
	$external_vendor = '%'.$_GET['external_vendor'].'%';
	global $connection;
	global $database;
	$sql = "SELECT external_vendor FROM posts WHERE external_vendor LIKE '".$external_vendor."'ORDER BY external_vendor";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
	$data = array();
	do { 
		$subarray = $result;
		array_push($data, $subarray);
	}
	while ($result=$query->fetch_assoc());
	echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
}

if(isset($_GET['external_doc_name'])) {
	$external_doc_name = '%'.$_GET['external_doc_name'].'%';
	global $connection;
	global $database;
	$sql = "SELECT external_doc_name FROM posts WHERE external_doc_name LIKE '".$external_doc_name."'ORDER BY external_doc_name";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
	$data = array();
	do { 
		$subarray = $result;
		array_push($data, $subarray);
	}
	while ($result=$query->fetch_assoc());
	echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
}

if(isset($_GET['status_cause'])) {
	$status_cause = '%'.$_GET['status_cause'].'%';
	global $connection;
	global $database;
	$sql = "SELECT status_cause FROM posts WHERE status_cause LIKE '".$status_cause."'ORDER BY status_cause";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
	$data = array();
	do { 
		$subarray = $result;
		array_push($data, $subarray);
	}
	while ($result=$query->fetch_assoc());
	echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
}

if(isset($_GET['short'])) {
	$short = '%'.$_GET['short'].'%';
	global $connection;
	global $database;
	$sql = "SELECT short FROM requests WHERE short LIKE '".$short."'ORDER BY short";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
	$data = array();
	do { 
		$subarray = $result;
		array_push($data, $subarray);
	}
	while ($result=$query->fetch_assoc());
	echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
}

if(isset($_GET['subject'])) {
	$subject = '%'.$_GET['subject'].'%';
	global $connection;
	global $database;
	$sql = "SELECT subject FROM requests WHERE subject LIKE '".$subject."'ORDER BY subject";
	$query = $database->query($sql);
	$result = $query->fetch_assoc();
	$data = array();
	do { 
		$subarray = $result;
		array_push($data, $subarray);
	}
	while ($result=$query->fetch_assoc());
	echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
}

?>