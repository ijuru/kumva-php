USE `{DBNAME}`;

-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 21, 2011 at 05:34 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kumva`
--

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}change`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}change` (
  `change_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_id` int(10) unsigned NOT NULL,
  `action` tinyint(3) unsigned NOT NULL,
  `submitter_id` int(10) unsigned NOT NULL,
  `submitted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) unsigned NOT NULL,
  `resolver_id` int(10) unsigned DEFAULT NULL,
  `resolved` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`change_id`),
  KEY `FK_{DBPREFIX}change_submitter` (`submitter_id`),
  KEY `FK_{DBPREFIX}change_resolver` (`resolver_id`),
  KEY `FK_{DBPREFIX}change_entry` (`entry_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}change_watch`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}change_watch` (
  `change_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`change_id`,`user_id`),
  KEY `FK_{DBPREFIX}change_watch_change` (`change_id`),
  KEY `FK_{DBPREFIX}change_watch_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}comment`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}comment` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `change_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approval` tinyint(1) NOT NULL,
  `text` text CHARACTER SET utf8,
  `voided` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `FK_{DBPREFIX}comment_change` (`change_id`),
  KEY `FK_{DBPREFIX}comment_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}entry`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}entry` (
  `entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media` int(10) unsigned NOT NULL,
  PRIMARY KEY (`entry_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}example`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}example` (
  `example_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `revision_id` int(10) unsigned NOT NULL,
  `form` varchar(255) CHARACTER SET utf8 NOT NULL,
  `meaning` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`example_id`),
  KEY `FK_{DBPREFIX}example_definition` (`revision_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}language`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}language` (
  `language_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(2) CHARACTER SET utf8 NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `localname` varchar(50) CHARACTER SET utf8 NOT NULL,
  `queryurl` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `hastranslation` tinyint(1) NOT NULL,
  `haslexical` tinyint(1) NOT NULL,
  PRIMARY KEY (`language_id`),
  UNIQUE KEY `UQ_{DBPREFIX}language_code` (`code`),
  KEY `IN_{DBPREFIX}tag_text` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}meaning`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}meaning` (
  `meaning_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `revision_id` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `meaning` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `flags` int(10) NOT NULL,
  PRIMARY KEY (`meaning_id`),
  KEY `FK_{DBPREFIX}meaning` (`revision_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}rank`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}rank` (
  `rank_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `threshold` int(10) unsigned NOT NULL,
  PRIMARY KEY (`rank_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}relationship`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}relationship` (
  `relationship_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `title` varchar(50) CHARACTER SET utf8 NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `system` tinyint(1) NOT NULL,
  `matchdefault` tinyint(1) NOT NULL,
  `defaultlang` char(2) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`relationship_id`),
  KEY `IN_{DBPREFIX}relationship_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}revision`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}revision` (
  `revision_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_id` int(10) unsigned NOT NULL,
  `number` int(10) unsigned NOT NULL,
  `status` int(2) unsigned NOT NULL,
  `change_id` int(10) unsigned DEFAULT NULL,
  `wordclass` varchar(5) CHARACTER SET utf8 DEFAULT NULL,
  `prefix` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `lemma` varchar(255) CHARACTER SET utf8 NOT NULL,
  `modifier` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `pronunciation` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `comment` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `unverified` tinyint(1) NOT NULL,
  PRIMARY KEY (`revision_id`),
  UNIQUE KEY `UQ_{DBPREFIX}definition_change` (`change_id`),
  KEY `IN_{DBPREFIX}definition_lemma` (`lemma`),
  KEY `FK_{DBPREFIX}definition_entry` (`entry_id`),
  KEY `FK_{DBPREFIX}definition_change` (`change_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}revision_nounclass`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}revision_nounclass` (
  `revision_id` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `nounclass` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`revision_id`,`nounclass`),
  KEY `FK_{DBPREFIX}definition_nounclass_definition` (`revision_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}revision_tag`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}revision_tag` (
  `revision_id` int(10) unsigned NOT NULL,
  `relationship_id` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `weight` int(10) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL,
  KEY `FK_{DBPREFIX}tag_definition_definition` (`revision_id`),
  KEY `FK_{DBPREFIX}tag_definition_tag` (`tag_id`),
  KEY `FK_{DBPREFIX}tag_definition_relationship` (`relationship_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}role`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}role` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}searchrecord`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}searchrecord` (
  `search_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `query` varchar(100) CHARACTER SET utf8 NOT NULL,
  `suggest` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `iterations` int(11) NOT NULL,
  `results` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `timetaken` int(11) NOT NULL,
  `remoteaddr` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `source` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`search_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}subscription`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}subscription` (
  `subscription_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`subscription_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}tag`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}tag` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang` char(2) CHARACTER SET utf8 NOT NULL,
  `text` varchar(50) CHARACTER SET utf8 NOT NULL,
  `stem` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `sound` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`tag_id`),
  KEY `IN_{DBPREFIX}tag_text` (`text`),
  KEY `IN_{DBPREFIX}tag_stem` (`stem`),
  KEY `IN_{DBPREFIX}tag_sound` (`sound`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}user`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(50) CHARACTER SET utf8 NOT NULL,
  `password` varchar(100) CHARACTER SET utf8 NOT NULL,
  `salt` varchar(100) CHARACTER SET utf8 NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `website` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `timezone` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `lastlogin` timestamp NULL DEFAULT NULL,
  `lastloginattempt` timestamp NULL DEFAULT NULL,
  `failedloginattempts` int(11) NOT NULL DEFAULT '0',
  `remembertoken` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `voided` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `UQ_{DBPREFIX}user_login` (`login`),
  UNIQUE KEY `UQ_{DBPREFIX}user_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}user_role`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}user_role` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  KEY `FK_{DBPREFIX}user_role_user` (`user_id`),
  KEY `FK_{DBPREFIX}user_role_role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `{DBPREFIX}user_subscription`
--

CREATE TABLE IF NOT EXISTS `{DBPREFIX}user_subscription` (
  `user_id` int(10) unsigned NOT NULL,
  `subscription_id` int(10) unsigned NOT NULL,
  KEY `FK_{DBPREFIX}user_subscription_user` (`user_id`),
  KEY `FK_{DBPREFIX}user_subscription_subscription` (`subscription_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `{DBPREFIX}change`
