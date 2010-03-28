drop table if exists `product`;
create table product (
	`id` int unsigned NOT NULL auto_increment,
	`slug` varchar(75) NOT NULL,
	`name` varchar(75) NOT NULL,
	`description` text NOT NULL,
	`product_id` int NOT NULL,
	PRIMARY KEY  (`id`),
	UNIQUE KEY `slug` (`slug`)
) engine=innodb;

drop table if exists `item`;
create table item (
	`id` int unsigned NOT NULL auto_increment,
	`product_id` int NOT NULL,
	`name` varchar(50) NOT NULL,
	`quantity` int NOT NULL,
	`price` decimal(5,2),
	PRIMARY KEY  (`id`),
	KEY (`product_id`)
) engine=innodb;

drop table if exists `user`;
create table user (
	`id` int unsigned NOT NULL auto_increment,
	`username` varchar(50) NOT NULL,
	`password` varchar(50) NOT NULL,
	`email` varchar(128) NOT NULL,
	PRIMARY KEY  (`id`),
	UNIQUE KEY (`username`)
) engine=innodb;

drop table if exists `category`;
create table category (
	`id` int unsigned NOT NULL auto_increment,
	`name` varchar(50),
	PRIMARY KEY  (`id`),
	UNIQUE KEY `name` (`name`)
) engine=innodb;
