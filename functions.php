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
        $api_data = [
            'id' => $id,
            'part' => 'id,status',
            'key' => 'AIzaSyAfKT2Ze2bVv5KtjJLOEZm0ed_j3UxLB3U',
        ];
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
        $res = '<img alt="youtube cover" width="360" height="188" src="' . $src . '" />';
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
        if (isset($parts['path']) && $parts['path'] == '/watch') {
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
        case ($diff < (60 * 60)):
            $time = round($diff / 60);
            $str = get_noun_plural_form($time, 'минута', 'минуты', 'минут');
            break;

        // часы - если до текущего времени прошло больше 60 минут, но меньше 24 часов, то формат будет вида «% часов назад»
        case ($diff >= 3600 && $diff < (24 * 3600)):
            $time = round($diff / (60 * 60));
            $str = get_noun_plural_form($time, 'час', 'часа', 'часов');
            break;

        // дни - если до текущего времени прошло больше 24 часов, но меньше 7 дней, то формат будет вида «% дней назад»
        case ($diff >= (24 * 3600) && $diff < (24 * 3600 * 7)):
            $time = round($diff / (60 * 60 * 24));
            $str = get_noun_plural_form($time, 'день', 'дня', 'дней');
            break;

        // недели - если до текущего времени прошло больше 7 дней, но меньше 5 недель, то формат будет вида «% недель назад»
        case ($diff >= (24 * 3600 * 7) && $diff < (24 * 3600 * 7 * 5)):
            $time = round($diff / (60 * 60 * 24 * 7));
            $str = get_noun_plural_form($time, 'неделя', 'недели', 'недель');
            break;

        // месяцы - если до текущего времени прошло больше 5 недель, то формат будет вида «% месяцев назад»
        default:
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
        foreach ($arr as $key => $value) {
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

/**
 * Получает значение из поля input массива POST для вывода в процессе валидации
 *
 * @param string $name
 * @return string|null
 */
function getPostVal($name)
{
    return !empty($_POST[$name]) ? h($_POST[$name]) : "";
}

/**
 * Очищает введенные в поля данные
 *
 * @param string $string
 * @return string
 */
function h($string)
{
    return htmlspecialchars(trim($string));
}

/**
 * Валидирует формат загруженного файла
 *
 * @param array $file
 * @return bool
 */
function check_uploaded_file_format(string $name) : bool
{
    $file = $_FILES[$name];

    $allowed_types = [
        'image/gif',
        'image/png',
        'image/jpeg',
    ];

    $file_name = $file['name'];
    $file_ext = explode('.', $file_name);
    $file_ext = strtolower(end($file_ext)); // jpg
    $file_tmp_name = $file['tmp_name'];
    $type = mime_content_type($file_tmp_name);

    if (!in_array($type, $allowed_types)) {
        return false;
    }

    return true;
}

/**
 * Валидирует размер загружаемого файла
 *
 * @param string $name
 * @param int $allowed_max_size
 * @return boolean
 */
function check_uploaded_file_size(string $name, $allowed_max_size) : bool
{
    $file = $_FILES[$name];

    $file_size = $file['size'];

    if ($file_size > $allowed_max_size) {
        return false;
    }

    return true;
}

function handle_uploaded_file($file, $avatar = false)
{
    $file_name = $file['name'];
    $file_ext = explode('.', $file_name);
    $file_ext = strtolower(end($file_ext));
    $file_tmp_name = $file['tmp_name'];
    $file_new_name = uniqid() . '.' . $file_ext;
    $path = (!$avatar) ? __DIR__ . '/uploads/' : __DIR__ . '/uploads/userpic/';

    return (@move_uploaded_file($file_tmp_name, $path . $file_new_name))
        ? $file_new_name
        : false;
}

function handle_attached_file($img_url)
{
    $path = __DIR__ . '/uploads/';
    $file_ext = explode('.', $img_url);
    $file_ext = strtolower(end($file_ext));
    $file_new_name = uniqid() . '.' . $file_ext;

    $image = file_get_contents($img_url);
	file_put_contents($path . $file_new_name, $image);

    return $file_new_name;
}

function check_attached_file_format(string $img_url) : bool
{
    $allowed_types = [
        'gif',
        'png',
        'jpeg',
        'jpg',
    ];

    $file_ext = explode('.', $img_url);
    $file_ext = strtolower(end($file_ext));

    if (!in_array($file_ext, $allowed_types)) {
        return false;
    }

    return true;
}

/**
 * Валидирует данные из форм, переданные через массив $_POST
 *
 * @param array $post
 * @return array|void Массив с ошибками либо пустой массив
 */
function validate_post_form($post, $rules)
{
    $errors = [];

    foreach ($post as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    return array_filter($errors);
}

/**
 * Валидирует поле формы добавления поста-картинки,
 * переданное через массив $_POST и присваивает сообщения об ошибке
 *
 * @param string $name
 * @return string
 */
function validate_photo_field($name)
{
    $allowed_max_size = 1 * 1024 * 1024; // 1048576 байт = 1Мб

    $messages = [
        'photo-heading' => [
            'required' => [
                'Заголовок поста',
                'Это поле должно быть заполнено.',
            ],
        ],
        'photo-url' => [
            'required' => [
                'Ссылка на фото',
                'Введите ссылку на любое фото из сети или загрузите фото весом не более ' . ($allowed_max_size / (1024 * 1024)) . ' Мб.',
            ],
            'wrong-format' => [
                'Неверный формат',
                'Загружаемый файл должен иметь формат GIF, JPG или PNG',
            ],
            'wrong-size' => [
                'Превышен размер',
                'Максимальный размер файла: ' . ($allowed_max_size / (1024 * 1024)) . ' Мб',
            ],
            'is_not_valid_url' => [
                'Неверный формат',
                'Ссылка должна иметь вид https:// и т.д.',
            ],
            'file_not_found' => [
                'Файл не найден',
                'Ссылка на файл указана некорректно.',
            ],
        ],
        'photo-tags' => [
            'required' => [
                'Теги для поста',
                'Это поле должно быть заполнено.',
            ],
        ],
    ];

    if (empty($_POST[$name]) && $name != 'photo-url') {
        return $messages[$name]['required'];
    }

    if ($name == 'photo-url') {
        if (fileUploaded('photo-file')) {
            if (!check_uploaded_file_format('photo-file')) {
                return $messages[$name]['wrong-format'];
            }
            if (!check_uploaded_file_size('photo-file', $allowed_max_size)) {
                return $messages[$name]['wrong-size'];
            }
        } else {
            if (empty($_POST[$name])) {
                return $messages[$name]['required'];
            }
            if (!validate_url($_POST[$name])) {
                return $messages[$name]['is_not_valid_url'];
            }
            if (!check_attached_file_format($_POST[$name])) {
                return $messages[$name]['wrong-format'];
            }
            if (!is_file_exists($_POST[$name])) {
                return $messages[$name]['file_not_found'];
            }
        }
    }
}

function validate_video_field($name)
{
    $messages = [
        'video-heading' => [
            'required' => [
                'Заголовок видео-поста',
                'Это поле должно быть заполнено.',
            ],
        ],
        'video-url' => [
            'required' => [
                'Ссылка на YouTube',
                'Это поле должно быть заполнено.',
            ],
            'is_not_valid_url' => [
                'Неверный формат',
                'Ссылка должна иметь вид https:// и т.д.',
            ],
            'video_not_exists' => [
                'Видео не найдено',
                'По этой ссылке не найдено видео на YouTube.',
            ],
        ],
        'video-tags' => [
            'required' => [
                'Теги для поста',
                'Это поле должно быть заполнено.',
            ],
        ],
    ];

    if (empty($_POST[$name])) {
        return $messages[$name]['required'];
    }

    if ($name == 'video-url') {
        if (!validate_url($_POST[$name])) {
            return $messages[$name]['is_not_valid_url'];
        }
        if (!check_youtube_url($_POST[$name])) {
            return $messages[$name]['video_not_exists'];
        }
    }
}

function validate_text_field($name)
{
    $messages = [
        'text-heading' => [
            'required' => [
                'Заголовок поста',
                'Это поле должно быть заполнено.',
            ],
        ],
        'text-content' => [
            'required' => [
                'Текст публикации',
                'Это поле должно быть заполнено.',
            ],
        ],
        'text-tags' => [
            'required' => [
                'Теги для поста',
                'Это поле должно быть заполнено.',
            ],
        ],
    ];

    if (empty($_POST[$name])) {
        return $messages[$name]['required'];
    }
}

function validate_quote_field($name)
{
    $messages = [
        'quote-heading' => [
            'required' => [
                'Заголовок цитаты',
                'Это поле должно быть заполнено.',
            ],
        ],
        'quote-content' => [
            'required' => [
                'Текст цитаты',
                'Это поле должно быть заполнено.',
            ],
        ],
        'quote-author' => [
            'required' => [
                'Автор цитаты',
                'Это поле должно быть заполнено.',
            ],
        ],
        'quote-tags' => [
            'required' => [
                'Теги для поста',
                'Это поле должно быть заполнено.',
            ],
        ],
    ];

    if (empty($_POST[$name])) {
        return $messages[$name]['required'];
    }
}

function validate_link_field($name)
{
    $messages = [
        'link-heading' => [
            'required' => [
                'Заголовок ссылки',
                'Это поле должно быть заполнено.',
            ],
        ],
        'link-url' => [
            'required' => [
                'Ссылка на ресурс',
                'Это поле должно быть заполнено.',
            ],
            'is_not_valid_url' => [
                'Неверный формат',
                'Ссылка должна иметь вид https:// и т.д.',
            ],
        ],
        'link-tags' => [
            'required' => [
                'Теги для поста',
                'Это поле должно быть заполнено.',
            ],
        ],
    ];

    if (empty($_POST[$name])) {
        return $messages[$name]['required'];
    }

    if ($name == 'link-url') {
        if (!validate_url($_POST[$name])) {
            return $messages[$name]['is_not_valid_url'];
        }
    }
}

/**
 * Валидирует поле формы добавления пользователя,
 * переданное через массив $_POST и присваивает сообщения об ошибке
 *
 * @param string $name
 * @return void
 */
function validate_user_field($name)
{
    $allowed_max_size = 1 * 1024 * 1024; // 1048576 байт = 1Мб

    $messages = [
        'registration-email' => [
            'required' => [
                'Адрес эл.почты',
                'Это поле должно быть заполнено.',
            ],
            'is_not_valid_email' => [
                'Неверный e-mail',
                'Введите корректный e-mail адрес.',
            ],
            'is_email_taken' => [
                'Адрес e-mail занят',
                'Этот e-mail уже занят. Используйте другой e-mail.'
            ],
        ],
        'registration-login' => [
            'required' => [
                'Логин пользователя',
                'Это поле должно быть заполнено.',
            ],
            'is_login_taken' => [
                'Логин занят',
                'Такой логин уже есть на блоге. Придумайте другой.'
            ],
        ],
        'registration-password' => [
            'required' => [
                'Придумайте пароль',
                'Это поле должно быть заполнено.',
            ],
        ],
        'registration-password-repeat' => [
            'required' => [
                'Повтор пароля',
                'Это поле должно быть заполнено.',
            ],
            'passwords_not_equal' => [
                'Пароли не совпадают',
                'Повтор пароля и пароль должны совпадать.',
            ],
        ],
        'userpic-file' => [
            'wrong-format' => [
                'Неверный формат',
                'Загружаемый файл должен иметь формат GIF, JPG или PNG',
            ],
            'wrong-size' => [
                'Превышен размер',
                'Максимальный размер файла: ' . ($allowed_max_size / (1024 * 1024)) . ' Мб',
            ],
        ],
    ];

    if (empty($_POST[$name]) && $name != 'userpic-file') {
        return $messages[$name]['required'];
    }

    if ($name == 'registration-email') {
        if (!validate_email($_POST[$name])) {
            return $messages[$name]['is_not_valid_email'];
        }
        if (is_email_taken($_POST[$name])) {
            return $messages[$name]['is_email_taken'];
        }
    }

    if ($name == 'registration-login') {
        if (is_login_taken($_POST[$name])) {
            return $messages[$name]['is_login_taken'];
        }
    }

    if ($name == 'registration-password-repeat') {
        if (!compare_passwords($_POST[$name], $_POST['registration-password'])) {
            return $messages[$name]['passwords_not_equal'];
        }
    }

    if ($name == 'userpic-file') {
        if (!check_uploaded_file_format('userpic-file')) {
            return $messages[$name]['wrong-format'];
        }
        if (!check_uploaded_file_size('userpic-file', $allowed_max_size)) {
            return $messages[$name]['wrong-size'];
        }
    }
}

function fileUploaded($name)
{
    if (empty($_FILES)) {
        return false;
    }
    $file = $_FILES[$name];
    if (!file_exists($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return false;
    }

    return true;
}

/**
 * Проверяет, является ли ссылка валидной ссылкой
 *
 * @param string $str
 * @return bool
 */
function validate_url($str) : bool
{
    return filter_var(strtolower(trim($str)), FILTER_VALIDATE_URL);
}

/**
 * Проверяет введенный email на соответствие формату
 *
 * @param string $name
 * @return bool|string
 */
function validate_email($name)
{
    return filter_var(strtolower(trim($name)), FILTER_VALIDATE_EMAIL);
}

/**
 * Проверяет, есть ли введенный email в базе данных
 *
 * @param string $email
 * @return boolean True - если email найден в БД и False, если не найден
 */
function is_email_taken($email)
{
    global $conn;
    $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";

    return selectRow($conn, $sql, [$email]) ? true : false;
}

/**
 * Проверяет, есть ли введенный login в базе данных
 *
 * @param string $login
 * @return boolean True - если login найден в БД и False, если не найден
 */
function is_login_taken($login)
{
    global $conn;
    $sql = "SELECT id FROM users WHERE login = ? LIMIT 1";

    return selectRow($conn, $sql, [$login]) ? true : false;
}

function compare_passwords(string $cpass, string $pass) : bool
{
    return ($cpass == $pass);
}

function is_file_exists($img_url)
{
    $headers = get_headers($img_url);

    return (preg_match("|200|", $headers[0])) ? true : false;
}

/**
 * Превращает строку тегов в массив
 *
 * @param string $tags
 * @return array
 */
function process_tags(string $tags) : array
{
    return array_filter(explode(' ', $tags));
}

/**
 * Вставляет теги в таблицу тегов БД
 *
 * @param mysqli $conn
 * @param array $tags
 * @param integer $post_id
 * @return string
 */
function insert_tags_into_db($conn, array $tags, int $post_id) : string
{
    $ids = '';
    foreach ($tags as $k => $v) {
        $v = mb_strtolower($v);
        $sql = "INSERT INTO tags (
                    name,
                    post_id
                ) VALUES (?, ?)";

        $stmt = db_get_prepare_stmt($conn, $sql, [$v, $post_id]);
        mysqli_stmt_execute($stmt);
        $ids .= mysqli_insert_id($conn) . ',';
    }

    return rtrim($ids, ',');
}

/**
 * Вставляет теги в пост
 *
 * @param mysqli $conn
 * @param string $value
 * @param integer $id
 * @return void
 */
function update_post_tags($conn, string $value, int $id)
{
    $sql = "UPDATE posts SET tag_ids = ? WHERE id = ?";
    $stmt = db_get_prepare_stmt($conn, $sql, [$value, $id]);
    mysqli_stmt_execute($stmt);

    return mysqli_stmt_affected_rows($stmt);
}

/**
 * Добавляет новый лот в базу данных
 *
 * @param mysqli $conn
 * @param array $post
 * @param string $image
 * @return int Возвращает ID добавленной записи
 */
function insert_photo_post_into_db($conn, $post, $image)
{
    $data = [
        'heading'   => h($post['photo-heading']),
        'user_id'   => 5,
        'type_id'   => (int)$post['type_id'],
        'content'   => $image,
    ];

    $tags = process_tags(h($post['photo-tags']));

    mysqli_begin_transaction($conn);

    try {
        $sql = "INSERT INTO posts (
            heading,
            user_id,
            type_id,
            content
        ) VALUES (?, ?, ?, ?)";

        $stmt = db_get_prepare_stmt($conn, $sql, $data);
        mysqli_stmt_execute($stmt);

        $post_id = mysqli_insert_id($conn);
        $tag_ids = insert_tags_into_db($conn, $tags, $post_id);
        update_post_tags($conn, $tag_ids, $post_id);

        mysqli_commit($conn);

        return $post_id;
    } catch (mysqli_sql_exception $exception) {
        mysqli_rollback($conn);

        throw $exception;
    }
}

/**
 * Добавляет пост-видео в БД
 *
 * @param mysqli $conn
 * @param array $post
 * @return int|void
 */
function insert_video_post_into_db($conn, $post)
{
    $data = [
        'heading'   => h($post['video-heading']),
        'user_id'   => 5,
        'type_id'   => (int)$post['type_id'],
        'content'   => h($post['video-url']),
    ];

    $tags = process_tags(h($post['video-tags']));

    mysqli_begin_transaction($conn);

    try {
        $sql = "INSERT INTO posts (
            heading,
            user_id,
            type_id,
            content
        ) VALUES (?, ?, ?, ?)";

        $stmt = db_get_prepare_stmt($conn, $sql, $data);
        mysqli_stmt_execute($stmt);

        $post_id = mysqli_insert_id($conn);
        $tag_ids = insert_tags_into_db($conn, $tags, $post_id);
        update_post_tags($conn, $tag_ids, $post_id);

        mysqli_commit($conn);

        return $post_id;
    } catch (mysqli_sql_exception $exception) {
        mysqli_rollback($conn);

        throw $exception;
    }
}

/**
 * Добавляет пост-текст в БД
 *
 * @param mysqli $conn
 * @param array $post
 * @return int|void
 */
function insert_text_post_into_db($conn, $post)
{
    $data = [
        'heading'   => h($post['text-heading']),
        'user_id'   => 5,
        'type_id'   => (int)$post['type_id'],
        'content'   => h($post['text-content']),
    ];

    $tags = process_tags(h($post['text-tags']));

    mysqli_begin_transaction($conn);

    try {
        $sql = "INSERT INTO posts (
            heading,
            user_id,
            type_id,
            content
        ) VALUES (?, ?, ?, ?)";

        $stmt = db_get_prepare_stmt($conn, $sql, $data);
        mysqli_stmt_execute($stmt);

        $post_id = mysqli_insert_id($conn);
        $tag_ids = insert_tags_into_db($conn, $tags, $post_id);
        update_post_tags($conn, $tag_ids, $post_id);

        mysqli_commit($conn);

        return $post_id;
    } catch (mysqli_sql_exception $exception) {
        mysqli_rollback($conn);

        throw $exception;
    }
}

