/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50728
 Source Host           : localhost:3306
 Source Schema         : laravel-short-url

 Target Server Type    : MySQL
 Target Server Version : 50728
 File Encoding         : 65001

 Date: 19/03/2023 22:41:28
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for clicks
-- ----------------------------
DROP TABLE IF EXISTS `clicks`;
CREATE TABLE `clicks`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `short_url` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `click` tinyint(4) NULL DEFAULT 0,
  `real_click` tinyint(4) NULL DEFAULT 0,
  `country` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '0',
  `country_full` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '0',
  `referer` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '0',
  `ip_address` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `ip_hashed` tinyint(4) NULL DEFAULT 0,
  `ip_anonymized` tinyint(4) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of clicks
-- ----------------------------
INSERT INTO `clicks` VALUES (1, '1kW1', 0, 1, 'N/A', 'Unknown', NULL, '4b84b15bff6ee5796152495a230e45e3d7e947d9', 1, 1, '2023-03-18 16:30:15', '2023-03-18 16:30:15');
INSERT INTO `clicks` VALUES (2, '1kW1', 1, 0, 'N/A', 'Unknown', NULL, '4b84b15bff6ee5796152495a230e45e3d7e947d9', 1, 1, '2023-03-18 16:30:33', '2023-03-18 16:30:33');

-- ----------------------------
-- Table structure for device_targets
-- ----------------------------
DROP TABLE IF EXISTS `device_targets`;
CREATE TABLE `device_targets`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `short_url_id` int(10) UNSIGNED NOT NULL,
  `device` int(10) UNSIGNED NOT NULL,
  `target_url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `device_targets_short_url_id_foreign`(`short_url_id`) USING BTREE,
  INDEX `device_targets_device_foreign`(`device`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for device_targets_enums
-- ----------------------------
DROP TABLE IF EXISTS `device_targets_enums`;
CREATE TABLE `device_targets_enums`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of device_targets_enums
-- ----------------------------
INSERT INTO `device_targets_enums` VALUES (1, 'windows', 'Windows');
INSERT INTO `device_targets_enums` VALUES (2, 'macos', 'Mac OS');
INSERT INTO `device_targets_enums` VALUES (3, 'android', 'Android');
INSERT INTO `device_targets_enums` VALUES (4, 'ios', 'iOS');

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (5, '2023_03_17_134533_create_urls_table', 1);

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token`) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type`, `tokenable_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for urls
-- ----------------------------
DROP TABLE IF EXISTS `urls`;
CREATE TABLE `urls`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员Id',
  `long_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '长域名',
  `short_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '短域名',
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否私密',
  `is_hidden` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否隐藏',
  `created_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updated_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `urls_user_id_index`(`user_id`) USING BTREE,
  INDEX `urls_is_delete_index`(`is_delete`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '域名表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of urls
-- ----------------------------
INSERT INTO `urls` VALUES (1, 1, 'http://laravel-short-url.local.com/', '', 1, 0, 1679156701, 1679156701, 0);
INSERT INTO `urls` VALUES (3, 0, 'http://laravel-short-url.local.com1/', '', 1, 0, 1679195544, 1679195544, 0);
INSERT INTO `urls` VALUES (4, 0, 'http://laravel-short-url.local1.com/', '', 1, 0, 1679195625, 1679195625, 0);
INSERT INTO `urls` VALUES (5, 0, 'http://laravel-short-url.local2.com/', '', 1, 0, 1679195653, 1679195653, 0);
INSERT INTO `urls` VALUES (6, 0, 'http://laravel-short-url.local20.com/', '', 1, 0, 1679195813, 1679195813, 0);
INSERT INTO `urls` VALUES (7, 0, 'http://laravel-short-url.local23.com/', '', 1, 0, 1679195851, 1679195851, 0);
INSERT INTO `urls` VALUES (8, 0, 'http://laravel-short-url.local25.com/', '', 1, 0, 1679195917, 1679195917, 0);
INSERT INTO `urls` VALUES (9, 0, 'http://laravel-short-url.local26.com/', '', 1, 0, 1679195935, 1679195935, 0);
INSERT INTO `urls` VALUES (10, 0, 'http://laravel-short-url.local27.com/', '', 1, 0, 1679195961, 1679195961, 0);
INSERT INTO `urls` VALUES (11, 0, 'http://laravel-short-url.local28.com', '', 1, 0, 1679196007, 1679196007, 0);
INSERT INTO `urls` VALUES (12, 0, 'http://laravel-short-url.local.com2', '', 1, 0, 1679196039, 1679196039, 0);
INSERT INTO `urls` VALUES (13, 0, 'http://laravel-short-url.local.com3', '', 1, 0, 1679196174, 1679196174, 0);
INSERT INTO `urls` VALUES (14, 0, 'http://laravel-short-url.local.com492822', '', 1, 0, 1679196243, 1679196243, 0);
INSERT INTO `urls` VALUES (15, 0, 'http://laravel-short-url.local.com466238', '', 1, 0, 1679196276, 1679196276, 0);
INSERT INTO `urls` VALUES (16, 0, 'http://laravel-short-url.local.com451067', '', 1, 0, 1679196290, 1679196290, 0);
INSERT INTO `urls` VALUES (17, 0, 'http://laravel-short-url.local.com480175', '', 1, 0, 1679196307, 1679196307, 0);
INSERT INTO `urls` VALUES (18, 0, 'http://laravel-short-url.local.com489778', '', 1, 0, 1679196331, 1679196331, 0);
INSERT INTO `urls` VALUES (19, 0, 'http://laravel-short-url.local.com415382', '6J', 1, 0, 1679196347, 1679196347, 0);
INSERT INTO `urls` VALUES (20, 0, 'https://www.baidu.com30812', 'vo', 1, 0, 1679196373, 1679196373, 0);
INSERT INTO `urls` VALUES (21, 0, 'https://ext.dcloud.net.cn/?page=1', 'eO', 1, 0, 1679196794, 1679196794, 0);
INSERT INTO `urls` VALUES (22, 0, 'https://ext.dcloud.net.cn/?page=2', 'oX', 0, 0, 1679196809, 1679196809, 0);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp(0) NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, '123456', '123456@qq.com', '2023-03-19 00:09:51', '$2y$10$XEOLRpVAOk8s223yubBk4OurDbkO9cKZFLPlscOd4ec8rtWqvh9ne', 'user', '8E9UfH2pc5sAMUcaN2m2TW1CUB144aHHtIRb51J8AJD5etUm9XBTWFZBj9cV', '2023-03-18 23:43:04', '2023-03-18 23:43:04');
INSERT INTO `users` VALUES (2, 'admin', 'admin@qq.com', '2023-03-19 00:09:54', '$2y$10$XEOLRpVAOk8s223yubBk4OurDbkO9cKZFLPlscOd4ec8rtWqvh9ne', 'admin', 'hQRNYgkughMFvw0s0QDfSw0ZqjDzVmcpTAsbGBWsCujKNgfdYMZZUYHtcAXw', NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
