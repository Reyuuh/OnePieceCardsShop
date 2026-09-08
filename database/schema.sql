-- OnePiece Cardshop Database Schema
-- Run this file against your MySQL database after creating it.

SET NAMES utf8mb4;

-- Categories
CREATE TABLE IF NOT EXISTS Categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE
);

-- Products
CREATE TABLE IF NOT EXISTS Products (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    title            VARCHAR(50),
    price            INT          DEFAULT 0,
    stockLevel       INT          DEFAULT 0,
    category_id      INT,
    popularityFactor INT          DEFAULT 0,
    imageUrl         VARCHAR(255),
    FOREIGN KEY (category_id) REFERENCES Categories(id)
);

-- Cart items (supports both guest sessions and logged-in users)
CREATE TABLE IF NOT EXISTS CartItem (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    productId INT          NOT NULL,
    quantity  INT          NOT NULL DEFAULT 1,
    sessionId VARCHAR(128),
    userId    INT          DEFAULT NULL
);

-- User shipping details
CREATE TABLE IF NOT EXISTS UserDetails (
    id           INT PRIMARY KEY,
    name         VARCHAR(50),
    streetAdress VARCHAR(100),
    postalCode   VARCHAR(20),
    city         VARCHAR(100)
);

-- Auth tables (managed by delight-im/auth)
CREATE TABLE IF NOT EXISTS `users` (
    `id`           int(10) unsigned NOT NULL AUTO_INCREMENT,
    `email`        varchar(249) COLLATE utf8mb4_unicode_ci NOT NULL,
    `password`     varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `username`     varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status`       tinyint(2) unsigned NOT NULL DEFAULT '0',
    `verified`     tinyint(1) unsigned NOT NULL DEFAULT '0',
    `resettable`   tinyint(1) unsigned NOT NULL DEFAULT '1',
    `roles_mask`   int(10) unsigned NOT NULL DEFAULT '0',
    `registered`   int(10) unsigned NOT NULL,
    `last_login`   int(10) unsigned DEFAULT NULL,
    `force_logout` mediumint(7) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users_confirmations` (
    `id`      int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(10) unsigned NOT NULL,
    `email`   varchar(249) COLLATE utf8mb4_unicode_ci NOT NULL,
    `selector` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `token`   varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `expires` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `selector` (`selector`),
    KEY `email_expires` (`email`, `expires`),
    KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users_remembered` (
    `id`       bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user`     int(10) unsigned NOT NULL,
    `selector` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `token`    varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `expires`  int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `selector` (`selector`),
    KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users_resets` (
    `id`       bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user`     int(10) unsigned NOT NULL,
    `selector` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `token`    varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `expires`  int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `selector` (`selector`),
    KEY `user_expires` (`user`, `expires`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users_throttling` (
    `bucket`         varchar(44) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
    `tokens`         float unsigned NOT NULL,
    `replenished_at` int(10) unsigned NOT NULL,
    `expires_at`     int(10) unsigned NOT NULL,
    PRIMARY KEY (`bucket`),
    KEY `expires_at` (`expires_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
