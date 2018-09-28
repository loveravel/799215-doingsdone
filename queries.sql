--
-- Дамп данных таблицы `projects`
--

INSERT INTO `projects` (`id`, `name`, `user_id`) VALUES
(1, 'Входящие', 1),
(2, 'Учёба', 1),
(3, 'Работа', 1),
(4, 'Домашние дела', 1),
(5, 'Авто', 1);

-- --------------------------------------------------------


--
-- Дамп данных таблицы `tasks`
--

INSERT INTO `tasks` (`id`, `project_id`, `user_id`, `date_add`, `status`, `name`, `file_path`, `deadline`) VALUES
(1, 3, 1, '2018-09-26 21:05:16', 1, 'New name', NULL, '2000-09-24'),
(2, 3, 1, '2018-09-26 21:03:55', 0, 'Выполнить тестовое задание', NULL, '2000-09-21'),
(3, 2, 1, '2018-09-26 21:03:55', 0, 'Сделать задание первого раздела', NULL, '2000-09-21'),
(4, 1, 1, '2018-09-26 21:03:55', 0, 'Встреча с другом', NULL, '2018-09-30'),
(5, 4, 1, '2018-09-26 21:03:55', 0, 'Купить корм для кота', NULL, NULL),
(6, 4, 1, '2018-09-26 21:03:55', 0, 'Заказать пиццу', NULL, NULL);

-- --------------------------------------------------------
--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `date_add`, `email`, `name`, `password`, `contacts`) VALUES
(1, '2018-09-26 20:40:56', 'ignat.v@gmail.com', 'Игнат', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka', '88005553535'),
(2, '2018-09-26 20:43:08', 'kitty_93@li.ru', 'Леночка', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa', '89504545123');

-- --------------------------------------------------------
--
-- получить список из всех проектов для одного пользователя;
--

SELECT `users`.`name`, `projects`.`name` FROM `users` INNER JOIN `projects` ON `users`.`id` = `projects`.`user_id` WHERE `users`.`id` = 1;

-- --------------------------------------------------------
--
-- получить список из всех задач для одного проекта;
--

SELECT `projects`.`name`, `tasks`.`name` FROM `projects` INNER JOIN `tasks` ON `projects`.`id` = `tasks`.`project_id` WHERE `projects`.`id` = 1;

-- --------------------------------------------------------
--
-- пометить задачу как выполненную;
--

UPDATE `tasks` SET `status`  = 1 WHERE id = 1;

-- --------------------------------------------------------
--
-- получить все задачи для завтрашнего дня;
--

SELECT `name`, `deadline` FROM `tasks` WHERE `deadline` >= date_add(CURRENT_DATE, INTERVAL 1 day) AND `deadline` < date_add(CURRENT_DATE, INTERVAL 2 day);

-- --------------------------------------------------------
--
-- обновить название задачи по её идентификатору.
--

UPDATE `tasks` SET `name` = "New name" WHERE id = 1;