DROP DATABASE IF EXISTS e2e;
CREATE DATABASE e2e;
CREATE USER 'e2e'@'localhost' IDENTIFIED BY 'e2e';
GRANT ALL ON e2e.* TO 'e2e'@'localhost';