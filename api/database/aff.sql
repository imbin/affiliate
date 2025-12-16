-- MySQL dump 10.13  Distrib 5.7.20, for Linux (x86_64)
--
-- Host: localhost    Database: aff
-- ------------------------------------------------------
-- Server version	5.7.20-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_user`
--

DROP TABLE IF EXISTS `admin_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名,可做登录',
  `passwd` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '哈希后的密码',
  `real_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `login_count` int(11) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `last_login_time` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '最近登录日期',
  `is_disabled` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否禁用:0=否,1=是',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_admin_user_name` (`user_name`),
  KEY `idx_admin_ctime` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_user`
--

LOCK TABLES `admin_user` WRITE;
/*!40000 ALTER TABLE `admin_user` DISABLE KEYS */;
INSERT INTO `admin_user` VALUES (1,'admin','$2y$10$4bMtdD./4fBjTYkgzTOF8uUsgwgNgvTNF9UxAQcK/xksr0oaWup4a','admin',0,'',0,'2025-12-16 03:02:32','2025-12-16 03:02:32');
/*!40000 ALTER TABLE `admin_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banner`
--

DROP TABLE IF EXISTS `banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banner` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '标题',
  `pic_url` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片URL',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态:1=已上架,2=已下架',
  `redirect_url` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '跳转 URL',
  `sku` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `return_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '返佣方式:1=金额,2=比例',
  `return_value` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '返佣金额/比例',
  `price` double(10,2) NOT NULL DEFAULT '0.00',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_banner_title` (`title`),
  KEY `idx_banner_sku` (`sku`),
  KEY `idx_banner_ctime` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banner`
--

LOCK TABLES `banner` WRITE;
/*!40000 ALTER TABLE `banner` DISABLE KEYS */;
/*!40000 ALTER TABLE `banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finance_trade`
--

DROP TABLE IF EXISTS `finance_trade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `finance_trade` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '所属用户',
  `business_sn` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '收支相关单号,如订单号,提现号',
  `amount` double(10,2) NOT NULL COMMENT '金额',
  `type` tinyint(4) NOT NULL COMMENT '收支类型：1=收入、2=提现',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_finance_trade_user_id` (`user_id`),
  KEY `idx_finance_business_sn` (`business_sn`),
  KEY `idx_finance_trade_ctime` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `finance_trade`
--

LOCK TABLES `finance_trade` WRITE;
/*!40000 ALTER TABLE `finance_trade` DISABLE KEYS */;
/*!40000 ALTER TABLE `finance_trade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finance_withdraw`
--

DROP TABLE IF EXISTS `finance_withdraw`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `finance_withdraw` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '提现单号',
  `user_id` bigint(20) NOT NULL COMMENT '所属用户',
  `amount` double(10,2) NOT NULL COMMENT '提现金额',
  `status` tinyint(4) NOT NULL COMMENT '提现状态:1=未审核、2=驳回、3=提现成功',
  `way` tinyint(4) NOT NULL COMMENT '提现方式：1=银行卡，2=支付宝',
  `card` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '提现银行卡号或支付宝账号',
  `name` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '收款人姓名',
  `remark` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '备注',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_finance_withdraw_sn` (`sn`),
  KEY `idx_finance_withdraw_user_id` (`user_id`),
  KEY `idx_finance_withdraw_ctime` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `finance_withdraw`
--

LOCK TABLES `finance_withdraw` WRITE;
/*!40000 ALTER TABLE `finance_withdraw` DISABLE KEYS */;
/*!40000 ALTER TABLE `finance_withdraw` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025_12_12_182307_init_talbes',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_goods`
--

DROP TABLE IF EXISTS `order_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_goods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL COMMENT '所属用户',
  `order_sn` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '订单号',
  `sku` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'SKU',
  `sku_quantity` int(11) NOT NULL DEFAULT '0' COMMENT 'SKU数量',
  `sku_price` double(10,2) NOT NULL COMMENT '单价',
  `subtotal` double(8,2) NOT NULL COMMENT '小计金额=SKU单价 x SKU数量',
  `commission_ratio` double(8,2) NOT NULL COMMENT '佣金比例',
  `sku_commission` double(8,2) NOT NULL COMMENT 'sku佣金=小计金额x(佣金比例/100)',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_order_goods_order_id` (`order_id`),
  KEY `idx_order_goods_order_sn` (`order_sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_goods`
--

LOCK TABLES `order_goods` WRITE;
/*!40000 ALTER TABLE `order_goods` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_info`
--

DROP TABLE IF EXISTS `order_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_info` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '所属用户',
  `order_sn` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '订单号',
  `order_amount` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额（不含运费）',
  `commission` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单佣金',
  `order_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '订单状态:1=未付款、2=已取消、3=已付款、4=已发货',
  `order_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '下单日期',
  `pay_time` timestamp NULL DEFAULT NULL COMMENT '支付日期',
  `deliver_time` timestamp NULL DEFAULT NULL COMMENT '发货日期',
  `commission_status` tinyint(4) NOT NULL COMMENT '佣金状态:1=未发放、2=已发放、3=不发放',
  `grant_days` int(11) NOT NULL DEFAULT '30' COMMENT '佣金发放周期,默认30天',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_order_info_time_status` (`order_time`,`order_status`),
  KEY `idx_order_info_pay_time_status` (`pay_time`,`order_status`),
  KEY `idx_order_info_user_id` (`user_id`),
  KEY `idx_order_info_sn` (`order_sn`),
  KEY `idx_order_info_deliver_time` (`deliver_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_info`
--

LOCK TABLES `order_info` WRITE;
/*!40000 ALTER TABLE `order_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_balance`
--

DROP TABLE IF EXISTS `user_balance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_balance` (
  `user_id` bigint(20) NOT NULL COMMENT '所属用户',
  `balance` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '余额（可提现金额）',
  `frozen` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '冻结金额（提现中）',
  `withdraw` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '已提现金额',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  KEY `idx_user_balance_ctime` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_balance`
--

LOCK TABLES `user_balance` WRITE;
/*!40000 ALTER TABLE `user_balance` DISABLE KEYS */;
INSERT INTO `user_balance` VALUES (1,0.00,0.00,0.00,'2025-12-16 03:02:33','2025-12-16 03:02:33');
/*!40000 ALTER TABLE `user_balance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名,可做登录',
  `email` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Email,可做登录',
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号，可做登录',
  `passwd` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '哈希后的密码',
  `nick_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `gender` tinyint(4) NOT NULL DEFAULT '0' COMMENT '性别:0=未设置，1=男，2=女',
  `birthday` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '出生年月',
  `login_count` int(11) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `last_login_time` timestamp NULL DEFAULT NULL COMMENT '最近登录日期',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态:1=待审核、2=审核通过、3=驳回',
  `track_code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用于追踪订单的追踪码',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'del:0=no，1=yes',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_user_name_unique` (`user_name`),
  KEY `idx_user_email` (`email`),
  KEY `idx_user_mobile` (`mobile`),
  KEY `idx_user_ctime` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'test','','','$2y$10$In58ZRC0xUztUkbBTTxcvu/X1eTmBMjc4DBCRUTCmSXy7wzMCuxma','',0,'',0,NULL,2,'',0,'2025-12-16 03:02:33','2025-12-16 03:02:33');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-16  3:02:42
