<main class="page__main page__main--search-results">
    <h1 class="visually-hidden"><?= $title; ?></h1>
    <section class="search">
        <h2 class="visually-hidden">Результаты поиска</h2>
        <div class="search__query-wrapper">
            <div class="search__query container">
                <span>Вы искали:</span>
                <span class="search__query-text"><?= $search_query; ?></span>
            </div>
        </div>
        <div class="search__results-wrapper">
            <?php if (is_array($posts) && !empty($posts)) : ?>
            <div class="container">
                <div class="search__content">
                    <?php foreach ($posts as $post) : ?>
                        <article class="search__post post post-<?= $post['type']; ?>">
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
                                    <a class="post__indicator post__indicator--likes button" href="?do=like&post_id=<?= $post['id']; ?>" title="Лайк">
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
                                </div>
                            </footer>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else : ?>
                <div class="search__no-results container">
                    <p class="search__no-results-info">
                        К сожалению, ничего не найдено.
                    </p>
                    <p class="search__no-results-desc">
                        Попробуйте изменить поисковый запрос или просто зайти в раздел
                        &laquo;Популярное&raquo;, там живет самый крутой контент.
                    </p>
                    <div class="search__links">
                        <a class="search__popular-link button button--main" href="popular.php">Популярное</a>
                        <a class="search__back-link" href="javascript:history.back()">Вернуться назад</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
