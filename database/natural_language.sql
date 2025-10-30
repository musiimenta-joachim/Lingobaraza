-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2024 at 09:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `natural_language`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_admin`
--

CREATE TABLE `about_admin` (
  `about_id` int(11) NOT NULL,
  `details` varchar(400) DEFAULT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `about_admin`
--

INSERT INTO `about_admin` (`about_id`, `details`, `admin_id`) VALUES
(2, '', 3);

-- --------------------------------------------------------

--
-- Table structure for table `about_project`
--

CREATE TABLE `about_project` (
  `about_id` int(11) NOT NULL,
  `project_details` varchar(4000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `about_project`
--

INSERT INTO `about_project` (`about_id`, `project_details`) VALUES
(0, 'It is a research data collection project which deals with natural language that is Luganda, Runyankole and English. We are glad for all the participants in this project and with maximum cooperation, we shall see it through. As we benefit from this, you also will. Thank you for your time and all the best on our journey. For God and my country.');

-- --------------------------------------------------------

--
-- Table structure for table `corrections`
--

CREATE TABLE `corrections` (
  `correction_id` varchar(20) NOT NULL,
  `correction` varchar(500) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `expert_id` int(11) NOT NULL,
  `sentence_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_logs`
--

CREATE TABLE `email_logs` (
  `id` int(11) NOT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `recipient_type` varchar(50) DEFAULT NULL,
  `success_count` int(11) DEFAULT NULL,
  `failure_count` int(11) DEFAULT NULL,
  `sent_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `language_id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`language_id`, `name`) VALUES
(1, 'English'),
(2, 'Luganda'),
(3, 'Runyankole');

-- --------------------------------------------------------

--
-- Table structure for table `sentences`
--

CREATE TABLE `sentences` (
  `sentence_id` varchar(20) NOT NULL,
  `sentence` varchar(500) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `language_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(60) DEFAULT NULL,
  `main_contact` varchar(45) DEFAULT NULL,
  `alt_contact` varchar(45) DEFAULT NULL,
  `age` date DEFAULT NULL,
  `gender` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  `preferred_languages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferred_languages`)),
  `level_of_fluency` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`level_of_fluency`)),
  `acc_type` varchar(45) DEFAULT NULL,
  `password` varchar(265) DEFAULT NULL,
  `reg_date` date DEFAULT NULL,
  `consent` int(11) DEFAULT NULL,
  `profile_picture` varchar(100) DEFAULT NULL,
  `last_logged_in` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `main_contact`, `alt_contact`, `age`, `gender`, `email`, `address`, `preferred_languages`, `level_of_fluency`, `acc_type`, `password`, `reg_date`, `consent`, `profile_picture`, `last_logged_in`) VALUES
(1, 'Sam Doe', '0783432322', '0724384726', '2024-11-07', 'male', 'samdoe@ymail.com', 'Kekuubo', '[\"English\",\"Runyankole\"]', '{\"English\":\"A-level\",\"Runyankole\":\"A-level\",\"Luganda\":\"Primary\"}', 'validator', '$2y$10$WFP1aQEj1AS/Z8.uDopYZudq/elk6FBl7cauxJ.hm7DwkiRMyktku', '2024-11-27', 1, NULL, '2024-12-17 09:53:30'),
(2, 'Kim Doe', '0783432320', '0772438470', '2024-11-08', 'male', 'kimdoe@ymail.com', 'Kekuubo', '[\"English\",\"Runyankole\"]', '{\"English\":\"A-level\",\"Runyankole\":\"O-level\",\"Luganda\":\"A-level\"}', 'contributor', '$2y$10$nSotx7C9TwF.3TBiIbEs2uTrxLlNt78qpZPg0TdobhzVjKoBL9qxq', '2024-11-27', 1, NULL, '2024-12-17 20:21:03'),
(3, 'Ham Doe', '0783432342', '0792438472', '2024-11-09', 'male', 'hamdoe@gmail.com', 'Kekuubo', '[\"English\",\"Luganda\",\"Runyankole\"]', '[{\"label\":\"English\",\"fluency\":\"A-level\"},{\"label\":\"Runyankole\",\"fluency\":\"A-level\"},{\"label\":\"Luganda\",\"fluency\":\"O-level\"}]', 'admin', '$2y$10$smjzSMnwEoRQJiSagJMQrukUR//iq8wSkoNNkovUXmEy0TNVGvMIC', '2024-11-27', 1, NULL, '2024-12-17 20:25:15');

