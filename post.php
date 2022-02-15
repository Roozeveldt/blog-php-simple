<?php

require_once('config.php');

if (!isset($_SESSION['user'])) {
    header("Location: " . PATH);
    exit();
} else {
    $cur_user_id = $_SESSION['user']['id'];
    $cur_user_login = $_SESSION['user']['login'];
    $cur_user_userpic = $_SESSION['user']['userpic'];
}

require_once('functions.php');
require_once('validation.php');

$input = filter_input_array(INPUT_GET);

$params = [];

if ($input) {
    foreach ($input as $k => $v) {
        if ($k == 'do') {
            switch ($v) {
                case 'subscribe':
                    $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);
                    subscribe_unsubscribe($cur_user_id, $user_id);
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                case 'like':
                    $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
                    like_unlike($cur_user_id, $post_id);
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                case 'repost':
                    $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
                    repost($cur_user_id, $post_id);
                    header("Location: " . PATH . "/profile.php?id=" . $cur_user_id);
                    exit();
                default:
                    break;
            }
        }
        if ($k == 'id') {
            $post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $post_id = abs((int)$post_id);
            $params[$k] = $post_id;

            // получаем данные поста
            $sql = "SELECT
                        posts.id,
                        posts.heading,
                        posts.content,
                        posts.quote_author,
                        posts.user_id,
                        posts.views_count,
                        posts.reposts_count,
                        types.type,
                        users.login,
                        users.userpic,
                        users.created_at,
                        (SELECT COUNT(likes.id) FROM likes WHERE likes.post_id = posts.id) AS likes_count,
                        (SELECT COUNT(posts.id) FROM posts WHERE posts.user_id = users.id) AS user_posts_count,
                        (SELECT COUNT(subscriptions.id) FROM subscriptions WHERE subscriptions.user_id = users.id) AS user_subscribers_count,
                        (SELECT COUNT(comments.id) FROM comments WHERE comments.post_id = posts.id) AS comments_count
                    FROM
                        posts
                    INNER JOIN
                        types
                    ON
                        types.id = posts.type_id
                    INNER JOIN
                        users
                    ON
                        users.id = posts.user_id
                    WHERE
                        posts.id = ?
            ";
            $post = selectRow($conn, $sql, [$post_id]);
            // комментарии к посту
            $sql = "SELECT
                        comments.id,
                        comments.content,
                        comments.user_id,
                        comments.created_at,
                        users.login,
                        users.userpic
                    FROM
                        comments
                    INNER JOIN
                        users
                    ON
                        users.id = comments.user_id
                    WHERE
                        post_id = ?
                    ORDER BY
                        comments.created_at DESC
                    ";
            if (!(isset($input['do']) && $input['do'] == 'show_all_comments')) {
                $sql .= " LIMIT " . COMMENTS_LIMIT;
            }
            $comments = selectRows($conn, $sql, [$post['id']]);
        }
    }
}

// добавление комментария
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $getPostVal = [];
    foreach ($_POST as $key => $value) {
        $getPostVal[$key] = getPostVal($key);
    }

    $errors = validate_post_form($_POST, $comment_rules);

    if (empty($errors)) {
        $user_id = (int)$_POST['user_id'];
        insert_comment_into_db($conn, $_POST, $cur_user_id);
        header("Location: " . PATH . "/profile.php?id=" . $user_id);
        exit();
    }
}

if (!isset($post)) {
    $main_content = include_template('404.php');
    $layout_content = include_template('layout.php', [
        'header' => include_template('header.php', [
            http_response_code(404),
            'title' => "readme: Пост не найден",
        ]),
        'content' => $main_content,
        'footer' => include_template('footer.php'),
    ]);
} else {
    switch ($post['type']) {
        case 'quote':
            $post_content = include_template('post-quote.php', [
                'text' => $post['content'],
                'author' => $post['quote_author'],
            ]);
            break;
        case 'link':
            $post_content = include_template('post-link.php', [
                'title' => $post['heading'],
                'url' => $post['content'],
            ]);
            break;
        case 'text':
            $post_content = include_template('post-text.php', [
                'text' => $post['content'],
            ]);
            break;
        case 'photo':
            $post_content = include_template('post-photo.php', [
                'alt' => $post['heading'],
                'img_url' => $post['content'],
            ]);
            break;
        case 'video':
            $post_content = include_template('post-video.php', [
                'youtube_url' => $post['content'],
            ]);
            break;
        default:
            $post_content = 'Шаблон не найден!';
            break;
    }

    // увеличиваем число просмотров на единицу
    $stmt = db_get_prepare_stmt($conn, "UPDATE posts SET views_count = (views_count + 1) WHERE id = ?", [$post_id]);
    mysqli_stmt_execute($stmt);

    $user_id = $post['user_id'];

    $main_content = include_template('post.php', [
        'params' => $params,
        'post' => $post,
        'post_content' => $post_content,
        'cur_user_login' => $cur_user_login,
        'cur_user_userpic' => $cur_user_userpic,
        'is_subscribed' => is_subscribed($cur_user_id, $user_id),
        'errors' => $errors ?? [],
        'getPostVal' => $getPostVal ?? [],
        'comments' => $comments ?? [],
    ]);

    $layout_content = include_template('layout.php', [
        'header' => include_template('header.php', [
            'count_new_messages' => count_new_messages($cur_user_id),
            'title' => 'readme: ' . $post['heading'],
        ]),
        'content' => $main_content,
        'footer' => include_template('footer.php'),
    ]);
}

print($layout_content);
