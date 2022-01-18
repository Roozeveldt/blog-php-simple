-- create database
CREATE DATABASE IF NOT EXISTS readme
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE readme;

-- create tables
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  login VARCHAR(32) NOT NULL UNIQUE,
  email VARCHAR(128) NOT NULL,
  password VARCHAR(128) NOT NULL,
  userpic VARCHAR(255) NOT NULL DEFAULT "userpic-big.jpg",
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS types (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(32) NOT NULL,
  type VARCHAR(10) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS posts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  heading VARCHAR(255) NOT NULL,
  user_id INT UNSIGNED NOT NULL DEFAULT 1,
  type_id INT UNSIGNED NOT NULL DEFAULT 3,
  content TEXT NOT NULL,
  reposts_count INT UNSIGNED DEFAULT 0,
  likes_count INT UNSIGNED DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `comments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `content` TEXT NOT NULL ,
  `user_id` INT UNSIGNED NOT NULL ,
  `post_id` INT UNSIGNED NOT NULL ,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`)
  )
  ENGINE = InnoDB
  COMMENT = 'Текстовый комментарий, оставленный к одному из постов.'
;

CREATE TABLE IF NOT EXISTS `likes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT UNSIGNED NOT NULL ,
  `post_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`)
  )
  ENGINE = InnoDB
  COMMENT = 'Лайки, поставленные пользователями к постам.'
;

CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `subscriber_id` INT UNSIGNED NOT NULL COMMENT 'пользователь, который подписался' ,
  `user_id` INT UNSIGNED NOT NULL COMMENT 'пользователь, на которого подписались' ,
  PRIMARY KEY (`id`)
  )
  ENGINE = InnoDB
  COMMENT = 'Подписка одним пользователем на другого.'
;

-- index on tasks name
CREATE INDEX p_heading ON posts(heading);

-- foreign keys
ALTER TABLE posts
  ADD CONSTRAINT fk_type_id
  FOREIGN KEY (type_id)
  REFERENCES types (id);

ALTER TABLE posts
  ADD CONSTRAINT fk_user_id
  FOREIGN KEY (user_id)
  REFERENCES users (id);

ALTER TABLE `comments`
  ADD FOREIGN KEY (`user_id`)
  REFERENCES `users`(`id`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

ALTER TABLE `comments`
  ADD FOREIGN KEY (`post_id`)
  REFERENCES `posts`(`id`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

ALTER TABLE `likes`
  ADD FOREIGN KEY (`user_id`)
  REFERENCES `users`(`id`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

ALTER TABLE `likes`
  ADD FOREIGN KEY (`post_id`)
  REFERENCES `posts`(`id`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

ALTER TABLE `subscriptions`
  ADD FOREIGN KEY (`subscriber_id`)
  REFERENCES `users`(`id`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

ALTER TABLE `subscriptions`
  ADD FOREIGN KEY (`user_id`)
  REFERENCES `users`(`id`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;