-- --------------------------------------------------------

--
-- Table structure for table `validated_sentences`
--

CREATE TABLE `validated_sentences` (
  `validation_id` int(11) NOT NULL,
  `status` varchar(45) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `expert_id` int(11) NOT NULL,
  `sentence_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voice_notes`
--

CREATE TABLE `voice_notes` (
  `voice_note_id` varchar(20) NOT NULL,
  `voice_note_path` varchar(100) DEFAULT NULL,
  `note_duration` int(11) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `recording_date` datetime DEFAULT NULL,
  `validation_date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `sentence_id` varchar(20) NOT NULL,
  `validator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `vote_id` int(11) NOT NULL,
  `status` varchar(45) DEFAULT NULL,
  `correction_id` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_admin`
--
ALTER TABLE `about_admin`
  ADD PRIMARY KEY (`about_id`),
  ADD KEY `fk_about_project_users1_idx` (`admin_id`);

--
-- Indexes for table `about_project`
--
ALTER TABLE `about_project`
  ADD PRIMARY KEY (`about_id`);

--
-- Indexes for table `corrections`
--
ALTER TABLE `corrections`
  ADD PRIMARY KEY (`correction_id`),
  ADD KEY `fk_corrections_users1_idx` (`expert_id`),
  ADD KEY `fk_corrections_sentences1_idx` (`sentence_id`);

--
-- Indexes for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`language_id`);

--
-- Indexes for table `sentences`
--
ALTER TABLE `sentences`
  ADD PRIMARY KEY (`sentence_id`),
  ADD KEY `fk_sentences_languages_idx` (`language_id`),
  ADD KEY `fk_sentences_users1_idx` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Indexes for table `validated_sentences`
--
ALTER TABLE `validated_sentences`
  ADD PRIMARY KEY (`validation_id`),
  ADD KEY `fk_validated_sentences_users1_idx` (`expert_id`),
  ADD KEY `fk_validated_sentences_sentences1_idx` (`sentence_id`);

--
-- Indexes for table `voice_notes`
--
ALTER TABLE `voice_notes`
  ADD PRIMARY KEY (`voice_note_id`),
  ADD KEY `fk_voice_notes_users1_idx` (`user_id`),
  ADD KEY `fk_voice_notes_sentences1_idx` (`sentence_id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`vote_id`),
  ADD KEY `fk_votes_corrections1_idx` (`correction_id`),
  ADD KEY `fk_votes_users1_idx` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_admin`
--
ALTER TABLE `about_admin`
  MODIFY `about_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `validated_sentences`
--
ALTER TABLE `validated_sentences`
  MODIFY `validation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `about_admin`
--
ALTER TABLE `about_admin`
  ADD CONSTRAINT `fk_about_project_users1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `corrections`
--
ALTER TABLE `corrections`
  ADD CONSTRAINT `fk_corrections_sentences1` FOREIGN KEY (`sentence_id`) REFERENCES `sentences` (`sentence_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_corrections_users1` FOREIGN KEY (`expert_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `sentences`
--
ALTER TABLE `sentences`
  ADD CONSTRAINT `fk_sentences_languages` FOREIGN KEY (`language_id`) REFERENCES `languages` (`language_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_sentences_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `validated_sentences`
--
ALTER TABLE `validated_sentences`
  ADD CONSTRAINT `fk_validated_sentences_sentences1` FOREIGN KEY (`sentence_id`) REFERENCES `sentences` (`sentence_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_validated_sentences_users1` FOREIGN KEY (`expert_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `voice_notes`
--
ALTER TABLE `voice_notes`
  ADD CONSTRAINT `fk_voice_notes_sentences1` FOREIGN KEY (`sentence_id`) REFERENCES `sentences` (`sentence_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_voice_notes_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `fk_votes_corrections1` FOREIGN KEY (`correction_id`) REFERENCES `corrections` (`correction_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_votes_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
