<?php
// Шаблонизатор
function include_template($name, $data) {
	$name = 'templates/' . $name;
	$result = '';

	if (!file_exists($name)) {
		return $result;
	}

	ob_start();
	extract($data);
	require_once $name;

	$result = ob_get_clean();

	return $result;
};

// Запрос к БД
function get_info ($link, $sql, $user_id) {
	$result = mysqli_query($link, $sql);
	if (!$result) {
		$info['error'] = mysqli_error($link);
	} else {
		$info['result'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
	}
	isset($info['error']) ? $error['info_var'] = mysqli_error($link) : $info_var = $info['result'];
	return $info_var;
}

// Запрос к БД (подготовленное выражение)
function get_info_def ($link, $sql, $user_id) {
	$result = mysqli_prepare($link, $sql);
	$stmt = db_get_prepare_stmt($link, $sql, [$user_id]);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$info = mysqli_fetch_assoc($result);
	return $info;
}

// Подсчет количества задач в проекте
function get_amount_tasks($all_tasks, $project_id) {

	$amount_tasks = 0;

	foreach ($all_tasks as $value) {
		if($value['project_id'] === $project_id) {
			$amount_tasks++;
		}
	};
	return $amount_tasks;
};

// Важные задачи (на выполнение осталось менее суток)
function important_task($task) {
	$current_time = time();
	$deadline = strtotime($task['deadline']);

	$difference_time = ($deadline - $current_time) / 3600;
	if(($difference_time <= 24) && ($task['deadline']) && (!$task['status'])) {
		return true;
	} else {
		return false;
	}
}