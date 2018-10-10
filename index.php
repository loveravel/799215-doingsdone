<?php
require_once 'init.php';

// Временно находится здесь
$user_id = 1;
$show_complete_tasks = rand(0, 1);

if (!$link) {
	$error = mysqli_connect_error();
	echo include_template('error.php', ['error' => $error]);
} else {

	// Запрос для получения данных о пользователе по id
	$sql = 'SELECT * FROM `users` WHERE `id` = 1';
	$user = get_info($link, $sql, $user_id);
	$username = $user['name'];

	// Запрос для получения проектов у текущего пользователя
	$sql = 'SELECT * FROM `projects` WHERE `user_id` = 1';
	$projects = get_info($link, $sql, $user_id);

	// Запрос для получения списка всех задач
	$sql = 'SELECT * FROM `tasks` WHERE `user_id` = '.$user_id;
	$all_tasks = get_info($link, $sql, $user_id);

	// Запрос для получения списка задач
	$sql = 'SELECT * FROM `tasks` WHERE `user_id` = '.$user_id;
	if (isset($_GET['project_id'])) {
		$project_id = $_GET['project_id'];
		$sql .= '&& `project_id` = '.$project_id;
	}
	$tasks = get_info($link, $sql, $user_id);

	// Проверка проекта на существование
	if (isset($_GET['project_id'])) {
		settype($project_id, 'integer');

		$project_existence = FALSE;
		foreach ($projects as $value) {
			if ($_GET['project_id'] == $value['id']) {
				$project_existence = TRUE;
			}
		}

		if (!$project_existence || $project_id = '') {
			header("HTTP/1.1 404 Not Found");
            die();
		}
	}

	$content = include_template('index.php', [
		'tasks' => $tasks,
		'show_complete_tasks' => $show_complete_tasks
	]);

	$layout = include_template('layout.php', [
		'title' => 'Дела в порядке',
		'username' => $username,
		'projects' => $projects,
		'all_tasks' => $all_tasks,
		'content' => $content
	]);
}

echo $layout;