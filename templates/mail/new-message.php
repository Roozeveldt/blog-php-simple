<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 3.2 Final//EN'>
<html>
    <body style='background-color:#ffffff; font-family:Trebuchet MS,sans-serif; color:#000; font-size:16px;'>
        <h3><?= $receiver; ?>, добрый день!</h3>
        <p>Наш замечательный сервис уведомляет вас, что пользователь с ником <b><?= $sender; ?></b> отправил вам личное сообщение. Прочитайте его на <a href="<?= PATH; ?>/messages.php?user_id=<?= $sender_id; ?>#comments-form">странице личных сообщений</a>.</p>
        <p>------------------<br>
        Уведомление от сервиса readme</p>
    </body>
</html>
