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
$tab = empty($input['type']) ? '' : $input['type'];

$sql = "SELECT
            posts.id AS id,
            posts.heading,
            posts.content,
            posts.quote_author,
            posts.user_id,
            posts.author_id,
            posts.tag_ids,
            posts.reposts_count,
            posts.is_reposted,
            posts.created_at,
            posts.updated_at,
            users.login,
            users.userpic,
            types.type,
            (SELECT users.login FROM users WHERE users.id = posts.author_id) AS author_login,
            (SELECT users.userpic FROM users WHERE users.id = posts.author_id) AS author_userpic,
            (SELECT COUNT(likes.id) FROM likes WHERE likes.post_id = posts.id) AS likes_count
        FROM
            posts
        JOIN
            users ON users.id = posts.user_id
        JOIN
            types ON types.id = posts.type_id
        WHERE
            user_id IN (SELECT subscriptions.user_id FROM subscriptions WHERE subscriber_id = ?)
";

$sql_data = [$cur_user_id];
$params = [];

if ($input) {
    foreach ($input as $k => $v) {
        if ($k == 'type') {
            $type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
            $sql .= " AND type_id = (SELECT types.id FROM types WHERE types.type = ?)";
            array_push($sql_data, $type);
        }
        if ($k == 'do') {
            switch ($v) {
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
    }
}

$sql .= " ORDER BY posts.created_at DESC";
$posts = selectRows($conn, $sql, $sql_data);

$url = "/" . pathinfo(__FILE__, PATHINFO_BASENAME) . "?" . http_build_query($params);

if ($posts) {
    foreach ($posts as $post) {
        if (!empty($post['tag_ids'])) {
            $sql = "SELECT id, name FROM tags WHERE id IN ({$post['tag_ids']})";
            $stmt = db_get_prepare_stmt($conn, $sql);
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
}

$main_content = include_template('feed.php', [
    'url' => $url,
    'tab' => $tab,
    'types' => $types,
    'posts' => $posts ?? [],
]);

$layout_content = include_template('layout.php', [
    'header' => include_template('header.php', [
        'title' => 'readme: Моя лента',
    ]),
    'content' => $main_content,
    'footer' => include_template('footer.php'),
]);

print($layout_content);
