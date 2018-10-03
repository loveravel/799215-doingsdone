<?php
require_once 'init.php';

if (!$link) {
	$error = mysqli_connect_error();
	$content = include_template('error.php', ['error' => $error]);
} else {
	$sql = 'SELECT * FROM `projects` WHERE `user_id` = 1;';
	$result = mysqli_query($link, $sql);

	if ($result) {
		$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
	} else {
		$error = mysqli_error($link);
		$content = include_template('error.php', ['error => $error']);
	}

	$sql = 'SELECT * FROM `tasks` WHERE `user_id` = 1;';
	$result = mysqli_query($link, $sql);

	if ($result) {
		$tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
	} else {
		$error = mysqli_error($link);
		$content = include_template('error.php', ['error => $error']);
	}
}

$page_content = include_template(
	'index.php',
	[
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);
$layout_content = include_template(
	'layout.php',
	[
    'content' => $page_content,
    'projects' => $projects,
    'tasks' => $tasks,
    'title' => 'Дела в порядке'
]);

echo $layout_content;