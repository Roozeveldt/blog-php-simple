<main class="page__main page__main--messages">
    <h1 class="visually-hidden">Личные сообщения</h1>
    <section class="messages tabs">
        <h2 class="visually-hidden">Сообщения</h2>
        <div class="messages__contacts">
            <?php if (is_array($receivers) && !empty($receivers)) : ?>
                <ul class="messages__contacts-list tabs__list">
                    <?php foreach ($receivers as $receiver) : ?>
                        <li class="messages__contacts-item <?php if (isset($receiver['message_count']) && $receiver['message_count'] > 0) : ?>messages__contacts-item--new<?php endif; ?>">
                            <a class="messages__contacts-tab <?php if ($tab == 'user-' . $receiver['id']) : ?>messages__contacts-tab--active tabs__item--active<?php endif; ?> tabs__item " href="?<?= http_build_query(array_merge(['user_id' => $receiver['id']])); ?>">
                                <div class="messages__avatar-wrapper">
                                    <img class="messages__avatar" src="uploads/userpic/<?= $receiver['userpic']; ?>" alt="Аватар пользователя <?= $receiver['login']; ?>" />
                                    <?php if (isset($receiver['message_count']) && $receiver['message_count'] > 0) : ?>
                                        <i class="messages__indicator"><?= $receiver['message_count']; ?></i>
                                    <?php endif; ?>
                                </div>
                                <div class="messages__info">
                                    <span class="messages__contact-name"> <?= $receiver['login']; ?> </span>
                                    <?php if (isset($receiver['message'])) : ?>
                                        <div class="messages__preview">
                                            <p class="messages__preview-text">
                                                <?= ($receiver['message']['sender_id'] == $cur_user_id) ? 'Вы: ' . $receiver['message']['content'] : $receiver['message']['content']; ?>
                                            </p>
                                            <time class="messages__preview-time" datetime="<?= date("c", strtotime($receiver['message']['created_at'])); ?>"><?= getRelativeLastMessageTime($receiver['message']['created_at']); ?></time>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>Переписка не начата</p>
            <?php endif; ?>
        </div>
        <div class="messages__chat">
            <div class="messages__chat-wrapper">
                <?php if ($new_receiver) : ?>
                    <h3>Начните личную переписку с пользователем <?= $receiver['login']; ?>, отправив сообщение через форму ниже.</h3>
                <?php elseif (empty($messages)) : ?>
                    <h3>Выберите собеседника из левой колонки или начните переписку с каким-либо пользователем из его профиля.</h3>
                <?php endif; ?>
                <?php if (!empty($messages)) : ?>
                    <ul class="messages__list tabs__content tabs__content--active">
                        <?php foreach ($messages as $message) : ?>
                            <li class="messages__item <?php if ($message['sender_id'] == $cur_user_id) : ?>messages__item--my<?php endif; ?>">
                                <div class="messages__info-wrapper">
                                    <div class="messages__item-avatar">
                                        <a class="messages__author-link" href="profile.php?id=<?= $message['sender_id']; ?>">
                                            <img class="messages__avatar" src="uploads/userpic/<?= $message['userpic']; ?>" alt="Аватар пользователя <?= $message['login']; ?>" />
                                        </a>
                                    </div>
                                    <div class="messages__item-info">
                                        <a class="messages__author" href="profile.php?id=<?= $message['sender_id']; ?>"> <?= $message['login']; ?> </a>
                                        <time class="messages__time" datetime="<?= date("c", strtotime($message['created_at'])); ?>"><?= getRelativePostDate($message['created_at']); ?></time>
                                    </div>
                                </div>
                                <p class="messages__text">
                                    <?= $message['content']; ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="comments">
                <?php if ($new_receiver || !empty($messages)) : ?>
                    <form id="comments-form" class="comments__form form" action="#comments-form" method="post">
                        <div class="comments__my-avatar">
                            <img class="comments__picture" src="uploads/userpic/<?= $cur_user_userpic; ?>" alt="Аватар пользователя <?= $cur_user_login; ?>" />
                        </div>
                        <div class="form__input-section <?= !empty($errors) ? "form__input-section--error" : ''; ?>">
                            <textarea class="comments__textarea form__textarea form__input" name="content" placeholder="Ваше сообщение"><?= getPostVal('content'); ?></textarea>
                            <label class="visually-hidden">Ваше сообщение</label>
                            <?php if (isset($errors['content'])) : ?>
                                <?php $form_error = include_template('form-error.php', [
                                    'key' => 'content',
                                    'errors' => $errors,
                                ]);
                                print($form_error); ?>
                            <?php elseif (isset($errors['receiver_id'])) : ?>
                                <?php $form_error = include_template('form-error.php', [
                                    'key' => 'receiver_id',
                                    'errors' => $errors,
                                ]);
                                print($form_error); ?>
                            <?php elseif (isset($errors['sender_id'])) : ?>
                                <?php $form_error = include_template('form-error.php', [
                                    'key' => 'sender_id',
                                    'errors' => $errors,
                                ]);
                                print($form_error); ?>
                            <?php endif; ?>
                        </div>
                        <input type="hidden" name="sender_id" value="<?= $cur_user_id; ?>">
                        <input type="hidden" name="receiver_id" value="<?= substr($tab, 5); ?>">
                        <button class="comments__submit button button--green" type="submit">Отправить</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
