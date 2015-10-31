--CREATE DATABASE `members`;
--USE `members`;

CREATE TABLE `Payments` (
  `id` integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  `email` char(64) NOT NULL,
  `submitted` datetime NOT NULL,
  `amount` int(11) NOT NULL,
  `verified` tinyint(1) DEFAULT NULL
);
CREATE INDEX `IDX_Payments_EMAIL` ON `Payments`(`email`);

CREATE TABLE `Users` (
  `id` integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  `email` char(64) NOT NULL,
  `password` char(32) NULL,
  `salt` char(32) NULL,
  `last_update` timestamp DEFAULT CURRENT_TIMESTAMP,
  `paid_verified` date DEFAULT NULL,
  `paid` date DEFAULT NULL,
  `since` date NOT NULL,
  `count` int(11) NOT NULL DEFAULT 0,
  `mac` char(40) DEFAULT NULL
);
CREATE UNIQUE INDEX `IDX_Users_EMAIL` ON `Users`(`email`);
CREATE UNIQUE INDEX `IDX_Users_PASSWORD` ON `Users`(`password`);
CREATE UNIQUE INDEX `IDX_Users_MAC` ON `Users`(`mac`);

CREATE TRIGGER [UpdateLastTime]
  AFTER UPDATE
  ON Users
  FOR EACH ROW
  BEGIN
    UPDATE Users SET last_update = CURRENT_TIMESTAMP WHERE id = old.id;
  END
