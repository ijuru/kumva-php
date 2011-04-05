
# Make definition.entry_id not null
ALTER TABLE `kumva`.`rw_definition` CHANGE COLUMN `entry_id` `entry_id` INT(10) UNSIGNED NOT NULL;

# Make definition.revision not null	
ALTER TABLE `kumva`.`rw_definition` CHANGE COLUMN `revision` `revision` INT(10) UNSIGNED NOT NULL;