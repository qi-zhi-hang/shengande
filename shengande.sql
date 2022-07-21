-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2022-07-21 13:08:28
-- 服务器版本： 5.6.49-log
-- PHP 版本： 7.2.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `shengande`
--

-- --------------------------------------------------------

--
-- 表的结构 `sad_admin`
--

CREATE TABLE `sad_admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `admin_name` varchar(20) DEFAULT NULL COMMENT '管理名称',
  `admin_password` char(200) DEFAULT NULL COMMENT '管理员密码',
  `group_id` int(11) DEFAULT NULL COMMENT '所属组ID',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '管理员状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sad_admin`
--

INSERT INTO `sad_admin` (`id`, `admin_name`, `admin_password`, `group_id`, `created_at`, `updated_at`, `status`) VALUES
(2, 'admin1', '$2y$10$OVtoc8Ad8/Q5d/zgWG6TNOQMH1PRpWVoAaONaMs68w5xIak762LrK', 1, '2022-07-20 05:46:06', '2022-07-20 05:46:06', 1);

-- --------------------------------------------------------

--
-- 表的结构 `sad_admin_group`
--

CREATE TABLE `sad_admin_group` (
  `id` int(10) UNSIGNED NOT NULL,
  `group_name` varchar(20) DEFAULT NULL COMMENT '组名',
  `permission_num` int(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sad_admin_group`
--

INSERT INTO `sad_admin_group` (`id`, `group_name`, `permission_num`, `created_at`, `updated_at`) VALUES
(1, '管理员', 100, '2022-07-19 23:11:51', '2022-07-19 23:11:54');

--
-- 转储表的索引
--

--
-- 表的索引 `sad_admin`
--
ALTER TABLE `sad_admin`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `sad_admin_group`
--
ALTER TABLE `sad_admin_group`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `sad_admin`
--
ALTER TABLE `sad_admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `sad_admin_group`
--
ALTER TABLE `sad_admin_group`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
