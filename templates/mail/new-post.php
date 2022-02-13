<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 3.2 Final//EN'>
<html>
    <body style='background-color:#ffffff; font-family:Trebuchet MS,sans-serif; color:#000; font-size:16px;'>
        <h3><?= $login; ?>, добрый день!</h3>
        <p>Пользователь <b><?= $author; ?></b> только что опубликовал новую запись <b>«<?= $post; ?>»</b>. Посмотрите её на <a href="<?= PATH; ?>/profile.php?id=<?= $author_id; ?>">странице пользователя</a>.</p>
        <p>------------------<br>
        Уведомление от сервиса readme</p>
    </body>
</html>
