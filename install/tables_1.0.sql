
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
  `accepted_id` int(10) unsigned DEFAULT NULL,
  `proposed_id` int(10) unsigned DEFAULT NULL,
  `delete_change_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`entry_id`),
  KEY `FK_{DBPREFIX}entry_accepted` (`accepted_id`),
  KEY `FK_{DBPREFIX}entry_proposed` (`proposed_id`),
  KEY `FK_{DBPREFIX}entry_delete_change` (`delete_change_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}definition` (
  `definition_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_id` int(10) unsigned NOT NULL,
  `revision` int(10) unsigned NOT NULL,
  `change_id` int(10) unsigned DEFAULT NULL,
  `wordclass` varchar(5) DEFAULT NULL,
  `prefix` varchar(10) DEFAULT NULL,
  `lemma` varchar(255) NOT NULL,
  `modifier` varchar(50) DEFAULT NULL,
  `meaning` varchar(255) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `flags` int(10) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`definition_id`),
  UNIQUE KEY `UQ_{DBPREFIX}definition_change` (`change_id`),
  KEY `IN_{DBPREFIX}definition_lemma` (`lemma`),
  KEY `FK_{DBPREFIX}definition_entry` (`entry_id`),
  KEY `FK_{DBPREFIX}definition_change` (`change_id`),
  CONSTRAINT `FK_{DBPREFIX}definition_entry` FOREIGN KEY (`entry_id`) REFERENCES `{DBPREFIX}entry` (`entry_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}definition_nounclass` (
  `definition_id` INT UNSIGNED NOT NULL,
  `order` INT UNSIGNED NOT NULL,
  `nounclass` TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (`definition_id`, `nounclass`),
  KEY `FK_{DBPREFIX}definition_nounclass_definition` (`definition_id`),
  CONSTRAINT `FK_{DBPREFIX}definition_nounclass_definition` FOREIGN KEY (`definition_id`) REFERENCES `{DBPREFIX}definition` (`definition_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}example` (
  `example_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `definition_id` INT UNSIGNED NOT NULL,
  `form` VARCHAR(255) NOT NULL,
  `meaning` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`example_id`),
  KEY `FK_{DBPREFIX}example_definition` (`definition_id`),
  CONSTRAINT `FK_{DBPREFIX}example_definition` FOREIGN KEY (`definition_id`) REFERENCES `{DBPREFIX}definition` (`definition_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}language` (
  `language_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` CHAR(2) NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `localname` VARCHAR(50) NOT NULL,
  `queryurl` VARCHAR(255),
  `hastranslation` TINYINT(1) NOT NULL,
  `haslexical` TINYINT(1) NOT NULL,
  PRIMARY KEY (`language_id`),
  KEY `IN_{DBPREFIX}tag_text` (`code`),
  UNIQUE KEY `UQ_{DBPREFIX}change_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}tag` (
  `tag_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lang` CHAR(2) NOT NULL,
  `text` VARCHAR(50) NOT NULL,
  `stem` VARCHAR(50),
  `sound` VARCHAR(50),
  PRIMARY KEY (`tag_id`),
  KEY `IN_{DBPREFIX}tag_text` (`text`),
  KEY `IN_{DBPREFIX}tag_stem` (`stem`),
  KEY `IN_{DBPREFIX}tag_sound` (`sound`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}relationship` (
  `relationship_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `title` VARCHAR(50) NOT NULL,
  `description` VARCHAR(255),
  `system` TINYINT(1) NOT NULL,
  `matchdefault` TINYINT(1) NOT NULL,
  `defaultlang` CHAR(2) NOT NULL,
  PRIMARY KEY (`relationship_id`),
  KEY `IN_{DBPREFIX}relationship_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}definition_tag` (
  `definition_id` INT UNSIGNED NOT NULL,
  `relationship_id` INT UNSIGNED NOT NULL,
  `order` INT UNSIGNED NOT NULL,
  `tag_id` INT UNSIGNED NOT NULL,
  `weight` INT UNSIGNED NOT NULL,
  `active` TINYINT(1) NOT NULL,		#TBR
  KEY `FK_{DBPREFIX}tag_definition_definition` (`definition_id`),
  KEY `FK_{DBPREFIX}tag_definition_tag` (`tag_id`),
  KEY `FK_{DBPREFIX}tag_definition_relationship` (`relationship_id`),
  CONSTRAINT `FK_{DBPREFIX}tag_definition_definition` FOREIGN KEY (`definition_id`) REFERENCES `{DBPREFIX}definition` (`definition_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_{DBPREFIX}tag_definition_tag` FOREIGN KEY (`tag_id`) REFERENCES `{DBPREFIX}tag` (`tag_id`),
  CONSTRAINT `FK_{DBPREFIX}tag_definition_relationship` FOREIGN KEY (`relationship_id`) REFERENCES `{DBPREFIX}relationship` (`relationship_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}user` (
  `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(50) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `salt` VARCHAR(100) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `timezone` VARCHAR(50) DEFAULT NULL,
  `lastlogin` TIMESTAMP NULL DEFAULT NULL,
  `voided` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}rank` (
  `rank_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `threshold` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`rank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}subscription` (
  `subscription_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`subscription_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}user_subscription` (
  `user_id` INT UNSIGNED NOT NULL,
  `subscription_id` INT UNSIGNED NOT NULL,
  KEY `FK_{DBPREFIX}user_subscription_user` (`user_id`),
  KEY `FK_{DBPREFIX}user_subscription_subscription` (`subscription_id`),
  CONSTRAINT `FK_{DBPREFIX}user_subscription_user` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`),
  CONSTRAINT `FK_{DBPREFIX}user_subscription_subscription` FOREIGN KEY (`subscription_id`) REFERENCES `{DBPREFIX}subscription` (`subscription_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}role` (
  `role_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}user_role` (
  `user_id` INT UNSIGNED NOT NULL,
  `role_id` INT UNSIGNED NOT NULL,
  KEY `FK_{DBPREFIX}user_role_user` (`user_id`),
  KEY `FK_{DBPREFIX}user_role_role` (`role_id`),
  CONSTRAINT `FK_{DBPREFIX}user_role_user` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`),
  CONSTRAINT `FK_{DBPREFIX}user_role_role` FOREIGN KEY (`role_id`) REFERENCES `{DBPREFIX}role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}change` (
  `change_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `action` TINYINT UNSIGNED NOT NULL, 
  `submitter_id` INT UNSIGNED NOT NULL,
  `submitted` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` TINYINT UNSIGNED NOT NULL,
  `resolver_id` INT UNSIGNED DEFAULT NULL,
  `resolved` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`change_id`),
  KEY `FK_{DBPREFIX}change_submitter` (`submitter_id`),
  KEY `FK_{DBPREFIX}change_resolver` (`resolver_id`),
  CONSTRAINT `FK_{DBPREFIX}change_submitter` FOREIGN KEY (`submitter_id`) REFERENCES `{DBPREFIX}user` (`user_id`),
  CONSTRAINT `FK_{DBPREFIX}change_resolver` FOREIGN KEY (`resolver_id`) REFERENCES `{DBPREFIX}user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}change_watch` (
  `change_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`change_id`, `user_id`),
  KEY `FK_{DBPREFIX}change_watch_change` (`change_id`),
  KEY `FK_{DBPREFIX}change_watch_user` (`user_id`),
  CONSTRAINT `FK_{DBPREFIX}change_watch_change` FOREIGN KEY (`change_id`) REFERENCES `{DBPREFIX}change` (`change_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_{DBPREFIX}change_watch_user` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{DBPREFIX}comment` (
  `comment_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `change_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approval` TINYINT(1) NOT NULL,
  `text` TEXT DEFAULT NULL,
  `voided` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`comment_id`),
  KEY `FK_{DBPREFIX}comment_change` (`change_id`),
  KEY `FK_{DBPREFIX}comment_user` (`user_id`),
  CONSTRAINT `FK_{DBPREFIX}comment_change` FOREIGN KEY (`change_id`) REFERENCES `{DBPREFIX}change` (`change_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_{DBPREFIX}comment_user` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE  `{DBPREFIX}searchrecord` (
  `search_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `query` VARCHAR(100) NOT NULL,
  `suggest` VARCHAR(100) DEFAULT NULL,
  `iterations` INT NOT NULL,
  `results` INT NOT NULL,
  `timestamp` TIMESTAMP NOT NULL,
  `timetaken` INT NOT NULL,
  `remoteaddr` VARCHAR(50) DEFAULT NULL,
  `source` VARCHAR(10) DEFAULT NULL,
  `user_id` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`search_id`),
  KEY `FK_{DBPREFIX}searchrecord_user` (`user_id`),
  CONSTRAINT `FK_{DBPREFIX}searchrecord_user` FOREIGN KEY (`user_id`) REFERENCES `{DBPREFIX}user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `{DBPREFIX}entry`
  ADD CONSTRAINT `FK_{DBPREFIX}entry_accepted` FOREIGN KEY (`accepted_id`) REFERENCES `{DBPREFIX}definition` (`definition_id`),
  ADD CONSTRAINT `FK_{DBPREFIX}entry_proposed` FOREIGN KEY (`proposed_id`) REFERENCES `{DBPREFIX}definition` (`definition_id`),
  ADD CONSTRAINT `FK_{DBPREFIX}entry_delete_change` FOREIGN KEY (`delete_change_id`) REFERENCES `{DBPREFIX}change` (`change_id`);
  
ALTER TABLE `{DBPREFIX}definition`
  ADD CONSTRAINT `FK_{DBPREFIX}definition_change` FOREIGN KEY (`change_id`) REFERENCES `{DBPREFIX}change` (`change_id`);
