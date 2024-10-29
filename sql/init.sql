CREATE Database IF NOT EXISTS db000;

DROP TABLE IF EXISTS `login`;

CREATE TABLE `login` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login_id` varchar(20) NOT NULL,
  `login_pw` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 86 DEFAULT CHARSET = utf8mb3;

INSERT INTO
  `login` (`login_id`, `login_pw`, `created`)
VALUES
  ('admin', 'KO{DB_FAKE_FLAG}', NOW()),
  ('asdf', '$2y$10$5mtfj2JGnjejRbmTi4UMiu9vI6iV5GOSiSV8w1kW7qcDVENfE.ct.', NOW());

DROP TABLE IF EXISTS `board`;

CREATE TABLE `board` (
  `idx` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `hit` int NOT NULL,
  PRIMARY KEY (`idx`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

INSERT INTO
  `board` (`name`, `title`, `content`, `date`, `hit`)
VALUES
  (
    'Admin',
    'Greeding! :)',
    'Feel free to post!!',
    NOW(),
    0
  );

DROP TABLE IF EXISTS `file`;

CREATE TABLE `file` (
  `idx` int NOT NULL AUTO_INCREMENT,
  `post_id` int DEFAULT NULL,
  `file_name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`idx`)
) ENGINE = InnoDB AUTO_INCREMENT = 346 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `game`;

CREATE TABLE `game` (
  `login_id` int NOT NULL,
  `highest_score` int DEFAULT NULL,
  PRIMARY KEY (`login_id`),
  CONSTRAINT `game_fk_1` FOREIGN KEY (`login_id`) REFERENCES `login` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `reply`;

CREATE TABLE `reply` (
  `idx` int NOT NULL AUTO_INCREMENT,
  `post_id` int DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idx`)
) ENGINE = InnoDB AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;