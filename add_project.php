<?php

require_once 'init.php';

if (!isset($_SESSION['user'])) {
    header('Location: /guest.php');
    exit();
}

$layout_content = check_connect($link, $layout_content);
if (empty($layout_content)) {
    // Запрос для получения данных о пользователе по id
    $sql = 'SELECT * FROM `users` WHERE `id` = '.$_SESSION['user']['id'];
    $user = get_info($link, $sql, $_SESSION['user'][0]['id']);
    $username = $user[0]['name'];

    // Запрос для получения проектов у текущего пользователя
    $sql = 'SELECT * FROM `projects` WHERE `user_id` = '.$_SESSION['user']['id'];
    $projects = get_info($link, $sql, $_SESSION['user']['id']);

    // Запрос для получения списка всех задач
    $sql = 'SELECT * FROM `tasks` WHERE `user_id` = '.$_SESSION['user']['id'];
    $all_tasks = get_info($link, $sql, $_SESSION['user']['id']);

    // Обработка данных из формы
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $info_list = $_POST;
        $info_list['name'] = mysqli_real_escape_string($link, $info_list['name']);

        $required_list = ['name'];

        // Валидация формы
        $error_list = do_validate_required_fields($info_list, $required_list);

        // Добавление задачи в БД
        if(empty($error_list)) {
            $sql = 'INSERT INTO `projects` SET `name` = "'.$info_list['name'].'", `user_id` = '.$_SESSION['user'][0]['id'];
            $result = mysqli_query($link, $sql);

            if ($result) {
                header("Location: /");
            } else {
                $content = include_template('error.php', ['error' => mysqli_error($link)]);
            }
        }
    }

    $content = include_template('add_project.php', [
        'projects' => $projects,
        'info_list' => $info_list,
        'error_list' => $error_list
    ]);

    $layout_content = include_template('layout.php', [
        'title' => 'Дела в порядке',
        'projects' => $projects,
        'all_tasks' => $all_tasks,
        'content' => $content
    ]);
}

echo $layout_content;