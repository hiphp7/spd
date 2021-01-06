/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.253
Source Server Version : 50173
Source Host           : 192.168.1.253:3306
Source Database       : shopping

Target Server Type    : MYSQL
Target Server Version : 50173
File Encoding         : 65001

Date: 2019-02-16 15:58:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `comm_support_bank_info`
-- ----------------------------
DROP TABLE IF EXISTS `comm_support_bank_info`;
CREATE TABLE `comm_support_bank_info` (
  `id` varchar(21) NOT NULL COMMENT 'ID',
  `bank_id` varchar(11) DEFAULT NULL COMMENT '银行编码',
  `bank_name` varchar(50) DEFAULT NULL COMMENT '银行名称',
  `image_id` varchar(20) DEFAULT NULL COMMENT '图片ID',
  `status` varchar(1) DEFAULT NULL COMMENT '1 有效 0无效',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `create_by` varchar(20) NOT NULL COMMENT '创建者',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `update_by` varchar(20) NOT NULL COMMENT '更新者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支持银行';

-- ----------------------------
-- Records of comm_support_bank_info
-- ----------------------------
INSERT INTO `comm_support_bank_info` VALUES ('1', 'CMB', '招商银行', null, '1', '2016-10-31 09:17:50', 'system', '2016-10-31 09:18:09', 'system');
INSERT INTO `comm_support_bank_info` VALUES ('10', 'CMBC', '民生银行', null, '1', '2016-10-31 09:18:09', 'system', '2016-10-31 09:18:09', 'system');
INSERT INTO `comm_support_bank_info` VALUES ('11', 'CIB', '兴业银行', null, '1', '2016-10-31 09:18:20', 'system', '2016-10-31 09:18:09', 'system');
INSERT INTO `comm_support_bank_info` VALUES ('12', 'SPDB', '上海浦东发展银行', null, '1', '2016-10-31 09:18:09', 'system', '2016-10-31 09:18:09', 'system');
INSERT INTO `comm_support_bank_info` VALUES ('13', 'CITIC', '中信银行', null, '1', '2016-10-31 09:18:09', 'system', '2016-10-31 09:18:09', 'system');
INSERT INTO `comm_support_bank_info` VALUES ('14', 'GDB', '广发银行', null, '1', '2016-10-31 09:18:09', 'system', '2016-10-31 09:18:09', 'system');
INSERT INTO `comm_support_bank_info` VALUES ('2', 'ICBC', '中国工商银行', null, '1', '2016-10-31 09:17:50', 'system', '2016-10-31 09:18:09', 'system');
INSERT INTO `comm_support_bank_info` VALUES ('3', 'ABC', '中国农业银行', null, '1', '2016-10-31 09:18:00', 'system', '2016-10-31 09:18:09', 'system');
INSERT INTO `comm_support_bank_info` VALUES ('4', 'CCB', '中国建设银行', null, '1', '2016-10-31 09:18:04', 'system', '2016-10-31 09:18:09', 'system');
INSERT INTO `comm_support_bank_info` VALUES ('5', 'PSBC', '中国邮政储蓄银行', null, '1', '2016-10-31 09:18:07', 'system', '2016-10-31 09:18:09', 'system');
INSERT INTO `comm_support_bank_info` VALUES ('6', 'BOC', '中国银行', null, '1', '2016-10-31 09:18:09', 'system', '2016-10-31 09:18:09', 'system');
INSERT INTO `comm_support_bank_info` VALUES ('7', 'COMM', '交通银行', null, '1', '2016-10-31 09:18:12', 'system', '2016-10-31 09:18:09', 'system');
INSERT INTO `comm_support_bank_info` VALUES ('8', 'CEB', '中国光大银行', null, '1', '2016-10-31 09:18:14', 'system', '2016-10-31 09:18:09', 'system');
INSERT INTO `comm_support_bank_info` VALUES ('9', 'SZPAB', '平安银行', null, '1', '2016-10-31 09:18:16', 'system', '2016-10-31 09:18:09', 'system');
