<?php

// устанавливаем часовой пояс по умолчанию
date_default_timezone_set('Asia/Novosibirsk');

// количество постов на странице
$posts_per_page = 6;

// Подключение к базе данных
const DB_HOST = "127.0.0.1";
const DB_USER = "root";
const DB_PASS = "root_password";
const DB_NAME = "readme";

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn) {
    mysqli_set_charset($conn, "utf8");

    // список проектов для текущего пользователя
    $sql = "SELECT id, name, type FROM types";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $types = [];
    foreach ($rows as $row) {
        $types[] = $row;
    }

    // список задач у текущего пользователя
    // TODO: объединить посты с пользователями
    $sql = "SELECT
                posts.id AS post_id,
                heading,
                content,
                reposts_count,
                likes_count,
                login,
                userpic,
                type
            FROM posts
            JOIN users ON users.id = posts.user_id
            JOIN types ON types.id = posts.type_id
            ORDER BY reposts_count DESC
            LIMIT 0, $posts_per_page
    ";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $posts = [];
    foreach ($rows as $row) {
        $posts[] = $row;
    }

    // Генерируем контент для главной страницы
    $main_content = include_template('main.php', [
        'types' => $types,
        'posts' => $posts,
    ]);

    // Подключаем основной шаблон
    $layout_content = include_template('layout.php', [
        'is_auth' => rand(0, 1),
        'user_name' => 'Марчков Вячеслав',
        'title' => 'readme: популярное',
        'content' => $main_content,
    ]);

    // Выводим шаблон и в нём контент главной страницы
    print($layout_content);
} else {
    print("Ошибка подключения к базе данных: " . mysqli_connect_error());
}

/**
 * Обрезает текст до указанной длины (в символах)
 *
 * @param string $text
 * @param integer $max_length
 * @return string Обрезанный до указанной длины текст
 */
function sliceText($text, $max_length = 300): string
{
    if (strlen($text) <= $max_length) {
        $text = "<p>" . $text . "</p>";
    } else {
        $arr = explode(" ", $text);
        $length = 0;
        $arr_text = [];
        foreach($arr as $key => $value) {
            $length += mb_strlen($value, 'UTF-8');
            $arr_text[] = $value;
            if ($length >= $max_length) {
                break;
            }
        }
        $text = "<p>" . implode(" ", $arr_text) . "...</p>" . "<a class='post-text__more-link' href='#'>Читать далее</a>";
    }

    return $text;
}

/**
 * Эмуляция разных дат для постов
 *
 * @param $index - Индекс записи в массиве $posts
 * @return false|string
 */
function generate_random_date($index)
{
    $deltas = [
        ['minutes' => 59],
        ['hours' => 23],
        ['days' => 6],
        ['weeks' => 4],
        ['months' => 11]
    ];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
}

/**
 * Получает относительное время между текущим моментом и предыдущим моментом
 *
 * @param string $post_date Дата в виде "2019-11-12 04:05:00"
 * @return string Строка в виде "15 минут назад" или "2 часа назад"
 */
function getRelativePostDate(string $post_date): string
{
    $cur_tstmp = strtotime('now');
    $post_tstmp = strtotime($post_date);
    $diff = $cur_tstmp - $post_tstmp;

    switch ($diff) {

        // минуты - если до текущего времени прошло меньше 60 минут, то формат будет вида «% минут назад»
        case ($diff < (60 * 60)) :
            $time = round($diff / 60);
            $str = get_noun_plural_form($time, 'минута', 'минуты', 'минут');
            break;

        // часы - если до текущего времени прошло больше 60 минут, но меньше 24 часов, то формат будет вида «% часов назад»
        case ($diff >= 3600 && $diff < (24 * 3600)) :
            $time = round($diff / (60 * 60));
            $str = get_noun_plural_form($time, 'час', 'часа', 'часов');
            break;

        // дни - если до текущего времени прошло больше 24 часов, но меньше 7 дней, то формат будет вида «% дней назад»
        case ($diff >= (24 * 3600) && $diff < (24 * 3600 * 7)) :
            $time = round($diff / (60 * 60 * 24));
            $str = get_noun_plural_form($time, 'день', 'дня', 'дней');
            break;

        // недели - если до текущего времени прошло больше 7 дней, но меньше 5 недель, то формат будет вида «% недель назад»
        case ($diff >= (24 * 3600 * 7) && $diff < (24 * 3600 * 7 * 5)) :
            $time = round($diff / (60 * 60 * 24 * 7));
            $str = get_noun_plural_form($time, 'неделя', 'недели', 'недель');
            break;

        // месяцы - если до текущего времени прошло больше 5 недель, то формат будет вида «% месяцев назад»
        default :
            $time = round($diff / 2629746);
            $str = get_noun_plural_form($time, 'месяц', 'месяца', 'месяцев');
            break;
    }

    return $time . " " . $str . " назад";
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}
