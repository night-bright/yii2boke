/*
Navicat MySQL Data Transfer

Source Server         : root
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yii2boke

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-03-17 13:06:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `auth_key` varchar(32) NOT NULL COMMENT '自动登录key',
  `password_hash` varchar(255) NOT NULL COMMENT '加密密码',
  `password_reset_token` varchar(255) DEFAULT NULL COMMENT '重置密码token',
  `email_validate_token` varchar(255) DEFAULT NULL COMMENT '邮箱验证token',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `role` smallint(6) NOT NULL DEFAULT '10' COMMENT '角色等级',
  `status` smallint(6) NOT NULL DEFAULT '10' COMMENT '状态',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `vip_lv` int(11) DEFAULT '0' COMMENT 'vip等级',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=561 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('560', 'admin', 'dU51useQY_rzD25oOfzINZwCFboNadZS', '$2y$13$kRC35O5PHYPxXqd1UC6tgO9L6GdUF2beV55.05fdTjLqVJ2BSSEKG', null, null, 'admin@admin.com', '10', '10', null, '0', '1552118895', '1552118895');

-- ----------------------------
-- Table structure for cats
-- ----------------------------
DROP TABLE IF EXISTS `cats`;
CREATE TABLE `cats` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `cat_name` varchar(255) DEFAULT NULL COMMENT '分类名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='分类表';

-- ----------------------------
-- Records of cats
-- ----------------------------
INSERT INTO `cats` VALUES ('1', '新闻');
INSERT INTO `cats` VALUES ('2', '文章');

-- ----------------------------
-- Table structure for feeds
-- ----------------------------
DROP TABLE IF EXISTS `feeds`;
CREATE TABLE `feeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='聊天信息表';

-- ----------------------------
-- Records of feeds
-- ----------------------------
INSERT INTO `feeds` VALUES ('10', '560', '111111', '1552722590');
INSERT INTO `feeds` VALUES ('11', '560', '你大爷', '1552722615');
INSERT INTO `feeds` VALUES ('12', '560', '哈哈哈', '1552722625');
INSERT INTO `feeds` VALUES ('13', '560', '11111', '1552722939');
INSERT INTO `feeds` VALUES ('14', '560', '3333333333333333333333333', '1552744136');
INSERT INTO `feeds` VALUES ('15', '560', '222222222222222222222222222222', '1552744143');
INSERT INTO `feeds` VALUES ('16', '560', '1111', '1552748390');
INSERT INTO `feeds` VALUES ('17', '560', '1111', '1552748395');

-- ----------------------------
-- Table structure for posts
-- ----------------------------
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `summary` varchar(255) DEFAULT NULL COMMENT '摘要',
  `content` text COMMENT '内容',
  `label_img` varchar(255) DEFAULT NULL COMMENT '标签图',
  `cat_id` int(11) DEFAULT NULL COMMENT '分类id',
  `user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `user_name` varchar(255) DEFAULT NULL COMMENT '用户名',
  `is_valid` tinyint(1) DEFAULT '0' COMMENT '是否有效：0-未发布 1-已发布',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_cat_valid` (`cat_id`,`is_valid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8 COMMENT='文章主表';

-- ----------------------------
-- Records of posts
-- ----------------------------
INSERT INTO `posts` VALUES ('112', '1111', '111', '<p>111<br/></p>', '/image/20190310/1552229212207672.jpg', '1', '560', 'admin', '0', '1552229499', '1552229499');
INSERT INTO `posts` VALUES ('113', '22222', '11', '<p>11</p>', '/image/20190310/1552229760924224.jpg', '1', '560', 'admin', '1', '1552229770', '1552229770');
INSERT INTO `posts` VALUES ('115', '测试文件', '测试文件', '<p>测试文件</p>', '/image/20190311/1552311054377929.jpg', '1', '560', 'admin', '1', '1552311302', '1552311302');
INSERT INTO `posts` VALUES ('117', '22222', '11111111111111112222222', '<p>11111111111111112222222</p>', '/yii2boke/frontend/web/image/20190317/1552797683150543.jpg', '2', '560', 'admin', '1', '1552578116', '1552797715');

-- ----------------------------
-- Table structure for post_extends
-- ----------------------------
DROP TABLE IF EXISTS `post_extends`;
CREATE TABLE `post_extends` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `post_id` int(11) DEFAULT NULL COMMENT '文章id',
  `browser` int(11) DEFAULT '0' COMMENT '浏览量',
  `collect` int(11) DEFAULT '0' COMMENT '收藏量',
  `praise` int(11) DEFAULT '0' COMMENT '点赞',
  `comment` int(11) DEFAULT '0' COMMENT '评论',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COMMENT='文章扩展表';

-- ----------------------------
-- Records of post_extends
-- ----------------------------
INSERT INTO `post_extends` VALUES ('38', '116', '5', '0', '0', '0');
INSERT INTO `post_extends` VALUES ('39', '117', '18', '0', '0', '0');

-- ----------------------------
-- Table structure for relation_post_tags
-- ----------------------------
DROP TABLE IF EXISTS `relation_post_tags`;
CREATE TABLE `relation_post_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `post_id` int(11) DEFAULT NULL COMMENT '文章ID',
  `tag_id` int(11) DEFAULT NULL COMMENT '标签ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_id` (`post_id`,`tag_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='文章和标签关系表';

-- ----------------------------
-- Records of relation_post_tags
-- ----------------------------
INSERT INTO `relation_post_tags` VALUES ('1', '115', '3');
INSERT INTO `relation_post_tags` VALUES ('2', '115', '4');
INSERT INTO `relation_post_tags` VALUES ('3', '116', '5');
INSERT INTO `relation_post_tags` VALUES ('4', '116', '6');
INSERT INTO `relation_post_tags` VALUES ('8', '117', '7');
INSERT INTO `relation_post_tags` VALUES ('9', '117', '8');
INSERT INTO `relation_post_tags` VALUES ('10', '117', '9');
INSERT INTO `relation_post_tags` VALUES ('11', '117', '10');

-- ----------------------------
-- Table structure for tags
-- ----------------------------
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `tag_name` varchar(255) DEFAULT NULL COMMENT '标签名称',
  `post_num` int(11) DEFAULT '0' COMMENT '关联文章数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_name` (`tag_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='标签表';

-- ----------------------------
-- Records of tags
-- ----------------------------
INSERT INTO `tags` VALUES ('3', '测试文件1', '1');
INSERT INTO `tags` VALUES ('4', '测试文件2', '1');
INSERT INTO `tags` VALUES ('5', 'qqqq', '1');
INSERT INTO `tags` VALUES ('6', 'www', '1');
INSERT INTO `tags` VALUES ('7', '111', '2');
INSERT INTO `tags` VALUES ('8', '222', '2');
INSERT INTO `tags` VALUES ('9', '333', '2');
INSERT INTO `tags` VALUES ('10', '11111', '1');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `auth_key` varchar(32) NOT NULL COMMENT '自动登录key',
  `password_hash` varchar(255) NOT NULL COMMENT '加密密码',
  `password_reset_token` varchar(255) DEFAULT NULL COMMENT '重置密码token',
  `email_validate_token` varchar(255) DEFAULT NULL COMMENT '邮箱验证token',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `role` smallint(6) NOT NULL DEFAULT '10' COMMENT '角色等级',
  `status` smallint(6) NOT NULL DEFAULT '10' COMMENT '状态',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `vip_lv` int(11) DEFAULT '0' COMMENT 'vip等级',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=563 DEFAULT CHARSET=utf8 COMMENT='会员表';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('560', 'admin', 'dU51useQY_rzD25oOfzINZwCFboNadZS', '$2y$13$kRC35O5PHYPxXqd1UC6tgO9L6GdUF2beV55.05fdTjLqVJ2BSSEKG', null, null, 'admin@admin.com', '10', '10', null, '0', '1552118895', '1552795518');
INSERT INTO `user` VALUES ('561', '1111111', 'cK72Ys9iuJvhQvot_SpmSxweH8m_rAMU', '$2y$13$pUR99Rkh487Vg9MfxGU3YuKHKqXT7x0DzZoR2aS8RvBtecSCo7Oau', null, null, 'aa@aa.com', '10', '10', null, '0', '1552126477', '1552126477');
INSERT INTO `user` VALUES ('562', '222222', 'u1HSbBYlfPxMNwIeVwRb8L2lhl_lavla', '$2y$13$WK9fW1bAzfIF6YZRcCXLourgkkWHg2TUedRCAM3kx5LMA7U99EcE6', null, null, 'aaa@aaaa.com', '10', '10', null, '0', '1552181574', '1552181574');
