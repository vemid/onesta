# fix for groupby
SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));

#fix caching_sha2_password
ALTER USER 'root'@'%' IDENTIFIED WITH mysql_native_password BY 'root';

# create databases
CREATE DATABASE IF NOT EXISTS `onesta`;
