<?php

require_once "vendor/autoload.php";

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

// Email settings
// https://accounts.google.com/DisplayUnlockCaptcha
const SMTPHost = "smtp.gmail.com";
const SMTPTls = "tls";
const SMTPPort = 587;
const Username = "foodconsalting@gmail.com";
const Password = "cn7UEDRnIeQkwtcIMuWS";
const SenderName = "readme";
const SenderEmail = "foodconsalting@gmail.com";

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn) {
    mysqli_set_charset($conn, "utf8");
} else {
    print("Ошибка подключения к базе данных: " . mysqli_connect_error());
}

// Конфигурация траспорта
$transport = (new Swift_SmtpTransport(SMTPHost, SMTPPort, SMTPTls))
  ->setUsername(Username)
  ->setPassword(Password)
;

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);
