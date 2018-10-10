<?php
require_once 'mysql_helper.php';
require_once 'functions.php';

$link = mysqli_connect("localhost", "root", "", "doingsdone");

mysqli_set_charset($link, "utf8");

$projects = [];
$tasks = [];