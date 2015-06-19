CREATE TABLE IF NOT EXISTS #__openchat_msg(
id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
user_id INT(10) NOT NULL,
msg TEXT,
datetime timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS #__openchat_blocked_users(
id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
user_id INT(10) NOT NULL,
datetime timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

