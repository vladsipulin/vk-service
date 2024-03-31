CREATE DATABASE IF NOT EXISTS vkService;
USE vkService;
CREATE TABLE IF NOT EXISTS users (
    id INT auto_increment primary key,
    email VARCHAR(255),
	password VARCHAR(255)
);
