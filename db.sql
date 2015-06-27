CREATE TABLE `Payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` char(64) NOT NULL,
  `submitted` date DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `verified` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_Payments_NAME` (`email`)
);

CREATE TABLE `Users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` char(64) NOT NULL,
  `password` text NOT NULL,
  `salt` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `paid` date DEFAULT NULL,
  `since` date NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_Users_NAME` (`email`)
);

