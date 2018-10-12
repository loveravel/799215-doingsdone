<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
	<input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

	<input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
	<nav class="tasks-switch">
		<a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
		<a href="/?show_tasks=for_today" class="tasks-switch__item">Повестка дня</a>
		<a href="/?show_tasks=for_tomorrow" class="tasks-switch__item">Завтра</a>
		<a href="/?show_tasks=overdue" class="tasks-switch__item">Просроченные</a>
	</nav>

	<label class="checkbox">
		<input class="checkbox__input visually-hidden show_completed" <?=($show_complete_tasks) ? 'checked' : '' ?> type="checkbox">
		<span class="checkbox__text">Показывать выполненные</span>
	</label>
</div>

<table class="tasks">
	<?php foreach ($tasks as $key => $value): ?>
		<?php
		if(!$show_complete_tasks && $value['status']) {
			continue;
		}
		?>
		<tr class="tasks__item task
			<?=($value['status']) ? 'task--completed' : '';?>
			<?=(important_task($value)) ? 'task--important' : '';?>"
		>
			<td class="task__select">
				<label class="checkbox task__checkbox">
					<input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1" <?=($value['status']) ? 'checked="checked"' : '' ; ?>>
					<span class="checkbox__text"><?=$value['name']?></span>
				</label>
			</td>

			<td class="task__file">
                <?php if(!empty($value['file_path'])) : ?>
				    <a class="download-link" href="<?= $value['file_path']?>"><?= $value['file_name']; ?></a>
                <? endif; ?>
			</td>

			<td class="task__date"><?=$value['deadline'];?></td>
		</tr>
	<?php endforeach;?>
</table>