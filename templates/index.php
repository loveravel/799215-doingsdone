<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
        <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
        <input class="checkbox__input visually-hidden show_completed"
               <?php if ($show_complete_tasks): ?>checked<?php endif; ?>
               type="checkbox">
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <?php foreach ($tasks as $key => $value): ?>
        <?php
        if(!$show_complete_tasks && $value['fulfilled'] == 'Да') {
            continue;
        }
        ?>
        <tr class="tasks__item task <?=($value['fulfilled'] == 'Да') ? 'task--completed' : '';?><?=(floor(((strtotime($value['date']) - time()) / $secs_in_hour) < 24) && ($value['date'] !== 'Нет')) ? 'task--important' : '';?>">
            <td class="task__select">
                <label class="checkbox task__checkbox">
                    <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1" <?=($value['fulfilled'] == 'Да') ? 'checked="checked"' : '' ; ?>>
                    <span class="checkbox__text"><?=$value['name']?></span>
                </label>
            </td>

            <td class="task__file">
                <a class="download-link" href="#">Home.psd</a>
            </td>

            <td class="task__date"><?=$value['date']?></td>
        </tr>
    <?php endforeach;?>
</table>