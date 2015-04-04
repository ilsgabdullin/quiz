-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Мар 30 2015 г., 12:11
-- Версия сервера: 5.1.30-community
-- Версия PHP: 5.4.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `poll`
--

-- --------------------------------------------------------

--
-- Структура таблицы `answer`
--

CREATE TABLE IF NOT EXISTS `answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Дамп данных таблицы `answer`
--

INSERT INTO `answer` (`id`, `question_id`, `title`) VALUES
(1, 1, 'Это мой первый визит'),
(2, 1, 'Раз в месяц и реже'),
(3, 1, 'Несколько раз в месяц'),
(4, 2, 'Новости'),
(5, 2, 'О компании'),
(6, 2, 'Производство'),
(7, 2, 'Контакты'),
(8, 3, 'Мужской'),
(9, 3, 'Женский'),
(10, 4, 'Меньше 20 лет'),
(11, 4, '20-30 лет'),
(12, 4, '31-40 лет'),
(13, 4, 'Старше 40'),
(14, 8, 'да'),
(15, 8, 'да'),
(18, 10, 'да'),
(19, 10, 'нет'),
(20, 8, 'нет'),
(21, 11, 'да'),
(22, 11, 'нет'),
(23, 11, 'наверное'),
(24, 12, 'да'),
(25, 12, 'нет');

-- --------------------------------------------------------

--
-- Структура таблицы `poll`
--

CREATE TABLE IF NOT EXISTS `poll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `state` (`state`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `poll`
--

INSERT INTO `poll` (`id`, `title`, `state`) VALUES
(1, 'Первый опрос', 1),
(2, 'Второй опрос', 2),
(4, 'Четвертый опрос', 3),
(5, 'Новый опрос', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `required` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `question`
--

INSERT INTO `question` (`id`, `poll_id`, `title`, `type`, `required`) VALUES
(1, 1, 'Как часто Вы заходите на сайт?', 1, 0),
(2, 1, 'Какие разделы представляют для Вас наибольший интерес?', 2, 1),
(3, 1, 'Ваш пол:', 1, 1),
(4, 1, 'Ваш возраст:', 1, 0),
(8, 2, 'Первый вопрос22', 1, 1),
(10, 2, 'Второй1', 1, 1),
(11, 5, 'Первый вопрос', 2, 1),
(12, 5, 'Второй вопрос', 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `result`
--

CREATE TABLE IF NOT EXISTS `result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `result`
--

INSERT INTO `result` (`id`, `poll_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 5),
(5, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `result_answer`
--

CREATE TABLE IF NOT EXISTS `result_answer` (
  `result_id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL,
  KEY `result_answer` (`result_id`,`answer_id`),
  KEY `result_id` (`result_id`),
  KEY `answer_id` (`answer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `result_answer`
--

INSERT INTO `result_answer` (`result_id`, `answer_id`) VALUES
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(2, 1),
(2, 5),
(2, 6),
(2, 9),
(2, 13),
(3, 2),
(3, 4),
(3, 8),
(3, 10),
(4, 21),
(4, 22),
(4, 24),
(5, 22),
(5, 23),
(5, 25);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
