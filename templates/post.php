<main class="page__main page__main--publication">
    <div class="container">
        <h1 class="page__title page__title--publication"><?= $post['heading']; ?></h1>
        <section class="post-details">
            <h2 class="visually-hidden">Публикация</h2>
            <div class="post-details__wrapper post-photo">
                <div class="post-details__main-block post post--details">
                    <?= $post_content; ?>
                    <div class="post__indicators">
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
                            <a class="post__indicator post__indicator--comments button" href="#add_comment" title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span><?= $post['comments_count']; ?></span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                            <a class="post__indicator post__indicator--repost button" href="?do=repost&post_id=<?= $post['id']; ?>" title="Репост">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-repost"></use>
                                </svg>
                                <span><?= $post['reposts_count']; ?></span>
                                <span class="visually-hidden">количество репостов</span>
                            </a>
                        </div>
                        <span class="post__view"><?= $post['views_count']; ?> <?= displayViewsCount($post['views_count']); ?></span>
                    </div>
                    <div class="comments">
                        <form class="comments__form form" action="" method="post" id="add_comment">
                            <div class="comments__my-avatar">
                                <img class="comments__picture" src="uploads/userpic/<?= $cur_user_userpic; ?>" alt="Аватар пользователя <?= $cur_user_login; ?>">
                            </div>
                            <div class="form__input-section <?= isset($errors['content']) ? "form__input-section--error" : ''; ?>">
                                <textarea class="comments__textarea form__textarea form__input" name="content" placeholder="Ваш комментарий"><?= getPostVal('content'); ?></textarea>
                                <label class="visually-hidden">Ваш комментарий</label>
                                <?php if (isset($errors['content'])) : ?>
                                    <?php $form_error = include_template('form-error.php', [
                                        'key' => 'content',
                                        'errors' => $errors,
                                    ]);
                                    print($form_error); ?>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="post_id" value="<?= $post['id']; ?>">
                            <input type="hidden" name="user_id" value="<?= $post['user_id']; ?>">
                            <button class="comments__submit button button--green" type="submit">Отправить</button>
                        </form>
                        <?php if (is_array($comments) && !empty($comments)) : ?>
                        <div class="comments__list-wrapper">
                            <ul class="comments__list">
                                <?php foreach ($comments as $comment) : ?>
                                    <li class="comments__item user">
                                        <div class="comments__avatar">
                                            <a class="user__avatar-link" href="profile.php?id=<?= $comment['user_id']; ?>">
                                                <img class="comments__picture" src="uploads/userpic/<?= $comment['userpic']; ?>" alt="Аватар пользователя <?= $comment['login']; ?>">
                                            </a>
                                        </div>
                                        <div class="comments__info">
                                            <div class="comments__name-wrapper" style="margin-bottom: 20px;">
                                                <a class="comments__user-name" href="profile.php?id=<?= $comment['user_id']; ?>">
                                                    <span><?= h($comment['login']); ?></span>
                                                </a>
                                                <time class="comments__time" datetime="<?= date("c", strtotime($comment['created_at'])); ?>"><?= getRelativePostDate($comment['created_at']) ?></time>
                                            </div>
                                            <p class="comments__text">
                                                <?= nl2br($comment['content']); ?>
                                            </p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if (count($comments) < $post['comments_count']) : ?>
                                <a class="comments__more-link" href="?<?= http_build_query(array_merge($params, ['do' => 'show_all_comments'])); ?>">
                                    <span>Показать все комментарии</span>
                                    <sup class="comments__amount"><?= $post['comments_count']; ?></sup>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php else : ?>
                            <p style="text-align: center;">Комментариев пока нет...</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="post-details__user user">
                    <div class="post-details__user-info user__info">
                        <div class="post-details__avatar user__avatar">
                            <a class="post-details__avatar-link user__avatar-link" href="profile.php?id=<?= $post['user_id']; ?>">
                                <img class="post-details__picture user__picture" src="uploads/userpic/<?= $post['userpic']; ?>" alt="Аватар пользователя <?= h($post['login']); ?>">
                            </a>
                        </div>
                        <div class="post-details__name-wrapper user__name-wrapper">
                            <a class="post-details__name user__name" href="profile.php?id=<?= $post['user_id']; ?>">
                                <span><?= h($post['login']); ?></span>
                            </a>
                            <time class="post-details__time user__time" datetime="<?= date('Y-m-d', strtotime($post['created_at'])); ?>"><?= displayUserRegistrationPeriod($post['created_at']); ?></time>
                        </div>
                    </div>
                    <div class="post-details__rating user__rating">
                        <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
                            <a href="profile.php?id=<?= $post['user_id']; ?>&tab=subscribers"><span class="post-details__rating-amount user__rating-amount"><?= $post['user_subscribers_count']; ?></span></a>
                            <span class="post-details__rating-text user__rating-text"><?= displaySubscribersCount($post['user_subscribers_count']); ?></span>
                        </p>
                        <p class="post-details__rating-item user__rating-item user__rating-item--publications">
                            <a href="profile.php?id=<?= $post['user_id']; ?>&tab=posts"><span class="post-details__rating-amount user__rating-amount"><?= $post['user_posts_count']; ?></span></a>
                            <span class="post-details__rating-text user__rating-text"><?= displayPostsCount($post['user_posts_count']); ?></span>
                        </p>
                    </div>
                    <div class="post-details__user-buttons user__buttons">
                        <a href="?do=subscribe&user_id=<?= $post['user_id']; ?>" class="user__button user__button--subscription button button--<?= $is_subscribed ? 'quartz' : 'main'; ?>">
                            <?= $is_subscribed ? ' Отписаться ' : ' Подписаться '; ?>
                        </a>
                        <?php if ($is_subscribed) : ?>
                            <a class="user__button user__button--writing button button--green" href="messages.php?user_id=<?= $post['user_id']; ?>">Сообщение</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
