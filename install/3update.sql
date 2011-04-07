
# Enforce uniqueness of definition.change_id
ALTER TABLE `rw_definition` ADD UNIQUE INDEX `UQ_rw_definition_change` (`change_id` ASC);

# Make definition.entry_id not null
ALTER TABLE `kumva`.`rw_definition` CHANGE COLUMN `entry_id` `entry_id` INT(10) UNSIGNED NOT NULL;

# Make definition.revision not null	
ALTER TABLE `kumva`.`rw_definition` CHANGE COLUMN `revision` `revision` INT(10) UNSIGNED NOT NULL;