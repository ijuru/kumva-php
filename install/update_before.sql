# Part 1

CREATE TABLE `rw_entry` (
  `entry_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `accepted_revision` INT UNSIGNED DEFAULT NULL,
  `proposed_revision` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`entry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `rw_definition` 
ADD COLUMN `entry_id` INT(10) UNSIGNED NULL AFTER `definition_id`, 
ADD COLUMN `revision` INT(10) UNSIGNED NULL AFTER `entry_id`;

ALTER TABLE `rw_definition` 
ADD CONSTRAINT `FK_definition_entry` FOREIGN KEY (`entry_id` ) REFERENCES `rw_entry` (`entry_id` ),
ADD INDEX `FK_definition_entry` (`entry_id` ASC);

ALTER TABLE `rw_change` 
ADD COLUMN `entry_id` INT(10) UNSIGNED NULL AFTER `change_id`;

ALTER TABLE `rw_change` 
ADD CONSTRAINT `FK_change_entry` FOREIGN KEY (`entry_id` ) REFERENCES `rw_entry` (`entry_id` ),
ADD INDEX `FK_change_entry` (`entry_id` ASC);


