-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 23 2015 г., 12:25
-- Версия сервера: 5.5.38
-- Версия PHP: 5.6.7-1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `new`
--

-- --------------------------------------------------------

--
-- Структура таблицы `album`
--

CREATE TABLE IF NOT EXISTS `album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artist` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `album`
--

INSERT INTO `album` (`id`, `artist`, `title`) VALUES
(3, 'Bruce  Springsteen', 'Wrecking Ball (Deluxe)'),
(4, 'Lana  Del  Rey', 'Born  To  Die'),
(5, 'Gotye', 'Making  Mirrors'),
(6, '12', '2');

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `image` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=87 ;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id`, `product_name`, `description`, `price`, `image`) VALUES
(71, 'wow', 'dogge', 12, 've1UUQtuplk.jpg'),
(85, 'pen', 'This is pen', 0.12, '333.png'),
(86, 'Pencil', 'This is pencil', 0.3, '2.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `state` smallint(6) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`user_id`, `username`, `email`, `display_name`, `password`, `state`, `role`) VALUES
(1, 'igor', 'bruce1993@mail.ru', NULL, '$2y$14$5w5MgSlVO7jS0RK4YrQ5c.0OcUW7O8X0zFDrriYsMCotmvi0c.ry2', NULL, NULL),
(2, NULL, 'bruce93@mail.ru', NULL, '$2y$14$330IvxyyBMmTgnht7Ze0Mec3F1FdkfZXrTI4R46jtB06KY8J3L4je', NULL, 'admin'),
(3, NULL, 'igor@mail.ru', NULL, '$2y$14$AxuIGljzFoWMrv34rQif7OrtUcbGoQxuDcqae41CS268iwDJJOZPq', NULL, NULL),
(4, NULL, 'batan999@mail.ru', NULL, '$2y$14$KnFWJ7rtLQ.2PxthIV9rL.cssLWNquNX3H4q1mKCeBbsPgFTb41MC', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
