<main class="page__main page__main--profile">
    <h1 class="visually-hidden">Профиль</h1>
    <div class="profile profile--default">
        <div class="profile__user-wrapper">
            <div class="profile__user user container">
                <div class="profile__user-info user__info">
                    <div class="profile__avatar user__avatar">
                        <img class="profile__picture user__picture" src="uploads/userpic/<?= $user['userpic']; ?>" alt="Аватар пользователя <?= $user['login']; ?>" />
                    </div>
                    <div class="profile__name-wrapper user__name-wrapper">
                        <span class="profile__name user__name"><?= show_user_name($user['login']); ?></span>
                        <time class="profile__user-time user__time" datetime="<?= date('Y-m-d', strtotime($user['created_at'])); ?>"><?= displayUserRegistrationPeriod($user['created_at']); ?> (дата регистрации <?= date('d.m.Y', strtotime($user['created_at'])); ?>)</time>
                    </div>
                </div>
                <div class="profile__rating user__rating">
                    <p class="profile__rating-item user__rating-item user__rating-item--publications">
                        <a href="?<?= http_build_query(array_merge(['id' => $user['id'], 'tab' => 'posts'])); ?>"><span class="user__rating-amount"><?= $user['posts_count']; ?></span></a>
                        <span class="profile__rating-text user__rating-text"><?= displayPostsCount($user['posts_count']); ?></span>
                    </p>
                    <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
                        <a href="?<?= http_build_query(array_merge(['id' => $user['id'], 'tab' => 'subscribers'])); ?>"><span class="user__rating-amount"><?= $user['subscribers_count']; ?></span></a>
                        <span class="profile__rating-text user__rating-text"><?= displaySubscribersCount($user['subscribers_count']); ?></span>
                    </p>
                </div>
                <div class="profile__user-buttons user__buttons">
                    <a href="?<?= http_build_query(array_merge(['do' => 'subscribe', 'user_id' => $user['id']])); ?>" class="profile__user-button user__button user__button--subscription button button--<?= $is_subscribed ? 'quartz' : 'main'; ?>">
                        <?= $is_subscribed ? ' Отписаться ' : ' Подписаться '; ?>
                    </a>
                    <?php if ($is_subscribed) : ?>
                        <a class="profile__user-button user__button user__button--writing button button--green" href="messages.php?user_id=<?= $user['id']; ?>">Сообщение</a>
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
                            <a class="profile__tabs-link filters__button tabs__item <?php if ($tab == 'posts') : ?>filters__button--active tabs__item--active<?php endif; ?> button" href="?<?= http_build_query(array_merge(['id' => $user['id'], 'tab' => 'posts'])); ?>">Посты</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item <?php if ($tab == 'likes') : ?>filters__button--active tabs__item--active<?php endif; ?> button" href="?<?= http_build_query(array_merge(['id' => $user['id'], 'tab' => 'likes'])); ?>">Лайки</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item <?php if ($tab == 'subscriptions') : ?>filters__button--active tabs__item--active<?php endif; ?> button" href="?<?= http_build_query(array_merge(['id' => $user['id'], 'tab' => 'subscriptions'])); ?>">Подписки</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item <?php if ($tab == 'subscribers') : ?>filters__button--active tabs__item--active<?php endif; ?> button" href="?<?= http_build_query(array_merge(['id' => $user['id'], 'tab' => 'subscribers'])); ?>">Подписчики</a>
                        </li>
                    </ul>
                </div>
                <div class="profile__tab-content">
                    <section class="profile__posts tabs__content <?php if ($tab == 'posts') : ?>tabs__content--active<?php endif; ?>">
                        <h2 class="visually-hidden">Публикации</h2>
                        <?php if (is_array($posts) && !empty($posts)) : ?>
                            <?php foreach ($posts as $post) : ?>
                                <article id="post-<?= $post['id']; ?>" class="profile__post post post-<?= $post['type']; ?>">
                                    <header class="post__header">
                                        <?php if ($post['is_reposted']) : ?>
                                            <div class="post__author" style="padding-bottom: 0;">
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
                                        <?php endif; ?>
                                        <h2 <?php if ($post['type'] == 'text') : ?>style="padding: 29px 40px 0 40px;"<?php endif; ?>><a href="post.php?id=<?= $post['id']; ?>"><?= $post['heading']; ?></a></h2>
                                    </header>
                                    <div class="post__main">

                                        <!-- пост-картинка -->
                                        <?php if ($post['type'] == 'photo') : ?>
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
                                    <footer class="post__footer">
                                        <div class="post__indicators">
                                            <div class="post__buttons">
                                                <a class="post__indicator post__indicator--likes button" href="?<?= http_build_query(array_merge(['do' => 'like', 'post_id' => $post['id']])); ?>#post-<?= $post['id']; ?>" title="Лайк">
                                                    <svg class="post__indicator-icon" width="20" height="17">
                                                        <use xlink:href="#icon-heart"></use>
                                                    </svg>
                                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                                        <use xlink:href="#icon-heart-active"></use>
                                                    </svg>
                                                    <span><?= $post['likes_count']; ?></span>
                                                    <span class="visually-hidden">количество лайков</span>
                                                </a>
                                                <a class="post__indicator post__indicator--repost button" href="?<?= http_build_query(array_merge(['do' => 'repost', 'post_id' => $post['id']])); ?>" title="Репост">
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
                                                    <li><a href="search.php?search=<?= urlencode("#{$v}"); ?>">#<?= $v; ?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </footer>
                                    <div id="comments_<?= $post['id']; ?>" class="comments">
                                        <?php if (!$post['show_comments']) : ?>
                                            <?php if ($post['comments_count']) : ?>
                                                <a class="comments__button button" href="?<?= http_build_query(array_merge($params, ['do' => 'show_comments', 'post_id' => $post['id']])); ?>#comments_<?= $post['id']; ?>">Показать комментарии (<?= $post['comments_count']; ?>)</a>
                                            <?php else : ?>
                                                <p style="text-align: center; margin-bottom: 30px;">Комментариев к этому посту пока нет...</p>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <div class="comments__list-wrapper">
                                                <ul class="comments__list">
                                                    <?php foreach ($post['comments'] as $key => $comment) : ?>
                                                        <li class="comments__item user">
                                                            <div class="comments__avatar">
                                                                <a class="user__avatar-link" href="profile.php?id=<?= $comment['user_id']; ?>">
                                                                    <img class="comments__picture" src="uploads/userpic/<?= $comment['userpic']; ?>" alt="Аватар пользователя <?= $comment['login']; ?>" />
                                                                </a>
                                                            </div>
                                                            <div class="comments__info">
                                                                <div class="comments__name-wrapper">
                                                                    <a class="comments__user-name" href="profile.php?id=<?= $comment['user_id']; ?>">
                                                                        <span><?= $comment['login']; ?></span>
                                                                    </a>
                                                                    <time class="comments__time" datetime="<?= date("c", strtotime($comment['created_at'])); ?>"><?= getRelativePostDate($comment['created_at']); ?></time>
                                                                </div>
                                                                <p class="comments__text">
                                                                    <?= nl2br($comment['content']); ?>
                                                                </p>
                                                            </div>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                                <?php if (count($post['comments']) < $post['comments_count']) : ?>
                                                    <a class="comments__more-link" href="?<?= http_build_query(array_merge($params, ['show' => 'all', 'post_id' => $post['id']])); ?>#comments_<?= $post['id']; ?>">
                                                        <span>Показать все комментарии</span>
                                                        <sup class="comments__amount"><?= $post['comments_count']; ?></sup>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <form id="form_<?= $post['id']; ?>" class="comments__form form" action="#form_<?= $post['id']; ?>" method="post">
                                        <div class="comments__my-avatar">
                                            <img class="comments__picture" src="uploads/userpic/<?= $cur_user_userpic; ?>" alt="Аватар пользователя <?= $cur_user_login; ?>" width="40" height="40">
                                        </div>
                                        <div class="form__input-section <?= ((isset($errors['comment_form_id']) && $errors['comment_form_id'] == "form_{$post['id']}") && isset($errors['content'])) ? "form__input-section--error" : ''; ?>">
                                            <textarea class="comments__textarea form__textarea form__input" name="content" placeholder="Ваш комментарий"><?= (isset($errors['comment_form_id']) && $errors['comment_form_id'] == "form_{$post['id']}") && isset($errors['content']) ? getPostVal('content') : ''; ?></textarea>
                                            <label class="visually-hidden">Ваш комментарий</label>
                                            <?php if ((isset($errors['comment_form_id']) && $errors['comment_form_id'] == "form_{$post['id']}") && isset($errors['content'])) : ?>
                                                <?php $form_error = include_template('form-error.php', [
                                                    'key' => 'content',
                                                    'errors' => $errors,
                                                ]);
                                                print($form_error); ?>
                                            <?php endif; ?>
                                        </div>
                                        <input type="hidden" name="post_id" value="<?= $post['id']; ?>">
                                        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                        <input type="hidden" name="comment_form" value="form_<?= $post['id']; ?>">
                                        <button class="comments__submit button button--green" type="submit">Отправить</button>
                                    </form>
                                </article>
                            <?php endforeach; ?>
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
                                            <?php $is_subscribed_to_sub_user = is_subscribed($cur_user_id, $subscription['sub_user_id']); ?>
                                            <a href="?<?= http_build_query(array_merge(['do' => 'subscribe', 'user_id' => $subscription['sub_user_id']])); ?>" class="post-mini__user-button user__button user__button--subscription button button--<?= $is_subscribed_to_sub_user ? 'quartz' : 'main'; ?>">
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
                                            <?php $is_subscribed_to_subscriber = is_subscribed($cur_user_id, $subscriber['subscriber_id']); ?>
                                            <a href="?<?= http_build_query(array_merge(['do' => 'subscribe', 'user_id' => $subscriber['subscriber_id']])); ?>" class="post-mini__user-button user__button user__button--subscription button button--<?= $is_subscribed_to_subscriber ? 'quartz' : 'main'; ?>">
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
