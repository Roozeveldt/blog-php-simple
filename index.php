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

    $errors = validate_post_form($_POST, $login_rules);

    if (empty($errors)) {
        $login = h($_POST['login']);
        $sql = "SELECT id, login, email, password, userpic FROM users WHERE login = ? LIMIT 1";
        $user = selectRow($conn, $sql, [$login]);

        if (!$user) {
            $errors['password'] = 'Вы ввели неверный логин и/или пароль';
        } else {
            if (!password_verify($_POST['password'], $user['password'])) {
                $errors['password'] = 'Вы ввели неверный логин и/или пароль';
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

$layout_content = include_template('main.php', [
    'title' => 'readme: Блог, каким он должен быть',
    'errors' => isset($errors) ? $errors : [],
    'getPostVal' => isset($getPostVal) ? $getPostVal : [],
    'footer' => include_template('footer.php'),
]);

print($layout_content);
