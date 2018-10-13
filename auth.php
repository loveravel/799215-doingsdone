<?php

require_once 'init.php';

if(isset($_SESSION['user'])) {
    header("Location: /");
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
    $info['error_list'] = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $info_list = $_POST;

        foreach ($info_list as $value) {
            $value = mysqli_real_escape_string($link, $value);
        }

        $required_list = ['email','password'];

        // Валидация формы
        $info = do_validate_auth_form($link, $info_list, $required_list);

        $error_list = $info['error_list'];

        if (empty($info['error_list'])) {
            header("Location: /");
            exit();
        }
    }

    $content = include_template('auth.php', [
        'info_list' => $info_list,
        'error_list' => $error_list
    ]);

    $layout_content = include_template('layout.php', [
        'title' => 'Дела в порядке',
        'content' => $content
    ]);
}

echo $layout_content;