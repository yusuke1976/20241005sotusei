-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql617.db.sakura.ne.jp
-- 生成日時: 2024 年 10 月 05 日 17:32
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
-- テーブルの構造 `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `recipient_username` varchar(255) NOT NULL,
  `sender_username` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `notifications`
--

INSERT INTO `notifications` (`id`, `recipient_username`, `sender_username`, `message`, `is_read`, `created_at`) VALUES
(6, 'test2', 'gs_kadai', 'gs_kadaiさんが新しい本「」を投稿しました。', 0, '2024-08-12 06:43:31'),
(7, 'test3', 'gs_kadai', 'gs_kadaiさんが新しい本「」を投稿しました。', 0, '2024-08-12 06:43:31'),
(8, 'test600', 'gs_kadai', 'gs_kadaiさんが新しい本「」を投稿しました。', 0, '2024-08-12 06:43:31'),
(9, 'test2', 'gs_kadai', 'gs_kadaiさんが新しい本「シンプル四柱推命　最強の人生をプランニングできる」を投稿しました。', 0, '2024-08-12 06:45:06'),
(10, 'test3', 'gs_kadai', 'gs_kadaiさんが新しい本「シンプル四柱推命　最強の人生をプランニングできる」を投稿しました。', 0, '2024-08-12 06:45:06'),
(11, 'test600', 'gs_kadai', 'gs_kadaiさんが新しい本「シンプル四柱推命　最強の人生をプランニングできる」を投稿しました。', 0, '2024-08-12 06:45:06'),
(12, 'gs_kadai', '', 'test600さんがあなたをフォローしました。', 0, '2024-10-03 17:28:25'),
(13, 'gs_kadai', '', 'test600さんがあなたをフォローしました。', 0, '2024-10-03 17:28:31'),
(14, 'gs_kadai', '', 'test600さんがあなたをフォローしました。', 0, '2024-10-03 23:36:44'),
(15, 'test600', 'gs_kadai', 'gs_kadaiさんからメッセージが届きました。', 0, '2024-10-04 03:24:12'),
(16, 'test600', 'test500', 'test500さんからメッセージが届きました。', 0, '2024-10-04 03:28:36'),
(17, 'test600', 'test500', 'test500さんからメッセージが届きました。', 0, '2024-10-04 03:30:21');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
