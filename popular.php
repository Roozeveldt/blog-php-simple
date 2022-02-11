<?php

require_once('config.php');

if (!isset($_SESSION['user'])) {
    header("Location: " . PATH);
    exit();
} else {
    $cur_user_id = $_SESSION['user']['id'];
}

require_once('functions.php');
require_once('types.php');

$input = filter_input_array(INPUT_GET);

$default_sort = 'views';
$default_type = 'all';

$sort = empty($input['sort']) ? $default_sort : h($input['sort']);
$tab = empty($input['type']) ? $default_type : h($input['type']);

$sql_data = [];

$params['sort'] = $default_sort;
$params['type'] = $default_type;

$page = 1;
$offset = 0;

$sql = "SELECT
            posts.id AS id,
            posts.heading,
            posts.content,
            posts.quote_author,
            posts.user_id,
            posts.views_count,
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
";
$sql_count = "SELECT COUNT(id) AS count FROM posts";

if ($input) {
    foreach ($input as $k => $v) {
        if ($k == 'page') {
            $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
            $offset = ($page - 1) * PER_PAGE;
        }
        if ($k == 'type' && $v !== 'all') {
            $type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
            $where = " WHERE type_id = (SELECT types.id FROM types WHERE types.type = ?)";
            $sql .= $where;
            $sql_count .= $where;
            $params[$k] = $type;
            array_push($sql_data, $type);
        }
        if ($k == 'sort') {
            $sort = $v;
            $params[$k] = $sort;
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

switch ($sort) {
    case 'likes':
        $order_by = 'likes_count';
        break;

    case 'date':
        $order_by = 'created_at';
        break;

    default:
        $order_by = 'views_count';
        break;
}

$posts_count = selectRow($conn, $sql_count, $sql_data); // количество постов для пагинации
$total_pages = ceil($posts_count['count'] / PER_PAGE); // всего страниц

$sql .= " ORDER BY " . $order_by . " DESC";
$sql .= " LIMIT ?, ?";

array_push($sql_data, $offset);
array_push($sql_data, PER_PAGE);

$posts = selectRows($conn, $sql, $sql_data); // посты

$main_content = include_template('popular.php', [
    'params' => $params,
    'sort' => $sort,
    'default_sort' => $default_sort,
    'tab' => $tab,
    'types' => $types,
    'posts' => $posts ?? [],
    'page' => $page,
    'total_pages' => $total_pages,
]);

$layout_content = include_template('layout.php', [
    'header' => include_template('header.php', [
        'title' => 'readme: Популярное',
    ]),
    'content' => $main_content,
    'footer' => include_template('footer.php'),
]);

print($layout_content);
