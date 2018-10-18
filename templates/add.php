<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form"  action="add.php" method="post" enctype="multipart/form-data">
	<div class="form__row">
		<label class="form__label" for="name">Название <sup>*</sup></label>

		<input class="form__input <?= isset($error_list['name']) ? 'form__input--error' : ''; ?>" type="text" name="name" id="name" 
		value="<?= isset($info_list['name']) ? htmlspecialchars($info_list['name']) : ''; ?>" placeholder="Введите название">
		<p class="form__message">
			<span class="form__message error-message"><?= isset($error_list['name']) ? $error_list['name'] : ''; ?></span>
		</p>
	</div>

	<div class="form__row">
		<label class="form__label" for="project">Проект <sup>*</sup></label>

		<select class="form__input form__input--select" name="project" id="project">
			<?php foreach ($projects as $value):?>
			<option
				value="<?= $value['id']; ?>"
				<?= (isset($info_list['project']) && $info_list['project'] === $value['id']) ? 'selected' : ''; ?>
			>
				<?= $value['name'];?>
			</option>
			<?php endforeach;?>
		</select>
        <p class="form__message">
            <span class="form__message error-message"><?= isset($error_list['project']) ? $error_list['project'] : ''; ?></span>
        </p>
	</div>

	<div class="form__row">
		<label class="form__label" for="deadline">Дата выполнения</label>

        <input class="form__input form__input--date <?= isset($error_list['deadline']) ? 'form__input--error' : ''; ?>" type="date" name="deadline" id="deadline" value="<?= isset($info_list['deadline']) ? htmlspecialchars($info_list['deadline']) : ''; ?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
        <p class="form__message">
            <span class="form__message error-message"><?= isset($error_list['deadline']) ? $error_list['deadline'] : ''; ?></span>
        </p>
	</div>

	<div class="form__row">
		<label class="form__label" for="preview">Файл</label>

		<div class="form__input-file">
			<input class="visually-hidden" type="file" name="preview" id="preview" value="<?= isset($info_list['preview']) ? htmlspecialchars($info_list['preview']) : ''; ?>">

			<label class="button button--transparent" for="preview">
				<span>Выберите файл</span>
			</label>
		</div>
	</div>

	<div class="form__row form__row--controls">

        <?php if($error_list): ?>
            <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
        <?php endif; ?>

		<input class="button" type="submit" name="" value="Добавить">
	</div>
</form>