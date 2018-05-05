/*
 * Statements to upgrade an existing ClientCal Schema from v2.00 to v2.01
 * 
 * @author Paul D. Bird II <doug@katmore.com>
 * 
 * @version 2.01
 * 
 */

--
-- create 'user_deleted_duplicate' table
--
DROP TABLE IF EXISTS user_deleted_duplicate;
CREATE TABLE `user_deleted_duplicate` (
  `user_key` bigint(20) unsigned NOT NULL,
  `username` tinytext NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` tinytext DEFAULT NULL,
  `level` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- create user_unique table
--
DROP TABLE IF EXISTS user_unique;
CREATE TABLE `user_unique` (
  `user_key` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` tinytext DEFAULT NULL,
  `level` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_key`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- delete users with duplicate usernames, 
-- keep the user_key with lowest user_key
-- (this mimics how the application logic would have handled duplicate usernames) 
--
-- save deleted users to 'user_deleted_duplicate' table, just in case
--
START TRANSACTION;
INSERT INTO user_unique (user_key, username, email, password, level)
   SELECT 
   t1.user_key, t1.username, t1.email, t1.password, t1.level
	FROM user AS t1
	LEFT JOIN user AS t2
	ON t1.username = t2.username
	AND t1.user_key > t2.user_key
	WHERE t2.user_key IS NULL
;
INSERT INTO user_deleted_duplicate (user_key, username, email, password, level)
   SELECT user_key, username, email, password, level FROM user WHERE user_key IN(
      SELECT n1.user_key FROM `user` n1, `user` n2 WHERE n1.user_key > n2.user_key AND n1.username = n2.username
   )
;
COMMIT;
DROP TABLE user;
RENAME TABLE user_unique TO user;



