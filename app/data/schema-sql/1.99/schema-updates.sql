/*
 * Statements to upgrade an existing ClientCal Schema from v1.98 to v1.99
 * 
 * @author Paul D. Bird II
 * 
 * @version 1.99
 */
ALTER TABLE `customer_file` CHANGE `hash` `hash` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `customer_file` ENGINE = INNODB DEFAULT CHARSET=utf8 COLLATE utf8_bin;

ALTER TABLE `customer` DROP INDEX `name`, ADD INDEX `name` (`name`);

ALTER TABLE `customer` ENGINE = INNODB DEFAULT CHARSET=utf8 COLLATE utf8_bin;

drop table  customer_mailqueue;

ALTER TABLE `customer_file` ENGINE = INNODB DEFAULT CHARSET=utf8 COLLATE utf8_bin;

drop table session;