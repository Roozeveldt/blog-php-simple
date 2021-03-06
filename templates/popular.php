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
                        <a class="sorting__link <?php if ($sort == 'views') : ?>sorting__link--active<?php endif; ?>" href="?<?= http_build_query(array_merge($params, ['sort' => 'views'])); ?>">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link <?php if ($sort == 'likes') : ?>sorting__link--active<?php endif; ?>" href="?<?= http_build_query(array_merge($params, ['sort' => 'likes'])); ?>">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link <?php if ($sort == 'date') : ?>sorting__link--active<?php endif; ?>" href="?<?= http_build_query(array_merge($params, ['sort' => 'date'])); ?>">
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
                        <a class="filters__button filters__button--ellipse filters__button--all <?php if ($tab == 'all') : ?>filters__button--active<?php endif; ?>" href="?<?= http_build_query(array_merge($params, ['sort' => $default_sort, 'type' => 'all'])); ?>">
                            <span>Все</span>
                        </a>
                    </li>
                    <?php foreach ($types as $type) : ?>
                        <li class="popular__filters-item filters__item">
                            <a class="filters__button filters__button--<?= $type['type']; ?> button <?php if ($tab == $type['type']) : ?>filters__button--active<?php endif; ?>" href="?<?= http_build_query(array_merge($params, ['sort' => $default_sort, 'type' => $type['type']])); ?>">
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
                <?php foreach ($posts as $post) : ?>
                    <article class="popular__post post post-<?= $post['type']; ?>">
                        <header class="post__header">
                            <h2>
                                <a href="post.php?id=<?= $post['id']; ?>">
                                    <?= $post['heading']; ?>
                                </a>
                            </h2>
                        </header>
                        <div class="post__main">

                            <!--содержимое для поста-цитаты-->
                            <?php if ($post['type'] == "quote") : ?>
                                <blockquote>
                                    <p><?= $post['content']; ?></p>
                                    <cite><?= $post['quote_author']; ?></cite>
                                </blockquote>

                            <!--содержимое для поста-ссылки-->
                            <?php elseif ($post['type'] == "link") : ?>
                                <div class="post-link__wrapper">
                                    <a class="post-link__external" href="<?= $post['content']; ?>" title="Перейти по ссылке" target="_blank">
                                        <div class="post-link__info-wrapper">
                                            <div class="post-link__icon-wrapper">
                                                <img src="https://www.google.com/s2/favicons?domain=<?= $post['content']; ?>" alt="Иконка">
                                            </div>
                                            <div class="post-link__info">
                                                <h3><?= $post['heading']; ?></h3>
                                            </div>
                                        </div>
                                        <span style="word-break: break-all;"><?= $post['content']; ?></span>
                                    </a>
                                </div>

                            <!--содержимое для поста-фото-->
                            <?php elseif ($post['type'] == "photo") : ?>
                                <div class="post-photo__image-wrapper">
                                    <img src="uploads/<?= $post['content']; ?>" alt="<?= $post['heading']; ?>" width="360" height="240">
                                </div>

                            <!--содержимое для поста-видео-->
                            <?php elseif ($post['type'] == "video") : ?>
                                <div class="post-video__block">
                                    <div class="post-video__preview">
                                        <img src="<?= embed_youtube_cover($post['content']); ?>" alt="<?= $post['heading']; ?>" width="360" height="240">
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
                                <?= sliceText($post['content'], $post['id'], 200); ?>
                            <?php endif; ?>
                        </div>
                        <footer class="post__footer">
                            <div class="post__author">
                                <a class="post__author-link" href="profile.php?id=<?= $post['user_id']; ?>" title="<?= $post['login']; ?>">
                                    <div class="post__avatar-wrapper">
                                        <img class="post__author-avatar" src="uploads/userpic/<?= $post['userpic']; ?>" width="40" height="40" alt="Аватар пользователя <?= $post['login']; ?>">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?= $post['login']; ?></b>
                                        <time class="post__time" datetime="<?= date("c", strtotime($post['created_at'])); ?>" title="<?= date("d.m.Y H:i", strtotime($post['created_at'])); ?>"><?= getRelativePostDate($post['created_at']); ?></time>
                                    </div>
                                </a>
                            </div>
                            <div class="post__indicators">
                                <div class="post__buttons">
                                    <a class="post__indicator post__indicator--likes button" href="?<?= http_build_query(array_merge($params, ['do' => 'like', 'post_id' => $post['id']])); ?>" title="Лайк">
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
                            </div>
                        </footer>
                    </article>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Постов в этой категории пока нет...</p>
            <?php endif; ?>
        </div>
        <?php if ($posts && $total_pages > 1) : ?>
            <div class="popular__page-links">
                <a class="popular__page-link popular__page-link--prev button button--gray" <?php if ($page != 1) : ?>href="?<?= http_build_query(array_merge($params, ['page' => ($page - 1)])); ?>"<?php else : ?>style="pointer-events: none;cursor: not-allowed;"<?php endif; ?>>Предыдущая страница</a>
                <a class="popular__page-link popular__page-link--next button button--gray" <?php if ($page != $total_pages) : ?>href="?<?= http_build_query(array_merge($params, ['page' => ($page + 1)])); ?>"<?php else : ?>style="pointer-events: none;cursor: not-allowed;"<?php endif; ?>>Следующая страница</a>
            </div>
        <?php endif; ?>
    </div>
</section>
