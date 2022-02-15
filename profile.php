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
$tab = empty($input['tab']) ? 'posts' : $input['tab'];
$params = [];

if ($input) {
    foreach ($input as $k => $v) {
        if ($k == 'id') {
            $user_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $user_id = abs((int)$user_id);
            $sql = "SELECT
                    users.id,
                    login,
                    email,
                    userpic,
                    users.created_at,
                    (SELECT COUNT(posts.id) FROM posts WHERE posts.user_id = ?) AS posts_count,
                    (SELECT COUNT(subscriptions.id) FROM subscriptions WHERE subscriptions.user_id = ?) AS subscribers_count
                FROM
                    users
                WHERE
                    users.id = ?";
            $user = selectRow($conn, $sql, [$user_id, $user_id, $user_id]);
            $sql = "SELECT
                        posts.id,
                        posts.heading,
                        posts.content,
                        posts.quote_author,
                        posts.user_id,
                        posts.author_id,
                        users.login AS author_login,
                        users.userpic AS author_userpic,
                        posts.tag_ids,
                        posts.reposts_count,
                        posts.is_reposted,
                        posts.created_at,
                        posts.updated_at,
                        types.type,
                        (SELECT COUNT(id) FROM likes WHERE post_id = posts.id) AS likes_count,
                        (SELECT COUNT(id) FROM comments WHERE post_id = posts.id) AS comments_count
                    FROM
                        posts
                    INNER JOIN
                        types
                    ON
                        types.id = posts.type_id
                    LEFT JOIN
                        users
                    ON
                        users.id = posts.author_id
                    WHERE
                        user_id = ?
                    ORDER BY
                        posts.created_at DESC";
            $posts = selectRows($conn, $sql, [$user_id]);
            foreach ($posts as $post) {
                $posts[$post['id']]['show_comments'] = false;
                if (!empty($post['tag_ids'])) {
                    $stmt = db_get_prepare_stmt($conn, "SELECT id, name FROM tags WHERE id IN ({$post['tag_ids']})");
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    $arr = [];
                    foreach ($rows as $row) {
                        $arr[$row['id']] = $row['name'];
                    }
                    $posts[$post['id']]['tags'] = $arr;
                }
            }
            $params[$k] = $v;
        }
        if ($k == 'tab') {
            switch ($v) {
                case 'likes':
                    $sql = "SELECT
                                likes.id,
                                likes.user_id AS user_id,
                                likes.post_id AS post_id,
                                likes.created_at,
                                users.login,
                                users.userpic,
                                posts.content,
                                posts.type_id AS type_id,
                                types.name,
                                types.type
                            FROM
                                likes
                            INNER JOIN
                                users ON likes.user_id = users.id
                            INNER JOIN
                                posts ON likes.post_id = posts.id
                            INNER JOIN
                                types ON posts.type_id = types.id
                            WHERE
                                post_id IN(SELECT id FROM posts WHERE user_id = ?)
                            ORDER BY
                                likes.created_at DESC";
                    $likes = selectRows($conn, $sql, [$user_id]);
                    $tab = $v;
                    break;
                case 'subscriptions':
                    $sql = "SELECT
                                subscriptions.id,
                                subscriptions.user_id AS sub_user_id,
                                users.login,
                                users.userpic,
                                users.created_at,
                                (SELECT COUNT(posts.id) FROM posts WHERE posts.user_id = sub_user_id) AS user_posts_count
                            FROM
                                subscriptions
                            INNER JOIN
                                users ON subscriptions.user_id = users.id
                            WHERE
                                subscriber_id = ?
                            ORDER BY
                                users.created_at
                            ASC
                    ";
                    $subscriptions = selectRows($conn, $sql, [$user_id]);
                    foreach ($subscriptions as $k => $subscription) {
                        $sql = "SELECT
                                    COUNT(id) AS count
                                FROM
                                    subscriptions
                                WHERE
                                    user_id = ?";
                        $user_subscribers_count = selectRow($conn, $sql, [$subscription['sub_user_id']]);
                        $subscriptions[$k]['user_subscribers_count'] = $user_subscribers_count['count'];
                    }
                    $tab = $v;
                    break;
                case 'subscribers':
                    $sql = "SELECT
                                subscriptions.id,
                                subscriptions.subscriber_id AS subscriber_id,
                                users.login,
                                users.userpic,
                                users.created_at,
                                (SELECT COUNT(posts.id) FROM posts WHERE posts.user_id = subscriber_id) AS subscriber_posts_count
                            FROM
                                subscriptions
                            INNER JOIN
                                users ON subscriptions.subscriber_id = users.id
                            WHERE
                                user_id = ?
                            ORDER BY
                                users.created_at
                            ASC";
                    $subscribers = selectRows($conn, $sql, [$user_id]);
                    foreach ($subscribers as $k => $subscriber) {
                        $sql = "SELECT
                                    COUNT(id) AS count
                                FROM
                                    subscriptions
                                WHERE
                                    subscriber_id = ?";
                        $user_subscriptions_count = selectRow($conn, $sql, [$subscriber['subscriber_id']]);
                        $subscribers[$k]['user_subscriptions_count'] = $user_subscriptions_count['count'];
                    }
                    $tab = $v;
                    break;
                default:
                    $tab = $v;
                    break;
            }
        }
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
                case 'show_comments':
                    $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
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
                            comments.post_id = ?
                        ORDER BY
                            comments.created_at DESC";
                    if (!(isset($input['show']) && $input['show'] == 'all')) {
                        $sql .= " LIMIT " . COMMENTS_LIMIT;
                    }
                    $comments = selectRows($conn, $sql, [$post_id]);
                    $posts[$post_id]['show_comments'] = true;
                    $posts[$post_id]['comments'] = $comments;
                    $params[$k] = $v;
                    break;
                default:
                    break;
            }
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

    if (!empty($errors)) {
        $errors['comment_form_id'] = $_POST['comment_form'];
    } else {
        $user_id = (int)$_POST['user_id'];
        insert_comment_into_db($conn, $_POST, $cur_user_id);
        header("Location: " . PATH . "/profile.php?id=" . $user_id);
        exit();
    }
}

if (!isset($user)) {
    $main_content = include_template('404.php');
    $layout_content = include_template('layout.php', [
        'header' => include_template('header.php', [
            http_response_code(404),
            'title' => "readme: Пользователь не найден",
        ]),
        'content' => $main_content,
        'footer' => include_template('footer.php'),
    ]);
} else {
    $main_content = include_template('profile.php', [
        'tab' => $tab,
        'user' => $user,
        'cur_user_id' => $cur_user_id,
        'cur_user_login' => $cur_user_login,
        'cur_user_userpic' => $cur_user_userpic,
        'params' => $params,
        'is_subscribed' => is_subscribed($cur_user_id, $user_id),
        'posts' => $posts ?? [],
        'likes' => $likes ?? [],
        'subscriptions' => $subscriptions ?? [],
        'subscribers' => $subscribers ?? [],
        'errors' => $errors ?? [],
        'getPostVal' => $getPostVal ?? [],
    ]);

    $layout_content = include_template('layout.php', [
        'header' => include_template('header.php', [
            'count_new_messages' => count_new_messages($cur_user_id),
            'title' => 'readme: Профиль пользователя ' . $user['login'],
        ]),
        'content' => $main_content,
        'footer' => include_template('footer.php'),
    ]);
}

print($layout_content);
