-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2018 at 08:17 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exam`
--

-- --------------------------------------------------------

--
-- Table structure for table `exm_exam`
--

CREATE TABLE `exm_exam` (
  `exm_no` int(11) NOT NULL,
  `exm_sbj_code` varchar(5) DEFAULT NULL COMMENT 'Subject Code',
  `exm_crs_code` varchar(5) DEFAULT NULL COMMENT 'used to create multiple ''Quizzes''',
  `exm_year` year(4) DEFAULT NULL,
  `exm_type` char(1) DEFAULT NULL COMMENT 'M - Mid Semester\nE - End Semester',
  `exm_lec_code` varchar(10) DEFAULT NULL COMMENT 'usr_reg_no of instructor',
  `exm_duration` int(11) DEFAULT NULL,
  `exm_status` char(1) DEFAULT 'J' COMMENT 'J - Just Created\nP - Published\nC - Completed',
  `exm_edit_date` date DEFAULT NULL,
  `exm_pub_date` date DEFAULT NULL,
  `exm_due_date` date DEFAULT NULL,
  `exm_tot_marks` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exm_mcq_answer`
--

CREATE TABLE `exm_mcq_answer` (
  `mcq_exm_no` int(11) NOT NULL,
  `mcq_qst_no` int(11) NOT NULL,
  `mcq_ans_no` int(11) NOT NULL,
  `mcq_answer` text,
  `mcq_is_ans` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exm_question`
--

CREATE TABLE `exm_question` (
  `qst_exm_no` int(11) NOT NULL,
  `qst_no` int(11) NOT NULL,
  `qst_type` char(1) DEFAULT NULL COMMENT 'M - MCQ',
  `qst_marks` int(11) DEFAULT NULL,
  `qst_question` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exm_str_answer`
--

CREATE TABLE `exm_str_answer` (
  `str_exm_no` int(11) NOT NULL,
  `str_qst_no` int(11) NOT NULL,
  `str_answer` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exm_take`
--

CREATE TABLE `exm_take` (
  `tke_std_id` varchar(10) NOT NULL,
  `tke_exm_no` int(11) NOT NULL,
  `tke_start_time` timestamp NULL DEFAULT NULL,
  `tke_end_time` timestamp NULL DEFAULT NULL,
  `tke_tot_marks` int(11) DEFAULT NULL,
  `tke_mcq_marks` int(11) DEFAULT NULL,
  `tke_str_marks` int(11) DEFAULT NULL,
  `tke_percentage` int(11) DEFAULT NULL,
  `tke_exm_status` char(1) DEFAULT 'N' COMMENT 'N - Not Evaluated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exm_tke_answer`
--

CREATE TABLE `exm_tke_answer` (
  `tke_std_id` varchar(10) NOT NULL,
  `tke_exm_no` int(11) NOT NULL,
  `tke_qst_psudo_no` int(11) NOT NULL,
  `tke_qst_no` int(11) DEFAULT NULL,
  `tke_qst_answer` text,
  `tke_marks` int(11) DEFAULT NULL,
  `tke_eval_status` char(1) DEFAULT 'N' COMMENT 'N - Not Evaluated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `globals`
--

CREATE TABLE `globals` (
  `gbl_year` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `globals`
--

INSERT INTO `globals` (`gbl_year`) VALUES
(2014);

-- --------------------------------------------------------

--
-- Table structure for table `instructor_subject`
--

CREATE TABLE `instructor_subject` (
  `ins_id` varchar(10) NOT NULL,
  `ins_sbj_code` varchar(5) NOT NULL,
  `ins_crs_code` varchar(5) NOT NULL,
  `ins_year` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sbj_course`
--

CREATE TABLE `sbj_course` (
  `crs_code` varchar(5) NOT NULL,
  `crs_fld_code` int(11) NOT NULL,
  `crs_name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sbj_course`
--

INSERT INTO `sbj_course` (`crs_code`, `crs_fld_code`, `crs_name`) VALUES
('BOT01', 1, 'BSc General Degree in Botany'),
('BOT02', 1, 'BSc Special Degree in Botany'),
('CHE01', 2, 'BSc General Degree in Chemistry'),
('CHE02', 2, 'BSc Special Degree in Chemistry'),
('CMP01', 3, 'BSc General  Degree in Computer Science'),
('CMP02', 3, 'BSc General in Degree Information Technology'),
('CMP03', 3, 'BSc Special Degree in Computer Science'),
('CMP04', 3, 'BSc Special Degree in Information Technology'),
('GEO01', 4, 'BSc General  Degree in Geology'),
('GEO02', 4, 'BSc Special Degree in Geology'),
('MAT01', 5, 'BSc General  Degree in Mathematics'),
('MAT02', 5, 'BSc Special Degree in Mathematics'),
('MCB01', 6, 'BSc General  Degree in Moleculer Biology'),
('MCB02', 6, 'BSc Special Degree in Moleculer Biology'),
('PHY01', 7, 'BSc General  Degree in Physics'),
('PHY02', 7, 'BSc Special Degree in Physics'),
('STA01', 8, 'BSc General  Degree in Statistics'),
('STA02', 8, 'BSc Special Degree in Statistics'),
('ZLG01', 9, 'BSc General  Degree in Zoology'),
('ZLG02', 9, 'BSc Special Degree in Zoology');

-- --------------------------------------------------------

--
-- Table structure for table `sbj_crs_sbj`
--

CREATE TABLE `sbj_crs_sbj` (
  `csb_crs_code` varchar(5) NOT NULL,
  `csb_sbj_code` varchar(5) NOT NULL,
  `csb_semester` int(11) NOT NULL,
  `csb_year` year(4) NOT NULL,
  `csb_compulsory` tinyint(1) DEFAULT NULL COMMENT 'True - Compulsory'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sbj_crs_sbj`
--

INSERT INTO `sbj_crs_sbj` (`csb_crs_code`, `csb_sbj_code`, `csb_semester`, `csb_year`, `csb_compulsory`) VALUES
('BOT01', 'BT501', 1, 2014, 1),
('BOT01', 'BT502', 1, 2014, 1),
('BOT01', 'BT503', 1, 2014, 1),
('BOT01', 'BT504', 1, 2014, 1),
('BOT01', 'BT505', 1, 2014, 1),
('BOT01', 'BT506', 1, 2014, 1),
('BOT01', 'BT516', 2, 2014, 0),
('BOT01', 'BT517', 2, 2014, 0),
('BOT01', 'BT519', 2, 2014, 0),
('BOT01', 'BT520', 2, 2014, 0),
('BOT01', 'BT521', 2, 2014, 0),
('BOT01', 'BT522', 2, 2014, 0),
('BOT01', 'BT526', 2, 2014, 0),
('CHE01', 'CH501', 1, 2014, 1),
('CHE01', 'CH502', 1, 2014, 1),
('CHE01', 'CH503', 1, 2014, 1),
('CHE01', 'CH504', 1, 2014, 1),
('CHE01', 'CH511', 1, 2014, 1),
('CHE01', 'CH516', 2, 2014, 1),
('CHE01', 'CH517', 2, 2014, 1),
('CHE01', 'CH518', 2, 2014, 0),
('CHE01', 'CH519', 2, 2014, 0),
('CHE01', 'CH526', 2, 2014, 1),
('CMP01', 'SC531', 1, 2014, 0),
('CMP01', 'SC532', 1, 2014, 0),
('CMP01', 'SC533', 1, 2014, 0),
('CMP01', 'SC534', 1, 2014, 0),
('CMP01', 'SC535', 1, 2014, 1),
('CMP01', 'SC536', 1, 2014, 0),
('CMP01', 'SC537', 1, 2014, 1),
('CMP01', 'SC538', 1, 2014, 1),
('CMP01', 'SC539', 1, 2014, 1),
('CMP01', 'SC546', 2, 2014, 1),
('CMP01', 'SC547', 2, 2014, 0),
('CMP01', 'SC548', 2, 2014, 0),
('CMP01', 'SC549', 2, 2014, 0),
('CMP01', 'SC550', 2, 2014, 0),
('CMP01', 'SC551', 2, 2014, 0),
('CMP01', 'SC552', 2, 2014, 0),
('CMP01', 'SC553', 2, 2014, 0),
('CMP01', 'SC554', 2, 2014, 1),
('GEO01', 'ES531', 1, 2014, 1),
('GEO01', 'ES532', 1, 2014, 1),
('GEO01', 'ES533', 1, 2014, 1),
('GEO01', 'ES534', 1, 2014, 1),
('GEO01', 'ES535', 1, 2014, 1),
('GEO01', 'ES536', 1, 2014, 0),
('GEO01', 'ES537', 1, 2014, 0),
('GEO01', 'ES538', 1, 2014, 0),
('GEO01', 'ES546', 2, 2014, 1),
('GEO01', 'ES547', 2, 2014, 1),
('GEO01', 'ES548', 2, 2014, 1),
('GEO01', 'ES549', 2, 2014, 1),
('GEO01', 'ES550', 2, 2014, 1),
('GEO01', 'ES551', 2, 2014, 0),
('GEO01', 'ES552', 2, 2014, 0),
('GEO01', 'ES553', 2, 2014, 0),
('MAT01', 'MT501', 1, 2014, 1),
('MAT01', 'MT502', 1, 2014, 1),
('MAT01', 'MT503', 1, 2014, 1),
('MAT01', 'MT504', 1, 2014, 0),
('MAT01', 'MT505', 1, 2014, 1),
('MAT01', 'MT516', 2, 2014, 0),
('MAT01', 'MT517', 2, 2014, 1),
('MAT01', 'MT518', 2, 2014, 0),
('MAT01', 'MT519', 2, 2014, 0),
('MAT01', 'MT520', 2, 2014, 0),
('PHY01', 'PH500', 1, 2014, 1),
('PHY01', 'PH501', 1, 2014, 1),
('PHY01', 'PH502', 1, 2014, 1),
('PHY01', 'PH503', 1, 2014, 1),
('PHY01', 'PH504', 1, 2014, 1),
('PHY01', 'PH505', 1, 2014, 1),
('PHY01', 'PH506', 1, 2014, 1),
('PHY01', 'PH507', 1, 2014, 1),
('PHY01', 'PH516', 2, 2014, 1),
('PHY01', 'PH517', 2, 2014, 0),
('PHY01', 'PH518', 2, 2014, 0),
('PHY01', 'PH519', 2, 2014, 0),
('PHY01', 'PH520', 2, 2014, 0),
('PHY01', 'PH521', 2, 2014, 0),
('PHY01', 'PH522', 2, 2014, 0),
('PHY01', 'PH523', 2, 2014, 0),
('STA01', 'ST501', 1, 2014, 1),
('STA01', 'ST502', 1, 2014, 1),
('STA01', 'ST503', 1, 2014, 1),
('STA01', 'ST504', 1, 2014, 1),
('STA01', 'ST505', 1, 2014, 1),
('STA01', 'ST506', 1, 2014, 0),
('STA01', 'ST507', 1, 2014, 0),
('STA01', 'ST516', 2, 2014, 1),
('STA01', 'ST517', 2, 2014, 1),
('STA01', 'ST518', 2, 2014, 1),
('STA01', 'ST519', 2, 2014, 0),
('STA01', 'ST520', 2, 2014, 0),
('STA01', 'ST521', 2, 2014, 0),
('STA01', 'ST522', 2, 2014, 0),
('STA01', 'ST523', 2, 2014, 0),
('STA01', 'ST524', 2, 2014, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sbj_field`
--

CREATE TABLE `sbj_field` (
  `fld_code` int(11) NOT NULL,
  `fld_name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sbj_field`
--

INSERT INTO `sbj_field` (`fld_code`, `fld_name`) VALUES
(1, 'Botany'),
(2, 'Chemistry'),
(3, 'Computer Science'),
(4, 'Geology'),
(5, 'Mathematics'),
(6, 'Moleculer Biology'),
(7, 'Physics'),
(8, 'Statistics'),
(9, 'Zoology');

-- --------------------------------------------------------

--
-- Table structure for table `sbj_subject`
--

CREATE TABLE `sbj_subject` (
  `sbj_code` varchar(5) NOT NULL,
  `sbj_name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sbj_subject`
--

INSERT INTO `sbj_subject` (`sbj_code`, `sbj_name`) VALUES
('BT501', 'Genetics and Molecular Biology'),
('BT502', 'Sri Lankan Flora'),
('BT503', 'Plant Systematics and Biogeography'),
('BT504', 'Plant Ecology'),
('BT505', 'Basic Microbiology'),
('BT506', 'Advanced Plant Physiology and Biochemistry'),
('BT516', 'Industrial Microbiology'),
('BT517', 'Plant Pathology'),
('BT519', 'Toxins of Plant and Microbial Origin and thei'),
('BT520', 'Environmental Pollution and its Control'),
('BT521', 'Biodiversity Conservation and Management'),
('BT522', 'Advanced Systematics'),
('BT526', 'Remote Sensing and Geographic Information Sys'),
('CH501', 'Fundamentals of Analytical Method'),
('CH502', 'Instrumental Analysis'),
('CH503', 'Spectroscopic Methods'),
('CH504', 'Environmantal Analytical Chemistry'),
('CH511', 'Advanced Analytical Chemistry'),
('CH516', 'Analytical Seperations'),
('CH517', 'Electroanalytical Chemistry'),
('CH518', 'Special Topics in Analytical Chemistry I'),
('CH519', 'Special Topics in Analytical Chemistry II'),
('CH526', 'Advanced Analytical Chemistry'),
('ES531', 'Basic Geology'),
('ES532', 'Basic Mechanics'),
('ES533', 'Fundamentals of Hydrogeology'),
('ES534', 'Fundamentals of Engineering Geology'),
('ES535', 'Site Investigation'),
('ES536', 'Rock Mechanics'),
('ES537', 'Soil Mechanics'),
('ES538', 'Photo Geology and Remote Sensing'),
('ES546', 'Application of Engineering Geology'),
('ES547', 'Applied Hydrogeology'),
('ES548', 'Hydrochemistry and Water Quality'),
('ES549', 'Computer Software Applications'),
('ES550', 'Applied Geophysics'),
('ES551', 'Tunnelling and Underground Excavation'),
('ES552', 'Landslides and Stability of Slopes'),
('ES553', 'Environmantal Geology'),
('MT501', 'Differental Equations'),
('MT502', 'Statistical Quality Control'),
('MT503', 'Numerical Analystis'),
('MT504', 'Stochastic Process and Applications'),
('MT505', 'Operations Research'),
('MT516', 'Contro Theory'),
('MT517', 'Topics in Computer Science'),
('MT518', 'Optimization Theory'),
('MT519', 'Special Topica in Industrial Mathematics'),
('MT520', 'Theoritical Fluid Mathematics'),
('PH500', 'Mathematical Methods and Computational Method'),
('PH501', 'Quamtum Mechanics and Statistical Physics'),
('PH502', 'Electron Theory of Solids'),
('PH503', 'Structures and Properties of Solids, Phase Eq'),
('PH504', 'Semiconductors'),
('PH505', 'Ceramic Materials'),
('PH506', 'Polymers'),
('PH507', 'Solid State Ionic Meterials'),
('PH516', 'Materials Characterization Techniques'),
('PH517', 'Magnetic Materials and Superconductor Materia'),
('PH518', 'Glass and Glass Ceramics'),
('PH519', 'Semiconductors Device Technology'),
('PH520', 'Industrial Ceramics'),
('PH521', 'Nuclear Materials'),
('PH522', 'Metals and Alloys'),
('PH523', 'Introduction to Nanotechnology'),
('SC531', 'Database Systems'),
('SC532', 'Combinatorial Mathematics'),
('SC533', 'Introduction to Parallel Computing'),
('SC534', 'Programming Language Design and Compilers'),
('SC535', 'Operating System Design'),
('SC536', 'Graph Theory'),
('SC537', 'Computer Networks and Distributed Systems'),
('SC538', 'Artificial Intelligence'),
('SC539', 'Advanced Topics in Computer Graphics'),
('SC546', 'Software Engineering'),
('SC547', 'Computer Architecture'),
('SC548', 'Systems Analysis'),
('SC549', 'Artificial Nural Networks'),
('SC550', 'Linear Programming'),
('SC551', 'Communication networks for Computers'),
('SC552', 'Digital Image Processing'),
('SC553', 'Project Management'),
('SC554', 'Embedded Systems'),
('ST501', 'Theory of Statistics'),
('ST502', 'Data Analysis and Presentation'),
('ST503', 'Design and Analysis of Experiments'),
('ST504', 'Regression Analysis'),
('ST505', 'Sampling Techniques'),
('ST506', 'Multivariate Methods I'),
('ST507', 'Stochastic Process and Applications'),
('ST516', 'Time Series Analysis'),
('ST517', 'Non-Parametrics and Categorical Data Analysis'),
('ST518', 'Independant Study'),
('ST519', 'Multivariate Methods II'),
('ST520', 'Experimental Techniques'),
('ST521', 'Biased Estimation'),
('ST522', 'Binary Data Analysis'),
('ST523', 'Quality Control Statistics'),
('ST524', 'Special Topics');

-- --------------------------------------------------------

--
-- Table structure for table `student_subject`
--

CREATE TABLE `student_subject` (
  `std_id` varchar(10) NOT NULL,
  `std_crs_code` varchar(5) NOT NULL,
  `std_sbj_code` varchar(5) NOT NULL,
  `std_year` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `usr_marital`
--

CREATE TABLE `usr_marital` (
  `mtl_code` char(1) NOT NULL,
  `mtl_desc` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usr_marital`
--

INSERT INTO `usr_marital` (`mtl_code`, `mtl_desc`) VALUES
('D', 'Divorsed'),
('M', 'Married'),
('S', 'Single'),
('W', 'Widowed');

-- --------------------------------------------------------

--
-- Table structure for table `usr_title`
--

CREATE TABLE `usr_title` (
  `tit_code` int(11) NOT NULL,
  `tit_short` varchar(45) DEFAULT NULL,
  `tit_desc` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usr_title`
--

INSERT INTO `usr_title` (`tit_code`, `tit_short`, `tit_desc`) VALUES
(0, NULL, 'Student'),
(1, 'Prof.', 'Professor'),
(2, 'Asso. Prof.', 'Associate Professor'),
(3, 'SL I', 'Senior Lecturer I'),
(4, 'SL II', 'Senior Lecturer II'),
(5, 'Lec', 'Lecturer'),
(6, 'Asso. Lec.', 'Associate Lecturer');

-- --------------------------------------------------------

--
-- Table structure for table `usr_user`
--

CREATE TABLE `usr_user` (
  `usr_nic` varchar(10) NOT NULL,
  `usr_reg_no` varchar(10) DEFAULT NULL,
  `usr_password` varchar(32) DEFAULT NULL,
  `usr_title` int(11) DEFAULT NULL COMMENT 'Title\n\n0 - Student\n1 - Prof.\n2 - Asso. Prof.\n3 - SL I\n4 - SL II\n5 - Lec',
  `usr_first_name` varchar(45) DEFAULT NULL,
  `usr_middle_name` varchar(45) DEFAULT NULL,
  `usr_last_name` varchar(45) DEFAULT NULL,
  `usr_gender` char(1) DEFAULT NULL COMMENT 'M - Male',
  `usr_dob` date DEFAULT NULL,
  `usr_marital` char(1) DEFAULT NULL COMMENT 'Marital Status\n\nS - Single\nM - Married\nW - Widowd',
  `usr_email` varchar(45) DEFAULT NULL,
  `usr_type` char(1) DEFAULT NULL COMMENT 'User Type\n\nS - Student\nI - Instructor',
  `usr_approved` char(1) DEFAULT 'N' COMMENT 'Approval\n\nY - Yes',
  `usr_rec_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usr_user`
--

INSERT INTO `usr_user` (`usr_nic`, `usr_reg_no`, `usr_password`, `usr_title`, `usr_first_name`, `usr_middle_name`, `usr_last_name`, `usr_gender`, `usr_dob`, `usr_marital`, `usr_email`, `usr_type`, `usr_approved`, `usr_rec_date`) VALUES
('000000000V', '0000000000', '43e9a4ab75570f5b', NULL, 'System', 'Global', 'Administrator', 'M', '2014-06-17', 'S', 'admin@summit.com', 'A', 'Y', '2014-06-17 10:25:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exm_exam`
--
ALTER TABLE `exm_exam`
  ADD PRIMARY KEY (`exm_no`),
  ADD KEY `fk_exm_exam_usr_user1` (`exm_lec_code`),
  ADD KEY `fk_exm_exam_GLOBALS1` (`exm_year`);

--
-- Indexes for table `exm_mcq_answer`
--
ALTER TABLE `exm_mcq_answer`
  ADD PRIMARY KEY (`mcq_exm_no`,`mcq_qst_no`,`mcq_ans_no`);

--
-- Indexes for table `exm_question`
--
ALTER TABLE `exm_question`
  ADD PRIMARY KEY (`qst_exm_no`,`qst_no`);

--
-- Indexes for table `exm_str_answer`
--
ALTER TABLE `exm_str_answer`
  ADD PRIMARY KEY (`str_exm_no`,`str_qst_no`);

--
-- Indexes for table `exm_take`
--
ALTER TABLE `exm_take`
  ADD PRIMARY KEY (`tke_std_id`,`tke_exm_no`),
  ADD KEY `fk_exm_take_exm_exam1` (`tke_exm_no`);

--
-- Indexes for table `exm_tke_answer`
--
ALTER TABLE `exm_tke_answer`
  ADD PRIMARY KEY (`tke_std_id`,`tke_exm_no`,`tke_qst_psudo_no`);

--
-- Indexes for table `globals`
--
ALTER TABLE `globals`
  ADD PRIMARY KEY (`gbl_year`);

--
-- Indexes for table `instructor_subject`
--
ALTER TABLE `instructor_subject`
  ADD PRIMARY KEY (`ins_id`,`ins_sbj_code`,`ins_crs_code`,`ins_year`),
  ADD KEY `fk_instructor_subject_globals1` (`ins_year`);

--
-- Indexes for table `sbj_course`
--
ALTER TABLE `sbj_course`
  ADD PRIMARY KEY (`crs_code`,`crs_fld_code`),
  ADD KEY `fk_sbj_course_sbj_field1` (`crs_fld_code`);

--
-- Indexes for table `sbj_crs_sbj`
--
ALTER TABLE `sbj_crs_sbj`
  ADD PRIMARY KEY (`csb_crs_code`,`csb_sbj_code`,`csb_semester`,`csb_year`),
  ADD KEY `fk_sbj_crs_sbj_sbj_subject1` (`csb_sbj_code`),
  ADD KEY `fk_sbj_crs_sbj_GLOBALS1` (`csb_year`);

--
-- Indexes for table `sbj_field`
--
ALTER TABLE `sbj_field`
  ADD PRIMARY KEY (`fld_code`);

--
-- Indexes for table `sbj_subject`
--
ALTER TABLE `sbj_subject`
  ADD PRIMARY KEY (`sbj_code`);

--
-- Indexes for table `student_subject`
--
ALTER TABLE `student_subject`
  ADD PRIMARY KEY (`std_id`,`std_crs_code`,`std_sbj_code`,`std_year`),
  ADD KEY `fk_student_subject_globals1` (`std_year`);

--
-- Indexes for table `usr_marital`
--
ALTER TABLE `usr_marital`
  ADD PRIMARY KEY (`mtl_code`);

--
-- Indexes for table `usr_title`
--
ALTER TABLE `usr_title`
  ADD PRIMARY KEY (`tit_code`);

--
-- Indexes for table `usr_user`
--
ALTER TABLE `usr_user`
  ADD PRIMARY KEY (`usr_nic`),
  ADD UNIQUE KEY `usr_nic_UNIQUE` (`usr_nic`),
  ADD UNIQUE KEY `usr_reg_no_UNIQUE` (`usr_reg_no`),
  ADD KEY `fk_usr_user_usr_marital` (`usr_marital`),
  ADD KEY `fk_usr_user_usr_title1` (`usr_title`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exm_exam`
--
ALTER TABLE `exm_exam`
  MODIFY `exm_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `exm_exam`
--
ALTER TABLE `exm_exam`
  ADD CONSTRAINT `fk_exm_exam_GLOBALS1` FOREIGN KEY (`exm_year`) REFERENCES `globals` (`gbl_year`),
  ADD CONSTRAINT `fk_exm_exam_usr_user1` FOREIGN KEY (`exm_lec_code`) REFERENCES `usr_user` (`usr_nic`);

--
-- Constraints for table `exm_question`
--
ALTER TABLE `exm_question`
  ADD CONSTRAINT `fk_exm_question_exm_exam1` FOREIGN KEY (`qst_exm_no`) REFERENCES `exm_exam` (`exm_no`);

--
-- Constraints for table `exm_take`
--
ALTER TABLE `exm_take`
  ADD CONSTRAINT `fk_exm_take_exm_exam1` FOREIGN KEY (`tke_exm_no`) REFERENCES `exm_exam` (`exm_no`),
  ADD CONSTRAINT `fk_exm_take_usr_user1` FOREIGN KEY (`tke_std_id`) REFERENCES `usr_user` (`usr_nic`);

--
-- Constraints for table `instructor_subject`
--
ALTER TABLE `instructor_subject`
  ADD CONSTRAINT `fk_instructor_subject_globals1` FOREIGN KEY (`ins_year`) REFERENCES `globals` (`gbl_year`),
  ADD CONSTRAINT `fk_instructor_subject_usr_user1` FOREIGN KEY (`ins_id`) REFERENCES `usr_user` (`usr_nic`);

--
-- Constraints for table `sbj_course`
--
ALTER TABLE `sbj_course`
  ADD CONSTRAINT `fk_sbj_course_sbj_field1` FOREIGN KEY (`crs_fld_code`) REFERENCES `sbj_field` (`fld_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `sbj_crs_sbj`
--
ALTER TABLE `sbj_crs_sbj`
  ADD CONSTRAINT `fk_sbj_crs_sbj_GLOBALS1` FOREIGN KEY (`csb_year`) REFERENCES `globals` (`gbl_year`),
  ADD CONSTRAINT `fk_sbj_crs_sbj_sbj_course1` FOREIGN KEY (`csb_crs_code`) REFERENCES `sbj_course` (`crs_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_sbj_crs_sbj_sbj_subject1` FOREIGN KEY (`csb_sbj_code`) REFERENCES `sbj_subject` (`sbj_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `student_subject`
--
ALTER TABLE `student_subject`
  ADD CONSTRAINT `fk_student_subject_globals1` FOREIGN KEY (`std_year`) REFERENCES `globals` (`gbl_year`),
  ADD CONSTRAINT `fk_student_subject_usr_user1` FOREIGN KEY (`std_id`) REFERENCES `usr_user` (`usr_nic`);

--
-- Constraints for table `usr_user`
--
ALTER TABLE `usr_user`
  ADD CONSTRAINT `fk_usr_user_usr_marital` FOREIGN KEY (`usr_marital`) REFERENCES `usr_marital` (`mtl_code`),
  ADD CONSTRAINT `fk_usr_user_usr_title1` FOREIGN KEY (`usr_title`) REFERENCES `usr_title` (`tit_code`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `event_complete_exam` ON SCHEDULE EVERY 1 DAY STARTS '2014-07-12 00:00:00' ON COMPLETION PRESERVE ENABLE COMMENT 'Change exm_status to C - Completed' DO UPDATE exm_exam
SET exm_status = 'C',
	exm_edit_date = NOW()
WHERE
	exm_due_date < NOW()
    AND exm_status = 'P'$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
