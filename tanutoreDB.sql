-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jun 03, 2020 at 08:35 PM
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
  `compflg` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `delFlg` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `midDiv`
--
ALTER TABLE `midDiv`
  MODIFY `mid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `syuppin`
--
ALTER TABLE `syuppin`
  MODIFY `syuppin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tradeHistory`
--
ALTER TABLE `tradeHistory`
  MODIFY `trading_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
