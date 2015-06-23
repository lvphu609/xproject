/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50543
Source Host           : localhost:3306
Source Database       : xproject

Target Server Type    : MYSQL
Target Server Version : 50543
File Encoding         : 65001

Date: 2015-06-23 10:02:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for accounts
-- ----------------------------
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `date_of_birth` varchar(255) DEFAULT NULL,
  `gender` int(10) DEFAULT NULL,
  `identity_card_id` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `blood_group_id` int(20) DEFAULT NULL,
  `blood_group_rh_id` int(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_delete` int(1) DEFAULT NULL,
  `is_access_token` varchar(255) DEFAULT NULL,
  `avatar` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of accounts
-- ----------------------------

-- ----------------------------
-- Table structure for api_ci_sessions
-- ----------------------------
DROP TABLE IF EXISTS `api_ci_sessions`;
CREATE TABLE `api_ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of api_ci_sessions
-- ----------------------------

-- ----------------------------
-- Table structure for api_login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `api_login_attempts`;
CREATE TABLE `api_login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of api_login_attempts
-- ----------------------------
INSERT INTO `api_login_attempts` VALUES ('4', '127.0.0.1', 'admin', '2015-06-22 15:13:48');

-- ----------------------------
-- Table structure for api_logs
-- ----------------------------
DROP TABLE IF EXISTS `api_logs`;
CREATE TABLE `api_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of api_logs
-- ----------------------------
INSERT INTO `api_logs` VALUES ('1', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434961190', '0.398454', '1');
INSERT INTO `api_logs` VALUES ('2', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434961261', '0.209985', '1');
INSERT INTO `api_logs` VALUES ('3', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434961320', '0.407706', '1');
INSERT INTO `api_logs` VALUES ('4', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434961436', '0.196092', '1');
INSERT INTO `api_logs` VALUES ('5', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434961476', '0.227461', '1');
INSERT INTO `api_logs` VALUES ('6', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434961511', '0.217526', '1');
INSERT INTO `api_logs` VALUES ('7', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434961603', '0.216907', '1');
INSERT INTO `api_logs` VALUES ('8', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434961610', '0.187209', '1');
INSERT INTO `api_logs` VALUES ('9', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434961617', '0.17836', '1');
INSERT INTO `api_logs` VALUES ('10', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434961683', '0.211077', '1');
INSERT INTO `api_logs` VALUES ('11', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434961745', '0.250499', '1');
INSERT INTO `api_logs` VALUES ('12', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434961791', '0.199135', '1');
INSERT INTO `api_logs` VALUES ('13', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434962459', '0.703417', '1');
INSERT INTO `api_logs` VALUES ('14', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434962478', '0.291384', '1');
INSERT INTO `api_logs` VALUES ('15', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434962561', '0.213474', '1');
INSERT INTO `api_logs` VALUES ('16', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434962889', '0.231524', '1');
INSERT INTO `api_logs` VALUES ('17', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434962926', '0.27848', '1');
INSERT INTO `api_logs` VALUES ('18', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434963249', '0.239966', '1');
INSERT INTO `api_logs` VALUES ('19', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434963392', '0.395037', '1');
INSERT INTO `api_logs` VALUES ('20', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434963436', '0.270015', '1');
INSERT INTO `api_logs` VALUES ('21', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434963601', '0.418212', '1');
INSERT INTO `api_logs` VALUES ('22', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434963640', '0.289385', '1');
INSERT INTO `api_logs` VALUES ('23', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434963648', '0.184871', '1');
INSERT INTO `api_logs` VALUES ('24', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434963671', '0.679817', '1');
INSERT INTO `api_logs` VALUES ('25', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434964194', '0.364476', '1');
INSERT INTO `api_logs` VALUES ('26', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434964753', '0.238848', '1');
INSERT INTO `api_logs` VALUES ('27', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434964764', '0.23616', '1');
INSERT INTO `api_logs` VALUES ('28', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434964875', '0.193755', '1');
INSERT INTO `api_logs` VALUES ('29', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434964883', '0.250337', '1');
INSERT INTO `api_logs` VALUES ('30', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434965558', '0.280599', '1');
INSERT INTO `api_logs` VALUES ('31', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434965595', '0.203301', '1');
INSERT INTO `api_logs` VALUES ('32', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434965624', '0.303749', '1');
INSERT INTO `api_logs` VALUES ('33', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434965708', '0.273325', '1');
INSERT INTO `api_logs` VALUES ('34', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434965753', '0.243973', '1');
INSERT INTO `api_logs` VALUES ('35', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434965994', '0.275011', '1');
INSERT INTO `api_logs` VALUES ('36', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434966001', '0.273416', '1');
INSERT INTO `api_logs` VALUES ('37', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434966007', '0.253388', '1');
INSERT INTO `api_logs` VALUES ('38', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434966038', '0.255616', '1');
INSERT INTO `api_logs` VALUES ('39', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434966121', '0.224798', '1');
INSERT INTO `api_logs` VALUES ('40', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434966334', '0.230508', '1');
INSERT INTO `api_logs` VALUES ('41', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434966367', '0.217999', '1');
INSERT INTO `api_logs` VALUES ('42', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434966733', '0.300162', '1');
INSERT INTO `api_logs` VALUES ('43', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434966864', '0.281499', '1');
INSERT INTO `api_logs` VALUES ('44', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434966870', '0.230105', '1');
INSERT INTO `api_logs` VALUES ('45', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434967618', '0.423513', '1');
INSERT INTO `api_logs` VALUES ('46', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434967700', '0.190198', '1');
INSERT INTO `api_logs` VALUES ('47', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434968462', '0.200741', '1');
INSERT INTO `api_logs` VALUES ('48', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434968595', '0.266437', '1');
INSERT INTO `api_logs` VALUES ('49', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434968981', '0.192078', '1');
INSERT INTO `api_logs` VALUES ('50', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434969237', '0.223708', '1');
INSERT INTO `api_logs` VALUES ('51', 'api/accounts/create', 'post', null, '', '192.168.1.136', '1434969324', '0.176277', '1');
INSERT INTO `api_logs` VALUES ('52', 'api/accounts/test', 'get', 'a:1:{s:6:\"format\";s:4:\"json\";}', '', '192.168.1.136', '1434969441', '0.162972', '1');
INSERT INTO `api_logs` VALUES ('53', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434975894', '0.272567', '1');
INSERT INTO `api_logs` VALUES ('54', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434987265', '0.385329', '1');
INSERT INTO `api_logs` VALUES ('55', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434987278', '0.305948', '1');
INSERT INTO `api_logs` VALUES ('56', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434987495', '0.236411', '1');
INSERT INTO `api_logs` VALUES ('57', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434987497', '0.23774', '1');
INSERT INTO `api_logs` VALUES ('58', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434987502', '0.254969', '1');
INSERT INTO `api_logs` VALUES ('59', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434987744', '0.247212', '1');
INSERT INTO `api_logs` VALUES ('60', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434987868', '0.310724', '1');
INSERT INTO `api_logs` VALUES ('61', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434987881', '0.201752', '1');
INSERT INTO `api_logs` VALUES ('62', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434987890', '0.201966', '1');
INSERT INTO `api_logs` VALUES ('63', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434987896', '0.198765', '1');
INSERT INTO `api_logs` VALUES ('64', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434987913', '0.220995', '1');
INSERT INTO `api_logs` VALUES ('65', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434987999', '0.2146', '1');
INSERT INTO `api_logs` VALUES ('66', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988018', '0.251179', '1');
INSERT INTO `api_logs` VALUES ('67', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988060', '0.2294', '1');
INSERT INTO `api_logs` VALUES ('68', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988363', '0.200921', '1');
INSERT INTO `api_logs` VALUES ('69', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988390', '0.276152', '1');
INSERT INTO `api_logs` VALUES ('70', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988408', '0.27766', '1');
INSERT INTO `api_logs` VALUES ('71', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988409', '0.231849', '1');
INSERT INTO `api_logs` VALUES ('72', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988411', '0.212608', '1');
INSERT INTO `api_logs` VALUES ('73', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988436', '0.377333', '1');
INSERT INTO `api_logs` VALUES ('74', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988437', '0.395176', '1');
INSERT INTO `api_logs` VALUES ('75', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988437', '0.145459', '1');
INSERT INTO `api_logs` VALUES ('76', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988438', '0.245959', '1');
INSERT INTO `api_logs` VALUES ('77', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988439', '0.233144', '1');
INSERT INTO `api_logs` VALUES ('78', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988440', '0.249864', '1');
INSERT INTO `api_logs` VALUES ('79', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988441', '0.435382', '1');
INSERT INTO `api_logs` VALUES ('80', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988442', '0.22026', '1');
INSERT INTO `api_logs` VALUES ('81', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988442', '0.151776', '1');
INSERT INTO `api_logs` VALUES ('82', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988443', '0.238656', '1');
INSERT INTO `api_logs` VALUES ('83', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988444', '0.228529', '1');
INSERT INTO `api_logs` VALUES ('84', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988445', '0.235469', '1');
INSERT INTO `api_logs` VALUES ('85', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988470', '0.562947', '1');
INSERT INTO `api_logs` VALUES ('86', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988470', '0.791389', '1');
INSERT INTO `api_logs` VALUES ('87', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988471', '0.283075', '1');
INSERT INTO `api_logs` VALUES ('88', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988472', '0.302755', '1');
INSERT INTO `api_logs` VALUES ('89', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988477', '0.315209', '1');
INSERT INTO `api_logs` VALUES ('90', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988681', '0.235852', '1');
INSERT INTO `api_logs` VALUES ('91', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988934', '0.272343', '1');
INSERT INTO `api_logs` VALUES ('92', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434988989', '0.24035', '1');
INSERT INTO `api_logs` VALUES ('93', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434989950', '0.235386', '1');
INSERT INTO `api_logs` VALUES ('94', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434990019', '0.225141', '1');
INSERT INTO `api_logs` VALUES ('95', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434990022', '0.284522', '1');
INSERT INTO `api_logs` VALUES ('96', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434990505', '0.227483', '1');
INSERT INTO `api_logs` VALUES ('97', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434990547', '0.194874', '1');
INSERT INTO `api_logs` VALUES ('98', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434990726', '0.252291', '1');
INSERT INTO `api_logs` VALUES ('99', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434990750', '0.251245', '1');
INSERT INTO `api_logs` VALUES ('100', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434990835', '0.242094', '1');
INSERT INTO `api_logs` VALUES ('101', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434990872', '0.196964', '1');
INSERT INTO `api_logs` VALUES ('102', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434990907', '0.202163', '1');
INSERT INTO `api_logs` VALUES ('103', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434990953', '0.2727', '1');
INSERT INTO `api_logs` VALUES ('104', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434991133', '0.256427', '1');
INSERT INTO `api_logs` VALUES ('105', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434991617', '0.378907', '1');
INSERT INTO `api_logs` VALUES ('106', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434991649', '0.357558', '1');
INSERT INTO `api_logs` VALUES ('107', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434991651', '0.443232', '1');
INSERT INTO `api_logs` VALUES ('108', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434991684', '0.418415', '1');
INSERT INTO `api_logs` VALUES ('109', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434993307', '3.17755', '1');
INSERT INTO `api_logs` VALUES ('110', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434993812', '0.567479', '1');
INSERT INTO `api_logs` VALUES ('111', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434993859', '0.428769', '1');
INSERT INTO `api_logs` VALUES ('112', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434994515', '0.484316', '1');
INSERT INTO `api_logs` VALUES ('113', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434994806', '0.254455', '1');
INSERT INTO `api_logs` VALUES ('114', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434994816', '0.242953', '1');
INSERT INTO `api_logs` VALUES ('115', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434995928', '0.342215', '1');
INSERT INTO `api_logs` VALUES ('116', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434995962', '0.423996', '1');
INSERT INTO `api_logs` VALUES ('117', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434995972', '0.379803', '1');
INSERT INTO `api_logs` VALUES ('118', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1434996389', '0.484609', '1');
INSERT INTO `api_logs` VALUES ('119', 'api/accounts/create', 'post', 'a:5:{s:8:\"username\";s:0:\"\";s:8:\"password\";s:0:\"\";s:16:\"confirm_password\";s:0:\"\";s:5:\"email\";s:0:\"\";s:9:\"full_name\";s:0:\"\";}', '', '127.0.0.1', '1434996464', '0.365716', '1');
INSERT INTO `api_logs` VALUES ('120', 'api/accounts/create', 'post', 'a:5:{s:8:\"username\";s:0:\"\";s:8:\"password\";s:0:\"\";s:16:\"confirm_password\";s:0:\"\";s:5:\"email\";s:0:\"\";s:9:\"full_name\";s:0:\"\";}', '', '127.0.0.1', '1434996482', '0.329531', '1');
INSERT INTO `api_logs` VALUES ('121', 'api/accounts/create', 'post', 'a:5:{s:8:\"username\";s:3:\"asd\";s:8:\"password\";s:3:\"asd\";s:16:\"confirm_password\";s:3:\"asd\";s:5:\"email\";s:3:\"asd\";s:9:\"full_name\";s:3:\"asd\";}', '', '127.0.0.1', '1434996490', '0.236451', '1');
INSERT INTO `api_logs` VALUES ('122', 'api/accounts/create', 'post', 'a:5:{s:8:\"username\";s:3:\"asd\";s:8:\"password\";s:3:\"asd\";s:16:\"confirm_password\";s:3:\"asd\";s:5:\"email\";s:3:\"asd\";s:9:\"full_name\";s:3:\"asd\";}', '', '127.0.0.1', '1434996503', '0.389649', '1');
INSERT INTO `api_logs` VALUES ('123', 'api/accounts/create', 'post', 'a:5:{s:8:\"username\";s:3:\"asd\";s:8:\"password\";s:3:\"asd\";s:16:\"confirm_password\";s:3:\"asd\";s:5:\"email\";s:3:\"asd\";s:9:\"full_name\";s:3:\"asd\";}', '', '127.0.0.1', '1434996637', '0.308919', '1');
INSERT INTO `api_logs` VALUES ('124', 'api/accounts/create', 'post', 'a:5:{s:8:\"username\";s:3:\"asd\";s:8:\"password\";s:3:\"asd\";s:16:\"confirm_password\";s:0:\"\";s:5:\"email\";s:0:\"\";s:9:\"full_name\";s:0:\"\";}', '', '127.0.0.1', '1434996643', '0.265477', '1');
INSERT INTO `api_logs` VALUES ('125', 'api/accounts/create', 'post', 'a:5:{s:8:\"username\";s:0:\"\";s:8:\"password\";s:3:\"asd\";s:16:\"confirm_password\";s:0:\"\";s:5:\"email\";s:0:\"\";s:9:\"full_name\";s:0:\"\";}', '', '127.0.0.1', '1434996659', '0.294773', '1');
INSERT INTO `api_logs` VALUES ('126', 'api/accounts/create', 'post', 'a:5:{s:8:\"username\";s:2:\"ad\";s:8:\"password\";s:3:\"asd\";s:16:\"confirm_password\";s:3:\"asd\";s:5:\"email\";s:3:\"asd\";s:9:\"full_name\";s:3:\"asd\";}', '', '127.0.0.1', '1435025266', '0.269339', '1');
INSERT INTO `api_logs` VALUES ('127', 'api/accounts/create', 'post', 'a:5:{s:8:\"username\";s:2:\"ad\";s:8:\"password\";s:3:\"asd\";s:16:\"confirm_password\";s:3:\"asd\";s:5:\"email\";s:3:\"asd\";s:9:\"full_name\";s:3:\"asd\";}', '', '127.0.0.1', '1435025919', '0.927522', '1');
INSERT INTO `api_logs` VALUES ('128', 'api/accounts/create', 'post', 'a:5:{s:8:\"username\";s:2:\"ad\";s:8:\"password\";s:3:\"asd\";s:16:\"confirm_password\";s:3:\"asd\";s:5:\"email\";s:3:\"asd\";s:9:\"full_name\";s:3:\"asd\";}', '', '127.0.0.1', '1435026145', '0.481653', '1');
INSERT INTO `api_logs` VALUES ('129', 'api/accounts/create', 'post', 'a:5:{s:8:\"username\";s:2:\"ad\";s:8:\"password\";s:3:\"asd\";s:16:\"confirm_password\";s:3:\"asd\";s:5:\"email\";s:3:\"asd\";s:9:\"full_name\";s:3:\"asd\";}', '', '127.0.0.1', '1435026339', '0.308105', '1');
INSERT INTO `api_logs` VALUES ('130', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1435027088', '0.404294', '1');
INSERT INTO `api_logs` VALUES ('131', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1435027296', '0.331364', '1');
INSERT INTO `api_logs` VALUES ('132', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1435027447', '0.684381', '1');
INSERT INTO `api_logs` VALUES ('133', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1435027494', '0.639822', '1');
INSERT INTO `api_logs` VALUES ('134', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1435027628', '0.550285', '1');
INSERT INTO `api_logs` VALUES ('135', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1435028061', '0.523633', '1');
INSERT INTO `api_logs` VALUES ('136', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1435028257', '0.366523', '1');
INSERT INTO `api_logs` VALUES ('137', 'api/accounts/create', 'post', null, '', '127.0.0.1', '1435028296', '0.461291', '1');

-- ----------------------------
-- Table structure for api_user_autologin
-- ----------------------------
DROP TABLE IF EXISTS `api_user_autologin`;
CREATE TABLE `api_user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of api_user_autologin
-- ----------------------------

-- ----------------------------
-- Table structure for api_user_profiles
-- ----------------------------
DROP TABLE IF EXISTS `api_user_profiles`;
CREATE TABLE `api_user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of api_user_profiles
-- ----------------------------

-- ----------------------------
-- Table structure for api_users
-- ----------------------------
DROP TABLE IF EXISTS `api_users`;
CREATE TABLE `api_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of api_users
-- ----------------------------
INSERT INTO `api_users` VALUES ('3', 'admin', '$2a$08$Hlc9Nd9BqifdUIgVq6PRju/2fpuGGa7Y3/ZuRoo3SHZL.qIFT6yQy', 'admin@xproject.com', '1', '0', null, null, null, null, 'f9dcbbd4fc3147c79bdea80222b5d090', '127.0.0.1', '2015-06-23 09:58:16', '2015-06-22 15:16:52', '2015-06-23 09:58:16');

-- ----------------------------
-- Table structure for blood_group_rh
-- ----------------------------
DROP TABLE IF EXISTS `blood_group_rh`;
CREATE TABLE `blood_group_rh` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blood_group_rh
-- ----------------------------
INSERT INTO `blood_group_rh` VALUES ('1', 'Nhom mau Rh+', '2015-06-10 12:04:28', null);
INSERT INTO `blood_group_rh` VALUES ('2', 'Nhom mau Rh-', '2015-06-17 12:04:46', null);

-- ----------------------------
-- Table structure for blood_groups
-- ----------------------------
DROP TABLE IF EXISTS `blood_groups`;
CREATE TABLE `blood_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blood_groups
-- ----------------------------
INSERT INTO `blood_groups` VALUES ('1', 'Nhom mau A', '2015-06-15 11:58:53', null);
INSERT INTO `blood_groups` VALUES ('2', 'Nhom mau B', '2015-06-16 11:59:04', null);
INSERT INTO `blood_groups` VALUES ('3', 'Nhom mau O', '2015-06-24 11:59:46', null);

-- ----------------------------
-- Table structure for files
-- ----------------------------
DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `raw_name` varchar(255) DEFAULT NULL,
  `orig_name` varchar(255) DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `file_ext` varchar(255) DEFAULT NULL,
  `file_size` float DEFAULT NULL,
  `image_width` int(11) DEFAULT NULL,
  `image_height` int(11) DEFAULT NULL,
  `image_type` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of files
-- ----------------------------
INSERT INTO `files` VALUES ('16', 'icon-user-default.png', 'image/png', 'icon-user-default', 'icon-user-default.png', 'icon-user-default.png', '.png', '3.17', '462', '462', 'png', 'uploads/accounts/', '2015-06-23 09:47:08', null);
INSERT INTO `files` VALUES ('17', '10277712_557371384377750_5686690832517885206_n.jpg', 'image/jpeg', '10277712_557371384377750_5686690832517885206_n', '10277712_557371384377750_5686690832517885206_n.jpg', '10277712_557371384377750_5686690832517885206_n.jpg', '.jpg', '32.82', '960', '640', 'jpeg', 'uploads/accounts/', '2015-06-23 09:54:21', null);
INSERT INTO `files` VALUES ('18', '10277712_557371384377750_5686690832517885206_n.jpg', 'image/jpeg', '10277712_557371384377750_5686690832517885206_n', '10277712_557371384377750_5686690832517885206_n.jpg', '10277712_557371384377750_5686690832517885206_n.jpg', '.jpg', '32.82', '960', '640', 'jpeg', 'uploads/accounts/', '2015-06-23 09:57:37', null);
INSERT INTO `files` VALUES ('19', '10277712_557371384377750_5686690832517885206_n.jpg', 'image/jpeg', '10277712_557371384377750_5686690832517885206_n', '10277712_557371384377750_5686690832517885206_n.jpg', '10277712_557371384377750_5686690832517885206_n.jpg', '.jpg', '32.82', '960', '640', 'jpeg', 'uploads/accounts/', '2015-06-23 09:58:16', null);
