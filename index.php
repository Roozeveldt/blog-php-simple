<?php

$posts = [
    [
        "heading" => "Цитата",
        "type" => "post-quote",
        "content" => "Мы в жизни любим только раз, а после ищем лишь похожих",
        "user_name" => "Лариса",
        "userpic" => "userpic-larisa-small.jpg"
    ],
    [
        "heading" => "Игра престолов",
        "type" => "post-text",
        "content" => "Не могу дождаться начала финального сезона своего любимого сериала!",
        "user_name" => "Владик",
        "userpic" => "userpic.jpg"
    ],
    [
        "heading" => "Наконец, обработал фотки!",
        "type" => "post-photo",
        "content" => "rock-medium.jpg",
        "user_name" => "Владик",
        "userpic" => "userpic-mark.jpg"
    ],
    [
        "heading" => "Моя мечта",
        "type" => "post-photo",
        "content" => "coast-medium.jpg",
        "user_name" => "Лариса",
        "userpic" => "userpic-larisa-small.jpg"
    ],
    [
        "heading" => "Лучшие курсы",
        "type" => "post-link",
        "content" => "www.htmlacademy.ru",
        "user_name" => "Владик",
        "userpic" => "userpic.jpg"
    ],
    [
        "heading" => "Полезный пост про Байкал",
        "type" => "post-text",
        "content" => "Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской границы. Байкал считается самым глубоким озером в мире. Он окружен сетью пешеходных маршрутов, называемых Большой байкальской тропой. Деревня Листвянка, расположенная на западном берегу озера, – популярная отправная точка для летних экскурсий. Зимой здесь можно кататься на коньках и собачьих упряжках.",
        "user_name" => "Лариса Роговая",
        "userpic" => "userpic-larisa-small.jpg"
    ],
];

/**
 * Обрезает текст до указанной длины (в символах)
 *
 * @param string $text
 * @param integer $max_length
 * @return string Обрезанный до указанной длины текст
 */
function sliceText($text, $max_length = 300)
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

// Генерируем контент для главной страницы
$main_content = include_template('main.php', [
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
