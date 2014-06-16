-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Июн 17 2014 г., 00:02
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
  `event_date` varchar(255) NOT NULL,
  `event_booking` varchar(255) NOT NULL,
  `event_sale` varchar(255) NOT NULL,
  `event_img_name` varchar(255) NOT NULL,
  `event_img_md5` varchar(255) NOT NULL,
  `event_img_path` varchar(255) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_desc`, `event_status`, `event_date`, `event_booking`, `event_sale`, `event_img_name`, `event_img_md5`, `event_img_path`) VALUES
(6, 'йцуйуйуйуц', '<p>йуйуйу</p>', 3, '15.06.2014 20:48', '15.06.2014 20:48', '15.06.2014 20:48', 'Best-HD-Wallpaper1.jpg', '06d5c44222b18f0d0a57d0997160ad02.jpg', '/images/events/'),
(7, 'hhhhhhhhhhhhhhh', '<p>hhhhhhhhhhhhhhhhhhhhhhhhhhhhhh</p>\r\n<p>&nbsp;</p>\r\n<p>h<img src="../images/image/302462452.jpg" alt="" width="443" height="277" /></p>', 3, '15.06.2014 21:11', '15.06.2014 21:11', '15.06.2014 21:11', 'Best-HD-Wallpaper1.jpg', '06d5c44222b18f0d0a57d0997160ad02.jpg', '/images/events/'),
(8, 'vbxcbxcbxcb12313', '<p>xcbxcb123</p>', 3, '11.06.2014 21:16', '30.06.2014 21:16', '05.07.2014 21:16', 'lightning_76.jpg', '87edd826582219b67a875eb8a25402a2.jpg', '/images/events/'),
(9, 'Концерт Океан Ельзы ', '<p>PHP 5.6 is currently being tested, and the features and changes noted in this section are subject to change before the final 5.6.0 release. Please double check this migration guide before deploying a stable 5.6 release to production.</p>', 3, '16.06.2014 22:17', '16.06.2014 22:17', '16.06.2014 22:17', 'lightning_32.jpg', 'db7205fd1e6e950387e29cf18d156495.jpg', '/images/events/'),
(10, 'nsbdgdbsdgbsgb', '<p>sdgbsd<img src="http://ticket-sys.loc/images/image/302462452.jpg" alt="" width="1920" height="1200" /></p>', -1, '16.06.2014 22:25', '16.06.2014 22:25', '16.06.2014 22:25', 'lightning_51.jpg', '9285dc0d94b7e36248c818ff435007ef.jpg', '/images/events/');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Дамп данных таблицы `sector`
--

INSERT INTO `sector` (`sector_id`, `sector_name`, `sector_price`) VALUES
(1, ' 1 Сектор', 110),
(2, ' 2 Сектор', 800),
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
