-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 28, 2013 at 08:36 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `musicbox`
--
CREATE DATABASE IF NOT EXISTS `musicbox` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `musicbox`;

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE IF NOT EXISTS `artists` (
  `artist_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `biography` text,
  `soundcloud_url` varchar(255) DEFAULT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `short_biography` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`artist_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`artist_id`, `name`, `biography`, `soundcloud_url`, `likes`, `created_at`, `short_biography`, `image`) VALUES
(2, 'Bondax', 'Bondax is a newly formed band consisting of 2 teenage boys. Despite the fact that Adam and George are still enjoying their teens, their music shimmers with a maturity and sophistication.\r\n\r\nBondax is a newly formed band consisting of 2 teenage boys. Despite the fact that Adam and George are still enjoying their teens, their music shimmers with a maturity and sophistication.\r\n\r\nBondax is a newly formed band consisting of 2 teenage boys. Despite the fact that Adam and George are still enjoying their teens, their music shimmers with a maturity and sophistication.', 'https://soundcloud.com/bondax', 0, 1377520713, 'Bondax is a newly formed band consisting of 2 teenage boys. Despite Adam and George are still teens, their music shimmers with a maturity.', '2.jpg'),
(11, 'Quadron', 'Quadron is a Danish duo consisting of singer Coco O (full name Coco Maja Hastrup Karshoj) and musician/producer Robin Hannibal (full name Robin Braun). The group calls itself and the music they provide, electronic soul.\r\n\r\nThe self-titled debut album Quadron was released in late July 2009. Paste Magazine named Quadron "Best of What''s Next" in August 2010, and New York Magazine named Quadron''s self-titled album number 7 in their top 10 best albums of the year.\r\n\r\nIn 2011, Quadron collaborated with American DJ and record producer Kaskade for the song "Waste Love" off his album Fire & Ice.\r\n\r\nThe name of the band refers to the racial heritage of the band members.', 'https://soundcloud.com/quadronmusic', 0, 1377538984, 'Quadron is a Danish duo consisting of singer Coco O and musician Robin Hannibal. The group calls itself and the music they provide, electronic soul.', '11.jpg'),
(12, 'Disclosure', 'Disclosure are Guy and Howard Lawrence, two brothers from the UK making house/future music. After their debut single on Moshi-Moshi Records in september 2010, the duo released a 4 track EP on Transparent Records a year after. \r\n\r\n2012 saw the release of “Tenderly / Flow” and The Face EP on Greco-Roman. Their first single for Universal Record’s PMR was called “Latch”. \r\n\r\nOn February 1st 2013, they released “White Noise”, featuring vocals by Aluna Francis from AlunaGeorge. \r\nTheir debut album “Settle” was released on June 3rd , preceded by the single “You & Me” released on April 28th and premiered by Annie Mac on BBC Radio One on April 19th.', 'https://soundcloud.com/disclosuremusic', 10, 1379932535, 'Disclosure are Guy and Howard Lawrence, two brothers from the UK making house/future music.The duo released their debut album in 2013.', '12.jpg'),
(13, 'Eclair Fifi', 'Eclair Fifi is the first lady of LuckyMe Music.Art.Parties - the exciting young electronic hip hop label from Scotland. She has encyclopedic knowledge of italo, latin freestyle, electro, hip hop, house & generally club music and has become one of the most sought after young djs from the UK, playing the newest, most cutting edge white labels from some of the worlds leading producers. \r\n\r\nIn June she played back 2 back with John Computer at Sonar Festival 2010 in front of an 8500 strong crowd, as well as heading up the LuckyMe New York Showcase in Manhattan, New York this August. \r\nAnd so to complete the World tour of 2010, she plays in Colombo, Sri Lanka on New Years Eve.', 'https://soundcloud.com/eclairfifi', 5, 1379932535, 'Eclair Fifi is the first lady of LuckyMe Music.Art.Parties - the exciting young electronic hip hop label from Scotland.', '13.jpg'),
(14, 'Hudson Mohawke', 'Hudson Mohawke, (b. Ross Birchard), also known as DJ Itchy, Hudson Mo or Hud Mo, is an electronic music producer/DJ from Glasgow, Scotland, affiliated with the LuckyMe collective of musicians and artists. He is signed to Warp Records and G.O.O.D. Music. Hudson released his debut album Butter in October 2009. \r\n\r\nAt the age of 15 Birchard, under the name DJ Itchy, was the youngest ever UK DMC finalist. His earliest gigs as a club DJ were with Glasgow Uni’s Subcity Radio where he was part of the culture city kids show and later other shows including Turntable Science with Pro Vinylist Karim and Cloudo’s Happy Hardcore show. Birchard became Hudson Mohawke after seeing the name engraved on a statue in the hallway of his accommodation.', 'https://soundcloud.com/hudsonmohawke', 3, 1379932779, 'Hudson Mohawke, (b. Ross Birchard), also known as DJ Itchy, Hudson Mo or Hud Mo, is an electronic music producer/DJ from Glasgow, Scotland.\r\n', '14.jpg'),
(15, 'Mister Lies', 'Mister Lies is the product of dorm-room insomnia, the development of a family of friends in a city of strangers under altered states and the feeling of homesickness for places where one only feels homesick. \r\n\r\nIt is also the operational alias for a 20-year-old producer, multi-instrumentalist and feral child who was raised by wolves in the forests of a Connecticut suburb. Somewhere down the road, those winds whisked him away and dropped him off in Chicago.', 'https://soundcloud.com/mister-lies', 0, 1379932779, 'Mister Lies is the product of dorm-room insomnia, the development of a family of friends in a city of strangers under altered states.', '15.jpg'),
(17, 'Irvin Dally', 'Irvin Dally was raised in southern Illinois, eventually moving to Colorado and growing up in Spain. Dally eventually found himself in Sacramento and Santa Rosa before setting up more permanently in Los Angeles. Self-taught and self-styled, Dally started out as a member of Sacramento band Brother before recording more experimental and expansive folk and pop music.\r\n\r\nHis cassette release, The Countryside of Southern Illinois and the Daydreams That Almost Got Me to 19 Years revealed an adventurous spirit early on through the focused course of a 26 minute song.', 'https://soundcloud.com/irvindally', 0, 1379932934, 'Self-taught and self-styled, Dally started out as a member of  band Brother before recording more experimental and expansive folk and pop music.', '17.jpg'),
(18, 'Maya Jane Coles', 'Maya Jane Coles is a music producer, audio engineer and DJ based in the United Kingdom, born in London of British and Japanese descent.\r\n\r\nUnder her real name, she mostly composes and plays house music, while her alias Nocturnal Sunshine is dedicated to dubstep.\r\nShe also takes part in an electronic dub duo called She Is Danger with Lena Cullen.', 'https://soundcloud.com/mayajanecoles', 0, 1379933057, 'Maya Jane Coles is a music producer, audio engineer and DJ based in the United Kingdom, born in London of British and Japanese descent.', '18.jpg'),
(19, 'Chromeo', 'Chromeo is an electrofunk duo based in Montreal, Canada and New York City. They are P-Thugg  (Patrick Gemayel) on keyboards, synthesizers and talk box, and Dave 1 (David Macklovitch) on guitar and lead vocals. \r\n\r\nThe two were best friends since childhood and officially formed the band in 2002. They describe themselves as the only successful Arab/Jewish collaboration since the beginning of time.', 'https://soundcloud.com/chromeo', 0, 1379933057, 'Chromeo is an electrofunk duo based in Montreal. They are P-Thugg on keyboards, synthesizers and talk box, and Dave 1 on guitar and lead vocals.', '19.jpg'),
(24, 'Crystal Castles', 'Crystal Castles is a Canadian electronic band formed in 2004 in Toronto consisting of producer Ethan Kath and vocalist Alice Glass. The duo is known for their chaotic live shows and lo-fi melancholic homemade productions. They released many limited vinyl EPs between 2006 and 2007.\r\n\r\nIn 2006, their first single/EP "Alice Practice" was released on vinyl. The release was limited to only 300 copies. "Alice Practice" would later be included on their debut album, Crystal Castles, released in 2008. Other singles from the album include "Crimewave", "Air War", "Courtship Dating" and "Vanished". The album received highly positive reviews and was listed on NME''s "Top 100 Greatest Albums of the Decade" list at No. 39.[1]\r\nIn 2010, they announced their second album, titled Crystal Castles, after they released their first studio EP, Celestica/Doe Deer. The album was their first release to chart on the Billboard Hot 100, and includes their first worldwide charting single, "Not In Love" featuring Robert Smith of The Cure. The album has received general acclaim and was placed on many 2010 top critics lists.\r\n\r\nTheir third album, (III) was released on November 12, 2012. Three singles have been released: "Plague", "Wrath of God" and "Sad Eyes".', 'https://soundcloud.com/crystal-castles', 50, 1380018142, 'Crystal Castles is a Canadian electronic band formed in 2004 in Toronto consisting of producer Ethan Kath and vocalist Alice Glass.', '24.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `artist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text,
  `published` tinyint(1) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `artist_id`, `user_id`, `comment`, `published`, `created_at`) VALUES
(6, 19, 1, 'This artist is really cool!', 1, 1380132466);

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE IF NOT EXISTS `likes` (
  `like_id` int(11) NOT NULL AUTO_INCREMENT,
  `artist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`like_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `artist_id`, `user_id`, `created_at`) VALUES
(1, 11, 1, 1380060156),
(2, 17, 1, 1380060156),
(3, 13, 1, 1380060180),
(4, 15, 1, 1380060180),
(5, 24, 1, 1380063121),
(6, 18, 1, 1380063163),
(7, 16, 1, 1380131610);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `salt` varchar(23) NOT NULL,
  `password` varchar(88) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `salt`, `password`, `mail`, `role`, `created_at`, `image`) VALUES
(1, 'admin', '1260889385528018eda0a12', 'YXylVBIE3HLEUNQEH5Z5bSua4vpEG0flg2V1OcpWw4wzel1nomjtkoG2XVKpug3R4hD18tI0Uj1r8z/3rXxtNg==', 'noreply@musicbox.nothing', 'ROLE_ADMIN', 1379889332, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
