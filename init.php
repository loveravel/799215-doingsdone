<?php

session_start();

require_once 'mysql_helper.php';
require_once 'functions.php';

$link = mysqli_connect("localhost", "root", "", "doingsdone");

if (!$link) {
    $error['error_connect'] = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
    $layout_content = include_template('error.php', [
        'title' => 'Дела в порядке',
        'content' => $content
    ]);
} else {
    mysqli_set_charset($link, "utf8");
}


$content = [];
$layout_content = [];
$result = [];
$projects = [];
$tasks = [];
$tasks_search = [];
$info_list = [];
$error_list = [];
$show_complete_tasks = NULL;
