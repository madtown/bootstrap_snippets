<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
global $workClass;
if (isset($_GET['request_id'])) {
	$request_id = $_GET['request_id'];
}
if (isset($_GET['post_id'])) {
	$post_id = $_GET['post_id'];
}
if (isset($request_id) && !isset($post_id)) {
	$posts = $workClass->find_all_posts_by_request_id($request_id);
	$workClass->formatThread($posts, $request_id);
}
if (isset($post_id)) {
	$posts = $workClass->find_new_posts($request_id, $post_id);
	$workClass->formatThread($posts, $request_id);
}
if (!isset($request_id) && !isset($post_id)) {
	$posts = $workClass->find_all_part_posts();
	$workClass->formatThread($posts, $request_id);
}
?>