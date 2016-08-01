/*
Navicat MySQL Data Transfer

Source Server         : 123.59.146.75
Source Server Version : 50713
Source Host           : 123.59.146.75:3306
Source Database       : education

Target Server Type    : MYSQL
Target Server Version : 50713
File Encoding         : 65001

Date: 2016-08-01 16:04:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for edu_app_releases
-- ----------------------------
DROP TABLE IF EXISTS `edu_app_releases`;
CREATE TABLE `edu_app_releases` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `version` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `file1` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `is_top` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='应用版本管理';

-- ----------------------------
-- Records of edu_app_releases
-- ----------------------------

-- ----------------------------
-- Table structure for edu_apps
-- ----------------------------
DROP TABLE IF EXISTS `edu_apps`;
CREATE TABLE `edu_apps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `intro` text COLLATE utf8_unicode_ci,
  `pic1` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='应用管理';

-- ----------------------------
-- Records of edu_apps
-- ----------------------------

-- ----------------------------
-- Table structure for edu_siteconfig
-- ----------------------------
DROP TABLE IF EXISTS `edu_siteconfig`;
CREATE TABLE `edu_siteconfig` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `option_title` varchar(100) NOT NULL DEFAULT '',
  `option_name` varchar(100) NOT NULL DEFAULT '',
  `option_value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='站点配置';

-- ----------------------------
-- Records of edu_siteconfig
-- ----------------------------
INSERT INTO `edu_siteconfig` VALUES ('1', '网站标题', 'meta_title', '乐行云享');
INSERT INTO `edu_siteconfig` VALUES ('2', '底部信息', 'meta_copyright', '<p>Copyright © 乐行云享</p>');
INSERT INTO `edu_siteconfig` VALUES ('3', 'Logo图片', 'site_logo', '');
