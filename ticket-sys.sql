-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Июн 15 2014 г., 02:06
-- Версия сервера: 5.5.34
-- Версия PHP: 5.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `ticket-sys`
--

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) NOT NULL,
  `event_desc` text NOT NULL,
  `event_status` int(11) NOT NULL,
  `event_date` datetime NOT NULL,
  `event_booking` datetime NOT NULL,
  `event_sale` datetime NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_desc`, `event_status`, `event_date`, `event_booking`, `event_sale`) VALUES
(1, 'Матч', 'Матч описание, Матч описаниеМатч описаниеМатч описаниеМатч описаниеМатч описаниеМатч описание  Матч описание мМатч описание Матч описание Матч описание Матч описание ', 1, '2014-06-03 00:00:00', '2014-06-18 00:00:00', '0000-00-00 00:00:00'),
(2, 'Матч', 'Матч описание, Матч описаниеМатч описаниеМатч описаниеМатч описаниеМатч описаниеМатч описание  Матч описание мМатч описание Матч описание Матч описание Матч описание ', 1, '2014-06-03 00:00:00', '2014-06-18 00:00:00', '0000-00-00 00:00:00'),
(3, 'Концерт', 'йцуйцу йцуй цуйц\r\nуй\r\nцу\r\nйуй', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, '23123312', '123312312', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `event_status`
--

CREATE TABLE IF NOT EXISTS `event_status` (
  `estatus_id` int(11) NOT NULL AUTO_INCREMENT,
  `estatus_name` varchar(255) NOT NULL,
  PRIMARY KEY (`estatus_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `event_status`
--

INSERT INTO `event_status` (`estatus_id`, `estatus_name`) VALUES
(1, 'произошло'),
(2, 'отменено'),
(3, 'перенесено');

-- --------------------------------------------------------

--
-- Структура таблицы `place`
--

CREATE TABLE IF NOT EXISTS `place` (
  `place_id` int(11) NOT NULL AUTO_INCREMENT,
  `place_number` int(11) NOT NULL,
  PRIMARY KEY (`place_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

--
-- Дамп данных таблицы `place`
--

INSERT INTO `place` (`place_id`, `place_number`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15),
(16, 16),
(17, 17),
(18, 18),
(19, 19),
(20, 20),
(21, 21),
(22, 22),
(23, 23),
(24, 24),
(25, 25),
(26, 26),
(27, 27),
(28, 28),
(29, 29),
(30, 30),
(31, 31),
(32, 32),
(33, 33),
(34, 34),
(35, 35),
(36, 36),
(37, 37),
(38, 38),
(39, 39),
(40, 40),
(41, 41),
(42, 42),
(43, 43),
(44, 44),
(45, 45),
(46, 46),
(47, 47),
(48, 48),
(49, 49),
(50, 50);

-- --------------------------------------------------------

--
-- Структура таблицы `row`
--

CREATE TABLE IF NOT EXISTS `row` (
  `row_id` int(11) NOT NULL AUTO_INCREMENT,
  `row_number` int(11) NOT NULL,
  PRIMARY KEY (`row_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Дамп данных таблицы `row`
--

INSERT INTO `row` (`row_id`, `row_number`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15),
(16, 16),
(17, 17),
(18, 18),
(19, 19),
(20, 20);

-- --------------------------------------------------------

--
-- Структура таблицы `sector`
--

CREATE TABLE IF NOT EXISTS `sector` (
  `sector_id` int(11) NOT NULL AUTO_INCREMENT,
  `sector_name` varchar(30) NOT NULL,
  `sector_price` int(11) NOT NULL,
  PRIMARY KEY (`sector_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=54 ;

--
-- Дамп данных таблицы `sector`
--

INSERT INTO `sector` (`sector_id`, `sector_name`, `sector_price`) VALUES
(1, ' 1 Сектор', 110),
(2, ' 2 Сектор', 80),
(3, ' 3 Сектор', 55),
(4, ' 4 Сектор', 50),
(5, ' 5 Сектор', 45),
(6, ' 6 Сектор', 50),
(7, ' 7 Сектор', 45),
(8, ' 8 Сектор', 45),
(9, ' 9 Сектор', 45),
(10, ' 10 Сектор', 65),
(11, ' 11 Сектор', 80),
(12, ' 12 Сектор', 110),
(13, ' 13 Сектор', 130),
(14, ' 14 Сектор', 110),
(15, ' 15 Сектор', 80),
(16, ' 16 Сектор', 65),
(17, ' 17 Сектор', 55),
(18, ' 18 Сектор', 45),
(19, ' 19 Сектор', 45),
(20, ' 20 Сектор', 54),
(21, ' 21 Сектор', 45),
(22, ' 22 Сектор', 50),
(23, ' 23 Сектор', 55),
(24, ' 24 Сектор', 80),
(25, ' 25 Сектор', 110),
(26, 'VIP A Сектор', 250),
(27, 'VIP D Сектор', 150);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
