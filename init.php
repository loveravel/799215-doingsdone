<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

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

$content = "";
$layout_content = "";

$result = [];

$projects = [];
$project_id = "";

$tasks = [];

$search = NULL;

$info_list = [];
$error_list = [];

$show_complete_tasks = NULL;

$result_send = [];
