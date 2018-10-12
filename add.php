<?php

require_once 'init.php';

if (!$link) {
	$error['error_connect'] = mysqli_connect_error();
	$content = include_template('error.php', ['error' => $error]);
	$layout_content = include_template('error.php', [
		'title' => 'Дела в порядке',
		'content' => $content
	]);
} else {

	$user_id = 1;
	if(isset($_GET['show_completed'])) {
		$show_complete_tasks = $_GET['show_completed'];
		settype($project_id, 'integer');
	} else {
		$show_complete_tasks = '';
	}

	if(isset($_GET['show_tasks'])) {
		$show_tasks = $_GET['show_tasks'];
		settype($project_id, 'integer');
	} else {
		$show_complete_tasks = '';
	}

	$info_list = [];
	$error_list = [];

	// Запрос для получения данных о пользователе по id
	$sql = 'SELECT * FROM `users` WHERE `id` = 1';
	$user = get_info($link, $sql, $user_id);
	$username = $user[0]['name'];

	// Запрос для получения проектов у текущего пользователя
	$sql = 'SELECT * FROM `projects` WHERE `user_id` = 1';
	$projects = get_info($link, $sql, $user_id);

	// Запрос для получения списка всех задач
	$sql = 'SELECT * FROM `tasks` WHERE `user_id` = 1';
	$all_tasks = get_info($link, $sql, $user_id);

	// Обработка данных из формы
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$info_list = $_POST;

		foreach ($info_list as $value) {
			$value = mysqli_real_escape_string($link, $value);
		}

		$required_list = ['name','project'];
		$error_list = [];

		// Валидация формы
		$error_list = do_validate_task_form($info_list, $required_list, $projects);
		$error_list += do_validate_date($info_list);


		// Добавление задачи в БД
		if(empty($error_list)) {
			$sql = 'INSERT INTO `tasks` SET `project_id` = '.$info_list['project'].', `user_id` = '.$user_id.', `name` = "'.$info_list['name'].'"';

			if(!empty($_FILES['preview']['name'])) {
			    $file_name = $_FILES['preview']['name'];
				$file_path = __DIR__ . '/uploads/';
				$file_url = '/uploads/' . $file_name;

				move_uploaded_file($_FILES['preview']['tmp_name'], $file_path . $file_name);
				$info_list['file_path'] = $file_url;
				$sql .= ', `file_name` = "' . $file_name . '", `file_path` = "' . $file_url . '"';
			}

			if (!empty($info_list['deadline'])) {
				$sql .= ', `deadline` = date("'.$info_list['deadline'].'")';
			}
			
			$result = mysqli_query($link, $sql);

			if ($result) {
				header("Location: /");
			} else {
				$content = include_template('error.php', ['error' => mysqli_error($link)]);
			}
		}
	}

	$content = include_template('add.php', [
		'projects' => $projects,
		'info_list' => $info_list,
		'error_list' => $error_list
	]);
	
	$layout_content = include_template('layout.php', [
		'title' => 'Дела в порядке',
		'username' => $username,
		'projects' => $projects,
		'all_tasks' => $all_tasks,
		'content' => $content
	]);
}

echo $layout_content;