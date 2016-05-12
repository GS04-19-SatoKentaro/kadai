-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016 年 5 月 13 日 03:58
-- サーバのバージョン： 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `an`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `an_table`
--

CREATE TABLE IF NOT EXISTS `an_table` (
`id` int(12) NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `age` tinyint(3) unsigned NOT NULL COMMENT '年齢',
  `sex` enum('男性','女性','無回答','') COLLATE utf8_unicode_ci NOT NULL,
  `postal` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `region` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `rating` tinyint(1) unsigned NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='アンケートの管理用';

--
-- テーブルのデータのダンプ `an_table`
--

INSERT INTO `an_table` (`id`, `name`, `mail`, `age`, `sex`, `postal`, `region`, `address`, `tel`, `rating`, `comments`, `date`) VALUES
(1, '越智 音々', 'eochi@tpjegkp.yd', 33, '女性', '520-0062', '滋賀県', '大津市大谷町4-6', '0740919993', 3, 'その他のコメント。', '2016-05-10 00:46:34'),
(2, '森 菜々実', 'Nanami_Mori@gvrpoemxp.gre.ohx', 51, '女性', '639-1136', '奈良県', '大和郡山市本庄町1-3-17', '0744158619', 4, 'その他の感想。色々な感想。', '2016-05-10 00:48:26'),
(3, '笠井 俊二', 'shunji31913@vimyr.ed.ntm', 29, '男性', '518-0406', '三重県', '名張市すずらん台西１番町3-17-2', '0598861175', 2, 'その他の様々な感想。', '2016-05-10 00:51:03'),
(4, '柏木 莉央', 'rio_kashiwagi@wmtscbrwr.vq', 44, '女性', '769-2101', '香川県', 'さぬき市志度3-16-9 ハウス志度218', '087573390', 2, 'その他の色々な感想。', '2016-05-10 01:17:15'),
(5, '大矢 昌宏', 'masahiro00507@kkyzlphhe.hk', 58, '男性', '969-6273', '福島県', '大沼郡会津美里町八木沢4-9-9 八木沢アパート406', '0242152270', 1, '色々な感想文。', '2016-05-10 17:47:37'),
(6, '田崎 日菜', 'wb=bucqhina37812@bvbumhcv.wtugq.tn', 35, '女性', '991-0801', '山形県', '西村山郡大江町左沢4-17-6', '0237071118', 5, 'その他の色々な感想。', '2016-05-10 17:51:11'),
(7, '坪田 麻央', 'mao8886@rqrwvxbtbh.tc', 41, '女性', '649-2612', '和歌山県', '西牟婁郡すさみ町口和深1-8-1', '0738239653', 3, '感想、いろんな感想。なんやかんやの感想。', '2016-05-10 17:53:00'),
(8, '小柳 未央', 'mio974@sxakrcf.bmf', 45, '女性', '518-1325', '三重県', '伊賀市丸柱2-6-1', '0595937349', 4, '非常に良いと思います。個人的にはオススメです。', '2016-05-10 18:05:12'),
(9, '小柳 未央', 'mio974@sxakrcf.bmf', 45, '女性', '518-1325', '三重県', '伊賀市丸柱2-6-1', '0595937349', 4, '非常に良いと思います。個人的にはオススメです。', '2016-05-11 21:18:23'),
(10, '庄司 岩男', 'iwao201@prkvgnwnhb.sux', 48, '男性', '707-0423', '岡山県', '美作市小原田2-3 小原田プラチナ300', '0865836884', 5, '最高、間違いなし。', '2016-05-12 22:04:20');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(12) unsigned NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL COMMENT '登録日時',
  `date_updated` datetime NOT NULL COMMENT '変更日時'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ログインユーザ管理用';

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `mail`, `password`, `date_added`, `date_updated`) VALUES
(1, 'test', 'test@test.com', '$2y$10$buE8a.EsQngRoCKrezYw7.5viJi.6GFp12Q5GlIhkDsnwyPucccaC', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'test2', 'test2@test.com', '$2y$10$uii/xECZy/tgJfcODC05U.EwnLVkGzWxD.VCxyiZ/OuWSmOeNR992', '2016-05-12 21:48:51', '2016-05-12 21:48:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `an_table`
--
ALTER TABLE `an_table`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `mail` (`mail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `an_table`
--
ALTER TABLE `an_table`
MODIFY `id` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(12) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
