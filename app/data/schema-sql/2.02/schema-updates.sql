/*
 * Statements to upgrade an existing ClientCal Schema from v2.01 to v2.02
 * 
 * @author Paul D. Bird II <doug@katmore.com>
 * 
 * @version 2.02
 * 
 */

--
-- create 'user_deleted' table
--
DROP TABLE IF EXISTS user_deleted;
CREATE TABLE `user_deleted` (
  `user_key` bigint(20) unsigned NOT NULL,
  `username` tinytext NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` tinytext DEFAULT NULL,
  `level` int(10) unsigned DEFAULT NULL,
  `deleted_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



