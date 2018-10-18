<h2 class="content__main-heading">Регистрация аккаунта</h2>

<form class="form" action="register.php" method="post">
    <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>

        <input class="form__input <?= isset($error_list['email']) ? 'form__input--error' : ''; ?>" type="text" name="email" id="email"
               value="<?= isset($info_list['email']) ? htmlspecialchars($info_list['email']) : ''; ?>" placeholder="Введите e-mail">

        <p class="form__message">
            <?= isset($error_list['email']) ? $error_list['email'] : ''; ?>
        </p>
    </div>

    <div class="form__row">
        <label class="form__label" for="password">Пароль <sup>*</sup></label>

        <input class="form__input <?= isset($error_list['password']) ? 'form__input--error' : ''; ?>" type="password" name="password" id="password"
               value="<?= isset($info_list['password']) ? htmlspecialchars($info_list['password']) : ''; ?>" placeholder="Введите пароль">

        <p class="form__message">
            <?= isset($error_list['password']) ? $error_list['password'] : ''; ?>
        </p>
    </div>

    <div class="form__row">
        <label class="form__label" for="name">Имя <sup>*</sup></label>

        <input class="form__input <?= isset($error_list['name']) ? 'form__input--error' : ''; ?>" type="text" name="name" id="name"
               value="<?= isset($info_list['name']) ? htmlspecialchars($info_list['name']) : ''; ?>" placeholder="Введите имя">

        <p class="form__message">
            <?= isset($error_list['name']) ? $error_list['name'] : ''; ?>
        </p>
    </div>

    <div class="form__row form__row--controls">

        <?php if($error_list): ?>
            <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
        <?php endif; ?>

        <input class="button" type="submit" name="" value="Зарегистрироваться">
    </div>
</form>