/**
 * Добавляет пост-цитату в БД
 *
 * @param mysqli $conn
 * @param array $post
 * @return int|void
 */
function insert_quote_post_into_db($conn, $post)
{
    $data = [
        'heading'   => h($post['quote-heading']),
        'user_id'   => 5,
        'type_id'   => (int)$post['type_id'],
        'content'   => h($post['quote-content']),
        'quote_author' => h($post['quote-author']),
    ];

    $tags = process_tags(h($post['quote-tags']));

    mysqli_begin_transaction($conn);

    try {
        $sql = "INSERT INTO posts (
            heading,
            user_id,
            type_id,
            content,
            quote_author
        ) VALUES (?, ?, ?, ?, ?)";

        $stmt = db_get_prepare_stmt($conn, $sql, $data);
        mysqli_stmt_execute($stmt);

        $post_id = mysqli_insert_id($conn);
        $tag_ids = insert_tags_into_db($conn, $tags, $post_id);
        update_post_tags($conn, $tag_ids, $post_id);

        mysqli_commit($conn);

        return $post_id;
    } catch (mysqli_sql_exception $exception) {
        mysqli_rollback($conn);

        throw $exception;
    }
}

/**
 * Добавляет пост-ссылку в БД
 *
 * @param mysqli $conn
 * @param array $post
 * @return int|void
 */
