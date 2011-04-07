# Part 1

CREATE TABLE `rw_entry` (
  `entry_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `accepted_id` INT UNSIGNED DEFAULT NULL,
  `proposed_id` INT UNSIGNED DEFAULT NULL,
  `delete_change_id` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`entry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `rw_entry`
  ADD CONSTRAINT `FK_rw_entry_accepted` FOREIGN KEY (`accepted_id` ) REFERENCES `rw_definition` (`definition_id` ),
  ADD INDEX `FK_rw_entry_accepted` (`accepted_id` ASC),
  ADD CONSTRAINT `FK_rw_entry_proposed` FOREIGN KEY (`proposed_id` ) REFERENCES `rw_definition` (`definition_id` ),
  ADD INDEX `FK_rw_entry_proposed` (`proposed_id` ASC),
  ADD CONSTRAINT `FK_rw_entry_delete_change` FOREIGN KEY (`delete_change_id` ) REFERENCES `rw_change` (`change_id` ),
  ADD INDEX `FK_rw_entry_delete_change` (`delete_change_id` ASC);

ALTER TABLE `rw_definition` ADD COLUMN `entry_id` INT UNSIGNED NULL AFTER `definition_id`;
ALTER TABLE `rw_definition` ADD COLUMN `revision` INT UNSIGNED NULL AFTER `entry_id`;
ALTER TABLE `rw_definition` ADD COLUMN `change_id` INT UNSIGNED NULL AFTER `revision`;

ALTER TABLE `rw_definition` 
ADD CONSTRAINT `FK_rw_definition_entry` FOREIGN KEY (`entry_id` ) REFERENCES `rw_entry` (`entry_id` ),
ADD INDEX `FK_rw_definition_entry` (`entry_id` ASC);

ALTER TABLE `rw_definition` 
ADD CONSTRAINT `FK_rw_definition_change` FOREIGN KEY (`change_id` ) REFERENCES `rw_change` (`change_id` ),
ADD INDEX `FK_rw_definition_change` (`change_id` ASC);



