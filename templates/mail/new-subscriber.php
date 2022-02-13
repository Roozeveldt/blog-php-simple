<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 3.2 Final//EN'>
<html>
    <body style='background-color:#ffffff; font-family:Trebuchet MS,sans-serif; color:#000; font-size:16px;'>
        <h3><?= $user_login; ?>, добрый день!</h3>
        <p>На вас подписался новый пользователь <b><?= $subscriber_login; ?></b>. Вот <a href="<?= PATH; ?>/profile.php?id=<?= $subscriber_id; ?>">ссылка</a> на его профиль.</p>
        <p>------------------<br>
        Уведомление от сервиса readme</p>
    </body>
</html>
