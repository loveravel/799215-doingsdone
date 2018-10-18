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
	'SELECT *, `users`.`name` AS `username`, t.name AS task_name
FROM `tasks` AS t 
INNER JOIN `users` ON `users`.`id` = t.user_id
WHERE `deadline` >= CURRENT_DATE
AND `deadline` <= date_add(CURRENT_DATE , INTERVAL 1 DAY)
AND `status` = 0');

if ($result && mysqli_num_rows($result)) {
	$tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

	$recipients = [];
	$users = [];

	foreach ($tasks as $value) {
		$users[$value['user_id']][] = [
			'id' => $value['id'],
			'username' => $value['username'],
			'email' => $value['email'],
			'task_name' => $value['task_name'],
			'deadline' => $value['deadline']
		];
	}
	foreach ($users as $key => $value) {
		$username = $value[0]['username'];

		$message = new Swift_Message();
		$message->setSubject('Уведомление от сервиса «Дела в порядке»');
		$message->setFrom(['keks@phpdemo.ru' => 'Keks']);
		$message->setTo([$value[0]['email']]);

		$sql = '
			SELECT *, DATE_FORMAT(`deadline`, "%d.%m.%Y") AS `deadline`
			FROM `tasks`
			WHERE `deadline` >= CURRENT_DATE
			AND `deadline` <= date_add(CURRENT_DATE , INTERVAL 1 DAY)
			AND `status` = 0
			AND `user_id` = '.$value[0]['id'];
		$user_tasks = get_info($link, $sql);

		$message_content = include_template('message.php', ['username' => $username, 'user_tasks' => $user_tasks]);
		$message->setBody($message_content, 'text/html');

		$result_send = $mailer->send($message);
		var_dump($message_content);
	}
}

if ($result_send) {
	echo 'Рассылка прошла успешно';
} else {
	echo 'Не удалось отправить рассылку: ' . $logger->dump();
}