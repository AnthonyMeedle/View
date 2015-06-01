
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- view
-- ---------------------------------------------------------------------

ALTER TABLE `view` ADD `subtree_view` VARCHAR(255) AFTER `view`;
ALTER TABLE `view` ADD `children_view` VARCHAR(255) AFTER `view`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
