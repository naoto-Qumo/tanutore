-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jun 08, 2020 at 12:39 AM
-- Server version: 5.7.26
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `tanutore`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `major_id` int(11) NOT NULL,
  `mid_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `major_id`, `mid_id`) VALUES
(1, 1, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `chat_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `syuppin_id` int(11) NOT NULL,
  `seller_eval` int(1) DEFAULT NULL,
  `buyer_eval` int(1) DEFAULT NULL,
  `compflg` tinyint(1) NOT NULL DEFAULT '0',
  `comptime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`chat_id`, `seller_id`, `buyer_id`, `syuppin_id`, `seller_eval`, `buyer_eval`, `compflg`, `comptime`) VALUES
(1, 1, 2, 4, 5, 3, 1, '2020-06-03 21:37:49'),
(2, 2, 1, 5, 3, 5, 1, '2020-06-03 22:02:51'),
(3, 1, 2, 6, 1, 5, 1, '2020-06-04 23:46:58'),
(4, 1, 2, 7, 3, 1, 1, '2020-06-04 23:47:07'),
(5, 1, 2, 7, NULL, NULL, 1, '2020-06-04 23:47:07');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `major_id` int(11) NOT NULL,
  `mid_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `item_name`, `img`, `major_id`, `mid_id`) VALUES
