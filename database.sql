/*
Navicat MySQL Data Transfer

Source Server         : Local
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : testapp

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2018-10-08 12:02:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for author
-- ----------------------------
DROP TABLE IF EXISTS `author`;
CREATE TABLE `author` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of author
-- ----------------------------
INSERT INTO `author` VALUES ('1', 'Mike');
INSERT INTO `author` VALUES ('2', 'Jason');
INSERT INTO `author` VALUES ('3', 'Stella');
INSERT INTO `author` VALUES ('4', 'Beth');
INSERT INTO `author` VALUES ('5', 'Alex');
INSERT INTO `author` VALUES ('6', 'Joey');
INSERT INTO `author` VALUES ('7', 'Joe');
INSERT INTO `author` VALUES ('8', 'Berth');
INSERT INTO `author` VALUES ('9', 'Kate');
INSERT INTO `author` VALUES ('10', 'Donald');
INSERT INTO `author` VALUES ('11', 'Jane');
INSERT INTO `author` VALUES ('12', 'Jannet');
INSERT INTO `author` VALUES ('13', 'Latifa');
INSERT INTO `author` VALUES ('14', 'Andrew');
INSERT INTO `author` VALUES ('15', 'Amy');
INSERT INTO `author` VALUES ('16', 'Stephanie');
INSERT INTO `author` VALUES ('17', 'Andy');
INSERT INTO `author` VALUES ('25', 'New Author');
INSERT INTO `author` VALUES ('26', 'New Author');
INSERT INTO `author` VALUES ('27', 'New Author');
INSERT INTO `author` VALUES ('28', 'New Author');
INSERT INTO `author` VALUES ('29', 'New Author');
INSERT INTO `author` VALUES ('30', 'New Author');
INSERT INTO `author` VALUES ('31', 'New Author');
INSERT INTO `author` VALUES ('32', 'New Author');
INSERT INTO `author` VALUES ('33', 'New Author');
INSERT INTO `author` VALUES ('34', 'New Author');
INSERT INTO `author` VALUES ('35', 'New Author');

-- ----------------------------
-- Table structure for book
-- ----------------------------
DROP TABLE IF EXISTS `book`;
CREATE TABLE `book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `pub_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of book
-- ----------------------------
INSERT INTO `book` VALUES ('1', 'First Book', null, null);
INSERT INTO `book` VALUES ('2', 'Second Book', null, null);
INSERT INTO `book` VALUES ('3', 'Third Book', null, null);
INSERT INTO `book` VALUES ('4', 'Fourth Book', null, null);
INSERT INTO `book` VALUES ('5', 'Fifth Book', null, null);
INSERT INTO `book` VALUES ('6', 'Sixth Book', null, null);
INSERT INTO `book` VALUES ('7', 'Seventh Book', null, null);
INSERT INTO `book` VALUES ('8', 'Book number 8', null, null);
INSERT INTO `book` VALUES ('9', 'Book Nine', null, null);
INSERT INTO `book` VALUES ('10', 'Tenth Book', null, null);
INSERT INTO `book` VALUES ('11', 'Book Eleven', null, null);
INSERT INTO `book` VALUES ('12', 'Book Twelve', null, null);
INSERT INTO `book` VALUES ('13', 'Thirtheenth Book', null, null);
INSERT INTO `book` VALUES ('14', 'Book No. Fourteen', null, null);
INSERT INTO `book` VALUES ('15', 'Book Fifteen', null, null);

-- ----------------------------
-- Table structure for book_author
-- ----------------------------
DROP TABLE IF EXISTS `book_author`;
CREATE TABLE `book_author` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(10) unsigned DEFAULT NULL,
  `author_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `book_id` (`book_id`,`author_id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of book_author
-- ----------------------------
INSERT INTO `book_author` VALUES ('46', '7', '3');
INSERT INTO `book_author` VALUES ('45', '6', '12');
INSERT INTO `book_author` VALUES ('44', '1', '17');
INSERT INTO `book_author` VALUES ('9', '2', '1');
INSERT INTO `book_author` VALUES ('10', '2', '8');
INSERT INTO `book_author` VALUES ('43', '3', '1');
INSERT INTO `book_author` VALUES ('13', '4', '1');
INSERT INTO `book_author` VALUES ('14', '4', '10');
INSERT INTO `book_author` VALUES ('15', '5', '1');
INSERT INTO `book_author` VALUES ('16', '5', '11');
INSERT INTO `book_author` VALUES ('47', '8', '5');
INSERT INTO `book_author` VALUES ('48', '9', '7');
INSERT INTO `book_author` VALUES ('49', '10', '8');
