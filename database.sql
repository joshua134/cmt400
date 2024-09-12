CREATE DATABASE project;

CREATE TABLE `department` (
                              `id` int NOT NULL AUTO_INCREMENT,
                              `department` varchar(254) NOT NULL,
                              PRIMARY KEY (`id`),
                              UNIQUE KEY `department` (`department`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci


CREATE TABLE `user` (
                        `id` int NOT NULL AUTO_INCREMENT,
                        `firstname` varchar(20) NOT NULL,
                        `lastname` varchar(20) NOT NULL,
                        `email` varchar(120) NOT NULL,
                        `password_reset_code` int DEFAULT NULL,
                        `activation_code` int DEFAULT NULL,
                        `password` varchar(254) NOT NULL,
                        `is_activated` tinyint(1) DEFAULT '0',
                        `is_blocked` tinyint(1) DEFAULT '0',
                        `is_admin` tinyint(1) DEFAULT '0',
                        `is_lecturer` tinyint(1) DEFAULT '0',
                        `is_normal` tinyint(1) DEFAULT '1',
                        `departmentID` int DEFAULT NULL,
                        `activated_at` datetime DEFAULT NULL,
                        `blocked_at` datetime DEFAULT NULL,
                        `password_reset_at` datetime DEFAULT NULL,
                        `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `email` (`email`),
                        KEY `departmentID` (`departmentID`),
                        CONSTRAINT `user_ibfk_1` FOREIGN KEY (`departmentID`) REFERENCES `department` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci


CREATE TABLE `board` (
                         `id` int NOT NULL AUTO_INCREMENT,
                         `title` varchar(254) NOT NULL,
                         `content` text NOT NULL,
                         `media` varchar(200) DEFAULT NULL,
                         `departmentID` int DEFAULT NULL,
                         `lecID` int DEFAULT NULL,
                         `is_notice` tinyint(1) DEFAULT NULL,
                         `is_announcement` tinyint(1) DEFAULT NULL,
                         `is_lecturer` tinyint(1) DEFAULT '0',
                         `is_admin` tinyint(1) DEFAULT '0',
                         `updated_at` datetime DEFAULT NULL,
                         `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                         PRIMARY KEY (`id`),
                         KEY `departmentID` (`departmentID`),
                         KEY `lecID` (`lecID`),
                         CONSTRAINT `board_ibfk_1` FOREIGN KEY (`departmentID`) REFERENCES `department` (`id`),
                         CONSTRAINT `board_ibfk_2` FOREIGN KEY (`lecID`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci


CREATE TABLE `feedback` (
                            `id` int NOT NULL AUTO_INCREMENT,
                            `is_blocked` tinyint(1) DEFAULT '0',
                            `content` text NOT NULL,
                            `boardID` int DEFAULT NULL,
                            `userID` int NOT NULL,
                            `updated_at` datetime DEFAULT NULL,
                            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                            `ip_addr` varchar(45) NOT NULL,
                            PRIMARY KEY (`id`),
                            KEY `boardID` (`boardID`),
                            KEY `userID` (`userID`),
                            CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`boardID`) REFERENCES `board` (`id`),
                            CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci


CREATE TABLE `contact` (
                           `id` int NOT NULL AUTO_INCREMENT,
                           `contact_email` varchar(120) NOT NULL,
                           `contact_subject` varchar(254) NOT NULL,
                           `contact_message` text NOT NULL,
                           `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                           PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci


