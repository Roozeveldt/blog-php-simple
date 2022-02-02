<?php

require_once('config.php');

/* if (!isset($_SESSION['user'])) {
    http_response_code(403);
    exit('У вас нет прав для добавления лота! Зарегистрируйтесь на сайте и добавьте лот.');
} */

require_once('functions.php');
require_once('validation.php');

// типы контента
require_once('types.php');

// включённая при первичной загрузке страницы вкладка
$type = 'text';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $getPostVal = [];
    foreach ($_POST as $key => $value) {
        $getPostVal[$key] = getPostVal($key);
    }

    $type = h($_POST['type']);
    $file = array_key_first($_FILES);

    switch ($type) {
        case 'photo':
            $errors = validate_post_form($_POST, $photo_post_rules);
            break;
        case 'video':
            $errors = validate_post_form($_POST, $video_post_rules);
            break;
        case 'text':
            $errors = validate_post_form($_POST, $text_post_rules);
            break;
        case 'quote':
            $errors = validate_post_form($_POST, $quote_post_rules);
            break;
        case 'link':
            $errors = validate_post_form($_POST, $link_post_rules);
            break;
        default:
            $errors = [
                'Неизвестная ошибка',
                'Произошла неизвестная ошибка. Попробуйте ещё раз.',
            ];
            break;
    }

    if (empty($errors)) {
        switch ($type) {
            case 'photo':
                $image = (is_uploaded_file($_FILES[$file]['tmp_name']))
                    ? handle_uploaded_file($_FILES[$file])
                    : handle_attached_file(h($_POST['photo-url']));
                $last_id = insert_photo_post_into_db($conn, $_POST, $image);
                break;
            case 'video':
                $last_id = insert_video_post_into_db($conn, $_POST);
                break;
            case 'text':
                $last_id = insert_text_post_into_db($conn, $_POST);
                break;
            case 'quote':
                $last_id = insert_quote_post_into_db($conn, $_POST);
                break;
            case 'link':
                $last_id = insert_link_post_into_db($conn, $_POST);
                break;
            default:
                # code...
                break;
        }
        header("Location: " . PATH . "/post.php?id=" . $last_id);
        exit();
    }
}

$main_content = include_template('add-post.php', [
    'type' => $type,
    'types' => $types,
    'errors' => isset($errors) ? $errors : [],
    'getPostVal' => isset($getPostVal) ? $getPostVal : [],
]);

$layout_content = include_template('layout.php', [
    'header' => include_template('header.php', [
        'is_auth' => rand(0, 1),
        'user_name' => 'Марчков Вячеслав',
        'title' => 'readme: Создание новой публикации',
    ]),
    'content' => $main_content,
    'footer' => include_template('footer.php'),
]);

print($layout_content);
