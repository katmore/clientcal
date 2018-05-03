/*
 * Statements to upgrade an existing ClientCal Schema from v1.99 to v2.00
 * 
 * @author Paul D. Bird II <doug@katmore.com>
 * 
 * @version 2.00
 * 
 */
ALTER TABLE user ADD COLUMN `email` varchar(100) DEFAULT NULL AFTER `username`;