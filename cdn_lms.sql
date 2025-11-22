-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2025 at 06:30 AM
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
-- Database: `cdn_lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `assignment_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'assignment',
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quiz_data` longtext DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `total_marks` int(11) NOT NULL DEFAULT 100,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`assignment_id`, `course_id`, `type`, `title`, `description`, `quiz_data`, `points`, `due_date`, `total_marks`, `created_at`) VALUES
(1, 2, 'assignment', 'SQL Problem', 'kiiiii', NULL, 100, '2025-10-31 23:59:00', 100, '2025-10-29 04:09:34'),
(2, 2, 'assignment', 'Make CV', 'make a curicullom vitae the follwing file is a example', NULL, 60, '2025-11-03 22:56:00', 100, '2025-10-29 14:56:59'),
(15, 2, 'assignment', 'Example ', '2 files', NULL, 100, '2025-11-04 00:39:00', 100, '2025-11-01 15:36:38'),
(17, 2, 'assignment', 'Assignment Example', 'Gawaan na', NULL, 100, '2025-11-04 23:59:00', 100, '2025-11-04 03:15:40'),
(18, 6, 'assignment', 'check the assignments', 'Hello', NULL, 50, '2025-11-02 15:00:00', 100, '2025-11-04 07:00:10'),
(19, 6, 'assignment', 'hello', 'world', NULL, 48, '2025-11-05 15:24:00', 100, '2025-11-04 07:24:48'),
(20, 6, 'assignment', 'review chapter 1', 'the following files', NULL, 20, '2025-11-07 23:59:00', 100, '2025-11-05 12:22:40'),
(21, 2, 'announcement', 'activity', 'will be posted soon', NULL, NULL, NULL, 100, '2025-11-10 13:33:14'),
(22, 2, 'announcement', 'Activity', 'make a cv the following files are example', NULL, NULL, NULL, 100, '2025-11-10 15:32:21'),
(23, 6, 'announcement', 'helo', 'hola espania', NULL, NULL, NULL, 100, '2025-11-10 17:23:07'),
(24, 6, 'activity', 'activity', 'make this', NULL, 100, '2025-11-12 11:59:00', 100, '2025-11-10 17:23:59'),
(25, 6, 'assignment', 'example ', 'if working', NULL, 100, '2025-11-14 01:29:00', 100, '2025-11-10 17:29:32'),
(26, 7, 'activity', 'hello', 'eaxample', NULL, 100, '2025-11-13 01:32:00', 100, '2025-11-10 17:32:24'),
(27, 7, 'assignment', 'eaxmple again', 'helo', NULL, 100, '2025-11-12 01:33:00', 100, '2025-11-10 17:33:52'),
(28, 7, 'assignment', 'helo working?', 'example', NULL, 100, '2025-11-13 01:37:00', 100, '2025-11-10 17:37:56'),
(29, 2, 'activity', 'smkas', 'axas', NULL, 100, '2025-11-20 12:49:00', 100, '2025-11-12 04:47:07'),
(31, 2, 'announcement', 'watch this tutorial', '<p><iframe src=\"https://www.youtube.com/embed/HGTJBPNC-Gw?si=fi5pjjUfqvvWpumW\" width=\"560\" height=\"314\" allowfullscreen=\"allowfullscreen\"></iframe></p>', NULL, NULL, NULL, 100, '2025-11-12 13:06:39'),
(32, 2, 'announcement', 'SQL', '<p><iframe src=\"https://www.youtube.com/embed/NqP0-UkIQS4?si=tCjZi-pZ_H3wEBeB\" width=\"560\" height=\"314\" allowfullscreen=\"allowfullscreen\"></iframe></p>', NULL, NULL, NULL, 100, '2025-11-12 16:18:52'),
(33, 7, 'assignment', 'sample kay mattt', '<p>gamitin ang tinyMCE</p>', NULL, 100, '2025-11-20 10:02:00', 100, '2025-11-17 02:02:15'),
(34, 6, 'activity', 'activity 3', '<p>make this as activity</p>', NULL, 100, '2025-11-22 22:39:00', 100, '2025-11-18 14:39:37'),
(35, 6, 'quiz', 'System Review', 'Complete this quiz by the due date.', '{\n \"title\": \"Quick Quiz\",\n \"description\": \"take a screenshot of you scores\",\n \"logoPosition\": \"right\",\n \"completedHtml\": \"<h3>Thank you</h3>\",\n \"pages\": [\n  {\n   \"name\": \"page1\",\n   \"elements\": [\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"question1\",\n     \"correctAnswer\": \"Item 1\",\n     \"choices\": [\n      {\n       \"value\": \"Item 1\",\n       \"text\": \"Lucky\"\n      },\n      {\n       \"value\": \"Item 2\",\n       \"text\": \"Rainsell\"\n      },\n      {\n       \"value\": \"Item 3\",\n       \"text\": \"Lux\"\n      }\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"question2\",\n     \"title\": \"Sino ang kauna-unahang bisaya noong siglo 1890\'s\",\n     \"correctAnswer\": \"Item 3\",\n     \"choices\": [\n      {\n       \"value\": \"Item 1\",\n       \"text\": \"Jambee\"\n      },\n      {\n       \"value\": \"Item 2\",\n       \"text\": \"Jambisaya\"\n      },\n      {\n       \"value\": \"Item 3\",\n       \"text\": \"Jamil\"\n      }\n     ]\n    }\n   ],\n   \"title\": \"Answer the following\"\n  }\n ],\n \"maxTimeToFinish\": 240,\n \"maxTimeToFinishPage\": 30,\n \"showTimerPanel\": \"top\",\n \"showTimerPanelMode\": \"page\",\n \"widthMode\": \"responsive\"\n}', 100, '2025-11-22 11:59:00', 100, '2025-11-19 12:39:45'),
(36, 6, 'quiz', 'General Knowledge', 'Complete this quiz by the due date.', '{\n \"title\": \"General Knowledge Quiz\",\n \"description\": \"A 20-item multiple choice quiz covering science, history, and geography.\",\n \"completedHtml\": \"<h3>tantado olollll</h3>\",\n \"pages\": [\n  {\n   \"name\": \"page1\",\n   \"elements\": [\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q1\",\n     \"title\": \"What is the capital of France?\",\n     \"correctAnswer\": \"Paris\",\n     \"choices\": [\n      \"Berlin\",\n      \"Madrid\",\n      \"Paris\",\n      \"Rome\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q2\",\n     \"title\": \"Which planet is known as the Red Planet?\",\n     \"correctAnswer\": \"Mars\",\n     \"choices\": [\n      \"Earth\",\n      \"Mars\",\n      \"Jupiter\",\n      \"Venus\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q3\",\n     \"title\": \"Who wrote \'Romeo and Juliet\'?\",\n     \"correctAnswer\": \"William Shakespeare\",\n     \"choices\": [\n      \"Charles Dickens\",\n      \"William Shakespeare\",\n      \"Mark Twain\",\n      \"Jane Austen\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q4\",\n     \"title\": \"What is the largest ocean on Earth?\",\n     \"correctAnswer\": \"Pacific Ocean\",\n     \"choices\": [\n      \"Atlantic Ocean\",\n      \"Indian Ocean\",\n      \"Arctic Ocean\",\n      \"Pacific Ocean\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q5\",\n     \"title\": \"Which element has the chemical symbol \'O\'?\",\n     \"correctAnswer\": \"Oxygen\",\n     \"choices\": [\n      \"Gold\",\n      \"Oxygen\",\n      \"Silver\",\n      \"Iron\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q6\",\n     \"title\": \"In which year did the Titanic sink?\",\n     \"correctAnswer\": \"1912\",\n     \"choices\": [\n      \"1905\",\n      \"1912\",\n      \"1918\",\n      \"1923\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q7\",\n     \"title\": \"What is the hardest natural substance on Earth?\",\n     \"correctAnswer\": \"Diamond\",\n     \"choices\": [\n      \"Gold\",\n      \"Iron\",\n      \"Diamond\",\n      \"Platinum\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q8\",\n     \"title\": \"Which country is home to the Kangaroo?\",\n     \"correctAnswer\": \"Australia\",\n     \"choices\": [\n      \"India\",\n      \"Australia\",\n      \"South Africa\",\n      \"Brazil\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q9\",\n     \"title\": \"How many continents are there on Earth?\",\n     \"correctAnswer\": \"7\",\n     \"choices\": [\n      \"5\",\n      \"6\",\n      \"7\",\n      \"8\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q10\",\n     \"title\": \"What is the boiling point of water at sea level?\",\n     \"correctAnswer\": \"100°C\",\n     \"choices\": [\n      \"50°C\",\n      \"100°C\",\n      \"150°C\",\n      \"200°C\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q11\",\n     \"title\": \"Who painted the Mona Lisa?\",\n     \"correctAnswer\": \"Leonardo da Vinci\",\n     \"choices\": [\n      \"Vincent van Gogh\",\n      \"Pablo Picasso\",\n      \"Leonardo da Vinci\",\n      \"Claude Monet\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q12\",\n     \"title\": \"What is the smallest country in the world?\",\n     \"correctAnswer\": \"Vatican City\",\n     \"choices\": [\n      \"Monaco\",\n      \"Vatican City\",\n      \"San Marino\",\n      \"Liechtenstein\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q13\",\n     \"title\": \"Which organ in the human body pumps blood?\",\n     \"correctAnswer\": \"Heart\",\n     \"choices\": [\n      \"Brain\",\n      \"Lungs\",\n      \"Heart\",\n      \"Liver\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q14\",\n     \"title\": \"What is the currency of Japan?\",\n     \"correctAnswer\": \"Yen\",\n     \"choices\": [\n      \"Yuan\",\n      \"Won\",\n      \"Yen\",\n      \"Dollar\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q15\",\n     \"title\": \"Which gas do plants absorb from the atmosphere?\",\n     \"correctAnswer\": \"Carbon Dioxide\",\n     \"choices\": [\n      \"Oxygen\",\n      \"Carbon Dioxide\",\n      \"Nitrogen\",\n      \"Hydrogen\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q16\",\n     \"title\": \"Who was the first person to step on the Moon?\",\n     \"correctAnswer\": \"Neil Armstrong\",\n     \"choices\": [\n      \"Yuri Gagarin\",\n      \"Buzz Aldrin\",\n      \"Neil Armstrong\",\n      \"Michael Collins\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q17\",\n     \"title\": \"What is the fastest land animal?\",\n     \"correctAnswer\": \"Cheetah\",\n     \"choices\": [\n      \"Lion\",\n      \"Cheetah\",\n      \"Horse\",\n      \"Leopard\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q18\",\n     \"title\": \"Which instrument measures temperature?\",\n     \"correctAnswer\": \"Thermometer\",\n     \"choices\": [\n      \"Barometer\",\n      \"Thermometer\",\n      \"Speedometer\",\n      \"Compass\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q19\",\n     \"title\": \"What is the main ingredient in guacamole?\",\n     \"correctAnswer\": \"Avocado\",\n     \"choices\": [\n      \"Tomato\",\n      \"Onion\",\n      \"Avocado\",\n      \"Pepper\"\n     ]\n    },\n    {\n     \"type\": \"radiogroup\",\n     \"name\": \"q20\",\n     \"title\": \"Which color is NOT in a rainbow?\",\n     \"correctAnswer\": \"Black\",\n     \"choices\": [\n      \"Red\",\n      \"Green\",\n      \"Black\",\n      \"Violet\"\n     ]\n    }\n   ]\n  }\n ],\n \"maxTimeToFinish\": 300,\n \"maxTimeToFinishPage\": 30,\n \"showTimerPanel\": \"top\",\n \"showTimerPanelMode\": \"page\"\n}', 40, '2025-11-29 23:59:00', 100, '2025-11-19 13:43:32');

-- --------------------------------------------------------

--
-- Table structure for table `assignment_attachments`
--

CREATE TABLE `assignment_attachments` (
  `attachment_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignment_attachments`
--

INSERT INTO `assignment_attachments` (`attachment_id`, `assignment_id`, `file_name`, `file_path`, `uploaded_at`) VALUES
(1, 15, 'Lucky_Rainsell_Lopez_CV.docx', 'uploads/assignments/materials/2/15/Lucky_Rainsell_Lopez_CV.docx', '2025-11-01 15:36:38'),
(2, 15, 'sqlPROBLEM.docx', 'uploads/assignments/materials/2/15/sqlPROBLEM.docx', '2025-11-01 15:36:38'),
(5, 17, 'Lucky_Rainsell_Lopez_CV.docx', 'uploads/assignments/materials/2/17/Lucky_Rainsell_Lopez_CV.docx', '2025-11-04 03:15:40'),
(6, 17, 'sqlPROBLEM.docx', 'uploads/assignments/materials/2/17/sqlPROBLEM.docx', '2025-11-04 03:15:40'),
(7, 18, 'activity.jpg', 'uploads/assignments/materials/6/18/activity.jpg', '2025-11-04 07:00:10'),
(8, 18, 'activitycon.jpg', 'uploads/assignments/materials/6/18/activitycon.jpg', '2025-11-04 07:00:10'),
(9, 19, '538193012_778647424569608_558135437170826032_n.jpg', 'uploads/assignments/materials/6/19/538193012_778647424569608_558135437170826032_n.jpg', '2025-11-04 07:24:48'),
(10, 19, 'activity.jpg', 'uploads/assignments/materials/6/19/activity.jpg', '2025-11-04 07:24:48'),
(11, 20, 'Chapter_1_AI_Smart_Agriculture.docx', 'uploads/assignments/materials/6/20/Chapter_1_AI_Smart_Agriculture.docx', '2025-11-05 12:22:40'),
(12, 22, 'Lucky_Rainsell_Lopez_CV.docx', 'uploads/assignments/materials/2/22/Lucky_Rainsell_Lopez_CV.docx', '2025-11-10 15:32:22'),
(13, 24, 'sqlPROBLEM.docx', 'uploads/assignments/materials/6/24/sqlPROBLEM.docx', '2025-11-10 17:23:59'),
(14, 26, 'Lucky_Rainsell_Lopez_CV.docx', 'uploads/assignments/materials/7/26/Lucky_Rainsell_Lopez_CV.docx', '2025-11-10 17:32:24'),
(15, 26, 'sqlPROBLEM.docx', 'uploads/assignments/materials/7/26/sqlPROBLEM.docx', '2025-11-10 17:32:24'),
(16, 28, 'Lucky_Rainsell_Lopez_CV.docx', 'uploads/assignments/materials/7/28/Lucky_Rainsell_Lopez_CV.docx', '2025-11-10 17:37:57'),
(17, 28, 'sqlPROBLEM.docx', 'uploads/assignments/materials/7/28/sqlPROBLEM.docx', '2025-11-10 17:37:57'),
(18, 33, 'Lucky_Rainsell_Lopez_CV.docx', 'uploads/assignments/materials/7/33/Lucky_Rainsell_Lopez_CV.docx', '2025-11-17 02:02:15'),
(19, 33, 'sqlPROBLEM.docx', 'uploads/assignments/materials/7/33/sqlPROBLEM.docx', '2025-11-17 02:02:15'),
(20, 34, 'MyEVN.pdf', 'uploads/assignments/materials/6/34/MyEVN.pdf', '2025-11-18 14:39:38');

-- --------------------------------------------------------

--
-- Table structure for table `assignment_submissions`
--

CREATE TABLE `assignment_submissions` (
  `submission_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `grade` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `quiz_result_json` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignment_submissions`
--

INSERT INTO `assignment_submissions` (`submission_id`, `assignment_id`, `student_id`, `file_path`, `submitted_at`, `grade`, `feedback`, `quiz_result_json`) VALUES
(1, 1, 1, 'uploads/assignments/submissions/2/1/f27721258856ae5aabc909f667bd48496c69747e.docx', '2025-10-29 14:13:22', 85, 'more improvements totoy', NULL),
(2, 2, 1, '[{\"file_path\":\"uploads\\/assignments\\/submissions\\/2\\/2\\/6895904b44edbdb60cf0f174fa9929388d5a98cb.docx\",\"file_name\":\"Lucky_Rainsell_Lopez_CV.docx\"},{\"file_path\":\"uploads\\/assignments\\/submissions\\/2\\/2\\/f509bad07dd6ac55a52aeb0cab9355b42612a9a5.docx\",\"file_', '2025-11-02 10:28:06', 50, 'Ayos pa totoy', NULL),
(3, 15, 1, '[{\"file_path\":\"uploads\\/assignments\\/submissions\\/2\\/15\\/c3837fc40452edf38640cbb1c4d7e07eeab97bae.docx\",\"file_name\":\"Lucky_Rainsell_Lopez_CV.docx\"},{\"file_path\":\"uploads\\/assignments\\/submissions\\/2\\/15\\/0c555426b289d169d72b47afd1557acca9bdf714.docx\",\"fil', '2025-11-03 01:22:53', 85, 'totoy galingan mo pa', NULL),
(4, 17, 1, '[{\"file_path\":\"uploads\\/assignments\\/submissions\\/2\\/17\\/c573ad54284dbf561db9ebb43dc461f858056780.docx\",\"file_name\":\"MindoroWAPCSS_Lopez - Copy.docx\"},{\"file_path\":\"uploads\\/assignments\\/submissions\\/2\\/17\\/6744e4e9dbc8b980e6bb044580a63cd5bbb7fc2c.docx\",\"', '2025-11-04 03:20:18', 70, 'Ayusi ang pasa', NULL),
(6, 18, 1, '[{\"file_path\":\"uploads\\/assignments\\/submissions\\/6\\/18\\/5f035bed651bdfe2a7328dbd2012747065839fbf.png\",\"file_name\":\"{6F4D2F99-880E-4655-9CBB-AE57A18869CC}.png\"}]', '2025-11-04 07:41:51', NULL, NULL, NULL),
(7, 35, 1, '', '2025-11-19 05:40:53', 100, 'ang galing mo totoy', '{\"question1\":\"Item 1\",\"question2\":\"Item 3\"}'),
(8, 36, 1, '', '2025-11-19 06:44:52', 10, NULL, '{\"q1\":\"Paris\",\"q2\":\"Mars\",\"q3\":\"William Shakespeare\",\"q4\":\"Pacific Ocean\",\"q5\":\"Oxygen\"}'),
(9, 35, 10, '', '2025-11-19 07:26:38', 100, NULL, '{\"question1\":\"Item 1\",\"question2\":\"Item 3\"}'),
(10, 34, 10, '[{\"file_path\":\"uploads\\/assignments\\/submissions\\/6\\/34\\/8e8b4cc72e600721100e70cbab42ff62ccdec8c8.pdf\",\"file_name\":\"LMS - Colegio de Naujan.pdf\"}]', '2025-11-19 14:27:31', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `teacher_id` int(11) NOT NULL,
  `enrollment_code` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `title`, `description`, `teacher_id`, `enrollment_code`, `created_at`, `updated_at`) VALUES
(2, 'ITP122', 'ako si sir idol nyoko', 2, '7NF8N6', '2025-10-27 13:17:18', '2025-11-03 01:29:28'),
(3, 'ITE121', 'hello idol', 2, 'FB2QQR', '2025-10-29 03:05:19', '2025-11-02 09:38:29'),
(6, 'ENG111', 'bakit naman over magpa miss?', 2, 'CCX31K', '2025-11-02 09:38:12', '2025-11-02 09:38:12'),
(7, 'Filipino', 'tagalog', 2, '6HX3ZW', '2025-11-03 01:20:25', '2025-11-03 01:20:25'),
(10, 'ITE112', 'welcome to class', 8, 'CPJ61S', '2025-11-10 13:30:42', '2025-11-10 13:30:42'),
(11, 'SOCSCI', 'Jemalene Saludo', 9, '4AJEIG', '2025-11-17 12:30:25', '2025-11-17 12:30:25'),
(12, 'ITE131', 'ma\'am jem', 9, '920B0DD4', '2025-11-18 08:40:53', '2025-11-18 08:40:53');

-- --------------------------------------------------------

--
-- Table structure for table `course_materials`
--

CREATE TABLE `course_materials` (
  `material_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_materials`
--

INSERT INTO `course_materials` (`material_id`, `course_id`, `file_name`, `file_path`, `uploaded_at`) VALUES
(1, 2, 'Lucky_Rainsell_Lopez_CV.docx', 'uploads/courses/2/materials/Lucky_Rainsell_Lopez_CV.docx', '2025-11-01 11:07:10'),
(3, 2, 'sqlPROBLEM.docx', 'uploads/courses/2/materials/sqlPROBLEM.docx', '2025-11-01 13:26:22'),
(4, 2, 'MindoroWAPCSS_Lopez - Copy.docx', 'uploads/courses/2/materials/MindoroWAPCSS_Lopez - Copy.docx', '2025-11-04 03:16:10'),
(5, 2, 'MindoroWAPCSS_Lopez - Copy1.docx', 'uploads/courses/2/materials/MindoroWAPCSS_Lopez - Copy1.docx', '2025-11-04 03:22:01'),
(6, 6, 'activitycon.jpg', 'uploads/courses/6/materials/activitycon.jpg', '2025-11-04 06:59:26'),
(7, 6, 'CONCEPTUAL FRAMEWORK.pdf', 'uploads/courses/6/materials/CONCEPTUAL FRAMEWORK.pdf', '2025-11-05 12:23:02'),
(8, 7, 'sqlPROBLEM.docx', 'uploads/courses/7/materials/sqlPROBLEM.docx', '2025-11-10 17:32:59');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enrollment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`enrollment_id`, `student_id`, `course_id`, `status`, `enrolled_at`) VALUES
(1, 1, 2, 'approved', '2025-10-27 15:01:04'),
(5, 1, 7, 'rejected', '2025-11-03 01:21:54'),
(6, 1, 6, 'approved', '2025-11-04 03:12:52'),
(7, 1, 3, 'pending', '2025-11-19 14:20:29'),
(8, 10, 6, 'approved', '2025-11-19 14:24:38');

-- --------------------------------------------------------

--
-- Table structure for table `post_replies`
--

CREATE TABLE `post_replies` (
  `reply_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_replies`
--

INSERT INTO `post_replies` (`reply_id`, `assignment_id`, `user_id`, `content`, `created_at`) VALUES
(1, 21, 2, 'sir wala pa po signal sir', '2025-11-10 13:34:02'),
(2, 21, 2, 'sir wala pa po signal sir', '2025-11-10 13:34:14'),
(3, 21, 2, 'sir wala pa po signal sir', '2025-11-10 13:34:24'),
(4, 21, 1, 'hello', '2025-11-10 13:36:25'),
(5, 21, 1, 'hello', '2025-11-10 13:45:49'),
(6, 21, 1, 'sir', '2025-11-10 13:49:10'),
(7, 21, 2, 'hello, good eve mga anak. sige next meeting nalang', '2025-11-10 15:06:53'),
(8, 25, 1, 'hi sir', '2025-11-10 17:39:56'),
(9, 22, 1, 'ayaw ko nga\n', '2025-11-10 17:40:38'),
(10, 22, 2, 'mga anak', '2025-11-12 02:20:04'),
(11, 22, 1, 'hi sir', '2025-11-12 02:20:51');

-- --------------------------------------------------------

--
-- Table structure for table `site_announcements`
--

CREATE TABLE `site_announcements` (
  `announcement_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_announcements`
--

INSERT INTO `site_announcements` (`announcement_id`, `admin_id`, `title`, `content`, `created_at`) VALUES
(1, 3, 'welcome greetings', 'welcome to cdm lms', '2025-11-10 17:55:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','teacher','admin') NOT NULL DEFAULT 'student',
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `suspension_reason` text DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `role`, `status`, `suspension_reason`, `google_id`, `created_at`, `updated_at`) VALUES
(1, 'Lucky', 'Lopez', 'luxzpole21@gmail.com', '$2y$10$yYR44x7JtL/zVBAx7Kw7W.VfyH5OmTQPHD9ZuD9Pnc.Uql8rSDPuO', 'student', 'approved', NULL, NULL, '2025-10-25 17:49:37', '2025-11-19 14:20:59'),
(2, 'Marasigan', 'Ron', 'teacher@gmail.com', '$2y$10$YpjFO9tg.AJ3QMOcZKrK.OaAymhArWRr3iNhCvzzD46osNT9Cnmlm', 'teacher', 'approved', NULL, NULL, '2025-10-25 17:51:38', '2025-11-17 02:04:41'),
(3, 'Admin', 'lopez', 'colegiodenaujan@gmail.com', '$2y$10$Iu3mOyWIYM2o.2k.ejvNy.BnUxLHQFlmBHRXGXCU2gDF6TPbzjnhS', 'admin', 'approved', NULL, NULL, '2025-10-25 17:58:56', '2025-11-08 13:39:18'),
(8, 'ron', 'marasigan', 'Ronmarasigan@gmail.com', '$2y$10$HoF8yaTkPgHJBKCU/dmQFeiM.GxF87r0XlbhuhrPvfEsrKiA2Ujbq', 'teacher', 'suspended', 'sample', NULL, '2025-11-08 11:23:44', '2025-11-19 02:32:18'),
(9, 'mia', 'casanova', 'miacasanova519@gmail.com', '$2y$10$G7oQjA7y9T1U.AwfwdEyiu.wQ2dO2fYFKFK0ZY7BF.Mh.FkUcvURu', 'teacher', 'approved', NULL, '105445938052775166349', '2025-11-17 12:29:49', '2025-11-17 13:09:43'),
(10, 'Angela', 'Lomio', 'angelalomio@gmail.com', '$2y$10$22XDujZC9hyVh2683I9NCOobq71xxCzxsOiqYsIKaWASx0fPNONgS', 'student', 'approved', NULL, NULL, '2025-11-19 14:22:39', '2025-11-19 14:22:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `fk_assignment_course` (`course_id`),
  ADD KEY `idx_type` (`type`);

--
-- Indexes for table `assignment_attachments`
--
ALTER TABLE `assignment_attachments`
  ADD PRIMARY KEY (`attachment_id`),
  ADD KEY `assignment_id` (`assignment_id`);

--
-- Indexes for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  ADD PRIMARY KEY (`submission_id`),
  ADD UNIQUE KEY `student_assignment_unique` (`student_id`,`assignment_id`),
  ADD KEY `fk_submission_assignment` (`assignment_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD UNIQUE KEY `enrollment_code` (`enrollment_code`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `course_materials`
--
ALTER TABLE `course_materials`
  ADD PRIMARY KEY (`material_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD UNIQUE KEY `student_course_status_unique` (`student_id`,`course_id`,`status`),
  ADD KEY `fk_enroll_course` (`course_id`);

--
-- Indexes for table `post_replies`
--
ALTER TABLE `post_replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `idx_assignment_id` (`assignment_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `site_announcements`
--
ALTER TABLE `site_announcements`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `assignment_attachments`
--
ALTER TABLE `assignment_attachments`
  MODIFY `attachment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  MODIFY `submission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `course_materials`
--
ALTER TABLE `course_materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `post_replies`
--
ALTER TABLE `post_replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `site_announcements`
--
ALTER TABLE `site_announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `fk_assignment_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment_attachments`
--
ALTER TABLE `assignment_attachments`
  ADD CONSTRAINT `assignment_attachments_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`assignment_id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  ADD CONSTRAINT `fk_submission_assignment` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`assignment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_submission_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `course_materials`
--
ALTER TABLE `course_materials`
  ADD CONSTRAINT `course_materials_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `fk_enroll_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_enroll_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `post_replies`
--
ALTER TABLE `post_replies`
  ADD CONSTRAINT `fk_reply_to_assignment` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`assignment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reply_to_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `site_announcements`
--
ALTER TABLE `site_announcements`
  ADD CONSTRAINT `fk_annc_to_admin` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
