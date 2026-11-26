SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "-03:00";

CREATE TABLE `job_answers` (
  `id` int(11) NOT NULL,
  `response_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answer_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `job_applications` (
  `id` int(11) NOT NULL,
  `form_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `applicant_email` varchar(255) DEFAULT NULL,
  `applicant_mobile` varchar(13) NOT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Reviewed','Hired') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `job_forms` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `job_questions` (
  `id` int(11) NOT NULL,
  `form_id` int(11) DEFAULT NULL,
  `question_text` varchar(255) DEFAULT NULL,
  `question_type` enum('text','textarea','number','select','checkbox','radio','file') DEFAULT NULL,
  `options` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `job_responses` (
  `id` int(11) NOT NULL,
  `form_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `applicant_email` varchar(255) DEFAULT NULL,
  `applicant_mobile` varchar(13) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resume` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Reviewed','Hired') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `useers` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `useers` (`id`, `username`, `name`, `email`, `password`, `mobile`, `role`) VALUES
(1, 'admin', 'Jean Douglas', 'suporte@jeandouglas.com.br', '$2y$10$ql0q3i2b/wvs3gEogJPg9.A4H2tOg.7ZPnQQoqEVMhoLN8571qTdG', '48999563416', 'admin');

ALTER TABLE `job_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `response_id` (`response_id`),
  ADD KEY `question_id` (`question_id`);

ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

ALTER TABLE `job_forms` ADD PRIMARY KEY (`id`);
ALTER TABLE `job_questions` ADD PRIMARY KEY (`id`), ADD KEY `form_id` (`form_id`);
ALTER TABLE `job_responses` ADD PRIMARY KEY (`id`), ADD KEY `form_id` (`form_id`);
ALTER TABLE `useers` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`);

ALTER TABLE `job_answers` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
ALTER TABLE `job_applications` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
ALTER TABLE `job_forms` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
ALTER TABLE `job_questions` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;
ALTER TABLE `job_responses` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
ALTER TABLE `useers` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `job_answers`
  ADD CONSTRAINT `job_answers_ibfk_1` FOREIGN KEY (`response_id`) REFERENCES `job_applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `job_questions` (`id`) ON DELETE CASCADE;

ALTER TABLE `job_applications`
  ADD CONSTRAINT `job_applications_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `job_forms` (`id`) ON DELETE CASCADE;

ALTER TABLE `job_questions`
  ADD CONSTRAINT `job_questions_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `job_forms` (`id`) ON DELETE CASCADE;

ALTER TABLE `job_responses`
  ADD CONSTRAINT `job_responses_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `job_forms` (`id`) ON DELETE CASCADE;

COMMIT;
