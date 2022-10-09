/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.4.24-MariaDB : Database - gallery
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`gallery` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `gallery`;

/*Table structure for table `comments` */

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` int(11) NOT NULL,
  `loaddate` datetime NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `c_comments_image_fk` (`image`),
  KEY `c_comments_user_fk` (`user`),
  CONSTRAINT `c_comments_image_fk` FOREIGN KEY (`image`) REFERENCES `photos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `c_comments_user_fk` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `comments` */

/*Table structure for table `photos` */

DROP TABLE IF EXISTS `photos`;

CREATE TABLE `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `owner` int(11) DEFAULT NULL,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `c_images_owner_fk` (`owner`),
  FULLTEXT KEY `i_photos_filename` (`filename`),
  CONSTRAINT `c_images_owner_fk` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4;

/*Data for the table `photos` */

insert  into `photos`(`id`,`filename`,`title`,`owner`,`type`) values 
(107,'river-surrounded-by-forests-under-a-cloudy-sky-in-thuringia-in-germany_181624-30863.jpg','Загрузил 0707.1010.2222 DVSt',9,'jpg'),
(108,'rusia_baikal.jpg','Загрузил 0707.1010.2222 DVSt',9,'jpg'),
(109,'shutterstock_385517032.jpg','Загрузил 0707.1010.2222 DVSt',9,'jpg'),
(110,'559f29ab37c5ce62894856d98b3a335e.jpg','Загрузил 0707.1010.2222 DVSt',9,'jpg'),
(111,'1617050097_39-p-oboi-russkaya-priroda-42.jpg','Загрузил 0707.1010.2222 DVSt',9,'jpg'),
(112,'Без названия (1).jpg','Загрузил 0707.1010.2222 DVSt',9,'jpg');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `hash` varchar(250) NOT NULL DEFAULT '',
  `email` varchar(40) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

/*Data for the table `users` */

insert  into `users`(`id`,`login`,`password`,`hash`,`email`,`ip`) values 
(4,'DVS1234','$2y$10$qzHf8zEpI5Y3LMtVZrdaBe.QuphIvySCxknB5bXI93oJl3nF3jEb6','JYEuZY','','::1'),
(8,'DVS12345','$2y$10$l1AJr6Ye47LlLaJ.PAghFOplc5A.9SLvsUS1gT5aTXKym9n7MQHu.','9MRbzm','','::1');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
