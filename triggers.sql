-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Июн 27 2014 г., 17:26
-- Версия сервера: 5.5.36-log
-- Версия PHP: 5.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `ticket-sys`
--

--
-- Структура таблицы `tickets_count`
--

CREATE TABLE IF NOT EXISTS `tickets_count` (
  `count_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `sector_id` int(11) NOT NULL,
  `row_no` int(11) NOT NULL,
  `free_count` int(11) NOT NULL,
  PRIMARY KEY (`count_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

--
-- Триггеры `events`
--
DROP TRIGGER IF EXISTS `count_create`;
DELIMITER //
CREATE TRIGGER `count_create` AFTER INSERT ON `events`
 FOR EACH ROW BEGIN
	INSERT INTO tickets_count 
    (event_id, sector_id, row_no, free_count) 
    SELECT NEW.event_id, sector_id, row_no, count(place_no)
    	FROM place GROUP BY sector_id, row_no;
END
//
DELIMITER ;

--
-- Триггеры `tickets`
--
DROP TRIGGER IF EXISTS `counter_decrement`;
DELIMITER //
CREATE TRIGGER `counter_decrement` AFTER INSERT ON `tickets`
 FOR EACH ROW BEGIN
	IF NEW.ticket_type = 'purchased' 
    OR NEW.ticket_type = 'reserved' THEN
    	SELECT row_no
        	INTO @row_no
            FROM place
            WHERE place_id = NEW.place_id;
        SELECT sector_id
          	INTO @sector_id
            FROM place
            WHERE place_id = NEW.place_id;
    	UPDATE tickets_count
			SET free_count = free_count - 1
			WHERE event_id = NEW.event_id
        	AND sector_id = @sector_id
        	AND row_no = @row_no;
    END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `counter_decrement_update`;
DELIMITER //
CREATE TRIGGER `counter_decrement_update` AFTER UPDATE ON `tickets`
 FOR EACH ROW BEGIN
	IF NOT NEW.ticket_type IS NULL
    AND OLD.ticket_type IS NULL THEN
    	SELECT row_no
        	INTO @row_no
            FROM place
            WHERE place_id = NEW.place_id;
        SELECT sector_id
          	INTO @sector_id
            FROM place
            WHERE place_id = NEW.place_id;
    	UPDATE tickets_count
			SET free_count = free_count - 1
			WHERE event_id = NEW.event_id
        	AND sector_id = @sector_id
        	AND row_no = @row_no;
    END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `increment_counter`;
DELIMITER //
CREATE TRIGGER `increment_counter` AFTER DELETE ON `tickets`
 FOR EACH ROW BEGIN
	IF OLD.ticket_type IS NULL THEN
    	SELECT row_no
        	INTO @row_no
            FROM place
            WHERE place_id = OLD.place_id;
        SELECT sector_id
          	INTO @sector_id
            FROM place
            WHERE place_id = OLD.place_id;
    	UPDATE tickets_count
			SET free_count = free_count + 1
			WHERE event_id = OLD.event_id
        	AND sector_id = @sector_id
        	AND row_no = @row_no;
    END IF;
END
//
DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
