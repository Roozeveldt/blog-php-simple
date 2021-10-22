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
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

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
