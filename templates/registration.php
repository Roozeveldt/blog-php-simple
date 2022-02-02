<main class="page__main page__main--registration">
    <div class="container">
        <h1 class="page__title page__title--registration">Регистрация</h1>
    </div>
    <section class="registration container">
        <h2 class="visually-hidden">Форма регистрации</h2>
        <form class="registration__form form" action="" method="post" enctype="multipart/form-data">
            <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                    <div class="registration__input-wrapper form__input-wrapper">
                        <label class="registration__label form__label" for="registration-email">Электронная почта <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?= isset($errors['registration-email']) ? "form__input-section--error" : ''; ?>">
                            <input class="registration__input form__input" id="registration-email" type="text" name="registration-email" placeholder="Укажите эл.почту" value="<?= getPostVal('registration-email'); ?>">
                            <?php if (isset($errors['registration-email'])) : ?>
                                <?php $form_error = include_template('form-error.php', [
                                    'key' => 'registration-email',
                                    'errors' => $errors,
                                ]);
                                print($form_error); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="registration__input-wrapper form__input-wrapper">
                        <label class="registration__label form__label" for="registration-login">Логин <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?= isset($errors['registration-login']) ? "form__input-section--error" : ''; ?>">
                            <input class="registration__input form__input" id="registration-login" type="text" name="registration-login" placeholder="Укажите логин" value="<?= getPostVal('registration-login'); ?>">
                            <?php if (isset($errors['registration-login'])) : ?>
                                <?php $form_error = include_template('form-error.php', [
                                    'key' => 'registration-login',
                                    'errors' => $errors,
                                ]);
                                print($form_error); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="registration__input-wrapper form__input-wrapper">
                        <label class="registration__label form__label" for="registration-password">Пароль <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?= isset($errors['registration-password']) ? "form__input-section--error" : ''; ?>">
                            <input class="registration__input form__input" id="registration-password" type="password" name="registration-password" placeholder="Придумайте пароль" value="<?= getPostVal('registration-password'); ?>">
                            <?php if (isset($errors['registration-password'])) : ?>
                                <?php $form_error = include_template('form-error.php', [
                                    'key' => 'registration-password',
                                    'errors' => $errors,
                                ]);
                                print($form_error); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="registration__input-wrapper form__input-wrapper">
                        <label class="registration__label form__label" for="registration-password-repeat">Повтор пароля <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?= isset($errors['registration-password-repeat']) ? "form__input-section--error" : ''; ?>">
                            <input class="registration__input form__input" id="registration-password-repeat" type="password" name="registration-password-repeat" placeholder="Повторите пароль" value="<?= getPostVal('registration-password-repeat'); ?>">
                            <?php if (isset($errors['registration-password-repeat'])) : ?>
                                <?php $form_error = include_template('form-error.php', [
                                    'key' => 'registration-password-repeat',
                                    'errors' => $errors,
                                ]);
                                print($form_error); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if (count($errors)) : ?>
                    <div class="form__invalid-block">
                        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                        <ul class="form__invalid-list">
                            <?php foreach ($errors as $error) : ?>
                                <li class="form__invalid-item"><?= implode(". ", $error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <label for="userpic-file"></label>
                <input type="file" id="userpic-file" name="userpic-file">
                <br><br><br>
            </div>
            <!-- <div class="registration__input-file-container form__input-container form__input-container--file">
                <div class="registration__input-file-wrapper form__input-file-wrapper">
                    <div class="registration__file-zone form__file-zone dropzone">
                        <input class="registration__input-file form__input-file" id="userpic-file" type="file" name="userpic-file" title=" ">
                        <div class="form__file-zone-text">
                            <span>Перетащите фото сюда</span>
                        </div>
                    </div>
                    <button class="registration__input-file-button form__input-file-button button" type="button">
                        <span>Выбрать фото</span>
                            <svg class="registration__attach-icon form__attach-icon" width="10" height="20">
                                <use xlink:href="#icon-attach"></use>
                            </svg>
                    </button>
                </div>
                <div class="registration__file form__file dropzone-previews">

                </div>
            </div> -->
            <button class="registration__submit button button--main" type="submit">Отправить</button>
        </form>
    </section>
</main>