--
ALTER TABLE `{DBPREFIX}change`
  ADD CONSTRAINT `FK_{DBPREFIX}change_entry` FOREIGN KEY (`entry_id`) REFERENCES `{DBPREFIX}entry` (`entry_id`),
  ADD CONSTRAINT `FK_{DBPREFIX}change_resolver` FOREIGN KEY (`resolver_id`) REFERENCES `{DBPREFIX}user` (`user_id`),
  ADD CONSTRAINT `FK_{DBPREFIX}change_submitter` FOREIGN KEY (`submitter_id`) REFERENCES `{DBPREFIX}user` (`user_id`),
  ADD CONSTRAINT `{DBPREFIX}change_ibfk_1` FOREIGN KEY (`entry_id`) REFERENCES `{DBPREFIX}entry` (`entry_id`),
  ADD CONSTRAINT `{DBPREFIX}change_ibfk_2` FOREIGN KEY (`resolver_id`) REFERENCES `{DBPREFIX}user` (`user_id`),
  ADD CONSTRAINT `{DBPREFIX}change_ibfk_3` FOREIGN KEY (`submitter_id`) REFERENCES `{DBPREFIX}user` (`user_id`);

--
-- Constraints for table `{DBPREFIX}change_watch`
--
ALTER TABLE `{DBPREFIX}change_watch`
  ADD CONSTRAINT `FK_{DBPREFIX}change_watch_change` FOREIGN KEY (`change_id`) REFERENCES `{DBPREFIX}change` (`change_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_{DBPREFIX}change_watch_user` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`),
  ADD CONSTRAINT `{DBPREFIX}change_watch_ibfk_1` FOREIGN KEY (`change_id`) REFERENCES `{DBPREFIX}change` (`change_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `{DBPREFIX}change_watch_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`);

--
-- Constraints for table `{DBPREFIX}comment`
--
ALTER TABLE `{DBPREFIX}comment`
  ADD CONSTRAINT `FK_{DBPREFIX}comment_change` FOREIGN KEY (`change_id`) REFERENCES `{DBPREFIX}change` (`change_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_{DBPREFIX}comment_user` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`),
  ADD CONSTRAINT `{DBPREFIX}comment_ibfk_1` FOREIGN KEY (`change_id`) REFERENCES `{DBPREFIX}change` (`change_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `{DBPREFIX}comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`);