function insert_link_post_into_db($conn, $post)
{
    $data = [
        'heading'   => h($post['link-heading']),
        'user_id'   => 5,
        'type_id'   => (int)$post['type_id'],
        'content'   => h($post['link-url']),
    ];

    $tags = process_tags(h($post['link-tags']));

    /* Начало транзакции */
    mysqli_begin_transaction($conn);

    try {
        $sql = "INSERT INTO posts (
            heading,
            user_id,
            type_id,
            content
        ) VALUES (?, ?, ?, ?)";

        $stmt = db_get_prepare_stmt($conn, $sql, $data);
        mysqli_stmt_execute($stmt);

        $post_id = mysqli_insert_id($conn);
        $tag_ids = insert_tags_into_db($conn, $tags, $post_id);
        update_post_tags($conn, $tag_ids, $post_id);

        /* Если код достигает этой точки без ошибок, фиксируем данные в базе данных. */
        mysqli_commit($conn);

        return $post_id;
    } catch (mysqli_sql_exception $exception) {
        mysqli_rollback($conn);

        throw $exception;
    }
}

/**
 * Добавляет нового пользователя в базу данных
 *
 * @param mysqli $conn
 * @param array $post
 * @return bool
 */
function insert_user_into_db($conn, $post, $avatar)
{
    $data = [
        'email'         => h($post['registration-email']),
        'login'         => h($post['registration-login']),
        'password'      => password_hash($post['registration-password'], PASSWORD_DEFAULT),
        'userpic'       => $avatar,
    ];

    $sql = "INSERT INTO users (
                email,
                login,
                password,
                userpic
            ) VALUES (?, ?, ?, ?)";

    $stmt = db_get_prepare_stmt($conn, $sql, $data);
    mysqli_stmt_execute($stmt);

    return true;
}
