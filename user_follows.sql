-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql617.db.sakura.ne.jp
-- 生成日時: 2024 年 10 月 05 日 17:33
-- サーバのバージョン： 5.7.40-log
-- PHP のバージョン: 8.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `gs-dev27-41_20240815sotusei`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `user_follows`
--

CREATE TABLE `user_follows` (
  `id` int(11) NOT NULL,
  `follower_username` varchar(255) NOT NULL,
  `followed_username` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `user_follows`
--

INSERT INTO `user_follows` (`id`, `follower_username`, `followed_username`, `created_at`) VALUES
(1, 'gs_kadai', 'test3', '2024-07-29 13:32:09'),
(6, 'gs_kadai', 'test2', '2024-07-29 14:19:51'),
(7, 'gs_kadai', 'test500', '2024-07-29 14:19:57'),
(8, 'gs_kadai', 'test600', '2024-07-29 14:19:59'),
(9, 'test600', 'test2', '2024-07-29 14:20:52'),
(10, 'test600', 'test3', '2024-07-29 14:20:54'),
(12, 'test3', 'gs_kadai', '2024-07-29 14:45:37'),
(13, 'test2', 'gs_kadai', '2024-07-29 14:47:35'),
(17, 'test600', 'gs_kadai', '2024-10-03 23:36:44');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `user_follows`
--
ALTER TABLE `user_follows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_follow` (`follower_username`,`followed_username`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `user_follows`
--
ALTER TABLE `user_follows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
