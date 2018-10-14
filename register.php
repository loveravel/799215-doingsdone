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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $info_list = $_POST;

        foreach ($info_list as $value) {
            $value = mysqli_real_escape_string($link, $value);
        }

        $required_list = ['email','password','name'];
        $error_list = [];

        // Валидация формы
        $error_list = do_validate_register_form($link, $info_list, $required_list);

        // Добавление задачи в БД
        if(empty($error_list)) {
            $password = password_hash($info_list['password'], PASSWORD_DEFAULT);

            $sql = 'INSERT INTO `users` SET `email` = "'.$info_list['email'].'", `name` = "'.$info_list['name'].'", `password` = "'.$password.'"';
            $result = mysqli_query($link, $sql);

            if ($result) {
                header('Location: /auth.php');
            } else {
                $content = include_template('error.php', ['error' => mysqli_error($link)]);
            }
        }
    }

    $content = include_template('register.php', [
        'info_list' => $info_list,
        'error_list' => $error_list
    ]);

    $layout_content = include_template('layout.php', [
        'title' => 'Дела в порядке',
        'content' => $content
    ]);
}

echo $layout_content;