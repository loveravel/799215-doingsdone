<?php

require_once 'vendor/autoload.php';
require_once 'init.php';
require_once 'functions.php';

$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
$transport -> setUsername("keks@phpdemo.ru");
$transport -> setPassword("htmlacademy");

$mailer = new Swift_Mailer($transport);

$logger = new Swift_Plugins_Loggers_ArrayLogger();
$mailer -> registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

$result = mysqli_query($link, 'SELECT * FROM `users` INNER JOIN `tasks` ON `tasks`.`user_id` = `users`.`id` WHERE `deadline` >= CURRENT_DATE AND `deadline` <= date_add(CURRENT_DATE , INTERVAL 1 DAY)');

if ($result && mysqli_num_rows($result)) {
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
	print_r($tasks);
    $result = mysqli_query($link, 'SELECT * FROM `users`');

    if ($result && mysqli_num_rows($result)) {
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
		print_r($users);
        $recipients = [];
	    $recipients_name = [];

        foreach ($users as $key => $value) {
	        $recipients[$value['email']] = $value['name'];
	        $recipients_name[$key] = $value['name'];

	        $message = new Swift_Message();
	        $message -> setSubject('Уведомление от сервиса «Дела в порядке»');
	        $message -> setFrom(['keks@phpdemo.ru' => 'Keks']);
	        $message -> setBcc($recipients);

	        $message_content = include_template('message.php', ['recipients_name' => $recipients_name, 'tasks' => $tasks]);
	        $message -> setBody($message_content, 'text/html');

	        $result_send = $mailer -> send($message);
        }

        if ($result_send) {
            echo 'Рассылка прошла успешно';
        } else {
            echo 'Не удалось отправить рассылку: '.$logger -> dump();
        }
    }
}