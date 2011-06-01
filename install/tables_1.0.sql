
USE `{DBNAME}`;

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `{DBPREFIX}searchrecord`;
DROP TABLE IF EXISTS `{DBPREFIX}comment`;
DROP TABLE IF EXISTS `{DBPREFIX}change_watch`;
DROP TABLE IF EXISTS `{DBPREFIX}change`;
DROP TABLE IF EXISTS `{DBPREFIX}user_role`;
DROP TABLE IF EXISTS `{DBPREFIX}role`;
DROP TABLE IF EXISTS `{DBPREFIX}user_subscription`;
DROP TABLE IF EXISTS `{DBPREFIX}subscription`;
DROP TABLE IF EXISTS `{DBPREFIX}rank`;
DROP TABLE IF EXISTS `{DBPREFIX}user`;
DROP TABLE IF EXISTS `{DBPREFIX}definition_tag`;
DROP TABLE IF EXISTS `{DBPREFIX}relationship`;
DROP TABLE IF EXISTS `{DBPREFIX}tag`;
DROP TABLE IF EXISTS `{DBPREFIX}language`;
DROP TABLE IF EXISTS `{DBPREFIX}example`;
DROP TABLE IF EXISTS `{DBPREFIX}definition_nounclass`;
DROP TABLE IF EXISTS `{DBPREFIX}definition`;
DROP TABLE IF EXISTS `{DBPREFIX}entry`;

SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE `{DBPREFIX}entry` (
  `entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`entry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}definition` (
  `definition_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_id` int(10) unsigned NOT NULL,
  `revision` int(10) unsigned NOT NULL,
  `revisionstatus` int(2) unsigned NOT NULL,
  `change_id` int(10) unsigned DEFAULT NULL,
  `wordclass` varchar(5) DEFAULT NULL,
  `prefix` varchar(10) DEFAULT NULL,
  `lemma` varchar(255) NOT NULL,
  `modifier` varchar(50) DEFAULT NULL,
  `meaning` varchar(255) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `flags` int(10) NOT NULL,
  `unverified` tinyint(1) NOT NULL,
  PRIMARY KEY (`definition_id`),
  UNIQUE KEY `UQ_{DBPREFIX}definition_change` (`change_id`),
  KEY `IN_{DBPREFIX}definition_lemma` (`lemma`),
  KEY `FK_{DBPREFIX}definition_entry` (`entry_id`),
  KEY `FK_{DBPREFIX}definition_change` (`change_id`),
  CONSTRAINT `FK_{DBPREFIX}definition_entry` FOREIGN KEY (`entry_id`) REFERENCES `{DBPREFIX}entry` (`entry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}definition_nounclass` (
  `definition_id` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `nounclass` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`definition_id`,`nounclass`),
  KEY `FK_{DBPREFIX}definition_nounclass_definition` (`definition_id`),
  CONSTRAINT `FK_{DBPREFIX}definition_nounclass_definition` FOREIGN KEY (`definition_id`) REFERENCES `{DBPREFIX}definition` (`definition_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}example` (
  `example_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `definition_id` int(10) unsigned NOT NULL,
  `form` varchar(255) NOT NULL,
  `meaning` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`example_id`),
  KEY `FK_{DBPREFIX}example_definition` (`definition_id`),
  CONSTRAINT `FK_{DBPREFIX}example_definition` FOREIGN KEY (`definition_id`) REFERENCES `{DBPREFIX}definition` (`definition_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}language` (
  `language_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  `localname` varchar(50) NOT NULL,
  `queryurl` varchar(255) DEFAULT NULL,
  `hastranslation` tinyint(1) NOT NULL,
  `haslexical` tinyint(1) NOT NULL,
  PRIMARY KEY (`language_id`),
  UNIQUE KEY `UQ_{DBPREFIX}language_code` (`code`),
  KEY `IN_{DBPREFIX}tag_text` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}tag` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang` char(2) NOT NULL,
  `text` varchar(50) NOT NULL,
  `stem` varchar(50) DEFAULT NULL,
  `sound` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`tag_id`),
  KEY `IN_{DBPREFIX}tag_text` (`text`),
  KEY `IN_{DBPREFIX}tag_stem` (`stem`),
  KEY `IN_{DBPREFIX}tag_sound` (`sound`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}relationship` (
  `relationship_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `system` tinyint(1) NOT NULL,
  `matchdefault` tinyint(1) NOT NULL,
  `defaultlang` char(2) NOT NULL,
  PRIMARY KEY (`relationship_id`),
  KEY `IN_{DBPREFIX}relationship_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}definition_tag` (
  `definition_id` int(10) unsigned NOT NULL,
  `relationship_id` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `weight` int(10) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL,
  KEY `FK_{DBPREFIX}tag_definition_definition` (`definition_id`),
  KEY `FK_{DBPREFIX}tag_definition_tag` (`tag_id`),
  KEY `FK_{DBPREFIX}tag_definition_relationship` (`relationship_id`),
  CONSTRAINT `FK_{DBPREFIX}tag_definition_definition` FOREIGN KEY (`definition_id`) REFERENCES `{DBPREFIX}definition` (`definition_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_{DBPREFIX}tag_definition_relationship` FOREIGN KEY (`relationship_id`) REFERENCES `{DBPREFIX}relationship` (`relationship_id`),
  CONSTRAINT `FK_{DBPREFIX}tag_definition_tag` FOREIGN KEY (`tag_id`) REFERENCES `{DBPREFIX}tag` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `timezone` varchar(50) DEFAULT NULL,
  `lastlogin` timestamp NULL DEFAULT NULL,
  `voided` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `UQ_{DBPREFIX}user_login` (`login`),
  UNIQUE KEY `UQ_{DBPREFIX}user_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}rank` (
  `rank_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `threshold` int(10) unsigned NOT NULL,
  PRIMARY KEY (`rank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}subscription` (
  `subscription_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`subscription_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}user_subscription` (
  `user_id` int(10) unsigned NOT NULL,
  `subscription_id` int(10) unsigned NOT NULL,
  KEY `FK_{DBPREFIX}user_subscription_user` (`user_id`),
  KEY `FK_{DBPREFIX}user_subscription_subscription` (`subscription_id`),
  CONSTRAINT `FK_{DBPREFIX}user_subscription_subscription` FOREIGN KEY (`subscription_id`) REFERENCES `{DBPREFIX}subscription` (`subscription_id`),
  CONSTRAINT `FK_{DBPREFIX}user_subscription_user` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}role` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}user_role` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  KEY `FK_{DBPREFIX}user_role_user` (`user_id`),
  KEY `FK_{DBPREFIX}user_role_role` (`role_id`),
  CONSTRAINT `FK_{DBPREFIX}user_role_role` FOREIGN KEY (`role_id`) REFERENCES `{DBPREFIX}role` (`role_id`),
  CONSTRAINT `FK_{DBPREFIX}user_role_user` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}change` (
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
  KEY `FK_{DBPREFIX}change_entry` (`entry_id`),
  CONSTRAINT `FK_{DBPREFIX}change_entry` FOREIGN KEY (`entry_id`) REFERENCES `{DBPREFIX}entry` (`entry_id`),
  CONSTRAINT `FK_{DBPREFIX}change_resolver` FOREIGN KEY (`resolver_id`) REFERENCES `{DBPREFIX}user` (`user_id`),
  CONSTRAINT `FK_{DBPREFIX}change_submitter` FOREIGN KEY (`submitter_id`) REFERENCES `{DBPREFIX}user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}change_watch` (
  `change_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`change_id`,`user_id`),
  KEY `FK_{DBPREFIX}change_watch_change` (`change_id`),
  KEY `FK_{DBPREFIX}change_watch_user` (`user_id`),
  CONSTRAINT `FK_{DBPREFIX}change_watch_change` FOREIGN KEY (`change_id`) REFERENCES `{DBPREFIX}change` (`change_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_{DBPREFIX}change_watch_user` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}comment` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `change_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approval` tinyint(1) NOT NULL,
  `text` text,
  `voided` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `FK_{DBPREFIX}comment_change` (`change_id`),
  KEY `FK_{DBPREFIX}comment_user` (`user_id`),
  CONSTRAINT `FK_{DBPREFIX}comment_change` FOREIGN KEY (`change_id`) REFERENCES `{DBPREFIX}change` (`change_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_{DBPREFIX}comment_user` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}searchrecord` (
  `search_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `query` varchar(100) NOT NULL,
  `suggest` varchar(100) DEFAULT NULL,
  `iterations` int(11) NOT NULL,
  `results` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timetaken` int(11) NOT NULL,
  `remoteaddr` varchar(50) DEFAULT NULL,
  `source` varchar(10) DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`search_id`),
  KEY `FK_{DBPREFIX}searchrecord_user` (`user_id`),
  CONSTRAINT `FK_{DBPREFIX}searchrecord_user` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `{DBPREFIX}definition`
  ADD CONSTRAINT `FK_{DBPREFIX}definition_change` FOREIGN KEY (`change_id`) REFERENCES `{DBPREFIX}change` (`change_id`);
