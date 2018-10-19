<?php
/**
 * Подключение шалбонов
 * @param string $name Имя шаблона
 * @param array $data Массив данных для шаблона
 *
 * @return string $result Подключаемый шаблон
 */
function include_template($name, $data) {
	$name = 'templates/' . $name;
	$result = '';

	if (!file_exists($name)) {
		return $result;
	}

	ob_start();
	extract($data);
	require $name;

	$result = ob_get_clean();

	return $result;
}

/**
 * Обработка запроса к БД
 * @param mysqli $link Соединение с БД
 * @param string $sql  SQL-запрос
 *
 * @return array $info_var Массив значений полученный из БД по SQL-запросу
 * */
function get_info ($link, $sql) {
    $info = [];
    $info_var = [];
	$result = mysqli_query($link, $sql);
	if (!$result) {
		$info['error'] = mysqli_error($link);
	} else {
		$info['result'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
	}
	isset($info['error']) ? $error['info_var'] = mysqli_error($link) : $info_var = $info['result'];
	return $info_var;
}

/**
 * Подсчет количества задач в проекте
 * @param array $all_tasks Массив задач
 * @param integer $project_id Уникальный номер проекта
 *
 * @return integer $amount_tasks Количество задач в проекте
 */
function get_amount_tasks($all_tasks, $project_id) {

	$amount_tasks = 0;

	foreach ($all_tasks as $value) {
		if($value['project_id'] === $project_id) {
			$amount_tasks++;
		}
	};
	return $amount_tasks;
};

/**
 * Проеверка задачи на важность (задача считается важной, если на выполнение осталось менее суток)
 * @param array $task Массив данных задачи
 *
 * @return integer $amount_tasks Количество задач в проекте
 */
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

/**
 * Валидация даты
 * @param array $info_list Массив значений $_POST
 *
 * @return array $error_list Массив ошибок
 */
function do_validate_date($info_list) {
    $error_list = [];

    if (!empty($info_list['deadline'])) {
        $format = 'Y-m-d';
        DateTime::createFromFormat($format, $info_list['deadline']);
        $date_errors = DateTime::getLastErrors();
        if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
            $error_list['deadline'] = 'Неверный формат даты!';
        }
    }

    return $error_list;
}

/**
 * Проверка полей обязательных для заполнения
 * @param array $info_list Массив значений $_POST
 * @param array $required_fields Массив содержащий список обязательных для заполнения полей
 *
 * @return array $error_list Массив ошибок
 */
function do_validate_required_fields($info_list, $required_fields) {
    $error_list = [];

    foreach ($required_fields as $field) {
        if (empty($info_list[$field])) {
            $error_list[$field] = 'Заполните поле!';
        }
    }

    return $error_list;
}

/**
 * Валидация формы для создания задач
 * @param array $info_list Массив значений $_POST
 * @param array $required_fields Массив содержащий список обязательных для заполнения полей
 * @param array $projects Массив содержащий список проектов
 *
 * @return array $error_list Массив ошибок
 */
function do_validate_task_form($info_list, $required_fields, $projects) {

    $error_list = do_validate_required_fields($info_list, $required_fields);

    $projects_id = [];
    foreach ($projects as $value) {
    	$projects_id[] = $value['id'];
    }

	if (isset($info_list['project']) && !in_array($info_list['project'], $projects_id)) {
			$error_list['project'] = 'Проекта не существует!';
    }

	return $error_list;
}

/**
 * Валидация формы для регистрации новгого пользователя
 * @param mysqli $link Соединение с БД
 * @param array $info_list Массив значений $_POST
 * @param array $required_fields Массив содержащий список обязательных для заполнения полей
 *
 * @return array $error_list Массив ошибок
 */
function do_validate_register_form ($link, $info_list, $required_fields) {
    $error_list = do_validate_required_fields($info_list, $required_fields);

    if (!isset($error_list['email'])) {
        if(!filter_var($info_list['email'], FILTER_VALIDATE_EMAIL)) {
            $error_list['email'] = 'e-mail  введен некорректно!';
        }
        $sql = 'SELECT * FROM `users` WHERE `email` = "'.$info_list['email'].'"';
        $result = mysqli_query($link, $sql);
        if(mysqli_num_rows($result) > 0) {
            $error_list['email'] = 'Пользователь с таким e-mail уже существует!';
        }
    }

    return $error_list;
}

/**
 * Валидация формы для аутентификации пользователя
 * @param mysqli $link Соединение с БД
 * @param array $info_list Массив значений $_POST
 * @param array $required_fields Массив содержащий список обязательных для заполнения полей
 *
 * @return array $error_list Массив ошибок
 */
function do_validate_auth_form ($link, $info_list, $required_fields) {
    $info['error_list'] = [];

    foreach ($required_fields as $field) {
        if (empty($info_list[$field])) {
            $info['error_list'][$field] = 'Заполните поле!';
        }
    }

    $sql = 'SELECT * FROM `users` WHERE `email` = "' . $info_list['email'] . '"';
    $result = mysqli_query($link, $sql);

    $user = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : null;

    if (empty($info['error_list']['email']) && mysqli_num_rows($result) === 0) {
            $info['error_list']['email'] = 'Пользователя с таким e-mail не существует!';
    }

    if (!count($info['error_list']) && $user) {
        if (password_verify($info_list['password'], $user[0]['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $info['error_list']['password'] = 'Неверный пароль!';
        }
    }

    return $info;
}

/**
 * Обновление статуса задачи
 * @param mysqli $link Соединение с БД
 * @param array $info_status_list Массив значений $_GET
 *
 * @return boolean $result Статус задачи
 */
function update_task_status ($link, $info_status_list) {
    $info_status_list['check'] = intval($info_status_list['check']);
    $info_status_list['task_id'] = intval($info_status_list['task_id']);
    $sql = 'UPDATE `tasks` SET `status` = '.$info_status_list['check'].' WHERE id = '.$info_status_list['task_id'];
    $result = mysqli_query($link, $sql);

    if (!$result) {
        $result['error'];
    }

    return $result;
}

/**
 * Полнотекстовый поиск задач
 * @param mysqli $link Соединение с БД
 * @param string $search Массив значений $_POST
 *
 * @return array $tasks Задачи найденные при поиске
 */
function do_search_task($link, $search) {
	mysqli_query($link, 'CREATE FULLTEXT INDEX task_ft_search ON tasks(name)');
	$tasks = [];

	if ($search) {
		$sql = 'SELECT * FROM `tasks` WHERE MATCH(name) AGAINST("'.$search.'")';
		$tasks = get_info($link, $sql);
	}

	return $tasks;
}
