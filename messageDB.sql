-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 產生時間： 2018 年 05 月 20 日 17:43
-- 伺服器版本: 5.7.22-0ubuntu0.16.04.1
-- PHP 版本： 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `messageDB`
--

-- --------------------------------------------------------

--
-- 資料表結構 `account`
--

CREATE TABLE `account` (
  `userid` int(11) NOT NULL,
  `username` varchar(1024) NOT NULL,
  `password` varchar(1024) NOT NULL,
  `email` varchar(1024) NOT NULL,
  `image` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `account`
--

INSERT INTO `account` (`userid`, `username`, `password`, `email`, `image`) VALUES
(0, 'root', 'root1234', 'iamroot@gmail.com', '0_image.jpeg'),
(1, 'mary', 'mary1234', 'prettymary@gmail.com', ''),
(2, 'john', 'john0204', 'johnny@gmail.com', ''),
(3, 'patric', 'patric9487', 'handsome@gmail.com', '3_image.jpeg'),
(4, 'vanessa', 'vanessa0601', 'vanessavanessa@gmail.com', '');

-- --------------------------------------------------------

--
-- 資料表結構 `command`
--

CREATE TABLE `command` (
  `userid` int(11) DEFAULT NULL,
  `postid` int(11) DEFAULT NULL,
  `commandid` int(11) NOT NULL,
  `text` varchar(1024) DEFAULT NULL,
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `command`
--

INSERT INTO `command` (`userid`, `postid`, `commandid`, `text`, `date_time`) VALUES
(0, 1, 1, 'okay', '2018-05-19 15:18:35'),
(0, 1, 2, 'no okay', '2018-05-19 15:18:35');

-- --------------------------------------------------------

--
-- 資料表結構 `post`
--

CREATE TABLE `post` (
  `userid` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `article` varchar(1024) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `post`
--

INSERT INTO `post` (`userid`, `postid`, `title`, `article`, `date_time`) VALUES
(0, 1, 'fuck', 'you', '2018-05-19 15:18:56'),
(0, 2, 'fuck', 'me', '2018-05-19 15:18:56');

-- --------------------------------------------------------

--
-- 資料表結構 `reply`
--

CREATE TABLE `reply` (
  `userid` int(11) DEFAULT NULL,
  `commandid` int(11) DEFAULT NULL,
  `replyid` int(11) NOT NULL,
  `text` varchar(1024) DEFAULT NULL,
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `reply`
--

INSERT INTO `reply` (`userid`, `commandid`, `replyid`, `text`, `date_time`) VALUES
(0, 2, 1, 'okay', '2018-05-19 15:19:16'),
(0, 2, 2, 'no okay', '2018-05-19 15:19:16');

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- 資料表索引 `command`
--
ALTER TABLE `command`
  ADD PRIMARY KEY (`commandid`),
  ADD KEY `postid` (`postid`);

--
-- 資料表索引 `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`postid`),
  ADD KEY `userid` (`userid`);

--
-- 資料表索引 `reply`
--
ALTER TABLE `reply`
  ADD PRIMARY KEY (`replyid`),
  ADD KEY `commandid` (`commandid`);

--
-- 已匯出資料表的限制(Constraint)
--

--
-- 資料表的 Constraints `command`
--
ALTER TABLE `command`
  ADD CONSTRAINT `command_ibfk_1` FOREIGN KEY (`postid`) REFERENCES `post` (`postid`);

--
-- 資料表的 Constraints `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `account` (`userid`);

--
-- 資料表的 Constraints `reply`
--
ALTER TABLE `reply`
  ADD CONSTRAINT `reply_ibfk_1` FOREIGN KEY (`commandid`) REFERENCES `command` (`commandid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
