<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>

<p>Уважаемый, <?= $username; ?>. На <?= $user_tasks[0]['deadline']; ?> у вас запланированы задачи: </p>

<table>
    <tr>
        <th>Номер</th>
        <th>Название задачи</th>
    </tr>
    <tbody>
	<?php foreach ($user_tasks as $key => $value): ?>
        <tr>
            <td><?= $key+1; ?></td>
            <td><?= $value['name']; ?></td>
        </tr>
	<?php endforeach; ?>
    </tbody>
</table>

</body>
</html>