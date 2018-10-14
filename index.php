<?php
require_once 'init.php';

if(isset($_GET['show_completed'])) {
    intval($_GET['show_completed']);
	$show_complete_tasks = $_GET['show_completed'];
} else {
	$show_complete_tasks = '';
}

if (!$link) {
	$error = mysqli_connect_error();
	echo include_template('error.php', ['error' => $error]);
} else {
    if (isset($_GET['task_id']) && isset($_GET['check'])) {
        intval($_GET['task_id']);

        $info_status_list['task_id'] = $_GET['task_id'];
        $info_status_list['status'] = $_GET['check'];

        $result = update_task_status($link, $info_status_list);
        if (isset($result['error'])) {
            $error['update_task_status'] = $result['error'];
        } else {
            header('Location: /');
        }
    }

	// Запрос для получения проектов у текущего пользователя
	$sql = 'SELECT * FROM `projects` WHERE `user_id` = '.$_SESSION['user'][0]['id'];
	$projects = get_info($link, $sql, $_SESSION['user'][0]['id']);

	// Запрос для получения списка всех задач
	$sql = 'SELECT * FROM `tasks` WHERE `user_id` = '.$_SESSION['user'][0]['id'];
	$all_tasks = get_info($link, $sql, $_SESSION['user'][0]['id']);

	// Запрос для получения списка задач
    $sql = 'SELECT * FROM `tasks` WHERE `user_id` = '.$_SESSION['user'][0]['id'];
	if (isset($_GET['project_id'])) {
		$project_id = $_GET['project_id'];
		$sql .= '&& `project_id` = '.$project_id;
	}
	$tasks = get_info($link, $sql, $_SESSION['user'][0]['id']);

	// Проверка проекта на существование
	if (isset($_GET['project_id'])) {
		intval($_GET['project_id']);
		$project_existence = FALSE;
		foreach ($projects as $value) {
			if ($_GET['project_id'] === $value['id']) {
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

	$layout_content = include_template('layout.php', [
		'title' => 'Дела в порядке',
		'projects' => $projects,
		'all_tasks' => $all_tasks,
		'content' => $content
	]);
}

echo $layout_content;