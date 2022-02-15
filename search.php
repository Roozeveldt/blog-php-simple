<?php

require_once('config.php');

if (!isset($_SESSION['user'])) {
    header("Location: " . PATH);
    exit();
} else {
    $cur_user_id = $_SESSION['user']['id'];
}

require_once('functions.php');

$input = filter_input_array(INPUT_GET);

if ($input) {
    foreach ($input as $k => $v) {
        if ($k == 'search') {
            $hash = substr($v, 0, 1);
            $title = 'Страница результатов поиска';
            if ($hash === '#') {
                // поиск по тегу - получаем название тега
                $search = substr($v, 1);
                $sql = "SELECT
                            posts.id AS id,
                            posts.heading,
                            posts.content,
                            posts.quote_author,
                            posts.user_id,
                            posts.created_at,
                            users.login,
                            users.userpic,
                            types.type,
                            (SELECT COUNT(likes.id) FROM likes WHERE likes.post_id = posts.id) AS likes_count,
                            (SELECT COUNT(comments.id) FROM comments WHERE comments.post_id = posts.id) AS comments_count
                        FROM
                            posts
                        JOIN
                            users ON users.id = posts.user_id
                        JOIN
                            types ON types.id = posts.type_id
                        WHERE
                            posts.id IN (
                                SELECT tags.post_id
                                FROM tags
                                WHERE tags.name
                                LIKE ?)
                        ORDER BY
                            posts.created_at
                        DESC
                ";
                $posts = selectRows($conn, $sql, [$search]);
                $search_query = '#' . $search;
            } else {
                // поиск по поисковому запросу
                $search = h($v);
                if (!empty($search)) {
                    $sql = "SELECT
                                posts.id AS id,
                                posts.heading,
                                posts.content,
                                posts.quote_author,
                                posts.user_id,
                                posts.created_at,
                                users.login,
                                users.userpic,
                                types.type,
                                MATCH(heading, content) AGAINST(?) AS score,
                                (SELECT COUNT(likes.id) FROM likes WHERE likes.post_id = posts.id) AS likes_count,
                                (SELECT COUNT(comments.id) FROM comments WHERE comments.post_id = posts.id) AS comments_count
                            FROM
                                posts
                            JOIN
                                users ON users.id = posts.user_id
                            JOIN
                                types ON types.id = posts.type_id
                            WHERE
                                MATCH(heading, content) AGAINST(?)
                            ORDER BY
                                score
                            DESC
                    ";
                    $posts = selectRows($conn, $sql, [$search, $search]);
                    $search_query = mb_strtolower($search);
                } else {
                    $posts = [];
                    $search_query = 'поисковый запрос не задан';
                    $title .= ' (нет результатов)';
                }
            }
        }
        if ($k == 'do') {
            switch ($v) {
                case 'like':
                    $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
                    like_unlike($cur_user_id, $post_id);
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                default:
                    break;
            }
        }
    }
}

$main_content = include_template('search.php', [
    'search_query' => $search_query,
    'title' => $title,
    'posts' => $posts,
]);

$layout_content = include_template('layout.php', [
    'header' => include_template('header.php', [
        'count_new_messages' => count_new_messages($cur_user_id),
        'title' => 'readme: ' . $title,
    ]),
    'content' => $main_content,
    'footer' => include_template('footer.php'),
]);

print($layout_content);
