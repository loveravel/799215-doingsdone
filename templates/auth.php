<form class="form" action="index.html" method="post">
    <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>

        <input class="form__input <?= isset($error_list['email']) ? 'form__input--error' : ''; ?>" type="text" name="email" id="email"
               value="<?= isset($info_list['email']) ? $info_list['email'] : ''; ?>" placeholder="Введите e-mail">

        <p class="form__message">
            <?= isset($error_list['email']) ? $error_list['email'] : ''; ?>
        </p>

    </div>

    <div class="form__row">
        <label class="form__label" for="password">Пароль <sup>*</sup></label>

        <input class="form__input <?= isset($error_list['password']) ? 'form__input--error' : ''; ?>" type="password" name="password" id="password" value="" placeholder="Введите пароль">
    </div>

    <div class="form__row form__row--controls">

        <?php if($error_list): ?>
            <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
        <? endif; ?>

        <input class="button" type="submit" name="" value="Войти">
    </div>
</form>