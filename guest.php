<?php

require_once 'init.php';

if (isset($_SESSION['user'])) {
    header('Location: /');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $info_list = $_POST;

    foreach ($info_list as $value) {
        $value = mysqli_real_escape_string($link, $value);
    }

    $required_list = ['email','password'];

    // Валидация формы
    $info = do_validate_auth_form($link, $info_list, $required_list);

    $error_list = $info['error_list'];

    if (empty($info['error_list'])) {
        header('Location: /');
    }
}

$content = include_template('guest.php', []);

$layout_content = include_template('layout.php', [
    'title' => 'Дела в порядке',
    'content' => $content
]);

echo $layout_content;