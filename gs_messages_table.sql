-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql617.db.sakura.ne.jp
-- 生成日時: 2024 年 10 月 05 日 17:29
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
-- テーブルの構造 `gs_messages_table`
--

CREATE TABLE `gs_messages_table` (
  `id` int(11) NOT NULL,
  `sender_username` varchar(255) NOT NULL,
  `receiver_username` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `gs_messages_table`
--

INSERT INTO `gs_messages_table` (`id`, `sender_username`, `receiver_username`, `message`, `created_at`) VALUES
(1, 'gs_kadai', 'test2', 'ああああ', '2024-07-19 11:39:04'),
(3, 'gs_kadai', 'test2', 'ううううううう', '2024-07-19 15:04:22'),
(4, 'test3', 'gs_kadai', '投稿、参考にさせていただいています。これからも、悩みをたくさん解決して欲しいです。応援しています。', '2024-07-30 03:15:22'),
(5, 'test2', 'gs_kadai', '個別に悩みを聞いて欲しいです！', '2024-07-30 03:25:40'),
(6, 'test600', 'gs_kadai', '本当にいつも助かっています！', '2024-07-30 03:40:29'),
(7, 'test600', 'gs_kadai', '送れてますか？', '2024-08-02 14:41:46'),
(8, 'test600', 'gs_kadai', '通知されますか？', '2024-08-02 14:53:09'),
(9, 'test600', 'gs_kadai', 'これはどうだ？', '2024-08-02 14:58:00'),
(10, 'test600', 'gs_kadai', '通知はいくのかーい？', '2024-08-02 15:06:10'),
(11, 'test600', 'gs_kadai', 'なんで通知が表示されるの？', '2024-08-02 15:10:56'),
(12, 'test2', 'gs_kadai', '通知は？', '2024-08-02 15:12:24'),
(13, 'test2', 'gs_kadai', 'どうだ？', '2024-08-02 15:16:55'),
(15, 'gs_kadai', 'test600', 'いつも楽しい英語上達本を紹介いただき、ありがとうございます。', '2024-10-04 03:24:12'),
(16, 'test500', 'test600', '英語関係の悩み解決本では、私が1番を目指します！', '2024-10-04 03:28:36'),
(17, 'test500', 'test600', '今、一番ナウいtest500です！', '2024-10-04 03:30:21');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `gs_messages_table`
--
ALTER TABLE `gs_messages_table`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `gs_messages_table`
--
ALTER TABLE `gs_messages_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
