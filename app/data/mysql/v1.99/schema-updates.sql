CREATE TABLE `ccdbversion` (
  `version` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `installed_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO cc_schema SET version='1.99';

ALTER TABLE `customer_file` CHANGE `hash` `hash` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `customer_file` ENGINE = INNODB DEFAULT CHARSET=utf8 COLLATE utf8_bin;

ALTER TABLE `customer` DROP INDEX `name`, ADD INDEX `name` (`name`);

ALTER TABLE `customer` ENGINE = INNODB DEFAULT CHARSET=utf8 COLLATE utf8_bin;

drop table  customer_mailqueue;

ALTER TABLE `customer_file` ENGINE = INNODB DEFAULT CHARSET=utf8 COLLATE utf8_bin;

drop table session;