<section class="page__main page__main--popular">
    <div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                <ul class="popular__sorting-list sorting__list">
                    <li class="sorting__item sorting__item--popular">
                        <a class="sorting__link sorting__link--active" href="#">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Дата</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип контента:</b>
                <ul class="popular__filters-list filters__list">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <a class="filters__button filters__button--ellipse filters__button--all <?php if ($type_id == 0) : ?>filters__button--active<?php endif; ?>" href="/">
                            <span>Все</span>
                        </a>
                    </li>
                    <?php foreach($types as $type) : ?>
                    <li class="popular__filters-item filters__item">
                        <a class="filters__button filters__button--<?= $type['type']; ?> button <?php if ($type_id == $type['id']) : ?>filters__button--active<?php endif; ?>" href="?id=<?= $type['id']; ?>">
                            <span class="visually-hidden"><?= $type['name']; ?></span>
                            <svg class="filters__icon" width="24" height="24">
                                <use xlink:href="#icon-filter-<?= $type['type']; ?>"></use>
                            </svg>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
            <?php if (is_array($posts) && !empty($posts)) : ?>
                <?php foreach($posts as $post) : ?>
                <?php $post_date = generate_random_date($post['id']); ?>
                    <article class="popular__post post post-<?= $post['type']; ?>">
                        <header class="post__header">
                            <h2><a href="post.php?id=<?= $post['id']; ?>"><?= htmlspecialchars($post['heading']); ?></a></h2>
                        </header>
                        <div class="post__main">

                            <!--содержимое для поста-цитаты-->
                            <?php if ($post['type'] == "quote") : ?>
                            <blockquote>
                                <p><?= htmlspecialchars($post['content']); ?></p>
                                <cite><?= htmlspecialchars($post['quote_author']); ?></cite>
                            </blockquote>

                            <!--содержимое для поста-ссылки-->
                            <?php elseif ($post['type'] == "link") : ?>
                            <div class="post-link__wrapper">
                                <a class="post-link__external" href="<?= htmlspecialchars($post['content']); ?>" title="Перейти по ссылке" target="_blank">
                                    <div class="post-link__info-wrapper">
                                        <div class="post-link__icon-wrapper">
                                            <img src="https://www.google.com/s2/favicons?domain=<?= htmlspecialchars($post['content']); ?>" alt="Иконка">
                                        </div>
                                        <div class="post-link__info">
                                            <h3><?= htmlspecialchars($post['heading']); ?></h3>
                                        </div>
                                    </div>
                                    <span><?= htmlspecialchars($post['content']); ?></span>
                                </a>
                            </div>

                            <!--содержимое для поста-фото-->
                            <?php elseif ($post['type'] == "photo") : ?>
                            <div class="post-photo__image-wrapper">
                                <img src="uploads/<?= htmlspecialchars($post['content']); ?>" alt="<?= htmlspecialchars($post['heading']); ?>" width="360" height="240">
                            </div>

                            <!--содержимое для поста-видео-->
                            <?php elseif ($post['type'] == "video") : ?>
                            <div class="post-video__block">
                                <div class="post-video__preview">
                                    <?= embed_youtube_cover(htmlspecialchars($post['content'])); ?>
                                </div>
                                <a href="post.php?id=<?= $post['id']; ?>" class="post-video__play-big button">
                                    <svg class="post-video__play-big-icon" width="14" height="14">
                                        <use xlink:href="#icon-video-play-big"></use>
                                    </svg>
                                    <span class="visually-hidden">Запустить проигрыватель</span>
                                </a>
                            </div>

                            <!--содержимое для поста-текста-->
                            <?php elseif ($post['type'] == "text") : ?>
                                <?= sliceText(htmlspecialchars($post['content']), $post['id'], 200); ?>
                            <?php endif; ?>
                        </div>
                        <footer class="post__footer">
                            <div class="post__author">
                                <a class="post__author-link" href="#" title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <img class="post__author-avatar" src="img/<?= htmlspecialchars($post['userpic']); ?>" width="40" height="40" alt="Аватар пользователя <?= htmlspecialchars($post['login']); ?>">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?= htmlspecialchars($post['login']); ?></b>
                                        <time class="post__time" datetime="<?= date("c", strtotime($post_date)); ?>; ?>" title="<?= date("d.m.Y H:i", strtotime($post_date)); ?>"><?= getRelativePostDate($post_date); ?></time>
                                    </div>
                                </a>
                            </div>
                            <div class="post__indicators">
                                <div class="post__buttons">
                                    <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                        <svg class="post__indicator-icon" width="20" height="17">
                                            <use xlink:href="#icon-heart"></use>
                                        </svg>
                                        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                            <use xlink:href="#icon-heart-active"></use>
                                        </svg>
                                        <span><?= $post['likes_count']; ?></span>
                                        <span class="visually-hidden">количество лайков</span>
                                    </a>
                                    <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span>0</span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                </div>
                            </div>
                        </footer>
                    </article>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Постов в этой категории пока нет...</p>
            <?php endif; ?>
        </div>
    </div>
</section>
