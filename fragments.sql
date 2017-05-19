-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Gegenereerd op: 28 apr 2017 om 15:06
-- Serverversie: 5.7.9
-- PHP-versie: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fragments`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `blog`
--

DROP TABLE IF EXISTS `blog`;
CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `is_published` int(1) NOT NULL DEFAULT '0',
  `published_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `blog`
--

INSERT INTO `blog` (`id`, `title`, `slug`, `category_id`, `is_published`, `published_at`, `created_at`, `is_deleted`) VALUES
(1, 'Secrets of PHP', 'secrets-of-php', 1, 0, 0, 1493361930, 0),
(2, 'Advanced Javascript', 'advanced-javascript', 1, 0, 0, 1493361930, 0),
(3, 'A very cool, new, item', 'a-very-cool-new-item', 2, 0, 0, 1493374584, 0),
(4, 'PHP definitions', 'php-definitions', 2, 0, 0, 1493377722, 0),
(5, 'PHP magic methods2', 'php-magic-methods', 2, 0, 0, 1493377733, 0),
(6, 'Javascript Events', 'javascript-events', 2, 0, 0, 1493377754, 0),
(7, 'Javascript Event delegation', 'javascript-event-delegation', 2, 0, 0, 1493377766, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `blog_category`
--

DROP TABLE IF EXISTS `blog_category`;
CREATE TABLE IF NOT EXISTS `blog_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `is_deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `blog_category`
--

INSERT INTO `blog_category` (`id`, `title`, `slug`, `is_deleted`) VALUES
(1, 'Tech', 'tech', 0),
(2, 'Programming', 'programming', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `blog_content`
--

DROP TABLE IF EXISTS `blog_content`;
CREATE TABLE IF NOT EXISTS `blog_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `content` text,
  `class` varchar(255) NOT NULL,
  `sort_index` int(11) NOT NULL DEFAULT '0',
  `is_deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `blog_content`
--

INSERT INTO `blog_content` (`id`, `blog_id`, `type`, `content`, `class`, `sort_index`, `is_deleted`) VALUES
(19, 2, 1, 'a', 'col dt8 tb6 mb12', 1, 0),
(20, 2, 1, 'b', 'col dt4 tb6 mb12', 3, 0),
(21, 2, 1, 'c', 'col dt4 tb6 mb12', 0, 0),
(22, 2, 1, 'd', 'col dt4 tb6 mb12', 2, 0),
(23, 2, 1, 'e', 'col dt4 tb6 mb12', 4, 0),
(24, 2, 1, 'f', 'col dt12 tb12 mb12', 5, 0),
(25, 2, 1, 'g', 'col dt12 tb12 mb12', 6, 0),
(37, 2, 1, 'h', 'col dt12 tb12 mb12', 7, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password_hash`) VALUES
(1, 'xTheSl4y3rx', 'slayer@gmail.com', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