--
-- Constraints for table `{DBPREFIX}example`
--
ALTER TABLE `{DBPREFIX}example`
  ADD CONSTRAINT `FK_{DBPREFIX}example_definition` FOREIGN KEY (`revision_id`) REFERENCES `{DBPREFIX}revision` (`revision_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `{DBPREFIX}example_ibfk_1` FOREIGN KEY (`revision_id`) REFERENCES `{DBPREFIX}revision` (`revision_id`) ON DELETE CASCADE;

--
-- Constraints for table `{DBPREFIX}meaning`
--
ALTER TABLE `{DBPREFIX}meaning`
  ADD CONSTRAINT `FK_{DBPREFIX}meaning` FOREIGN KEY (`revision_id`) REFERENCES `{DBPREFIX}revision` (`revision_id`) ON DELETE CASCADE;

--
-- Constraints for table `{DBPREFIX}revision`
--
ALTER TABLE `{DBPREFIX}revision`
  ADD CONSTRAINT `FK_{DBPREFIX}definition_change` FOREIGN KEY (`change_id`) REFERENCES `{DBPREFIX}change` (`change_id`),
  ADD CONSTRAINT `FK_{DBPREFIX}definition_entry` FOREIGN KEY (`entry_id`) REFERENCES `{DBPREFIX}entry` (`entry_id`),
  ADD CONSTRAINT `{DBPREFIX}revision_ibfk_1` FOREIGN KEY (`change_id`) REFERENCES `{DBPREFIX}change` (`change_id`),
  ADD CONSTRAINT `{DBPREFIX}revision_ibfk_2` FOREIGN KEY (`entry_id`) REFERENCES `{DBPREFIX}entry` (`entry_id`);

--
-- Constraints for table `{DBPREFIX}revision_nounclass`
--
ALTER TABLE `{DBPREFIX}revision_nounclass`
  ADD CONSTRAINT `FK_{DBPREFIX}definition_nounclass_definition` FOREIGN KEY (`revision_id`) REFERENCES `{DBPREFIX}revision` (`revision_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `{DBPREFIX}revision_nounclass_ibfk_1` FOREIGN KEY (`revision_id`) REFERENCES `{DBPREFIX}revision` (`revision_id`) ON DELETE CASCADE;

--
-- Constraints for table `{DBPREFIX}revision_tag`
--
ALTER TABLE `{DBPREFIX}revision_tag`
  ADD CONSTRAINT `FK_{DBPREFIX}tag_definition_definition` FOREIGN KEY (`revision_id`) REFERENCES `{DBPREFIX}revision` (`revision_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_{DBPREFIX}tag_definition_relationship` FOREIGN KEY (`relationship_id`) REFERENCES `{DBPREFIX}relationship` (`relationship_id`),
  ADD CONSTRAINT `FK_{DBPREFIX}tag_definition_tag` FOREIGN KEY (`tag_id`) REFERENCES `{DBPREFIX}tag` (`tag_id`),
  ADD CONSTRAINT `{DBPREFIX}revision_tag_ibfk_1` FOREIGN KEY (`revision_id`) REFERENCES `{DBPREFIX}revision` (`revision_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `{DBPREFIX}revision_tag_ibfk_2` FOREIGN KEY (`relationship_id`) REFERENCES `{DBPREFIX}relationship` (`relationship_id`),
  ADD CONSTRAINT `{DBPREFIX}revision_tag_ibfk_3` FOREIGN KEY (`tag_id`) REFERENCES `{DBPREFIX}tag` (`tag_id`);

--
-- Constraints for table `{DBPREFIX}user_role`
--
ALTER TABLE `{DBPREFIX}user_role`
  ADD CONSTRAINT `FK_{DBPREFIX}user_role_role` FOREIGN KEY (`role_id`) REFERENCES `{DBPREFIX}role` (`role_id`),
  ADD CONSTRAINT `FK_{DBPREFIX}user_role_user` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`),
  ADD CONSTRAINT `{DBPREFIX}user_role_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `{DBPREFIX}role` (`role_id`),
  ADD CONSTRAINT `{DBPREFIX}user_role_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`);

--
-- Constraints for table `{DBPREFIX}user_subscription`
--
ALTER TABLE `{DBPREFIX}user_subscription`
  ADD CONSTRAINT `FK_{DBPREFIX}user_subscription_subscription` FOREIGN KEY (`subscription_id`) REFERENCES `{DBPREFIX}subscription` (`subscription_id`),
  ADD CONSTRAINT `FK_{DBPREFIX}user_subscription_user` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`),
  ADD CONSTRAINT `{DBPREFIX}user_subscription_ibfk_1` FOREIGN KEY (`subscription_id`) REFERENCES `{DBPREFIX}subscription` (`subscription_id`),
  ADD CONSTRAINT `{DBPREFIX}user_subscription_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`);
