<?php

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Проверяет, что переданная ссылка ведет на публично доступное видео с youtube
 * @param string $youtube_url Ссылка на youtube видео
 * @return bool
 */
function check_youtube_url($youtube_url)
{
    $res = false;
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $api_data = ['id' => $id, 'part' => 'id,status', 'key' => 'AIzaSyBN-AXBnCPxO3HJfZZdZEHMybVfIgt16PQ'];
        $url = "https://www.googleapis.com/youtube/v3/videos?" . http_build_query($api_data);

        $resp = file_get_contents($url);

        if ($resp && $json = json_decode($resp, true)) {
            $res = $json['pageInfo']['totalResults'] > 0 && $json['items'][0]['status']['privacyStatus'] == 'public';
        }
    }

    return $res;
}

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img alt="youtube cover" width="320" height="120" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id($youtube_url)
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] == '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } else {
            if ($parts['host'] == 'youtu.be') {
                $id = substr($parts['path'], 1);
            }
        }
    }

    return $id;
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

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

function debug($arr)
{
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

/**
 * Обрезает текст до указанной длины (в символах)
 *
 * @param string $text
 * @param integer $max_length
 * @return string Обрезанный до указанной длины текст
 */
function sliceText($text, $id, $max_length = 300): string
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
        $text = "<p>" . implode(" ", $arr_text) . "...</p>" . "<a class='post-text__more-link' href='post.php?id={$id}'>Читать далее</a>";
    }

    return $text;
}

function selectRows($link, $sql, $data = [])
{
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $arr = [];
    foreach ($rows as $row) {
        $arr[$row['id']] = $row;
    }

    return $arr;
}

function selectRow($link, $sql, $data)
{
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}

function displayUserRegistrationPeriod(string $date) : string
{
    $diff = str_replace(' назад', '', getRelativePostDate($date));
    $diff = explode(' ', $diff);
    if ($diff[0] >= 12 && str_contains($diff[1], 'месяц')) {
        $diff = ceil($diff[0] / 12) . ' '. get_noun_plural_form(
            ceil($diff[0] / 12),
            'год',
            'года',
            'лет'
        );

        return $diff . " на сайте";
    }

    return implode(' ', $diff) . " на сайте";
}

function displayPostsCount(int $count) : string
{
    return get_noun_plural_form(
        $count,
        'публикация',
        'публикации',
        'публикаций'
    );
}

function displaySubscribersCount(int $count) : string
{
    return get_noun_plural_form(
        $count,
        'подписчик',
        'подписчика',
        'подписчиков'
    );
}
