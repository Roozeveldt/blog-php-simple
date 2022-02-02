<?php

require_once('config.php');
require_once('functions.php');

$post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$post_id = abs((int)$post_id);

$sql = "SELECT
            posts.id,
            heading,
            content,
            quote_author,
            types.type,
            users.login AS uname,
            users.userpic,
            users.created_at,
            (SELECT COUNT(posts.id) FROM posts WHERE posts.user_id = users.id) AS user_posts_count,
            (SELECT COUNT(subscriptions.id) FROM subscriptions WHERE subscriptions.user_id = users.id) AS user_subscribers_count
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
debug($post);

if (!$post) {
    $main_content = include_template('404.php');
    $layout_content = include_template('layout.php', [
        'header' => include_template('header.php', [
            http_response_code(404),
            'title' => "readme: Пост не найден",
            'is_auth' => rand(0, 1),
            'user_name' => 'Марчков Вячеслав',
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

    $main_content = include_template('post.php', [
        'post' => $post,
        'post_content' => $post_content,
    ]);

    $layout_content = include_template('layout.php', [
        'header' => include_template('header.php', [
            'is_auth' => rand(0, 1),
            'user_name' => 'Марчков Вячеслав',
            'title' => 'readme: ' . $post['heading'],
        ]),
        'content' => $main_content,
        'footer' => include_template('footer.php'),
    ]);
}

print($layout_content);
