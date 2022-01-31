<main class="page__main page__main--adding-post">
    <div class="page__main-section">
        <div class="container">
            <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
            <div class="adding-post__tabs-wrapper tabs">
                <div class="adding-post__tabs filters">
                    <ul class="adding-post__tabs-list filters__list tabs__list">
                        <?php foreach ($types as $item) : ?>
                        <li class="adding-post__tabs-item filters__item">
                            <?php
                                $active = (empty($getPostVal) && $item['type'] == $type) || (!empty($getPostVal) && ($getPostVal['type'] == $item['type']))
                                    ? 'filters__button--active'
                                    : '';
                            ?>
                            <a class="adding-post__tabs-link filters__button filters__button--<?= $item['type']; ?> tabs__item button <?= $active; ?> tabs__item--active" href="#">
                                <svg class="filters__icon" width="24" height="24">
                                    <use xlink:href="#icon-filter-<?= $item['type']; ?>"></use>
                                </svg>
                                <span><?= $item['name']; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="adding-post__tab-content">
                    <?php foreach ($types as $item) : ?>
                        <section class="adding-post__photo tabs__content <?php if ($type == $item['type']) : ?>tabs__content--active<?php endif; ?>">
                            <h2 class="visually-hidden">Форма добавления <?= $item['name']; ?></h2>
                            <form class="adding-post__form form" action="add.php" method="post" enctype="multipart/form-data">
                                <div class="form__text-inputs-wrapper">
                                    <div class="form__text-inputs">
                                        <!-- заголовок поста -->
                                        <div class="adding-post__input-wrapper form__input-wrapper">
                                            <?php $heading = $item['type'] . '-heading'; ?>
                                            <label class="adding-post__label form__label" for="<?= $heading; ?>">Заголовок <span class="form__input-required">*</span></label>
                                            <div class="form__input-section <?= isset($errors[$heading]) ? "form__input-section--error" : ''; ?>">
                                                <input class="adding-post__input form__input" id="<?= $heading; ?>" type="text" name="<?= $heading; ?>" placeholder="Введите заголовок" value="<?= getPostVal($heading); ?>">
                                                <?php if (isset($errors[$heading])) : ?>
                                                    <?php $form_error = include_template('form-error.php', [
                                                        'key' => $heading,
                                                        'errors' => $errors,
                                                    ]);
                                                    print($form_error); ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- фото -->
                                        <?php if ($item['type'] == 'photo') : ?>
                                            <div class="adding-post__input-wrapper form__input-wrapper">
                                                <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета <span class="form__input-required">*</span></label>
                                                <div class="form__input-section <?= isset($errors['photo-url']) ? "form__input-section--error" : ""; ?>">
                                                    <input class="adding-post__input form__input" id="photo-url" type="text" name="photo-url" placeholder="Введите ссылку" value="<?= getPostVal('photo-url'); ?>">
                                                    <?php if (isset($errors['photo-url'])) : ?>
                                                        <?php $form_error = include_template('form-error.php', [
                                                            'key' => 'photo-url',
                                                            'errors' => $errors,
                                                        ]);
                                                        print($form_error); ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- видео -->
                                        <?php if ($item['type'] == 'video') : ?>
                                            <div class="adding-post__input-wrapper form__input-wrapper">
                                                <label class="adding-post__label form__label" for="video-url">Ссылка youtube <span class="form__input-required">*</span></label>
                                                <div class="form__input-section <?= isset($errors['video-url']) ? "form__input-section--error" : ''; ?>">
                                                    <input class="adding-post__input form__input" id="video-url" type="text" name="video-url" placeholder="Введите ссылку" value="<?= getPostVal('video-url'); ?>">
                                                    <?php if (isset($errors['video-url'])) : ?>
                                                        <?php $form_error = include_template('form-error.php', [
                                                            'key' => 'video-url',
                                                            'errors' => $errors,
                                                        ]);
                                                        print($form_error); ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- текст -->
                                        <?php if ($item['type'] == 'text') : ?>
                                            <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                                                <label class="adding-post__label form__label" for="text-content">Текст поста <span class="form__input-required">*</span></label>
                                                <div class="form__input-section <?= isset($errors['text-content']) ? "form__input-section--error" : ''; ?>">
                                                    <textarea class="adding-post__textarea form__textarea form__input" id="text-content" name="text-content" placeholder="Введите текст публикации"><?= getPostVal('text-content'); ?></textarea>
                                                    <?php if (isset($errors['text-content'])) : ?>
                                                        <?php $form_error = include_template('form-error.php', [
                                                            'key' => 'text-content',
                                                            'errors' => $errors,
                                                        ]);
                                                        print($form_error); ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- цитата -->
                                        <?php if ($item['type'] == 'quote') : ?>
                                            <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                                                <label class="adding-post__label form__label" for="quote-content">Текст цитаты <span class="form__input-required">*</span></label>
                                                <div class="form__input-section <?= isset($errors['quote-content']) ? "form__input-section--error" : ''; ?>">
                                                    <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input" id="quote-content" name="quote-content" placeholder="Текст цитаты"><?= getPostVal('quote-content'); ?></textarea>
                                                    <?php if (isset($errors['quote-content'])) : ?>
                                                        <?php $form_error = include_template('form-error.php', [
                                                            'key' => 'quote-content',
                                                            'errors' => $errors,
                                                        ]);
                                                        print($form_error); ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="adding-post__input-wrapper form__input-wrapper">
                                                <label class="adding-post__label form__label" for="quote-author">Автор <span class="form__input-required">*</span></label>
                                                <div class="form__input-section <?= isset($errors['quote-author']) ? "form__input-section--error" : ''; ?>">
                                                    <input class="adding-post__input form__input" id="quote-author" type="text" name="quote-author" placeholder="Автор цитаты" value="<?= getPostVal('quote-author'); ?>">
                                                    <?php if (isset($errors['quote-author'])) : ?>
                                                        <?php $form_error = include_template('form-error.php', [
                                                            'key' => 'quote-author',
                                                            'errors' => $errors,
                                                        ]);
                                                        print($form_error); ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- ссылка -->
                                        <?php if ($item['type'] == 'link') : ?>
                                            <div class="adding-post__input-wrapper form__input-wrapper">
                                                <label class="adding-post__label form__label" for="link-url">Ссылка <span class="form__input-required">*</span></label>
                                                <div class="form__input-section <?= isset($errors['link-url']) ? "form__input-section--error" : ''; ?>">
                                                    <input class="adding-post__input form__input" id="link-url" type="text" name="link-url" placeholder="Введите ссылку" value="<?= getPostVal('link-url'); ?>">
                                                    <?php if (isset($errors['link-url'])) : ?>
                                                        <?php $form_error = include_template('form-error.php', [
                                                            'key' => 'link-url',
                                                            'errors' => $errors,
                                                        ]);
                                                        print($form_error); ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- хэштэги -->
                                        <div class="adding-post__input-wrapper form__input-wrapper">
                                            <?php $tags = $item['type'] . '-tags'; ?>
                                            <label class="adding-post__label form__label" for="<?= $tags; ?>">Теги <span class="form__input-required">*</span></label>
                                            <div class="form__input-section <?= isset($errors[$tags]) ? "form__input-section--error" : ''; ?>">
                                                <input class="adding-post__input form__input" id="<?= $tags; ?>" type="text" name="<?= $tags; ?>" placeholder="Введите теги" value="<?= getPostVal($tags); ?>">
                                                <?php if (isset($errors[$tags])) : ?>
                                                    <?php $form_error = include_template('form-error.php', [
                                                        'key' => $tags,
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
                                <?php if ($item['type'] == 'photo') : ?>
                                    <div>
                                        <label for="photo-file"></label>
                                        <input type="file" id="photo-file" name="photo-file">
                                        <br><br><br>
                                    </div>
                                    <!-- <div class="adding-post__input-file-container form__input-container form__input-container--file">
                                        <div class="adding-post__input-file-wrapper form__input-file-wrapper">
                                            <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                                                <input class="adding-post__input-file form__input-file" id="photo-file" type="file" name="photo-file" title=" ">
                                                <div class="form__file-zone-text">
                                                    <span>Перетащите фото сюда</span>
                                                </div>
                                            </div>
                                            <button class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button" type="button">
                                                <span>Выбрать фото</span>
                                                <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
                                                    <use xlink:href="#icon-attach"></use>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">
                                        </div>
                                    </div> -->
                                <?php endif; ?>
                                <div class="adding-post__buttons">
                                    <input type="hidden" name="type" value="<?= $item['type']; ?>">
                                    <input type="hidden" name="type_id" value="<?= $item['id']; ?>">
                                    <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                                    <a class="adding-post__close" href="#">Закрыть</a>
                                </div>
                            </form>
                        </section>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>
