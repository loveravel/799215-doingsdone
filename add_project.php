<?php

require_once 'init.php';

if (!isset($_SESSION['user'])) {
    header("Location: /guest.php");
    exit();
}

if (!$link) {
    $error['error_connect'] = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
    $layout_content = include_template('error.php', [
        'title' => 'Дела в порядке',
        'content' => $content
    ]);
} else {

    $info_list = [];
    $error_list = [];

    // Запрос для получения данных о пользователе по id
    $sql = 'SELECT * FROM `users` WHERE `id` = '.$_SESSION['user'][0]['id'];
    $user = get_info($link, $sql, $_SESSION['user'][0]['id']);
    $username = $user[0]['name'];

    // Запрос для получения проектов у текущего пользователя
    $sql = 'SELECT * FROM `projects` WHERE `user_id` = '.$_SESSION['user'][0]['id'];
    $projects = get_info($link, $sql, $_SESSION['user'][0]['id']);

    // Запрос для получения списка всех задач
    $sql = 'SELECT * FROM `tasks` WHERE `user_id` = '.$_SESSION['user'][0]['id'];
    $all_tasks = get_info($link, $sql, $_SESSION['user'][0]['id']);

    // Обработка данных из формы
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $info_list = $_POST;

        foreach ($info_list as $value) {
            $value = mysqli_real_escape_string($link, $value);
        }

        $required_list = ['name'];
        $error_list = [];

        // Валидация формы
        $error_list = do_validate_project_form($info_list, $required_list);
        $error_list += do_validate_date($info_list);

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