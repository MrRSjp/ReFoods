-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 
-- サーバのバージョン： 10.4.10-MariaDB
-- PHP のバージョン: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `refoods`
--
CREATE DATABASE IF NOT EXISTS `refoods` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `refoods`;

-- --------------------------------------------------------

--
-- テーブルの構造 `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `location` text NOT NULL,
  `amount` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `expiration_date` datetime NOT NULL,
  `img_url` text NOT NULL,
  `email` text NOT NULL,
  `post_date` datetime NOT NULL,
  `buy_date` datetime DEFAULT NULL,
  `poster_id` int(11) NOT NULL,
  `purchaser_id` int(11) DEFAULT NULL,
  `is_purchased` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `posts`
--

INSERT INTO `posts` (`id`, `name`, `location`, `amount`, `price`, `expiration_date`, `img_url`, `email`, `post_date`, `buy_date`, `poster_id`, `purchaser_id`, `is_purchased`) VALUES
(1, 'テスト', '愛知県', 100, 1000, '2024-01-01 00:00:00', 'post1.png', 'test@example.com', '2024-01-01 00:00:00', NULL, 2, NULL, 0),
(2, 'テスト２', '愛知県', 100, 100, '2024-12-04 00:00:00', 'post2.png', 'test@example.com', '2024-12-03 00:00:00', '2024-12-04 15:32:47', 2, 3, 1),
(3, 'テスト３', '愛知県', 100, 100, '2024-12-06 00:00:00', 'post3.png', 'test@example.com', '2024-12-06 00:00:00', '2024-12-07 15:33:28', 3, 2, 1),
(4, 'テスト４', '愛知県', 100, 100, '2024-12-08 00:00:00', 'post4.png', 'test@example.com', '2024-12-11 00:00:00', NULL, 3, NULL, 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `session_id` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_date` datetime NOT NULL,
  `device_num` int(11) NOT NULL DEFAULT 0,
  `browser_num` int(11) NOT NULL DEFAULT 0,
  `platform_name` text DEFAULT '端末名不明',
  `ip_address` text NOT NULL,
  `login_location` text NOT NULL DEFAULT '不明'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `sessions`
--

INSERT INTO `sessions` (`id`, `session_id`, `user_id`, `login_date`, `device_num`, `browser_num`, `platform_name`, `ip_address`, `login_location`) VALUES
(1, 'a', 2, '2024-12-03 00:00:00', 1, 3, '端末名不明', '0', '日本,愛知県名古屋市');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `fa_uid` text DEFAULT NULL,
  `name` text NOT NULL,
  `address` text DEFAULT NULL,
  `latitude` text DEFAULT NULL,
  `longitude` text DEFAULT NULL,
  `user_img` text NOT NULL DEFAULT 'df/dfuserico.svg',
  `is_oimg` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `fa_uid`, `name`, `address`, `latitude`, `longitude`, `user_img`, `is_oimg`) VALUES
(1, NULL, NULL, 'izz95uxh9kbx', '管理者', '愛知県名古屋市中村区名駅３丁目２４−１５', '35.172512', '136.886357', 'df/dfuserico.svg', 0),
(2, NULL, NULL, '7jxg2akqdunw', 'テストユーザー', '愛知県名古屋市中村区名駅３丁目２４−１５', '35.172512', '136.886357', 'user2.png', 1),
(3, NULL, NULL, 'stpka66jibzq', 'テストユーザー２', '愛知県名古屋市中村区名駅３丁目２４−１５', '35.172512', '136.886357', 'user3.png', 1);

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- テーブルのAUTO_INCREMENT `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- テーブルのAUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
