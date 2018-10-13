<?php

require_once 'init.php';

if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}

header('Location: /guest.php');