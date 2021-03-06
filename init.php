<?php

session_start();

require_once 'mysql_helper.php';
require_once 'functions.php';

$link = mysqli_connect("localhost", "root", "", "doingsdone");

$title = "Дела в порядке";

$content = "";
$layout_content = "";

$result = [];

$username = "";

$projects = [];
$project_id = NULL;

$tasks = [];
$all_tasks =[];
$user_tasks = [];

$search = NULL;

$info_list = [];
$error_list = [];

$show_complete_tasks = NULL;

$result_send = [];

if (!$link) {
	$error['error_connect'] = mysqli_connect_error();
	$content = include_template('error.php', ['error' => $error]);
	$layout_content = include_template('error.php', [
		'title' => $title,
		'content' => $content
	]);
} else {
	mysqli_set_charset($link, "utf8");
}
