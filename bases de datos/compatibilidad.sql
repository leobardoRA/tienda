CREATE USER 'leo'@'localhost' IDENTIFIED WITH mysql_native_password BY '12345';
GRANT ALL PRIVILEGES ON abarrotera.* TO 'leo'@'localhost';
FLUSH PRIVILEGES;