IF cms('cms') IS NULL 
    CREATE DATABASE dbname

GO

CREATE TABLE `cms`.`users_table` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `user_image` varchar(100) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `cms`.`news_table` ( 
  `id` INT NOT NULL AUTO_INCREMENT , 
  `news_title` VARCHAR(255) NOT NULL ,
  `news_body` VARCHAR(255) NOT NULL 
  `news_author` VARCHAR(50) NOT NULL ,
  `news_image` VARCHAR(150) NOT NULL ,
  `approved` ENUM('0', '1') NOT NULL DEFAULT '0' 
  `created_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `cms`.`admin_table` ( 
  `id` INT NOT NULL AUTO_INCREMENT ,
  `admin_email` VARCHAR(100) ,
  `admin_password` VARCHAR(100) NOT NULL , 
  `admin_username` VARCHAR(50) NOT NULL ,
  `gender` VARCHAR(50) ,
  `admin_image` VARCHAR(100) ,  
  `created_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

