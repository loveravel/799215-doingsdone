<?php

session_start();

require_once 'mysql_helper.php';
require_once 'functions.php';

$link = mysqli_connect("localhost", "root", "", "doingsdone");

mysqli_set_charset($link, "utf8");

$result = [];

$projects = [];
$tasks = [];

$info_list = [];
$error_list = [];

$show_complete_tasks = NULL;
