
CREATE DATABASE `ggd_shortener`;

GO

USE `ggd_shortener`;

GO

CREATE TABLE `short_url` (
      `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `code` varchar(5) DEFAULT NULL,
      `url` varchar(2083) DEFAULT NULL
    );