(1, 'TOYなクロゼット', 'img/itemIMG/TOY/TOYなクロゼット.png', 1, 1),
(2, 'TOYなスクリーン', 'img/itemIMG/TOY/TOYなスクリーン.png', 1, 1),
(3, 'あかいおちば', 'img/itemIMG/おちば/あかいおちば.png', 1, 2),
(4, 'いしのみずうけ~もみじ~', 'img/itemIMG/おちば/いしのみずうけ~もみじ~.png', 1, 2),
(5, 'おおきなきのみのツリー', 'img/itemIMG/おちば/おおきなきのみのツリー.png', 1, 2),
(6, 'おちば', 'img/itemIMG/おちば/おちば.png', 1, 2),
(7, 'おちばスツール', 'img/itemIMG/おちば/おちばスツール.png', 1, 2),
(8, 'おちばのたきび', 'img/itemIMG/おちば/おちばのたきび.png', 1, 2),
(9, 'きいろのおちば', 'img/itemIMG/おちば/きいろのおちば.png', 1, 2),
(10, 'きのみのアーチ', 'img/itemIMG/おちば/きのみのアーチ.png', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `majorDiv`
--

CREATE TABLE `majorDiv` (
  `major_id` int(11) NOT NULL,
  `major_div` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `majorDiv`
--

INSERT INTO `majorDiv` (`major_id`, `major_div`) VALUES
(1, '家具'),
(2, '衣服');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` varchar(500) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `chat_id`, `sender_id`, `receiver_id`, `message`, `datetime`) VALUES
(1, 1, 2, 1, 'こんにちは！', '2020-06-03 11:50:32'),
(2, 1, 1, 2, 'どうも！', '2020-06-03 11:50:50'),
(3, 1, 1, 2, 'TOYなクロゼットありますか？', '2020-06-03 11:51:03'),
(4, 1, 2, 1, 'あります！', '2020-06-03 11:51:42'),
(5, 1, 2, 1, '交換してください！', '2020-06-03 11:51:49'),
(6, 1, 1, 2, '島パスワードは〇〇です！', '2020-06-03 11:52:16'),
(7, 2, 1, 2, 'ほしいです！', '2020-06-03 13:01:59'),
(8, 2, 2, 1, 'わかりました！', '2020-06-03 13:02:16'),
(9, 3, 2, 1, 'くれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれくれv\r\n', '2020-06-04 14:37:23'),
(10, 5, 2, 1, 'よろしく', '2020-06-04 14:43:39'),
(11, 4, 2, 1, 'yoroku', '2020-06-04 14:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `midDiv`
--

CREATE TABLE `midDiv` (
  `mid_id` int(11) NOT NULL,
  `mid_div` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `midDiv`
--

INSERT INTO `midDiv` (`mid_id`, `mid_div`) VALUES
(1, 'TOY'),
(2, 'おちば');

-- --------------------------------------------------------

--
-- Table structure for table `syuppin`
--

CREATE TABLE `syuppin` (
  `syuppin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ex_item_id` int(11) NOT NULL,
  `want_item_id` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `regtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comptime` datetime DEFAULT NULL,
  `delFlg` tinyint(1) NOT NULL DEFAULT '0',
  `del_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `syuppin`
--

INSERT INTO `syuppin` (`syuppin_id`, `user_id`, `ex_item_id`, `want_item_id`, `comment`, `regtime`, `comptime`, `delFlg`, `del_time`) VALUES
(1, 10, 1, 2, 'コメントはありません。', '2020-06-03 11:37:41', NULL, 0, NULL),
(2, 1, 1, 2, 'コメントはありません。', '2020-06-03 11:38:57', NULL, 1, NULL),
(3, 1, 2, 3, 'よろしく！', '2020-06-03 11:46:47', NULL, 1, '2020-06-03 20:47:01'),
(4, 1, 4, 1, 'ほしい！', '2020-06-03 11:49:24', '2020-06-03 20:52:36', 0, NULL),
(5, 2, 7, 6, 'おちば！', '2020-06-03 13:01:38', '2020-06-03 22:02:26', 0, NULL),
(6, 1, 1, 3, 'よろしく', '2020-06-04 14:35:17', '2020-06-04 23:46:36', 0, NULL),
(7, 1, 2, 5, 'コメントはありません。', '2020-06-04 14:35:31', '2020-06-04 23:46:42', 0, NULL),
(8, 2, 9, 10, 'コメントはありません。', '2020-06-04 14:37:01', NULL, 1, '2020-06-07 23:56:32'),
(9, 1, 4, 9, 'よろしくね', '2020-06-04 15:08:36', NULL, 1, '2020-06-05 01:03:10'),
(10, 1, 1, 5, 'doumo', '2020-06-04 16:03:44', NULL, 1, '2020-06-05 01:08:15'),
(11, 1, 1, 8, 'コメントはありません。', '2020-06-04 16:04:38', NULL, 1, '2020-06-05 01:05:45'),
(12, 1, 1, 5, '交換してくれよ', '2020-06-04 16:10:58', NULL, 0, NULL),
(13, 3, 1, 4, 'コメントはありません。', '2020-06-06 11:31:04', NULL, 1, '2020-06-07 23:56:13');

-- --------------------------------------------------------

--
-- Table structure for table `tradeHistory`
--

CREATE TABLE `tradeHistory` (
  `trading_id` int(11) NOT NULL,
  `syuppin_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `comptime` datetime DEFAULT NULL,
  `seller_eval` int(11) NOT NULL,
  `buyer_eval` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT 'img/initimg.png',
  `item_id` int(11) DEFAULT NULL,
  `syuppinn_id` int(11) DEFAULT NULL,
  `user_evaluation` float DEFAULT NULL,
  `login_time` datetime NOT NULL,
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `mail`, `pass`, `nickname`, `icon`, `item_id`, `syuppinn_id`, `user_evaluation`, `login_time`, `comment`) VALUES
(1, 'tanaka@mail.com', '$2y$10$95YgTSljvb9MGQAv7vBJYOVWGSUQiwZ5V/f6EGJ.PP8z4H/y8O8ce', 'ちゃちゃまる', 'uploads/865a8de1a4d1279553abd46f3be82fab1e88004c.jpeg', NULL, NULL, NULL, '2020-06-03 20:38:46', 'おはよう！'),
(2, 'aiueo@mail.com', '$2y$10$lh82CJkAbT.yl1jv/0bwhO2rBUShEFCC1prQoaqZc5vJ1Hllkx6CS', 'APORO', 'img/initimg.png', NULL, NULL, NULL, '2020-06-03 20:50:15', 'yoroshiku'),
(3, 'kakikukeko@mail.com', '$2y$10$JQrRN4c8WCKkXrSVxrCLiuwGn2pHquxKKjQAIbpM.eEmAiNaXIYYy', 'つばくろ', 'img/initimg.png', NULL, NULL, NULL, '2020-06-06 20:09:55', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`chat_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `majorDiv`
--
ALTER TABLE `majorDiv`
  ADD PRIMARY KEY (`major_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `midDiv`
--
ALTER TABLE `midDiv`
  ADD PRIMARY KEY (`mid_id`);

--
-- Indexes for table `syuppin`
--
ALTER TABLE `syuppin`
  ADD PRIMARY KEY (`syuppin_id`);

--
-- Indexes for table `tradeHistory`
--
ALTER TABLE `tradeHistory`
  ADD PRIMARY KEY (`trading_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `majorDiv`
--
ALTER TABLE `majorDiv`
  MODIFY `major_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `midDiv`
--
ALTER TABLE `midDiv`
  MODIFY `mid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `syuppin`
--
ALTER TABLE `syuppin`
  MODIFY `syuppin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tradeHistory`
--
ALTER TABLE `tradeHistory`
  MODIFY `trading_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
