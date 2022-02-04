<?php

require_once('config.php');

if (isset($_SESSION['user'])) {
    header("Location: " . PATH . '/feed.php');
    exit();
}

require_once('functions.php');
require_once('validation.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $getPostVal = [];
    foreach ($_POST as $key => $value) {
        $getPostVal[$key] = getPostVal($key);
    }

    $errors = validate_post_form($_POST, $signin_rules);

    if (empty($errors)) {
        $email = h($_POST['email']);
        $sql = "SELECT id, login, email, password, userpic FROM users WHERE email = ? LIMIT 1";
        $user = selectRow($conn, $sql, [$email]);

        if (!$user) {
            $errors['password'] = [
                'Ошибка!',
                'Вы ввели неверный логин и/или пароль',
            ];
        } else {
            if (!password_verify($_POST['password'], $user['password'])) {
                $errors['password'] = [
                    'Ошибка!',
                    'Вы ввели неверный логин и/или пароль',
                ];
            } else {
                foreach($user as $key => $value) {
                    if ($key != 'password') {
                        $_SESSION['user'][$key] = $value;
                    }
                }
                header("Location: " . PATH . '/feed.php');
                exit();
            }
        }
    }
}

$main_content = include_template('login.php', [
    'errors' => isset($errors) ? $errors : [],
    'getPostVal' => isset($getPostVal) ? $getPostVal : [],
]);

$layout_content = include_template('layout.php', [
    'header' => include_template('header.php', [
        'title' => 'readme: Авторизация',
    ]),
    'content' => $main_content,
    'footer' => include_template('footer.php'),
]);

print($layout_content);
