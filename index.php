<?php

require_once('config.php');
require_once('functions.php');
require_once('types.php');

$type_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$type_id = abs((int)$type_id);

$sql = "SELECT
            posts.id AS id,
            heading,
            content,
            quote_author,
            reposts_count,
            likes_count,
            login,
            userpic,
            type
        FROM posts
        JOIN users ON users.id = posts.user_id
        JOIN types ON types.id = posts.type_id
";

if ($type_id !== 0) {
    $sql .= "WHERE type_id = ?";
}

$sql .= " ORDER BY reposts_count DESC LIMIT 0, $posts_per_page";

$posts = selectRows($conn, $sql, ($type_id !== 0) ? [$type_id] : []);

$main_content = include_template('main.php', [
    'types' => $types,
    'posts' => $posts,
    'type_id' => $type_id ?? 0,
]);

$layout_content = include_template('layout.php', [
    'header' => include_template('header.php', [
        'is_auth' => 1,
        'user_name' => 'Марчков Вячеслав',
        'title' => 'readme: Популярное',
    ]),
    'content' => $main_content,
    'footer' => include_template('footer.php'),
]);

print($layout_content);
