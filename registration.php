<?php

require_once "config.php";

if (isset($_SESSION['user'])) {
    header("Location: " . PATH . '/feed.php');
    exit();
}

require_once "functions.php";
require_once "types.php";
require_once "validation.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $getPostVal = [];
    foreach ($_POST as $key => $value) {
        $getPostVal[$key] = getPostVal($key);
    }

    $errors = validate_post_form($_POST, $signup_rules);
    $key = array_key_first($_FILES);

    if (empty($errors)) {
        if (fileUploaded($key)) {
            $errors[$key] = validate_user_field($key);
            if (empty($errors[$key])) {
                $avatar = handle_uploaded_file($_FILES[$key], true);
                insert_user_into_db($conn, $_POST, $avatar);
                header("Location: " . PATH);
                exit();
            }
        } else {
            insert_user_into_db($conn, $_POST, DEFAULT_AVATAR);
            header("Location: " . PATH);
            exit();
        }
    }
}

$main_content = include_template('registration.php', [
    'errors' => isset($errors) ? $errors : [],
    'getPostVal' => isset($getPostVal) ? $getPostVal : [],
]);

$layout_content = include_template('layout.php', [
    'header' => include_template('header.php', [
        'title' => 'readme: Регистрация пользователя блога',
    ]),
    'content' => $main_content,
    'footer' => include_template('footer.php'),
]);

print($layout_content);
