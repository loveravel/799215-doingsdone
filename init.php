<?php
require_once 'functions.php';
$db = require_once 'config/db.php';

/*$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);*/
$link = mysqli_connect("localhost", "root", "", "doingsdone");
mysqli_set_charset($link, "utf8");

$projects = [];
$tasks = [];
$page_content = '';