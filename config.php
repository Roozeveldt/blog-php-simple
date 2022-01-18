<?php

// устанавливаем часовой пояс по умолчанию
date_default_timezone_set('Asia/Novosibirsk');

// количество постов на странице
$posts_per_page = 6;

const PATH = 'http://readme.local';

// Подключение к базе данных
const DB_HOST = "127.0.0.1";
const DB_USER = "root";
const DB_PASS = "root_password";
const DB_NAME = "readme";

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn) {
    mysqli_set_charset($conn, "utf8");
} else {
    print("Ошибка подключения к базе данных: " . mysqli_connect_error());
}
