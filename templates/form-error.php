<button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
<div class="form__error-text">
    <h3 class="form__error-title"><?= $errors[$key] ? $errors[$key][0] : ""; ?></h3>
    <p class="form__error-desc"><?= $errors[$key] ? $errors[$key][1] : ""; ?></p>
</div>
