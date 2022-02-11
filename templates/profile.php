<main class="page__main page__main--profile">
    <h1 class="visually-hidden">Профиль</h1>
    <div class="profile profile--default">
        <div class="profile__user-wrapper">
            <div class="profile__user user container">
                <div class="profile__user-info user__info">
                    <div class="profile__avatar user__avatar">
                        <img
                            class="profile__picture user__picture"
                            src="uploads/userpic/<?= $user['userpic']; ?>"
                            alt="Аватар пользователя <?= $user['login']; ?>"
                        />
                    </div>
                    <div class="profile__name-wrapper user__name-wrapper">
                        <span class="profile__name user__name"><?= show_user_name($user['login']); ?></span>
                        <time class="profile__user-time user__time" datetime="<?= date('Y-m-d', strtotime($user['created_at'])); ?>"><?= displayUserRegistrationPeriod($user['created_at']); ?> (дата регистрации <?= date('d.m.Y', strtotime($user['created_at'])); ?>)</time>
                    </div>
                </div>
                <div class="profile__rating user__rating">
                    <p class="profile__rating-item user__rating-item user__rating-item--publications">
                        <span class="user__rating-amount"><?= $user['posts_count']; ?></span>
                        <span class="profile__rating-text user__rating-text"><?= displayPostsCount($user['posts_count']); ?></span>
                    </p>
                    <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
                        <span class="user__rating-amount"><?= $user['subscribers_count']; ?></span>
                        <span class="profile__rating-text user__rating-text"><?= displaySubscribersCount($user['subscribers_count']); ?></span>
                    </p>
                </div>
                <div class="profile__user-buttons user__buttons">
                    <a href="<?= $url; ?>&do=subscribe&user_id=<?= $user['id']; ?>" class="profile__user-button user__button user__button--subscription button button--<?= $is_subscribed ? 'quartz' : 'main'; ?>">
                        <?= $is_subscribed ? ' Отписаться ' : ' Подписаться '; ?>
                    </a>
                    <?php if ($is_subscribed) : ?>
                        <a class="profile__user-button user__button user__button--writing button button--green" href="messages.php">Сообщение</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="profile__tabs-wrapper tabs">
            <div class="container">
                <div class="profile__tabs filters">
                    <b class="profile__tabs-caption filters__caption">Показать:</b>
                    <ul class="profile__tabs-list filters__list tabs__list">
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item <?php if ($tab == 'posts') : ?>filters__button--active tabs__item--active<?php endif; ?> button" href="<?= $url; ?>&tab=posts">Посты</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item <?php if ($tab == 'likes') : ?>filters__button--active tabs__item--active<?php endif; ?> button" href="<?= $url; ?>&tab=likes">Лайки</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item <?php if ($tab == 'subscriptions') : ?>filters__button--active tabs__item--active<?php endif; ?> button" href="<?= $url; ?>&tab=subscriptions">Подписки</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item <?php if ($tab == 'subscribers') : ?>filters__button--active tabs__item--active<?php endif; ?> button" href="<?= $url; ?>&tab=subscribers">Подписчики</a>
                        </li>
                    </ul>
                </div>
                <div class="profile__tab-content">
                    <section class="profile__posts tabs__content <?php if ($tab == 'posts') : ?>tabs__content--active<?php endif; ?>">
                        <h2 class="visually-hidden">Публикации</h2>
                        <?php if (is_array($posts) && !empty($posts)) : ?>
                            <?php foreach ($posts as $post) : ?>
                                <article class="profile__post post post-<?= $post['type']; ?>">
                                    <header class="post__header">
                                        <?php if ($post['is_reposted']) : ?>
                                            <div class="post__author">
                                                <a class="post__author-link" href="profile.php?id=<?= $post['author_id']; ?>" title="Автор оригинального поста">
                                                    <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                                                        <img class="post__author-avatar" src="uploads/userpic/<?= $post['author_userpic']; ?>" alt="Аватар пользователя <?= $post['author_login']; ?>" width="60" height="60" />
                                                    </div>
                                                    <div class="post__info">
                                                        <b class="post__author-name">Репост: <?= $post['author_login']; ?></b>
                                                        <time class="post__time" datetime="<?= date("c", strtotime($post['created_at'])); ?>"><?= getRelativePostDate($post['created_at']); ?></time>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php else : ?>
                                            <h2><a href="post.php?id=<?= $post['id']; ?>"><?= $post['heading']; ?></a> <?= $post['type']; ?></h2>
                                        <?php endif; ?>
                                    </header>
                                    <div class="post__main">
                                        <div class="post-photo__image-wrapper">
                                            <img src="../img/rock.jpg" alt="Фото от пользователя" width="760" height="396"/>
                                        </div>
                                    </div>
                                    <footer class="post__footer">
                                        <div class="post__indicators">
                                            <div class="post__buttons">
                                                <a class="post__indicator post__indicator--likes button" href="<?= $url; ?>&do=like&post_id=<?= $post['id']; ?>" title="Лайк">
                                                    <svg class="post__indicator-icon" width="20" height="17">
                                                        <use xlink:href="#icon-heart"></use>
                                                    </svg>
                                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                                        <use xlink:href="#icon-heart-active"></use>
                                                    </svg>
                                                    <span><?= $post['likes_count']; ?></span>
                                                    <span class="visually-hidden">количество лайков</span>
                                                </a>
                                                <a class="post__indicator post__indicator--repost button" href="<?= $url; ?>&do=repost&post_id=<?= $post['id']; ?>" title="Репост">
                                                    <svg class="post__indicator-icon" width="19" height="17">
                                                        <use xlink:href="#icon-repost"></use>
                                                    </svg>
                                                    <span><?= $post['reposts_count']; ?></span>
                                                    <span class="visually-hidden">количество репостов</span>
                                                </a>
                                            </div>
                                            <time class="post__time" datetime="<?= date("c", strtotime($post['updated_at'])); ?>"><?= getRelativePostDate($post['updated_at']); ?></time>
                                        </div>
                                        <?php if (isset($post['tags'])) : ?>
                                            <ul class="post__tags">
                                                <?php foreach ($post['tags'] as $k => $v) : ?>
                                                    <li><a href="search.php?id=<?= $k; ?>">#<?= $v; ?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </footer>
                                    <div class="comments">
                                        <a class="comments__button button" href="#">Показать комментарии</a>
                                    </div>
                                </article>
                            <?php endforeach; ?>

                                <article class="profile__post post post-text">
                                    <header class="post__header">
                                        <!-- вырезал -->
                                    </header>
                                    <div class="post__main">
                                    <h2><a href="#">Полезный пост про Байкал</a></h2>
                                    <p>
                                        Озеро Байкал – огромное древнее озеро в горах Сибири к
                                        северу от монгольской границы. Байкал считается самым
                                        глубоким озером в мире. Он окружен сетью пешеходных
                                        маршрутов, называемых Большой байкальской тропой. Деревня
                                        Листвянка, расположенная на западном берегу озера, –
                                        популярная отправная точка для летних экскурсий. Зимой
                                        здесь можно кататься на коньках и собачьих упряжках.
                                    </p>
                                    <a class="post-text__more-link" href="#">Читать далее</a>
                                    </div>
                                    <footer class="post__footer">
                                    <div class="post__indicators">
                                        <div class="post__buttons">
                                        <a
                                            class="post__indicator post__indicator--likes button"
                                            href="#"
                                            title="Лайк"
                                        >
                                            <svg
                                            class="post__indicator-icon"
                                            width="20"
                                            height="17"
                                            >
                                            <use xlink:href="#icon-heart"></use>
                                            </svg>
                                            <svg
                                            class="post__indicator-icon post__indicator-icon--like-active"
                                            width="20"
                                            height="17"
                                            >
                                            <use xlink:href="#icon-heart-active"></use>
                                            </svg>
                                            <span>250</span>
                                            <span class="visually-hidden">количество лайков</span>
                                        </a>
                                        <a
                                            class="post__indicator post__indicator--repost button"
                                            href="#"
                                            title="Репост"
                                        >
                                            <svg
                                            class="post__indicator-icon"
                                            width="19"
                                            height="17"
                                            >
                                            <use xlink:href="#icon-repost"></use>
                                            </svg>
                                            <span>5</span>
                                            <span class="visually-hidden"
                                            >количество репостов</span
                                            >
                                        </a>
                                        </div>
                                        <time class="post__time" datetime="2019-01-30T23:41"
                                        >15 минут назад</time
                                        >
                                    </div>
                                    <ul class="post__tags">
                                        <li><a href="#">#nature</a></li>
                                        <li><a href="#">#globe</a></li>
                                        <li><a href="#">#photooftheday</a></li>
                                        <li><a href="#">#canon</a></li>
                                        <li><a href="#">#landscape</a></li>
                                        <li><a href="#">#щикарныйвид</a></li>
                                    </ul>
                                    </footer>
                                    <div class="comments">
                                    <div class="comments__list-wrapper">
                                        <ul class="comments__list">
                                        <li class="comments__item user">
                                            <div class="comments__avatar">
                                            <a class="user__avatar-link" href="#">
                                                <img
                                                class="comments__picture"
                                                src="../img/userpic-larisa.jpg"
                                                alt="Аватар пользователя"
                                                />
                                            </a>
                                            </div>
                                            <div class="comments__info">
                                            <div class="comments__name-wrapper">
                                                <a class="comments__user-name" href="#">
                                                <span>Лариса Роговая</span>
                                                </a>
                                                <time class="comments__time" datetime="2019-03-20"
                                                >1 ч назад</time
                                                >
                                            </div>
                                            <p class="comments__text">Красота!!!1!</p>
                                            </div>
                                        </li>
                                        <li class="comments__item user">
                                            <div class="comments__avatar">
                                            <a class="user__avatar-link" href="#">
                                                <img
                                                class="comments__picture"
                                                src="../img/userpic-larisa.jpg"
                                                alt="Аватар пользователя"
                                                />
                                            </a>
                                            </div>
                                            <div class="comments__info">
                                            <div class="comments__name-wrapper">
                                                <a class="comments__user-name" href="#">
                                                <span>Лариса Роговая</span>
                                                </a>
                                                <time class="comments__time" datetime="2019-03-18"
                                                >2 дня назад</time
                                                >
                                            </div>
                                            <p class="comments__text">
                                                Озеро Байкал – огромное древнее озеро в горах
                                                Сибири к северу от монгольской границы. Байкал
                                                считается самым глубоким озером в мире. Он окружен
                                                сетью пешеходных маршрутов, называемых Большой
                                                байкальской тропой. Деревня Листвянка,
                                                расположенная на западном берегу озера, –
                                                популярная отправная точка для летних экскурсий.
                                                Зимой здесь можно кататься на коньках и собачьих
                                                упряжках.
                                            </p>
                                            </div>
                                        </li>
                                        </ul>
                                        <a class="comments__more-link" href="#">
                                        <span>Показать все комментарии</span>
                                        <sup class="comments__amount">45</sup>
                                        </a>
                                    </div>
                                    </div>
                                    <form class="comments__form form" action="#" method="post">
                                    <div class="comments__my-avatar">
                                        <img
                                        class="comments__picture"
                                        src="../img/userpic-medium.jpg"
                                        alt="Аватар пользователя"
                                        />
                                    </div>
                                    <textarea
                                        class="comments__textarea form__textarea"
                                        placeholder="Ваш комментарий"
                                    ></textarea>
                                    <label class="visually-hidden">Ваш комментарий</label>
                                    <button
                                        class="comments__submit button button--green"
                                        type="submit"
                                    >
                                        Отправить
                                    </button>
                                    </form>
                                </article>

                        <?php else: ?>
                            <p>Публикаций у данного автора пока нет...</p>
                        <?php endif; ?>
                    </section>
                    <section class="profile__likes tabs__content <?php if ($tab == 'likes') : ?>tabs__content--active<?php endif; ?>">
                        <h2 class="visually-hidden">Лайки</h2>
                        <?php if (is_array($likes) && !empty($likes)) : ?>
                            <ul class="profile__likes-list">
                                <?php foreach ($likes as $like) : ?>
                                    <li class="post-mini post-mini--<?= $like['type']; ?> post user">
                                        <div class="post-mini__user-info user__info">
                                            <div class="post-mini__avatar user__avatar">
                                                <a class="user__avatar-link" href="profile.php?id=<?= $like['user_id']; ?>">
                                                    <img class="post-mini__picture user__picture" src="uploads/userpic/<?= $like['userpic']; ?>" alt="Аватар пользователя <?= $like['login']; ?>"/>
                                                </a>
                                            </div>
                                            <div class="post-mini__name-wrapper user__name-wrapper">
                                                <a class="post-mini__name user__name" href="profile.php?id=<?= $like['user_id']; ?>">
                                                    <span><?= $like['login']; ?></span>
                                                </a>
                                                <div class="post-mini__action">
                                                    <span class="post-mini__activity user__additional">Лайкнул(а) вашу публикацию</span>
                                                    <time class="post-mini__time user__additional" datetime="<?= date("c", strtotime($like['created_at'])); ?>"><?= getRelativePostDate($like['created_at']); ?></time>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="post-mini__preview">
                                            <a class="post-mini__link" href="post.php?id=<?= $like['post_id']; ?>" title="Перейти на публикацию">
                                                <?php if ($like['type'] == 'photo') : ?>
                                                    <div class="post-mini__image-wrapper">
                                                        <img class="post-mini__image" src="uploads/<?= $like['content']; ?>" width="109" height="109" alt="Превью публикации"/>
                                                    </div>
                                                    <span class="visually-hidden"><?= $like['name']; ?></span>
                                                <?php elseif ($like['type'] == 'video') : ?>
                                                    <div class="post-mini__image-wrapper">
                                                        <img class="post-mini__image" src="<?= embed_youtube_cover(h($like['content'])); ?>" width="109" height="109" alt="Превью публикации"/>
                                                        <span class="post-mini__play-big">
                                                            <svg class="post-mini__play-big-icon" width="12" height="13">
                                                                <use xlink:href="#icon-video-play-big"></use>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <span class="visually-hidden"><?= $like['name']; ?></span>
                                                <?php else: ?>
                                                    <span class="visually-hidden"><?= $like['name']; ?></span>
                                                    <svg class="post-mini__preview-icon" width="21" height="21">
                                                        <use xlink:href="#icon-filter-<?= $like['type']; ?>"></use>
                                                    </svg>
                                                <?php endif; ?>
                                            </a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>Лайков к публикациям данного автора пока нет...</p>
                        <?php endif; ?>
                    </section>
                    <section class="profile__subscriptions tabs__content <?php if ($tab == 'subscriptions') : ?>tabs__content--active<?php endif; ?>">
                        <h2 class="visually-hidden">Подписки</h2>
                        <?php if (is_array($subscriptions) && !empty($subscriptions)) : ?>
                            <ul class="profile__subscriptions-list">
                                <?php foreach ($subscriptions as $subscription) : ?>
                                    <li class="post-mini post-mini--photo post user">
                                        <div class="post-mini__user-info user__info">
                                            <div class="post-mini__avatar user__avatar">
                                                <a class="user__avatar-link" href="profile.php?id=<?= $subscription['sub_user_id']; ?>">
                                                    <img class="post-mini__picture user__picture" src="uploads/userpic/<?= $subscription['userpic']; ?>" alt="Аватар пользователя <?= h($subscription['login']); ?>"/>
                                                </a>
                                            </div>
                                            <div class="post-mini__name-wrapper user__name-wrapper">
                                                <a class="post-mini__name user__name" href="profile.php?id=<?= $subscription['sub_user_id']; ?>">
                                                    <span><?= h($subscription['login']); ?></span>
                                                </a>
                                                <time class="post-mini__time user__additional" datetime="<?= date("c", strtotime($subscription['created_at'])); ?>"><?= displayUserRegistrationPeriod($subscription['created_at']); ?></time>
                                            </div>
                                        </div>
                                        <div class="post-mini__rating user__rating">
                                            <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                                <a href="profile.php?id=<?= $subscription['sub_user_id']; ?>&tab=posts"><span class="post-mini__rating-amount user__rating-amount"><?= $subscription['user_posts_count']; ?></span></a>
                                                <span class="post-mini__rating-text user__rating-text"><?= displayPostsCount($subscription['user_posts_count']); ?></span>
                                            </p>
                                            <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                                <a href="profile.php?id=<?= $subscription['sub_user_id']; ?>&tab=subscribers"><span class="post-mini__rating-amount user__rating-amount"><?= $subscription['user_subscribers_count']; ?></span></a>
                                                <span class="post-mini__rating-text user__rating-text"><?= displaySubscribersCount($subscription['user_subscribers_count']); ?></span>
                                            </p>
                                        </div>
                                        <div class="post-mini__user-buttons user__buttons">
                                            <?php $is_subscribed_to_sub_user = is_subscribed($cur_user, $subscription['sub_user_id']); ?>
                                            <a href="<?= $url; ?>&do=subscribe&user_id=<?= $subscription['sub_user_id']; ?>" class="post-mini__user-button user__button user__button--subscription button button--<?= $is_subscribed_to_sub_user ? 'quartz' : 'main'; ?>">
                                                <?= $is_subscribed_to_sub_user ? ' Отписаться ' : ' Подписаться '; ?>
                                            </a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>Данный автор ещё ни на кого не подписался...</p>
                        <?php endif; ?>
                    </section>
                    <section class="profile__subscriptions tabs__content <?php if ($tab == 'subscribers') : ?>tabs__content--active<?php endif; ?>">
                        <h2 class="visually-hidden">Подписчики</h2>
                        <?php if (is_array($subscribers) && !empty($subscribers)) : ?>
                            <ul class="profile__subscriptions-list">
                                <?php foreach ($subscribers as $subscriber) : ?>
                                    <li class="post-mini post-mini--photo post user">
                                        <div class="post-mini__user-info user__info">
                                            <div class="post-mini__avatar user__avatar">
                                                <a class="user__avatar-link" href="profile.php?id=<?= $subscriber['subscriber_id']; ?>">
                                                    <img class="post-mini__picture user__picture" src="uploads/userpic/<?= $subscriber['userpic']; ?>" alt="Аватар пользователя <?= $subscriber['login']; ?>"/>
                                                </a>
                                            </div>
                                            <div class="post-mini__name-wrapper user__name-wrapper">
                                                <a class="post-mini__name user__name" href="profile.php?id=<?= $subscriber['subscriber_id']; ?>">
                                                    <span><?= $subscriber['login']; ?></span>
                                                </a>
                                                <time class="post-mini__time user__additional" datetime="<?= date("c", strtotime($subscriber['created_at'])); ?>"><?= displayUserRegistrationPeriod($subscriber['created_at']); ?></time>
                                            </div>
                                        </div>
                                        <div class="post-mini__rating user__rating">
                                            <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                                <a href="profile.php?id=<?= $subscriber['subscriber_id']; ?>&tab=posts"><span class="post-mini__rating-amount user__rating-amount"><?= $subscriber['subscriber_posts_count']; ?></span></a>
                                                <span class="post-mini__rating-text user__rating-text"><?= displayPostsCount($subscriber['subscriber_posts_count']); ?></span>
                                            </p>
                                            <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                                <a href="profile.php?id=<?= $subscriber['subscriber_id']; ?>&tab=subscriptions"><span class="post-mini__rating-amount user__rating-amount"><?= $subscriber['user_subscriptions_count']; ?></span></a>
                                                <span class="post-mini__rating-text user__rating-text"><?= displaySubscriptionsCount($subscriber['user_subscriptions_count']); ?></span>
                                            </p>
                                        </div>
                                        <div class="post-mini__user-buttons user__buttons">
                                            <?php $is_subscribed_to_subscriber = is_subscribed($cur_user, $subscriber['subscriber_id']); ?>
                                            <a href="<?= $url; ?>&do=subscribe&user_id=<?= $subscriber['subscriber_id']; ?>" class="post-mini__user-button user__button user__button--subscription button button--<?= $is_subscribed_to_subscriber ? 'quartz' : 'main'; ?>">
                                                <?= $is_subscribed_to_subscriber ? ' Отписаться ' : ' Подписаться '; ?>
                                            </a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>Данный автор ещё не имеет подписчиков...</p>
                        <?php endif; ?>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>
