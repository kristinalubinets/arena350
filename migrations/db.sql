CREATE TABLE `events` (
                          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                          `name` varchar(255) NOT NULL,
                          `description` text DEFAULT NULL,
                          `date` datetime DEFAULT NULL,
                          `created` datetime NOT NULL DEFAULT current_timestamp(),
                          `updated` datetime NOT NULL DEFAULT current_timestamp(),
                          PRIMARY KEY (`id`),
                          UNIQUE KEY `event_name_uniq_idx` (`name`),
                          KEY `event_created_idx` (`created`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

ALTER TABLE `events` ADD `image_url` TEXT AFTER `description`;
