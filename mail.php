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

$result = mysqli_query($link,
	'SELECT `user_id`, `email`, `users`.`name` AS `username`, t.name AS task_name, DATE_FORMAT(`deadline`, "%d.%m.%Y") AS `deadline`
FROM `tasks` AS t 
INNER JOIN `users` ON `users`.`id` = t.user_id
WHERE `deadline` >= CURRENT_DATE
AND `deadline` <= date_add(CURRENT_DATE , INTERVAL 1 DAY)
AND `status` = 0');

if ($result && mysqli_num_rows($result)) {
	$tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

	$result = mysqli_query($link, 'SELECT * FROM `users`');

	if ($result && mysqli_num_rows($result)) {
		$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

		$recipients = [];
		$users = [];
		$user_tasks = [];

		foreach ($tasks as $value) {
			$users[$value['user_id']][] = [
				'username' => $value['username'],
				'email' => $value['email'],
				'task_name' => $value['task_name'],
				'deadline' => $value['deadline']
			];
		}
		print_r($tasks);
		foreach ($users as $key => $value) {
			$recipients = $value[0]['email'];
			$username = $value[0]['username'];
			$user_tasks = $value;

			$message = new Swift_Message();
			$message -> setSubject('Уведомление от сервиса «Дела в порядке»');
			$message -> setFrom(['keks@phpdemo.ru' => 'Keks']);
			$message -> setBcc($recipients);

			$message_content = include_template('message.php', ['username' => $username, 'tasks' => $tasks]);
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