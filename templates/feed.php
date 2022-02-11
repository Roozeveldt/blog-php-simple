<main class="page__main page__main--feed">
    <div class="container">
        <h1 class="page__title page__title--feed">Моя лента</h1>
    </div>
    <div class="page__main-wrapper container">
        <section class="feed">
            <h2 class="visually-hidden">Лента</h2>
            <div class="feed__main-wrapper">
                <div class="feed__wrapper">
                    <?php if (is_array($posts) && !empty($posts)) : ?>
                        <?php foreach ($posts as $post) : ?>
                            <article class="feed__post post post-<?= $post['type']; ?>">
                                <header class="post__header post__author">
                                    <a class="post__author-link" href="profile.php?id=<?= $post['user_id']; ?>" title="Автор">
                                        <div class="post__avatar-wrapper">
                                            <img class="post__author-avatar" src="uploads/userpic/<?= $post['userpic']; ?>" alt="Аватар пользователя <?= $post['login']; ?>" width="60" height="60">
                                        </div>
                                        <div class="post__info">
                                            <b class="post__author-name"><?= $post['login']; ?></b>
                                            <span class="post__time"><?= getRelativePostDate($post['created_at']); ?></span>
                                        </div>
                                    </a>
                                </header>
                                <?php if ($post['is_reposted']) : ?>
                                    <div class="post__author" style="padding-top: 0;">
                                        <a class="post__author-link" href="profile.php?id=<?= $post['author_id']; ?>" title="Автор оригинального поста">
                                            <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                                                <img class="post__author-avatar" src="uploads/userpic/<?= $post['author_userpic']; ?>" alt="Аватар пользователя <?= $post['author_login']; ?>" width="60" height="60" />
                                            </div>
                                            <div class="post__info">
                                                <b class="post__author-name">Репост: <?= $post['author_login']; ?></b>
                                                <time class="post__time" datetime="<?= date("c", strtotime($post['updated_at'])); ?>"><?= getRelativePostDate($post['updated_at']); ?></time>
                                            </div>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <div class="post__main">

                                    <!-- пост-картинка -->
                                    <?php if ($post['type'] == 'photo') : ?>
                                        <h2><a href="post.php?id=<?= $post['id']; ?>"><?= $post['heading']; ?></a></h2>
                                        <div class="post-photo__image-wrapper">
                                            <img src="uploads/<?= $post['content']; ?>" alt="Фото от пользователя" width="760" height="396">
                                        </div>
                                    <?php endif; ?>

                                    <!-- пост-видео -->
                                    <?php if ($post['type'] == 'video') : ?>
                                        <div class="post-video__block">
                                            <div class="post-video__preview">
                                                <img src="<?= embed_youtube_cover($post['content']); ?>" alt="Превью к видео" width="760" height="396">
                                            </div>
                                            <div class="post-video__control">
                                                <button class="post-video__play post-video__play--paused button button--video" type="button"><span class="visually-hidden">Запустить видео</span></button>
                                                <div class="post-video__scale-wrapper">
                                                    <div class="post-video__scale">
                                                        <div class="post-video__bar">
                                                            <div class="post-video__toggle"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video" type="button"><span class="visually-hidden">Полноэкранный режим</span></button>
                                            </div>
                                            <button class="post-video__play-big button" type="button">
                                                <svg class="post-video__play-big-icon" width="27" height="28">
                                                    <use xlink:href="#icon-video-play-big"></use>
                                                </svg>
                                                <span class="visually-hidden">Запустить проигрыватель</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>

                                    <!-- пост-текст -->
                                    <?php if ($post['type'] == 'text') : ?>
                                        <h2><a href="post.php?id=<?= $post['id']; ?>"><?= $post['heading']; ?></a></h2>
                                        <?= sliceText($post['content'], $post['id']); ?>
                                    <?php endif; ?>

                                    <!-- пост-цитата -->
                                    <?php if ($post['type'] == 'quote') : ?>
                                        <blockquote>
                                            <p><?= $post['content']; ?></p>
                                            <cite><?= $post['quote_author']; ?></cite>
                                        </blockquote>
                                    <?php endif; ?>

                                    <!-- пост-ссылка -->
                                    <?php if ($post['type'] == 'link') : ?>
                                        <div class="post-link__wrapper">
                                            <a class="post-link__external" href="<?= $post['content']; ?>" title="Перейти по ссылке" target="_blank">
                                                <div class="post-link__icon-wrapper">
                                                    <img src="../img/logo-vita.jpg" alt="Иконка">
                                                </div>
                                                <div class="post-link__info">
                                                    <h3><?= $post['heading']; ?></h3>
                                                    <p>Переходи по ссылке</p>
                                                    <span><?= $post['content']; ?></span>
                                                </div>
                                                <svg class="post-link__arrow" width="11" height="16">
                                                    <use xlink:href="#icon-arrow-right-ad"></use>
                                                </svg>
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                </div>
                                <footer class="post__footer post__indicators">
                                    <div class="post__buttons">
                                        <a class="post__indicator post__indicator--likes button" href="<?= $url; ?>do=like&post_id=<?= $post['id']; ?>" title="Лайк">
                                            <svg class="post__indicator-icon" width="20" height="17">
                                                <use xlink:href="#icon-heart"></use>
                                            </svg>
                                            <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                                <use xlink:href="#icon-heart-active"></use>
                                            </svg>
                                            <span><?= $post['likes_count']; ?></span>
                                            <span class="visually-hidden">количество лайков</span>
                                        </a>
                                        <a class="post__indicator post__indicator--comments button" href="post.php?id=<?= $post['id']; ?>#add_comment" title="Комментарии">
                                            <svg class="post__indicator-icon" width="19" height="17">
                                                <use xlink:href="#icon-comment"></use>
                                            </svg>
                                            <span><?= $post['comments_count']; ?></span>
                                            <span class="visually-hidden">количество комментариев</span>
                                        </a>
                                        <a class="post__indicator post__indicator--repost button" href="<?= $url; ?>do=repost&post_id=<?= $post['id']; ?>" title="Репост">
                                            <svg class="post__indicator-icon" width="19" height="17">
                                                <use xlink:href="#icon-repost"></use>
                                            </svg>
                                            <span><?= $post['reposts_count']; ?></span>
                                            <span class="visually-hidden">количество репостов</span>
                                        </a>
                                    </div>
                                </footer>
                                <?php if (isset($post['tags'])) : ?>
                                    <ul class="post__tags">
                                        <?php foreach ($post['tags'] as $k => $v) : ?>
                                            <li><a href="search.php?id=<?= $k; ?>">#<?= $v; ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <ul class="feed__filters filters">
                <li class="feed__filters-item filters__item">
                    <a class="filters__button <?php if ($tab == '') : ?>filters__button--active<?php endif; ?>" href="/">
                        <span>Все</span>
                    </a>
                </li>
                <?php foreach ($types as $type) : ?>
                    <li class="feed__filters-item filters__item">
                        <a class="filters__button filters__button--<?= $type['type']; ?> <?php if ($tab == $type['type']) : ?>filters__button--active<?php endif; ?> button" href="<?= $url; ?>type=<?= $type['type']; ?>">
                            <span class="visually-hidden"><?= $type['name']; ?></span>
                            <svg class="filters__icon" width="24" height="24">
                                <use xlink:href="#icon-filter-<?= $type['type']; ?>"></use>
                            </svg>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <aside class="promo">
            <article class="promo__block promo__block--barbershop">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
                </p>
                <a class="promo__link" href="#">
                    Подробнее
                </a>
            </article>
            <article class="promo__block promo__block--technomart">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Товары будущего уже сегодня в онлайн-сторе Техномарт!
                </p>
                <a class="promo__link" href="#">
                    Перейти в магазин
                </a>
            </article>
            <article class="promo__block">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Здесь<br> могла быть<br> ваша реклама
                </p>
                <a class="promo__link" href="#">
                    Разместить
                </a>
            </article>
        </aside>
    </div>
</main>
