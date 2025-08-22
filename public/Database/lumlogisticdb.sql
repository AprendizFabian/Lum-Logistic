-- Delete database if exists
DROP DATABASE IF EXISTS `lumlogisticdb`;

-- Create Database
CREATE DATABASE `lumlogisticdb` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

-- Use database
USE `lumlogisticdb`;

-- -----------------------------------------------------
-- Table `shelf_life`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `shelf_life`;
CREATE TABLE `shelf_life` (
  `id_shelf_life` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `concept` VARCHAR(100) NOT NULL,
  `duration` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_shelf_life`)
) ENGINE=InnoDB
  DEFAULT CHARACTER SET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `catalog`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `catalog`;
CREATE TABLE `catalog` (
  `id_product` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ean` VARCHAR(50) NOT NULL,
  `sync_id` INT(10) UNSIGNED NOT NULL,
  `vivo_id` INT(10) UNSIGNED NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `block_status` VARCHAR(100) NOT NULL,
  `image_url` VARCHAR(255) NULL DEFAULT NULL,
  `id_shelf_life` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_product`),
  INDEX `idx_catalog_ean` (`ean` ASC),
  INDEX `idx_catalog_sync_id` (`sync_id` ASC),
  INDEX `fk_catalog_shelf_life` (`id_shelf_life` ASC),
  CONSTRAINT `fk_catalog_shelf_life`
    FOREIGN KEY (`id_shelf_life`)
    REFERENCES `shelf_life` (`id_shelf_life`)
    ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARACTER SET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id_role` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` VARCHAR(100) NOT NULL,
  `role_description` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB
  AUTO_INCREMENT=3
  DEFAULT CHARACTER SET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `stores`
-- -----------------------------------------------------
INSERT INTO `roles` (`id_role`, `role_name`, `role_description`) VALUES
(1, 'Admin', 'Admin'),
(2, 'Usuario', 'Admin'),
(3, 'tienda', 'Usuario registrado como tienda');

-- -----------------------------------------------------
-- Table `stores`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stores`;
CREATE TABLE `stores` (
  `id_store` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `store_name` VARCHAR(150) NOT NULL,
  `store_address` VARCHAR(255) NOT NULL,
  `store_email` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NULL DEFAULT NULL,
  `id_role` INT(10) UNSIGNED NOT NULL DEFAULT 1,
  `google_id` VARCHAR(50) NULL DEFAULT NULL,
  `profile_picture_url` VARCHAR(255) NULL DEFAULT NULL,
  `provider` ENUM('google') NULL DEFAULT 'google',
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP(),
  `last_login` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id_store`),
  UNIQUE INDEX `store_email` (`store_email` ASC),
  UNIQUE INDEX `google_id` (`google_id` ASC),
  INDEX `fk_store_role` (`id_role` ASC),
  CONSTRAINT `fk_store_role`
    FOREIGN KEY (`id_role`)
    REFERENCES `roles` (`id_role`)
    ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARACTER SET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id_user` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NULL DEFAULT NULL,
  `id_role` INT(10) UNSIGNED NOT NULL,
  `google_id` VARCHAR(50) NULL DEFAULT NULL,
  `profile_picture_url` VARCHAR(255) NULL DEFAULT NULL,
  `provider` ENUM('local', 'google') NULL DEFAULT 'local',
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP(),
  `last_login` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE INDEX `email` (`email` ASC),
  UNIQUE INDEX `google_id` (`google_id` ASC),
  INDEX `fk_user_role` (`id_role` ASC),
  CONSTRAINT `fk_user_role`
    FOREIGN KEY (`id_role`)
    REFERENCES `roles` (`id_role`)
    ON UPDATE CASCADE
) ENGINE=InnoDB
  AUTO_INCREMENT=6
  DEFAULT CHARACTER SET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `validation_history`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `validation_history`;
CREATE TABLE `validation_history` (
  `id_validation` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ean` VARCHAR(50) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `expiration_date` DATE NOT NULL,
  `block_date` DATE NOT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP(),
  `category` VARCHAR(100) NOT NULL,
  `block_concept` VARCHAR(100) NOT NULL,
  `remarks` VARCHAR(255) NULL DEFAULT NULL,
  `id_store` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_validation`),
  INDEX `idx_history_ean` (`ean` ASC),
  INDEX `idx_history_expiration` (`expiration_date` ASC),
  INDEX `fk_history_store` (`id_store` ASC),
  CONSTRAINT `fk_history_store`
    FOREIGN KEY (`id_store`)
    REFERENCES `stores` (`id_store`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARACTER SET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;