
ALTER TABLE `kumva`.`rw_definition` DROP FOREIGN KEY `FK_definition_entry` ;
ALTER TABLE `kumva`.`rw_definition` 
	CHANGE COLUMN `entry_id` `entry_id` INT(10) UNSIGNED NOT NULL, 
	ADD CONSTRAINT `FK_definition_entry` FOREIGN KEY (`entry_id` ) REFERENCES `kumva`.`rw_entry` (`entry_id` );