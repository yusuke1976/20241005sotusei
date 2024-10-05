-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql617.db.sakura.ne.jp
-- 生成日時: 2024 年 10 月 05 日 17:31
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
-- テーブルの構造 `gs_worry`
--

CREATE TABLE `gs_worry` (
  `username` varchar(64) NOT NULL,
  `id` int(12) NOT NULL,
  `worry` text NOT NULL,
  `date` datetime NOT NULL,
  `proposal_count` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `gs_worry`
--

INSERT INTO `gs_worry` (`username`, `id`, `worry`, `date`, `proposal_count`) VALUES
('test3', 2, 'test3', '2024-07-20 23:48:26', 0),
('test3', 3, '洋書を読みたいが、何を読んだらいいのかわからない', '2024-07-21 13:49:22', 1),
('gs_kadai', 9, '悩みはなんでしょう？', '2024-07-31 21:02:40', 1),
('test500', 14, '上腕二頭筋の効率的な鍛え方がわかりません', '2024-08-18 09:34:29', 0);

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `gs_worry`
--
ALTER TABLE `gs_worry`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `gs_worry`
--
ALTER TABLE `gs_worry`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
