-- Table: books
CREATE TABLE `books` (
  `book_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `quantity` int DEFAULT '1',
  `available` int DEFAULT '1',
  `location` varchar(50) DEFAULT NULL,
  `added_date` date DEFAULT (curdate()),
  PRIMARY KEY (`book_id`),
  UNIQUE KEY `isbn` (`isbn`),
  CONSTRAINT `books_chk_1` CHECK ((`quantity` >= 0)),
  CONSTRAINT `books_chk_2` CHECK ((`available` >= 0))
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- Table: members
CREATE TABLE `members` (
  `member_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `class_grade` varchar(50) DEFAULT NULL,
  `member_type` enum('Student','Teacher','Staff') NOT NULL,
  `join_date` date DEFAULT (curdate()),
  `status` enum('Active','Inactive') DEFAULT 'Active',
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- Table: transactions
CREATE TABLE `transactions` (
  `transaction_id` int NOT NULL AUTO_INCREMENT,
  `book_id` int NOT NULL,
  `member_id` int NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `fine` decimal(10,2) DEFAULT '0.00',
  `status` enum('Issued','Returned','Overdue') DEFAULT 'Issued',
  PRIMARY KEY (`transaction_id`),
  KEY `book_id` (`book_id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- Table: users
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Librarian') DEFAULT 'Librarian',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users` (`user_id`, `username`, `password`, `role`) VALUES ('4', 'admin', '$2y$10$7kpiehqExhbtUP7U/SWR3OL25Mn3yWtfoWeF.0rSJNMzoq04y4g4u', 'Admin');

