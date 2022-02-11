<?php

// устанавливаем часовой пояс по умолчанию
date_default_timezone_set('Asia/Novosibirsk');

session_start();

// количество постов на странице
const PER_PAGE = 6;

// количество комментариев на странице
const COMMENTS_LIMIT = 2;

const PATH = 'http://readme.local';
const DEFAULT_AVATAR = 'userpic-big.jpg';

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
