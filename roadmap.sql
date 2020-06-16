-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2020 at 08:16 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `roadmap`
--

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `id` int(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `long_description` varchar(1000) NOT NULL,
  `estimate` int(5) NOT NULL DEFAULT 0,
  `milestone_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `priority` enum('1. Must Have','2. Should Have','3. Could Have','4. Would Have') NOT NULL,
  `code_base_milestone_id` int(20) NOT NULL,
  `code_base_ticket` varchar(2000) NOT NULL,
  `client_justification` varchar(100) NOT NULL,
  `client_interest` int(5) DEFAULT NULL,
  `code_base_tag` varchar(50) NOT NULL,
  `file_path` varchar(100) NOT NULL,
  `confidence_level` varchar(20) NOT NULL,
  `job_order` int(10) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `date_deleted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `job`
--

INSERT INTO `job` (`id`, `name`, `long_description`, `estimate`, `milestone_id`, `project_id`, `priority`, `code_base_milestone_id`, `code_base_ticket`, `client_justification`, `client_interest`, `code_base_tag`, `file_path`, `confidence_level`, `job_order`, `create_at`, `update_at`, `deleted`, `date_deleted`) VALUES
(566, 'Fix Milestone edit save issue', '<ul><li><b>As a</b> User of roadmap with the access of Milestone&nbsp;</li><li><b>I should</b> be able to edit milestone data</li><li><b>So that</b> I can update milestone information</li></ul>', 1, 252094, 14, '4. Would Have', 0, '', '', NULL, '', '', '3. Low', 2, '2020-06-13 21:53:28', '2020-06-14 16:12:10', 0, '0000-00-00 00:00:00'),
(567, 'Fix epic view bugs', '', 2, 252094, 14, '4. Would Have', 0, '', '', NULL, '', '', '3. Low', 1, '2020-06-13 22:06:21', '2020-06-14 16:12:10', 0, '0000-00-00 00:00:00'),
(568, 'Active epic board', '<ol><li>create an epic active dashboard</li><li>allow use to drag and drop status</li></ol>', 10, 252095, 14, '2. Should Have', 0, '', '', NULL, '', '', '1. High', 0, '2020-06-16 06:46:17', '2020-06-16 05:46:17', 0, '0000-00-00 00:00:00'),
(569, 'thinking about a new project for AI', '<ol><li>thinking about a new project for AI</li></ol>', 10, 252096, 14, '3. Could Have', 0, '', '', NULL, '', '', '2. Medium', 0, '2020-06-16 06:46:55', '2020-06-16 05:47:58', 0, '0000-00-00 00:00:00'),
(570, 'investigate the possible outcome', 'investigate the possible outcome', 10, 252096, 14, '4. Would Have', 0, '', '', NULL, '', '', '3. Low', 0, '2020-06-16 06:47:52', '2020-06-16 05:47:52', 0, '0000-00-00 00:00:00'),
(571, 'Create a active board allow user to drag and drop status', 'Create a active board allow user to drag and drop status', 10, 252095, 14, '2. Should Have', 0, '', '', NULL, '', '', '2. Medium', 0, '2020-06-16 06:53:36', '2020-06-16 05:53:36', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Stand-in structure for view `job_view`
-- (See below for the actual view)
--
CREATE TABLE `job_view` (
`id` int(30)
,`name` varchar(100)
,`long_description` varchar(1000)
,`estimate` int(5)
,`milestone_id` int(11)
,`project_id` int(11)
,`priority` enum('1. Must Have','2. Should Have','3. Could Have','4. Would Have')
,`code_base_milestone_id` int(20)
,`code_base_ticket` varchar(2000)
,`client_justification` varchar(100)
,`client_interest` int(5)
,`code_base_tag` varchar(50)
,`file_path` varchar(100)
,`confidence_level` varchar(20)
,`job_order` int(10)
,`create_at` timestamp
,`update_at` timestamp
,`deleted` tinyint(1)
,`date_deleted` timestamp
,`milestone_name` varchar(100)
,`active_milestone` int(1)
);

-- --------------------------------------------------------

--
-- Table structure for table `milestone`
--

CREATE TABLE `milestone` (
  `id` int(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `goal` text NOT NULL,
  `project_id` int(50) DEFAULT NULL,
  `story_points` int(11) NOT NULL DEFAULT 0,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `active` char(1) DEFAULT 'Y',
  `completed` int(2) DEFAULT 0,
  `create_at` datetime NOT NULL,
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `date_deleted` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `milestone`
--

INSERT INTO `milestone` (`id`, `name`, `goal`, `project_id`, `story_points`, `start_date`, `end_date`, `active`, `completed`, `create_at`, `update_at`, `deleted`, `date_deleted`) VALUES
(252094, 'Roadmap draft 1', '<ol><li>Fix Milestone edit save issue</li><li>Fix epic view bugs</li><li>Fix Delivery Timeline display issue</li><li>Fix login redirect issue&nbsp;</li></ol>', 14, 20, '2020-06-01 00:00:00', '2020-06-14 00:00:00', 'Y', 0, '2020-06-13 17:52:17', '2020-06-14 06:37:38', 0, '2020-06-13 16:52:17'),
(252095, 'Roadmap draft 2', '<ol><li>Active epic board</li><li>drag and drop epic from states</li></ol>', 14, 20, '2020-06-14 00:00:00', '2020-06-21 00:00:00', 'Y', 0, '2020-06-14 08:52:39', '2020-06-14 06:52:39', 0, '2020-06-14 07:52:39'),
(252096, 'AI Project', '<ol><li>thinking about a new project for AI</li><li>investigate the possible outcome</li></ol>', 14, 25, '2020-06-21 00:00:00', '2020-06-28 00:00:00', 'Y', 0, '2020-06-14 08:56:32', '2020-06-14 06:57:42', 0, '2020-06-14 07:56:32'),
(252097, 'AI Project undecided 1', '', 14, 20, '2020-06-28 00:00:00', '2020-07-05 00:00:00', 'Y', 0, '2020-06-14 18:07:19', '2020-06-14 16:07:19', 0, '2020-06-14 17:07:19'),
(252098, 'AI Project undecided 1.1', '', 14, 20, '2020-07-05 00:00:00', '2020-07-12 00:00:00', 'Y', 0, '2020-06-14 18:07:51', '2020-06-14 16:07:51', 0, '2020-06-14 17:07:51'),
(252099, 'CV Profolio Update', '', 14, 25, '2020-06-14 00:00:00', '2020-06-28 00:00:00', 'Y', 0, '2020-06-14 18:08:39', '2020-06-14 16:08:39', 0, '2020-06-14 17:08:39');

-- --------------------------------------------------------

--
-- Stand-in structure for view `milestone_view`
-- (See below for the actual view)
--
CREATE TABLE `milestone_view` (
`id` int(50)
,`name` varchar(100)
,`goal` text
,`project_name` varchar(50)
,`project_description` varchar(50)
,`project_id` int(50)
,`start_date` datetime
,`end_date` datetime
,`completed` int(2)
,`update_at` timestamp
,`story_points` int(11)
,`progress` int(7)
,`jobs` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` int(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL,
  `bugability_target` int(5) NOT NULL,
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `date_deleted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `name`, `description`, `bugability_target`, `create_at`, `update_at`, `deleted`, `date_deleted`) VALUES
(14, 'roadmap', 'Roadmap', 50, '2020-04-06 10:14:04', '2020-05-21 10:50:22', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `model_name` varchar(50) DEFAULT NULL,
  `model_id` int(50) DEFAULT NULL,
  `setting_key` varchar(100) DEFAULT NULL,
  `setting_value` varchar(2000) DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(50) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `reset_link` varchar(30) NOT NULL,
  `user_level_id` int(11) DEFAULT NULL,
  `last_logged_in` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` int(1) NOT NULL DEFAULT 0,
  `date_deleted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `password`, `reset_link`, `user_level_id`, `last_logged_in`, `create_at`, `update_at`, `deleted`, `date_deleted`) VALUES
(1, 'test', 'test', 'test@test.com', 'ba1245564dfefce8fe16096ea04b5594145825f673f7a1dfaa', '', 1, '2020-06-16 07:59:41', '2020-06-16 06:59:07', '2020-06-16 05:59:41', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_level`
--

CREATE TABLE `user_level` (
  `id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_level`
--

INSERT INTO `user_level` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Read Only'),
(3, 'Limited');

-- --------------------------------------------------------

--
-- Structure for view `job_view`
--
DROP TABLE IF EXISTS `job_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `job_view`  AS  select `job`.`id` AS `id`,`job`.`name` AS `name`,`job`.`long_description` AS `long_description`,`job`.`estimate` AS `estimate`,`job`.`milestone_id` AS `milestone_id`,`job`.`project_id` AS `project_id`,`job`.`priority` AS `priority`,`job`.`code_base_milestone_id` AS `code_base_milestone_id`,`job`.`code_base_ticket` AS `code_base_ticket`,`job`.`client_justification` AS `client_justification`,`job`.`client_interest` AS `client_interest`,`job`.`code_base_tag` AS `code_base_tag`,`job`.`file_path` AS `file_path`,`job`.`confidence_level` AS `confidence_level`,`job`.`job_order` AS `job_order`,`job`.`create_at` AS `create_at`,`job`.`update_at` AS `update_at`,`job`.`deleted` AS `deleted`,`job`.`date_deleted` AS `date_deleted`,`milestone`.`name` AS `milestone_name`,if(`milestone`.`active` = 'Y',1,0) AS `active_milestone` from (`project` left join (`job` left join `milestone` on(`milestone`.`id` = `job`.`milestone_id`)) on(`project`.`id` = `job`.`project_id` and `project`.`deleted` = 0)) where `job`.`deleted` = 0 group by `job`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `milestone_view`
--
DROP TABLE IF EXISTS `milestone_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `milestone_view`  AS  select `milestone`.`id` AS `id`,`milestone`.`name` AS `name`,`milestone`.`goal` AS `goal`,`project`.`name` AS `project_name`,`project`.`description` AS `project_description`,`milestone`.`project_id` AS `project_id`,`milestone`.`start_date` AS `start_date`,`milestone`.`end_date` AS `end_date`,`milestone`.`completed` AS `completed`,`milestone`.`update_at` AS `update_at`,`milestone`.`story_points` AS `story_points`,to_days(`milestone`.`end_date`) - to_days(curdate()) AS `progress`,count(distinct `job`.`id`) AS `jobs` from ((`milestone` join `project` on(`milestone`.`project_id` = `project`.`id` and `project`.`deleted` = 0)) left join `job` on(`job`.`milestone_id` = `milestone`.`id`)) where `milestone`.`completed` <> 1 and `milestone`.`end_date` >= curdate() group by `milestone`.`id` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `milestone`
--
ALTER TABLE `milestone`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_name_setting_key_model_id` (`setting_key`,`model_name`,`model_id`);

--
-- Indexes for table `ticket_management`
--
ALTER TABLE `ticket_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_level`
--
ALTER TABLE `user_level`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=572;

--
-- AUTO_INCREMENT for table `milestone`
--
ALTER TABLE `milestone`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252100;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=732;

--
-- AUTO_INCREMENT for table `ticket_management`
--
ALTER TABLE `ticket_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `user_level`
--
ALTER TABLE `user_level`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
