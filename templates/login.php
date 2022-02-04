<main class="page__main page__main--login">
    <div class="container">
        <h1 class="page__title page__title--login">Вход</h1>
    </div>
    <section class="login container">
        <h2 class="visually-hidden">Форма авторизации</h2>
        <form class="login__form form" action="" method="post">
            <div class="login__input-wrapper form__input-wrapper">
                <label class="login__label form__label" for="login-email">Электронная почта <span class="form__input-required">*</span></label>
                <div class="form__input-section <?= isset($errors['email']) ? "form__input-section--error" : ''; ?>">
                    <input class="login__input form__input" id="login-email" type="text" name="email" placeholder="Укажите эл.почту" value="<?= getPostVal('email'); ?>">
                    <?php if (isset($errors['email'])) : ?>
                        <?php $form_error = include_template('form-error.php', [
                            'key' => 'email',
                            'errors' => $errors,
                        ]);
                        print($form_error); ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="login__input-wrapper form__input-wrapper">
                <label class="login__label form__label" for="login-password">Пароль <span class="form__input-required">*</span></label>
                <div class="form__input-section <?= isset($errors['password']) ? "form__input-section--error" : ''; ?>">
                    <input class="login__input form__input" id="login-password" type="password" name="password" placeholder="Введите пароль" value="<?= getPostVal('password'); ?>">
                    <?php if (isset($errors['password'])) : ?>
                        <?php $form_error = include_template('form-error.php', [
                            'key' => 'password',
                            'errors' => $errors,
                        ]);
                        print($form_error); ?>
                    <?php endif; ?>
                </div>
            </div>
            <button class="login__submit button button--main" type="submit">Войти</button>
        </form>
    </section>
</main>
