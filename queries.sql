-- INSERT существующий список типов
INSERT INTO types (name, type)
  VALUES
  ("Фото", "photo"),
  ("Видео", "video"),
  ("Текст", "text"),
  ("Цитата", "quote"),
  ("Ссылка", "link");

-- INSERT придумайте пару пользователей
INSERT INTO users (login, email, password, userpic)
  VALUES
  ("Эльвира Хайпулинова", "user1@gmail.com", "user1@gmail.com", "userpic-elvira.jpg"),
  ("Таня Фирсова", "user2@gmail.com", "user2@gmail.com", "userpic-tanya.jpg"),
  ("Петр Демин", "user3@gmail.com", "user3@gmail.com", "userpic-petro.jpg"),
  ("Марк Смолов", "user4@gmail.com", "user4@gmail.com", "userpic-mark.jpg"),
  ("Лариса Роговая", "user5@gmail.com", "user5@gmail.com", "userpic-larisa.jpg");

-- INSERT существующий список постов
INSERT INTO posts (
    heading,
    user_id,
    type_id,
    content
  )
  VALUES
  ("Цитата", 5, 4, "Мы в жизни любим только раз, а после ищем лишь похожих");

-- SELECT & UPDATE
-- получить список из всех проектов для одного пользователя;
-- получить список из всех задач для одного проекта;
-- пометить задачу как выполненную;
-- обновить название задачи по её идентификатору.
