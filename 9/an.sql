-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016 年 5 月 26 日 23:48
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
  `postal_1` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `postal_2` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `region` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `rating` tinyint(1) unsigned NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='アンケートの管理用';

--
-- テーブルのデータのダンプ `an_table`
--

INSERT INTO `an_table` (`id`, `name`, `mail`, `age`, `sex`, `postal_1`, `postal_2`, `region`, `address`, `tel`, `rating`, `comments`, `date`, `date_updated`) VALUES
(1, '越智 音々', 'eochi@tpjegkp.yd', 33, '女性', '520', '0062', '滋賀県', '大津市大谷町4-6', '0740919993', 3, 'その他のコメント。更新しました。', '2016-05-10 00:46:34', '2016-05-18 21:29:04'),
(2, '森 菜々実', 'Nanami_Mori@gvrpoemxp.gre.ohx', 51, '女性', '639', '1136', '奈良県', '大和郡山市本庄町1-3-17', '0744158619', 5, 'コメントその他の更新しました。', '2016-05-10 00:48:26', '2016-05-18 21:42:50'),
(3, '河村 梨沙', 'risa_kawamura@csurynvm.jadek.tkj', 23, '女性', '960', '0735', '福島県', '伊達市梁川町鶴ケ岡3-4', '0245861560', 2, 'その他の色々なコメント。更新しました。', '2016-05-10 00:51:03', '2016-05-19 22:58:18'),
(4, '笠井 俊二', 'shunji31913@vimyr.ed.ntm', 29, '男性', '518', '0406', '三重県', '名張市すずらん台西１番町3-17-2', '0598861175', 5, 'その他の感想やコメント。更新しました。', '2016-05-10 01:17:15', '2016-05-19 22:59:58'),
(5, '柏木 莉央', 'rio_kashiwagi@wmtscbrwr.vq', 44, '女性', '769', '2101', '香川県', 'さぬき市志度3-16-9', '087573390', 1, 'その他の様々なコメント。更新しました。', '2016-05-10 17:47:37', '2016-05-19 23:04:46'),
(6, '大矢 昌宏', 'masahiro00507@kkyzlphhe.hk', 58, '男性', '969', '6273', '福島県', '大沼郡会津美里町八木沢4-9-9 八木沢アパート406', '0242152270', 4, 'その他の色々な様々なコメント。更新しました。', '2016-05-10 17:51:11', '2016-05-19 23:06:12'),
(12, '田崎 日菜', 'wb=bucqhina37812@bvbumhcv.wtugq.tn', 35, '女性', '991', '0801', '山形県', '西村山郡大江町左沢4-17-6', '0237071118', 5, '色々な感想。さらに更新。', '2016-05-19 23:41:43', '2016-05-25 23:31:12');

-- --------------------------------------------------------

--
-- テーブルの構造 `regions`
--

CREATE TABLE IF NOT EXISTS `regions` (
`region_id` tinyint(3) unsigned NOT NULL,
  `region_name` varchar(4) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='日本の都道府県のテーブル';

--
-- テーブルのデータのダンプ `regions`
--

INSERT INTO `regions` (`region_id`, `region_name`) VALUES
(1, '北海道'),
(2, '青森県'),
(3, '岩手県'),
(4, '宮城県'),
(5, '秋田県'),
(6, '山形県'),
(7, '福島県'),
(8, '茨城県'),
(9, '栃木県'),
(10, '群馬県'),
(11, '埼玉県'),
(12, '千葉県'),
(13, '東京都'),
(14, '神奈川県'),
(15, '新潟県'),
(16, '富山県'),
(17, '石川県'),
(18, '福井県'),
(19, '山梨県'),
(20, '長野県'),
(21, '岐阜県'),
(22, '静岡県'),
(23, '愛知県'),
(24, '三重県'),
(25, '滋賀県'),
(26, '京都府'),
(27, '大阪府'),
(28, '兵庫県'),
(29, '奈良県'),
(30, '和歌山県'),
(31, '鳥取県'),
(32, '島根県'),
(33, '岡山県'),
(34, '広島県'),
(35, '山口県'),
(36, '徳島県'),
(37, '香川県'),
(38, '愛媛県'),
(39, '高知県'),
(40, '福岡県'),
(41, '佐賀県'),
(42, '長崎県'),
(43, '熊本県'),
(44, '大分県'),
(45, '宮崎県'),
(46, '鹿児島県'),
(47, '沖縄県');

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
  `date_updated` datetime NOT NULL COMMENT '変更日時',
  `admin_flg` tinyint(1) NOT NULL,
  `life_flg` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ログインユーザ管理用';

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `mail`, `password`, `date_added`, `date_updated`, `admin_flg`, `life_flg`) VALUES
(1, 'admin', 'admin@test.com', '$2y$10$buE8a.EsQngRoCKrezYw7.5viJi.6GFp12Q5GlIhkDsnwyPucccaC', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0),
(2, 'test2', 'test2@test.com', '$2y$10$uii/xECZy/tgJfcODC05U.EwnLVkGzWxD.VCxyiZ/OuWSmOeNR992', '2016-05-12 21:48:51', '2016-05-12 21:48:51', 0, 0),
(3, 'test3', 'test3@test.com', '$2y$10$GsMALP6K1xLn9F2tKSFIGe8WM3c3315n.CwkmBlFwU7g4fAEnxVsq', '2016-05-14 14:06:34', '2016-05-14 14:06:34', 0, 0),
(4, 'test4', 'test4@test.com', '$2y$10$WGhoiEOgekZgBp329vUZMeO6PXKGFIvTRwjGPs.jTwOymzc/mN.fi', '2016-05-26 20:48:00', '2016-05-26 23:21:49', 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `an_table`
--
ALTER TABLE `an_table`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
 ADD PRIMARY KEY (`region_id`);

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
MODIFY `id` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
MODIFY `region_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(12) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
