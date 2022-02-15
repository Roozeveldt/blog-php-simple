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
$new_receiver = false;

if ($input) {
    foreach ($input as $k => $v) {
        if ($k == 'user_id') {
            $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_SPECIAL_CHARS);
            if (is_receiver_new($cur_user_id, $user_id)) {
                $new_receiver_data = selectRow($conn, "SELECT id, login, userpic FROM users WHERE id = ?", [$user_id]);
                $new_receiver = true;
            } else {
                $sql = "SELECT
                        messages.id,
                        messages.content,
                        messages.receiver_id,
                        messages.sender_id,
                        messages.created_at,
                        users.login,
                        users.userpic
                    FROM
                        messages
                    INNER JOIN
                        users
                    ON
                        users.id = messages.sender_id
                    WHERE
                        receiver_id = ?
                    AND
                        sender_id = ?
                    UNION
                    SELECT
                        messages.id,
                        messages.content,
                        messages.receiver_id,
                        messages.sender_id,
                        messages.created_at,
                        users.login,
                        users.userpic
                    FROM
                        messages
                    INNER JOIN
                        users
                    ON
                        users.id = messages.sender_id
                    WHERE
                        receiver_id = ?
                    AND
                        sender_id = ?
                    ORDER BY
                        created_at
                    ASC
                ";
                $messages = selectRows($conn, $sql, [$user_id, $cur_user_id, $cur_user_id, $user_id]);
                set_messages_as_read($user_id, $cur_user_id);
            }
            $tab = 'user-' . $v;
        }
    }
}

$sql = "SELECT DISTINCT receiver_id AS id FROM messages WHERE sender_id = ?
UNION SELECT DISTINCT sender_id AS id FROM messages WHERE receiver_id = ?";
$arr = selectRowsID($conn, $sql, [$cur_user_id, $cur_user_id]);
$arr_to_str = implode(",", $arr);

if ($arr_to_str !== '') {
    $sql = "SELECT
            id,
            login,
            userpic
        FROM
            users
        WHERE
            id
        IN ({$arr_to_str})";
    $data = selectRows($conn, $sql);
    foreach ($data as $key => $item) {
        $sql = "SELECT
                id,
                content,
                sender_id,
                receiver_id,
                created_at
            FROM
                messages
            WHERE
                sender_id = ?
            AND
                receiver_id = ?
            UNION
            SELECT
                id,
                content,
                sender_id,
                receiver_id,
                created_at
            FROM
                messages
            WHERE
                sender_id = ?
            AND
                receiver_id = ?
            ORDER BY
                created_at
            DESC
            LIMIT 1";
        $row = selectRow($conn, $sql, [$key, $cur_user_id, $cur_user_id, $key]);
        $data[$key]['message'] = $row;
    }
    foreach ($data as $key => $item) {
        $sql = "SELECT
                    COUNT(id) AS count
                FROM
                    messages
                WHERE
                    sender_id = ?
                AND
                    receiver_id = ?
                AND
                    is_new = '1'
        ";
        $count = selectRow($conn, $sql, [$key, $cur_user_id]);
        $data[$key]['message_count'] = $count['count'];
    }
}

if ($new_receiver) {
    $data[$new_receiver_data['id']] = $new_receiver_data;
}

// добавление сообщения
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $getPostVal = [];
    foreach ($_POST as $key => $value) {
        $getPostVal[$key] = getPostVal($key);
    }

    $errors = validate_post_form($_POST, $message_rules);

    if (empty($errors)) {
        insert_message_into_db($conn, $_POST);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

$main_content = include_template('messages.php', [
    'tab' => $tab ?? '',
    'receivers' => $data,
    'messages' => $messages ?? [],
    'cur_user_id' => $cur_user_id,
    'cur_user_login' => $cur_user_login,
    'cur_user_userpic' => $cur_user_userpic,
    'errors' => $errors ?? [],
    'getPostVal' => $getPostVal ?? [],
    'new_receiver' => $new_receiver,
]);

$layout_content = include_template('layout.php', [
    'header' => include_template('header.php', [
        'count_new_messages' => count_new_messages($cur_user_id),
        'title' => 'readme: Личные сообщения',
    ]),
    'content' => $main_content,
    'footer' => include_template('footer.php'),
]);

print($layout_content);
