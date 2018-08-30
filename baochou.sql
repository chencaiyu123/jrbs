/*
 Navicat MySQL Data Transfer

 Source Server         : mineSQL
 Source Server Type    : MySQL
 Source Server Version : 50722
 Source Host           : 127.0.0.1:3306
 Source Schema         : baochou

 Target Server Type    : MySQL
 Target Server Version : 50722
 File Encoding         : 65001

 Date: 30/08/2018 16:24:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(20) NOT NULL DEFAULT '' COMMENT 'admin账号',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `name` char(12) NOT NULL DEFAULT '' COMMENT 'admin名',
  `admin_level` tinyint(1) unsigned NOT NULL COMMENT '1,2,3',
  `created` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `logined` int(10) unsigned DEFAULT NULL COMMENT '登录时间',
  `email` char(20) NOT NULL DEFAULT '' COMMENT '注册邮箱',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin
-- ----------------------------
BEGIN;
INSERT INTO `admin` VALUES (1, 'admin', '85f7d858253f8905e3211828bcde08d3', '管理员', 3, 1504782415, 1535615632, '782673136@qq.com');
INSERT INTO `admin` VALUES (2, 'asdfgh', 'cfaacb35117afa8fde4b60b026eff950', '按时', 1, 1535021727, NULL, '');
INSERT INTO `admin` VALUES (3, 'admin23', '097ca4a4c9e731373f308e4057138aa3', '学生信息', 2, 1535022750, NULL, '');
COMMIT;

-- ----------------------------
-- Table structure for baodan
-- ----------------------------
DROP TABLE IF EXISTS `baodan`;
CREATE TABLE `baodan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `salas_id` int(11) NOT NULL COMMENT '提交报单的业务员id',
  `finance_id` int(11) NOT NULL COMMENT '审核报单的财务员id',
  `bill_number` char(6) NOT NULL COMMENT '票号后六位',
  `accepting_bank` char(100) NOT NULL COMMENT '承兑行',
  `amount` decimal(11,2) NOT NULL COMMENT '票面金额',
  `starttime` char(20) NOT NULL COMMENT '票据交易日期',
  `endtime` char(20) NOT NULL COMMENT '票到日期',
  `adjustment_days` int(5) NOT NULL COMMENT '调整天数',
  `days_remaining` int(5) NOT NULL COMMENT '票据总剩余天数',
  `money_rate` float(5,2) NOT NULL COMMENT '利率',
  `mark_up` decimal(5,2) NOT NULL COMMENT '公司加价',
  `modify_prices` decimal(5,2) NOT NULL COMMENT '调整价格',
  `company_quotation` decimal(8,2) NOT NULL COMMENT '公司报价',
  `intermediary_quotation` decimal(8,2) NOT NULL COMMENT '中介报价',
  `subscription_fee` decimal(6,2) NOT NULL COMMENT '打款费',
  `pay_subscription_fee_type` tinyint(1) NOT NULL COMMENT '打款费支付方式 1 从大款扣除 2 从小款扣除',
  `big_fare` decimal(11,2) NOT NULL COMMENT '大票票款',
  `small_fare` decimal(6,2) NOT NULL COMMENT '小款',
  `big_fare_customer_account` char(30) NOT NULL COMMENT '大票客户银行账号',
  `big_fare_customer_bank` char(100) NOT NULL COMMENT '大票客户开户银行',
  `big_fare_customer_name` char(30) NOT NULL COMMENT '大票客户开户姓名',
  `big_fare_customer_phone` char(11) NOT NULL COMMENT '大票客户手机',
  `big_fare_bank_number` char(20) NOT NULL COMMENT '大款汇款银行行号',
  `small_fare_customer_account` char(30) NOT NULL COMMENT '小款客户银行账号',
  `small_fare_customer_bank` char(100) NOT NULL COMMENT '小款客户开户银行',
  `small_fare_customer_name` char(20) NOT NULL COMMENT '小款客户开户姓名',
  `small_fare_customer_phone` char(11) NOT NULL COMMENT '小款客户手机',
  `remarks` char(100) NOT NULL COMMENT '备注',
  `other` char(50) NOT NULL COMMENT '其他',
  `status` int(2) unsigned NOT NULL COMMENT '报单状态 0:未审核  1:审核通过 2:审核不通过 3:软删除',
  `use` int(2) NOT NULL DEFAULT '0' COMMENT '是否使用',
  `creation_date` int(10) NOT NULL COMMENT '报单创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='报单业务表';

-- ----------------------------
-- Records of baodan
-- ----------------------------
BEGIN;
INSERT INTO `baodan` VALUES (1, 27, 0, '785258', '郑州银行', 5000000.00, '2018-05-18', '2019-05-08', 0, 355, 5.35, 0.00, 0.00, 5275.69, 5275.69, 0.00, 1, 4736215.50, 0.00, '15732101040002843', '农行山东省滨州市博兴县支行', '博兴县杰成商贸有限公司', '13795353585', '103466673498', '', '', '', '', '', '', 0, 0, 1526609397);
COMMIT;

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `created` char(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of category
-- ----------------------------
BEGIN;
INSERT INTO `category` VALUES (3, 'a', '1535432993');
COMMIT;

-- ----------------------------
-- Table structure for company
-- ----------------------------
DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company` char(100) NOT NULL,
  `uid` int(5) NOT NULL COMMENT '公司领导id',
  `created` int(10) unsigned NOT NULL,
  `tag` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='公司表';

-- ----------------------------
-- Records of company
-- ----------------------------
BEGIN;
INSERT INTO `company` VALUES (2, '上海倍耀贸易有限公司', 0, 1523170724, 0);
INSERT INTO `company` VALUES (3, '安徽安粮控股股份有限公司', 0, 1523171072, 0);
INSERT INTO `company` VALUES (4, '安徽家森家商贸有限公司', 0, 1523171092, 0);
INSERT INTO `company` VALUES (5, '安徽家森家文化发展有限公司', 0, 1523171118, 0);
INSERT INTO `company` VALUES (6, '安徽杰盟物流有限公司', 0, 1523171243, 0);
INSERT INTO `company` VALUES (7, '安徽晋源铜业有限公司', 0, 1523171250, 0);
INSERT INTO `company` VALUES (8, '安徽省粮油食品进出口（集团）公司', 0, 1523171257, 0);
INSERT INTO `company` VALUES (9, '常州金坛科兴商贸有限公司', 0, 1523171264, 0);
INSERT INTO `company` VALUES (10, '常州市骏舟贸易有限公司', 0, 1523171270, 0);
INSERT INTO `company` VALUES (11, '大连正大物资销售有限公司', 0, 1523171276, 0);
INSERT INTO `company` VALUES (12, '大冶市镇钦矿业有限公司', 0, 1523171282, 0);
INSERT INTO `company` VALUES (13, '德清县金宇贸易有限公司', 0, 1523171287, 0);
INSERT INTO `company` VALUES (14, '广州夏特贸易有限公司', 0, 1523171295, 0);
INSERT INTO `company` VALUES (15, '广州耀金农产品有限公司', 0, 1523171302, 0);
INSERT INTO `company` VALUES (16, '贵州长征天成控股股份有限公司', 0, 1523171309, 0);
INSERT INTO `company` VALUES (17, '好易购家庭购物有限公司', 0, 1523171315, 0);
INSERT INTO `company` VALUES (18, '河南豫光金铅股份有限公司', 0, 1523171322, 0);
INSERT INTO `company` VALUES (19, '河南中原黄金冶炼厂有限责任公司', 0, 1523171329, 0);
INSERT INTO `company` VALUES (20, '华北制药集团（天津）国际物流有限公司', 0, 1523171339, 0);
INSERT INTO `company` VALUES (21, '江苏省建工集团上海物贸发展有限公司', 0, 1523171346, 0);
INSERT INTO `company` VALUES (22, '江苏新苏浙金属材料有限公司', 0, 1523171352, 0);
INSERT INTO `company` VALUES (23, '江阴聚协春贸易有限公司', 0, 1523171360, 0);
INSERT INTO `company` VALUES (24, '江阴泽耀进出口有限公司', 0, 1523171368, 0);
INSERT INTO `company` VALUES (25, '快乐购物股份有限公司', 0, 1523171389, 0);
INSERT INTO `company` VALUES (26, '括兹国际贸易（上海）有限公司', 0, 1523171395, 0);
INSERT INTO `company` VALUES (27, '南京升欣圣国际贸易有限公司', 0, 1523171402, 0);
INSERT INTO `company` VALUES (28, '南京世界村新能源汽车有限公司', 0, 1523171409, 0);
INSERT INTO `company` VALUES (29, '南京天之路国际经贸有限公司', 0, 1523171416, 0);
INSERT INTO `company` VALUES (30, '宁波德威机械有限公司', 0, 1523171423, 0);
INSERT INTO `company` VALUES (31, '宁波迪塞恩贸易有限公司', 0, 1523171432, 0);
INSERT INTO `company` VALUES (32, '宁波鼎亿达贸易有限公司', 0, 1523171439, 0);
INSERT INTO `company` VALUES (33, '宁波汉铎贸易有限公司', 0, 1523171448, 0);
INSERT INTO `company` VALUES (34, '宁波恒威车轮有限公司', 0, 1523171454, 0);
INSERT INTO `company` VALUES (35, '宁波恒威数控机床有限公司', 0, 1523171460, 0);
INSERT INTO `company` VALUES (36, '全威（上海）有色金属有限公司', 0, 1523171467, 0);
INSERT INTO `company` VALUES (37, '润富实业（上海）有限公司', 0, 1523171474, 0);
INSERT INTO `company` VALUES (38, '上海阿斯顿马丁汽车销售有限公司', 0, 1523171480, 0);
INSERT INTO `company` VALUES (39, '上海班里金属材料有限公司', 0, 1523171488, 0);
INSERT INTO `company` VALUES (40, '上海超奕金属材料有限公司', 0, 1523171502, 0);
INSERT INTO `company` VALUES (41, '上海呈翼贸易有限公司', 0, 1523171508, 0);
INSERT INTO `company` VALUES (42, '上海福厚贸易有限公司', 0, 1523171515, 0);
INSERT INTO `company` VALUES (43, '上海阜茂贸易有限公司', 0, 1523171529, 0);
INSERT INTO `company` VALUES (44, '上海高谦贸易有限公司', 0, 1523171537, 0);
INSERT INTO `company` VALUES (45, '上海海通资源管理有限公司', 0, 1523171546, 0);
INSERT INTO `company` VALUES (46, '上海灏航船舶工程有限公司', 0, 1523171554, 0);
INSERT INTO `company` VALUES (47, '上海何启实业有限公司', 0, 1523171560, 0);
INSERT INTO `company` VALUES (48, '上海竑瑞贸易有限公司', 0, 1523171566, 0);
INSERT INTO `company` VALUES (49, '上海吉帝供应链管理有限公司', 0, 1523171572, 0);
INSERT INTO `company` VALUES (50, '上海嘉绍实业发展有限公司', 0, 1523171579, 0);
INSERT INTO `company` VALUES (51, '上海俭瑾实业有限公司', 0, 1523171588, 0);
INSERT INTO `company` VALUES (52, '上海金复电子商务有限公司', 0, 1523171596, 0);
INSERT INTO `company` VALUES (53, '上海炬伊机械设备有限公司', 0, 1523171604, 0);
INSERT INTO `company` VALUES (54, '上海聚洲金属材料有限公司', 0, 1523171611, 0);
INSERT INTO `company` VALUES (55, '上海莉能实业有限公司', 0, 1523171618, 0);
INSERT INTO `company` VALUES (56, '上海联凯实业有限责任公司', 0, 1523171625, 0);
INSERT INTO `company` VALUES (57, '上海禄甫国际贸易有限公司', 0, 1523171632, 0);
INSERT INTO `company` VALUES (58, '上海念平实业有限公司', 0, 1523171640, 0);
INSERT INTO `company` VALUES (59, '上海牛汇贸易有限公司', 0, 1523171647, 0);
INSERT INTO `company` VALUES (60, '上海攀高贸易有限公司', 0, 1523171654, 0);
INSERT INTO `company` VALUES (61, '上海奇暄商贸有限公司', 0, 1523171658, 0);
INSERT INTO `company` VALUES (62, '上海融际供应链管理有限公司', 0, 1523171665, 0);
INSERT INTO `company` VALUES (63, '上海森贸国际贸易有限公司', 0, 1523171671, 0);
INSERT INTO `company` VALUES (64, '上海善加国际贸易有限公司', 0, 1523171677, 0);
INSERT INTO `company` VALUES (65, '上海圣侨国际贸易有限公司', 0, 1523171684, 0);
INSERT INTO `company` VALUES (66, '上海时祥国际贸易有限公司', 0, 1523171689, 0);
INSERT INTO `company` VALUES (67, '上海蜀屹实业有限公司', 0, 1523171694, 0);
INSERT INTO `company` VALUES (68, '上海树玺国际贸易有限公司', 0, 1523171700, 0);
INSERT INTO `company` VALUES (69, '上海丝嘉国际贸易有限公司', 0, 1523171708, 0);
INSERT INTO `company` VALUES (70, '上海瞳栎贸易有限公司', 0, 1523171714, 0);
INSERT INTO `company` VALUES (71, '上海西欣实业有限公司', 0, 1523171719, 0);
INSERT INTO `company` VALUES (72, '上海祥归实业有限公司', 0, 1523171726, 0);
INSERT INTO `company` VALUES (73, '上海新安屹国际贸易有限公司', 0, 1523171733, 0);
INSERT INTO `company` VALUES (74, '上海亚炬资源有限公司', 0, 1523171746, 0);
INSERT INTO `company` VALUES (75, '上海尧信实业有限公司', 0, 1523171753, 0);
INSERT INTO `company` VALUES (76, '上海一麟国际贸易有限公司', 0, 1523171759, 0);
INSERT INTO `company` VALUES (77, '上海应泽国际贸易有限公司', 0, 1523171765, 0);
INSERT INTO `company` VALUES (78, '上海雍荟贸易有限公司', 0, 1523171773, 0);
INSERT INTO `company` VALUES (79, '上海越澜金属材料有限公司', 0, 1523171780, 0);
INSERT INTO `company` VALUES (80, '上海赟汀实业有限公司', 0, 1523171786, 0);
INSERT INTO `company` VALUES (81, '上海中汉国际贸易有限公司', 0, 1523171793, 0);
INSERT INTO `company` VALUES (82, '上海州羽实业有限公司', 0, 1523171800, 0);
INSERT INTO `company` VALUES (83, '上海逐天实业有限公司', 0, 1523171806, 0);
INSERT INTO `company` VALUES (84, '无锡利永重工科技有限公司', 0, 1523171812, 0);
INSERT INTO `company` VALUES (85, '无锡市运华物资有限公司', 0, 1523171817, 0);
INSERT INTO `company` VALUES (86, '偕悦国际贸易（上海）有限公司', 0, 1523171823, 0);
INSERT INTO `company` VALUES (87, '央广幸福购物（北京）有限公司', 0, 1523171875, 0);
INSERT INTO `company` VALUES (88, '远大生水资源有限公司', 0, 1523171899, 0);
INSERT INTO `company` VALUES (89, '展岳实业（上海）有限公司', 0, 1523171906, 0);
INSERT INTO `company` VALUES (90, '浙江富春江通信集团有限公司上海分公司', 0, 1523171913, 0);
INSERT INTO `company` VALUES (91, '中钢投上海有限公司', 0, 1523171919, 0);
INSERT INTO `company` VALUES (92, '中国林产品公司', 0, 1523171926, 0);
INSERT INTO `company` VALUES (93, '中丝（上海）能源发展有限公司', 0, 1523171948, 0);
INSERT INTO `company` VALUES (94, '中丝进出口有限责任公司', 0, 1523171974, 0);
INSERT INTO `company` VALUES (95, '中铜矿业资源有限公司', 0, 1523171985, 0);
INSERT INTO `company` VALUES (96, '镇江中能恒兴国际贸易有限公司', 0, 1523171991, 0);
INSERT INTO `company` VALUES (97, '宏图三胞高科技术有限公司', 0, 1523171999, 0);
INSERT INTO `company` VALUES (98, '南京启峻数码科技有限公司', 0, 1523172006, 0);
INSERT INTO `company` VALUES (99, '上海景筠商贸有限公司', 0, 1523172013, 0);
INSERT INTO `company` VALUES (100, '上海堃鸿实业有限公司', 0, 1523172019, 0);
INSERT INTO `company` VALUES (101, '上海乾盛冠源实业发展有限公司', 0, 1523172025, 0);
INSERT INTO `company` VALUES (102, '上海杉迅实业有限公司', 0, 1523172031, 0);
INSERT INTO `company` VALUES (103, '上海堃怡国际贸易有限公司', 0, 1523172036, 0);
INSERT INTO `company` VALUES (104, '上海瞳栎珠宝有限公司', 0, 1523172043, 0);
INSERT INTO `company` VALUES (105, '测试公司一', 0, 1526295162, 0);
COMMIT;

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `department` char(20) NOT NULL,
  `uid` int(5) NOT NULL COMMENT '部门负责人id',
  `created` int(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='公司部门表';

-- ----------------------------
-- Records of department
-- ----------------------------
BEGIN;
INSERT INTO `department` VALUES (1, '财务部', 0, 1526295220);
INSERT INTO `department` VALUES (2, '业务部', 0, 1526295591);
COMMIT;

-- ----------------------------
-- Table structure for groups
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` char(20) NOT NULL,
  `group_leader` int(11) NOT NULL,
  `did` int(5) NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of groups
-- ----------------------------
BEGIN;
INSERT INTO `groups` VALUES (1, '业务一组', 0, 2, 1526295632);
INSERT INTO `groups` VALUES (2, '业务二组', 0, 2, 1526295649);
INSERT INTO `groups` VALUES (5, 'ceshi', 0, 1, 1535022426);
COMMIT;

-- ----------------------------
-- Table structure for relation
-- ----------------------------
DROP TABLE IF EXISTS `relation`;
CREATE TABLE `relation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL,
  `shangyou` char(100) NOT NULL,
  `xiayou` char(100) NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Table structure for reports
-- ----------------------------
DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL,
  `sid` int(10) NOT NULL,
  `xid` int(10) NOT NULL,
  `category_id` int(10) NOT NULL,
  `time` int(10) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(20) NOT NULL,
  `password` char(32) NOT NULL,
  `name` char(12) NOT NULL,
  `phone` char(11) DEFAULT NULL,
  `wechat` char(40) DEFAULT NULL,
  `email` char(100) DEFAULT NULL,
  `group` int(2) unsigned DEFAULT NULL,
  `group_leader` tinyint(2) unsigned DEFAULT NULL,
  `is_salas` tinyint(2) unsigned DEFAULT NULL,
  `is_finance` tinyint(2) unsigned DEFAULT NULL,
  `finance_leader` tinyint(2) DEFAULT NULL,
  `is_admin` tinyint(2) unsigned DEFAULT NULL,
  `admin_level` int(2) unsigned NOT NULL DEFAULT '0' COMMENT '0不是管理 1,基础管理员， 2高级管理员， 3最大权限',
  `cid` int(2) NOT NULL COMMENT '所属公司id',
  `did` int(2) NOT NULL COMMENT '部门id',
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of user
-- ----------------------------
BEGIN;
INSERT INTO `user` VALUES (1, 'admin1', '03495af29a470dc84163134d1c5c80c8', '管理员', NULL, NULL, '782673136@qq.com', NULL, NULL, NULL, NULL, NULL, 1, 3, 0, 0, 1504782415);
INSERT INTO `user` VALUES (19, 'caiwu_test2', '1b8bae6236c9553db98046bd0ea43407', '财务二', '12345678900', '', '1@qq.com', NULL, NULL, NULL, 1, NULL, NULL, 0, 105, 1, 1526295282);
INSERT INTO `user` VALUES (20, 'caiwu_test3', 'e2e158a6bd26633e28bce45b5523d29d', '财务三', '12345678900', '', '1@qq.com', NULL, NULL, NULL, 1, NULL, NULL, 0, 105, 1, 1526295318);
INSERT INTO `user` VALUES (21, 'caiwu_test4', 'd2fe0ef7ecd56b6c79f688ff653017a1', '财务四', '12345678900', '', '1@qq.com', NULL, NULL, NULL, 1, NULL, NULL, 0, 105, 1, 1526295350);
INSERT INTO `user` VALUES (27, 'yewu_test1', 'e66d9cb565e48a119ed44220be812038', '业务一', '12345678900', '', '1@qq.com', 1, NULL, 1, NULL, NULL, NULL, 0, 105, 2, 1526295681);
INSERT INTO `user` VALUES (28, 'yewu_test2', 'bfd0d3eed71f179873b1024151fd1898', '业务二', '12345678900', '', '1@qq.com', 2, NULL, 1, NULL, NULL, NULL, 0, 105, 2, 1526295715);
INSERT INTO `user` VALUES (29, 'qwert', 'bb5d48093adac28a2c9e82924b304d65', '几居室', '123456789', '', '1233222', 5, NULL, NULL, NULL, NULL, NULL, 0, 2, 2, 1535021494);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
