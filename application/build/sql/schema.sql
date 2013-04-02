
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `parent_category_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `category_FI_1` (`parent_category_id`),
    CONSTRAINT `category_FK_1`
        FOREIGN KEY (`parent_category_id`)
        REFERENCES `category` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- feed
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `feed`;

CREATE TABLE `feed`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `link` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT(255),
    `updated` DATETIME,
    `type_id` INTEGER NOT NULL,
    `category_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `feed_FI_1` (`type_id`),
    INDEX `feed_FI_2` (`category_id`),
    CONSTRAINT `feed_FK_1`
        FOREIGN KEY (`type_id`)
        REFERENCES `feed_type` (`id`),
    CONSTRAINT `feed_FK_2`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- entry
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `entry`;

CREATE TABLE `entry`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `published` DATETIME,
    `updated` DATETIME,
    `link` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT(255),
    `read` TINYINT NOT NULL,
    `content` TEXT,
    `feed_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `entry_FI_1` (`feed_id`),
    CONSTRAINT `entry_FK_1`
        FOREIGN KEY (`feed_id`)
        REFERENCES `feed` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- feed_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `feed_type`;

CREATE TABLE `feed_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(50) NOT NULL,
    `password` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
