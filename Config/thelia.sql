
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- view
-- ---------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `view`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `view` VARCHAR(255),
    `source` LONGTEXT,
    `source_id` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
