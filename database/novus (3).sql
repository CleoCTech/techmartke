-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 04, 2025 at 09:33 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `novus`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `is_approver` tinyint(1) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `uuid`, `name`, `email`, `role`, `is_approver`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '1e7dfcd1-685b-4fda-b611-1aad1d2ecd7d', 'Administrator', 'admin@novustechhub.com', 1, 0, 1, NULL, NULL, NULL, NULL),
(2, '12f60ba6-53d8-4bef-8acb-96f258f335d7', 'Wenla', 'admin@wenlasystems.com', 1, 0, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `table_id` varchar(255) NOT NULL,
  `record_id` varchar(255) NOT NULL,
  `storageName` varchar(255) NOT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`id`, `uuid`, `filename`, `description`, `table_id`, `record_id`, `storageName`, `is_archived`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '0bfd75fb-df7f-4516-8c47-eef0d25085cf', 'FAQ-Dr.Makis_1749354868.pdf', 'Application Form', 'undefined', '400b050a-8b2e-4283-89ae-604b6656fd2d', 'attachments', 0, 'admin@novustechhub.com', NULL, '2025-06-08 00:54:32', '2025-06-08 00:54:32');

-- --------------------------------------------------------

--
-- Table structure for table `awards`
--

CREATE TABLE `awards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date_received` date NOT NULL,
  `awarding_body` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `publish_time` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_information`
--

CREATE TABLE `company_information` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `establishment_date` date DEFAULT NULL,
  `history` mediumtext DEFAULT NULL,
  `highlight` varchar(3000) DEFAULT NULL,
  `vision` mediumtext DEFAULT NULL,
  `mission` mediumtext DEFAULT NULL,
  `motto` varchar(255) DEFAULT NULL,
  `core_values` mediumtext DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `about_short` varchar(1000) DEFAULT NULL,
  `total_people_helped` int(11) NOT NULL DEFAULT 0,
  `programs_available` bigint(20) DEFAULT NULL,
  `job_placement_rate` bigint(20) DEFAULT NULL,
  `about` mediumtext DEFAULT NULL,
  `about_newsletter` varchar(255) DEFAULT NULL,
  `emails` varchar(255) NOT NULL,
  `branches` varchar(255) DEFAULT NULL,
  `total_members` varchar(255) NOT NULL,
  `phone_numbers` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 2,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_information`
--

INSERT INTO `company_information` (`id`, `uuid`, `company_name`, `short_name`, `establishment_date`, `history`, `highlight`, `vision`, `mission`, `motto`, `core_values`, `location`, `about_short`, `total_people_helped`, `programs_available`, `job_placement_rate`, `about`, `about_newsletter`, `emails`, `branches`, `total_members`, `phone_numbers`, `address`, `logo`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '400b050a-8b2e-4283-89ae-604b6656fd2d', 'Novus Computer Training Institute', 'Novus', '2016-07-07', '<p>Founded in 2017, Novus Institute of Technology emerged from a vision to bridge the gap between traditional education and the rapidly evolving technology landscape. Our founders, a group of industry veterans and academic pioneers, recognized the need for an institution that could prepare students for the challenges of tomorrow.</p><p>The name \"Novus\" means \"new\" in Latin, reflecting our commitment to innovation and fresh approaches to learning. We believe that education should not just transfer knowledge, but inspire creativity, foster critical thinking, and nurture the entrepreneurial spirit.</p><p>Our inspiration comes from the belief that technology, when guided by ethical principles and human values, can solve the world\'s greatest challenges. We strive to create not just skilled professionals, but responsible leaders who will shape a better future.</p>', '<p>Shaping Tomorrow\'s Technology Leaders Through Innovation, Excellence, and Practical Learning</p>', 'Our vision is to be a leading institution in computer science and technology education, renowned for our commitment to excellence, innovation, and inclusivity. We aspire to create a dynamic learning environment that fosters creativity, encourages collaboration, and prepares students to thrive in a rapidly evolving digital world. Through cutting-edge research, industry partnerships, and community engagement, we aim to shape the future of technology, inspire the next generation of tech leaders, and make a positive impact on society.', 'Our mission is to provide a transformative educational experience that nurtures creativity, fosters innovation, and promotes ethical practices in the field of computer science. We strive to cultivate a diverse and inclusive learning environment, equipping students with the skills and knowledge necessary to excel in the ever-evolving tech landscape. Through a commitment to excellence in teaching, research, and community engagement, we aim to inspire our students to make meaningful contributions to the world and to drive positive change in the digital age.', 'Coding the Future Today, Byte by Byte', '<ul><li><strong>Humility-</strong>&nbsp;Embracing a mindset of continuous learning and growth, acknowledging the contributions of others, and maintaining a genuine respect for diverse perspectives and experiences. We recognize that true progress comes from collaboration, openness, and the willingness to learn from mistakes and successes alike. By cultivating humility, we foster an environment where everyone feels valued and empowered to contribute to the collective advancement of knowledge and innovation. Humility is our powerful core value that promotes a culture of mutual respect and continuous improvement.</li><li><strong>Innovation</strong>: Embracing creativity and technological advancements to drive progress and solve complex problems.</li><li><strong>Excellence</strong>: Maintaining the highest standards in education, research, and professional practice.</li><li><strong>Integrity</strong>: Upholding ethical principles and honesty in all academic, research, and professional endeavors.</li><li><strong>Inclusivity</strong>: Fostering a diverse and welcoming environment where everyone is respected and valued.</li><li><strong>Collaboration</strong>: Promoting teamwork and partnership among students, faculty, industry, and the community.</li><li><strong>Lifelong Learning</strong>: Encouraging continuous personal and professional growth through education and development.</li><li><strong>Responsibility</strong>: Committing to the responsible use of technology for the betterment of society and the environment.</li><li><strong>Empowerment</strong>: Equipping students with the knowledge, skills, and confidence to succeed and lead in the tech industry.</li></ul>', 'Nakuru,Kenya', 'Shaping Tomorrow\'s Technology Leaders Through Innovation, Excellence, and Practical Learning', 0, 8, 95, '<p>Founded in 2017, Novus Institute of Technology emerged from a vision to bridge the gap between traditional education and the rapidly evolving technology landscape. Our founders, a group of industry veterans and academic pioneers, recognized the need for an institution that could prepare students for the challenges of tomorrow.</p><p>The name \"Novus\" means \"new\" in Latin, reflecting our commitment to innovation and fresh approaches to learning. We believe that education should not just transfer knowledge, but inspire creativity, foster critical thinking, and nurture the entrepreneurial spirit.</p><p>Our inspiration comes from the belief that technology, when guided by ethical principles and human values, can solve the world\'s greatest challenges. We strive to create not just skilled professionals, but responsible leaders who will shape a better future.</p>', 'Subscribe Our Newsletter To Get Latest Update And News', 'info@novustechhub.com', '8', '100', '0724 301 007 / 0791 675 898', '661-00300 Nakuru', 'null', 2, NULL, 'admin@novustechhub.com', NULL, '2025-06-04 04:23:10');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `projects` int(11) DEFAULT NULL,
  `students` int(11) DEFAULT NULL,
  `technologies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`technologies`)),
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `uuid`, `title`, `image`, `duration`, `level`, `description`, `projects`, `students`, `technologies`, `status`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, '1bad466d-a52b-45da-ab51-03582f2acd2e', 'Full Stack Web Development', 'FS_1749045139.jpg', '6 months', 'Beginner to Advanced', '<p>Master modern web development with hands-on projects and industry mentorship.</p>', 5, 21, '[\"React\",\" Vue\",\" Laravel\",\" Node.js\",\" MySQL\"]', '2', '2025-06-04 10:52:20', '2025-06-04 10:52:20', 'admin@novustechhub.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `uuid`, `name`, `description`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'beea1e5e-f31e-45c6-91f6-1744069ac0be', 'FIRST TIME COMERS DEPARTMENT', 'Making a weekly follow up in the churches to see if there was any first time comers.', 1, 'admin@divinelycalledministers.com', 'admin@divinelycalledministers.com', '2025-02-10 01:17:33', '2025-02-10 01:20:23'),
(2, '5227c0ee-22cb-4a31-9333-57ab4af88810', 'WELFARE DEPARTMENT', '. Looking after the welfare of ministers and members alike.  . Orphanage and widows  . Raising funds to support ministers and members in time of need . Representing the ministry comfort , show solidarity during various ceremonies in the community.', 1, 'admin@divinelycalledministers.com', NULL, '2025-02-10 01:18:28', '2025-02-10 01:18:28'),
(3, '12420b48-0616-42a6-9ec6-f6e241cf333c', 'EVANGELISM AND FOLLOW UP DEPARTMENT', 'See to it that all churches run an effective evangelism on a weekly basis', 1, 'admin@divinelycalledministers.com', NULL, '2025-02-10 01:19:19', '2025-02-10 01:19:19'),
(4, 'b3b7c04a-9a9f-441b-9cf4-9ab33a928b23', 'FINANCE DEPARTMENT', 'In charge of the church finances', 1, 'admin@divinelycalledministers.com', NULL, '2025-02-10 01:19:59', '2025-02-10 01:19:59'),
(5, '6e3ba174-31fa-490b-bc0e-f8ceb32edc5f', 'TESTIMONY DEPARTMENT', 'Encouraging each church to create time in their church weekly for the members to share their testimonies.', 1, 'admin@divinelycalledministers.com', NULL, '2025-02-10 01:20:57', '2025-02-10 01:20:57'),
(6, '0de3f834-b1af-4253-a894-0cf38bf18bfd', 'WORSHIP AND PRAISES DEPARTMENT', 'Establishing worship and praises team in each church and working with the various ministers to build the gift in the team.', 1, 'admin@divinelycalledministers.com', NULL, '2025-02-10 01:22:02', '2025-02-10 01:22:02'),
(7, '0e7d8feb-b420-4b5b-ae8a-2b16a429d7d7', 'USHERING DEPARTMENT', 'Making both new and old members feel welcome in the church always by assisting and supporting them in the church with various assistance', 1, 'admin@divinelycalledministers.com', NULL, '2025-02-10 01:22:30', '2025-02-10 01:22:30'),
(8, 'd06c5861-131d-49a3-8a56-6b9679940375', 'PRAYER WORRIORS DEPARTMENT', 'Working alongside each minister to set up Prayer team in the churhes who will be interceding and praying for the church', 1, 'admin@divinelycalledministers.com', NULL, '2025-02-10 01:23:15', '2025-02-10 01:23:15'),
(9, 'aff45601-8bb2-44ee-915c-b239e0aeb7a3', 'YOUTH MINISTRY DEPARTMENT', 'Build up passionate youth willing to serve the lord .', 1, 'admin@divinelycalledministers.com', NULL, '2025-02-10 01:23:46', '2025-02-10 01:23:46'),
(10, '5df29d48-4dfe-49ff-aba5-6858de5156f3', 'MINISTRY GROWTH DEPARTMENTS', 'Working on the growth of the ministry which includes setting up churches in various places.', 1, 'admin@divinelycalledministers.com', NULL, '2025-02-10 01:24:08', '2025-02-10 01:24:08'),
(11, '1d42a8d2-d5c5-4c54-af91-2f310dbd4784', 'PARTNERSHIP DEPARTMENT', 'Other churches or ministries and ministers who wants to work with us or affiliates with us.', 1, 'admin@divinelycalledministers.com', NULL, '2025-02-10 01:24:42', '2025-02-10 01:24:42'),
(12, 'e6ce2607-f597-4155-ada7-a3e2b885baa7', 'MEDIA AND ADVERTISEMENT DEPARTMENT', 'Includes television, radio, social media and various platforms for publicity', 1, 'admin@divinelycalledministers.com', NULL, '2025-02-10 01:25:13', '2025-02-10 01:25:13'),
(13, '2b1c8398-50f5-49f8-b0c1-fa8a70687fb0', 'CHILDREN DEPARTMENT', 'Children ministry', 1, 'admin@divinelycalledministers.com', NULL, '2025-02-10 01:25:47', '2025-02-10 01:25:47');

-- --------------------------------------------------------

--
-- Table structure for table `department_activities`
--

CREATE TABLE `department_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `fiscal_period_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `activity_date` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `remarks` text DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `department_activities`
--

INSERT INTO `department_activities` (`id`, `uuid`, `department_id`, `fiscal_period_id`, `title`, `description`, `activity_date`, `status`, `remarks`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '24640a1c-b7e8-4454-b6fb-2bb752019c36', 1, 1, 'Record Names', '<p>Hadfwfw</p>', '2025-02-25', 1, 'Good', 'admin@dcm-kenya.com', NULL, '2025-02-24 22:18:31', '2025-02-24 22:18:31');

-- --------------------------------------------------------

--
-- Table structure for table `department_goals`
--

CREATE TABLE `department_goals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `fiscal_period_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `target_date` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `remarks` text DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department_heads`
--

CREATE TABLE `department_heads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `designation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `fiscal_period_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `department_heads`
--

INSERT INTO `department_heads` (`id`, `uuid`, `department_id`, `designation_id`, `user_id`, `fiscal_period_id`, `created_at`, `updated_at`) VALUES
(3, 'f1dfc21f-fc43-494f-bdd4-d2740a72074c', 8, 9, 10, 1, '2025-02-22 23:49:02', '2025-02-22 23:49:02'),
(4, '1a35c512-b977-4638-aa10-13f9f2e89afe', 2, 8, 10, 1, '2025-02-27 21:56:54', '2025-02-27 21:56:54');

-- --------------------------------------------------------

--
-- Table structure for table `department_requests`
--

CREATE TABLE `department_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `from_department_id` bigint(20) UNSIGNED NOT NULL,
  `to_department_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `remarks` text DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `department_requests`
--

INSERT INTO `department_requests` (`id`, `uuid`, `from_department_id`, `to_department_id`, `type`, `title`, `description`, `status`, `remarks`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'dddfa975-b31b-4196-84f4-84faa53d33ee', 13, 3, 'resource', 'Test', '<h4>These changes will:</h4><ol><li>1. Add a loading state that we can control with axios<br>2. Show the loading state in the button text<br>3. Disable the button while submitting<br>4. Handle success and error cases with toast notifications<br>5. Reset the loading state when the request is complete</li></ol>', 3, 'sdcfgthht', 'admin@dcm-kenya.com', NULL, '2025-02-12 01:21:38', '2025-02-27 21:32:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('spiritual','administrative','operational','general') NOT NULL DEFAULT 'general',
  `description` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `uuid`, `slug`, `name`, `type`, `description`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'daf2dd4a-7747-432a-902e-cd192c65df70', 'senior_pastor', 'Senior Pastor', 'spiritual', 'Leads the church spiritually and provides pastoral care.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(2, '1b5ed731-f661-49fd-a458-8b0a073934df', 'associate_pastor', 'Associate Pastor', 'spiritual', 'Assists the senior pastor in spiritual duties.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(3, '6f1107fd-9f8a-4e1f-8bee-eecab8dd887b', 'evangelist', 'Evangelist', 'spiritual', 'Focuses on spreading the gospel and evangelism.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(4, '6785ec98-f95c-48e7-84da-d66a7cc404fb', 'prayer_coordinator', 'Prayer Coordinator', 'spiritual', 'Leads and organizes prayer sessions and intercessory activities.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(5, '86a48db7-7b5f-449c-80e1-beb2111f428e', 'ministry_leader', 'Ministry Leader', 'spiritual', 'Oversees a specific ministry within the church.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(6, '4445ecb4-06cb-467e-9cb3-69d8a595944e', 'worship_leader', 'Worship Leader', 'spiritual', 'Leads the congregation in worship and praise.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(7, 'a5513c6b-2599-4d52-8861-450e202e1cc5', 'church_administrator', 'Church Administrator', 'administrative', 'Manages the administrative operations of the church.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(8, 'e0a09b65-4e95-4f5a-a435-ef6960f3d4ce', 'finance_officer', 'Finance Officer', 'administrative', 'Handles financial records and budgeting for the church.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(9, '0ddbc0d9-8cff-4730-84da-c4458a48082d', 'department_head', 'Department Head (e.g., Youth Ministry Head)', 'administrative', 'Leads a specific department within the church.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(10, 'b681b7b4-4650-4efe-af81-18317277837a', 'media_director', 'Media Director', 'administrative', 'Manages media and communication strategies for the church.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(11, '775d339d-ba49-4e9a-8090-5c41eb1ba762', 'branch_coordinator', 'Branch Coordinator', 'administrative', 'Coordinates activities across church branches.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(12, 'bde023e4-67bd-42bc-bd7f-7b5c97d734bc', 'usher', 'Usher', 'operational', 'Assists with seating and order during church services.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(13, '725b9a3e-c0b1-47ec-8247-5b59b2fc90c0', 'choir_member', 'Choir Member', 'operational', 'Participates in the church choir and musical performances.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(14, '2529e3df-e79e-4270-83b0-a62ab466e9b4', 'technical_team_lead', 'Technical Team Lead', 'operational', 'Manages technical aspects of church services (sound, lighting, etc.).', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(15, 'fc50cd29-bd14-43eb-902b-21446c4eba07', 'event_organizer', 'Event Organizer', 'operational', 'Plans and executes church events and programs.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(16, '4372199a-112b-4f0b-bd3b-a34e75da7517', 'volunteer', 'Volunteer', 'general', 'General volunteer role for various church activities.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL),
(17, 'abd7b0fa-3af4-4bca-8878-57e9c375b860', 'member', 'Member', 'general', 'General designation for church members.', 1, NULL, NULL, '2025-02-09 23:52:45', '2025-02-09 23:52:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `event_type` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `content` mediumtext NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `speakers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`speakers`)),
  `attendees` int(11) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `online_link` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `publish_time` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `uuid`, `title`, `slug`, `event_type`, `description`, `content`, `start_date`, `end_date`, `start_time`, `end_time`, `location`, `type`, `speakers`, `attendees`, `price`, `online_link`, `cover_image`, `sequence`, `status`, `publish_time`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'd45d453b-0d27-47fa-a4e4-1393905f79cb', 'AI Workshop Series', 'ai-workshop-series', 4, '<p>Hands-on workshop covering machine learning fundamentals and practical applications.</p>', 'null', '2025-06-06', '2025-06-06', '14:00:00', '18:00:00', 'Innovation Lab', NULL, '[\"Dr. Kihara\",\" Industry AI Experts\"]', 50, '2000', NULL, 'photo-1540575467063-178a50c2df87_1749235541.png', NULL, 2, NULL, 'admin@novustechhub.com', 'admin@novustechhub.com', '2025-06-06 15:45:41', '2025-06-06 15:47:19'),
(2, '786510d7-5965-451e-8f58-098f0ce8ac5a', 'Tech Innovation Summit 2025', 'tech-innovation-summit-2025', 2, '<p>Join industry leaders discussing the future of technology and innovation.</p>', 'null', '2025-06-18', '2025-06-20', '09:00:00', '17:00:00', 'Main Auditorium', NULL, '[\"Safaricom Innovation Director\",\" CEO of TechCorp\"]', 500, 'Free', NULL, 'photo-1540575467063-178a50c2df87_1749235915.png', NULL, 2, NULL, 'admin@novustechhub.com', NULL, '2025-06-06 15:51:55', '2025-06-06 15:51:55');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `feedback` mediumtext DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL,
  `publish_time` datetime DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fiscal_periods`
--

CREATE TABLE `fiscal_periods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `fiscal_year_id` bigint(20) UNSIGNED NOT NULL,
  `period_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fiscal_periods`
--

INSERT INTO `fiscal_periods` (`id`, `uuid`, `fiscal_year_id`, `period_name`, `start_date`, `end_date`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '065178e2-a5bb-40eb-ba3e-921f64636664', 3, 'Q1', '2025-01-01', '2025-03-01', 'active', NULL, NULL, '2025-02-09 05:59:22', '2025-02-09 05:59:22'),
(2, '3ea7496d-e617-429d-b1bb-f9f317658daa', 3, 'Q2', '2025-04-01', '2025-06-01', 'pending', NULL, NULL, '2025-02-09 05:59:22', '2025-02-09 05:59:22'),
(3, '04bf78bc-cee3-4914-b0bc-1f384a44f7a0', 3, 'Q3', '2025-07-01', '2025-09-01', 'pending', NULL, NULL, '2025-02-09 05:59:22', '2025-02-09 05:59:22'),
(4, '7ad49908-b1a1-4296-85db-f9584620c671', 3, 'Q4', '2025-10-01', '2025-12-31', 'pending', NULL, NULL, '2025-02-09 05:59:22', '2025-02-09 05:59:22');

-- --------------------------------------------------------

--
-- Table structure for table `fiscal_years`
--

CREATE TABLE `fiscal_years` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `year` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fiscal_years`
--

INSERT INTO `fiscal_years` (`id`, `uuid`, `year`, `start_date`, `end_date`, `is_current`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '187cfb7e-8429-4250-9e26-5a3f5f0bc837', '2024-2025', '2024-01-01', '2024-12-31', 0, 'admin@divinelycalledministers.com', 'admin@dcm-kenya.com', '2025-02-07 19:03:27', '2025-02-27 18:27:49'),
(3, 'd146c093-0003-492c-b927-32862835576d', '2025', '2025-01-01', '2025-12-31', 1, 'admin@divinelycalledministers.com', 'admin@dcm-kenya.com', '2025-02-09 05:59:22', '2025-02-27 18:27:49');

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `cover_image` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `dimensions` varchar(255) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `publish_time` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_global` tinyint(1) NOT NULL DEFAULT 0,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `uuid`, `slug`, `title`, `cover_image`, `description`, `dimensions`, `sequence`, `category`, `status`, `publish_time`, `created_by`, `updated_by`, `created_at`, `updated_at`, `is_global`, `branch_id`) VALUES
(1, '4eb45b12-00fe-4f96-8481-c94108d993f1', 'student-collaboration', 'Student Collaboration', 'photo-1522202176988-66273c2fd55f_1749049355.png', NULL, NULL, 2, 'Learning', 2, NULL, 'admin@novustechhub.com', NULL, '2025-06-04 12:02:35', '2025-06-04 12:02:35', 0, NULL),
(2, '43865407-77b3-4f3c-b3b3-abec61957fe6', 'innovation-hub', 'Innovation Hub', 'photo-1581092795360-fd1ca04f0952_1749049401.png', NULL, NULL, 1, 'Innovation', 2, NULL, 'admin@novustechhub.com', NULL, '2025-06-04 12:03:21', '2025-06-04 12:03:21', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` mediumtext NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `uuid`, `name`, `email`, `phone_number`, `subject`, `message`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '0100c020-68af-4644-b6f0-b2df0bccb22e', 'Cleophas Omwenga', 'cleoctech@gmail.com', '0727057310', 'general', 'Test Query', 1, NULL, NULL, '2025-06-11 00:40:29', '2025-06-11 00:40:29');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2024_03_19_000001_create_partners_table', 1),
(7, '2024_03_19_000002_create_awards_table', 1),
(8, '2024_10_12_212258_create_sessions_table', 1),
(9, '2024_10_12_220753_create_admin_users_table', 1),
(10, '2024_10_19_010330_create_company_information_table', 1),
(11, '2024_10_19_011759_create_attachments_table', 1),
(12, '2024_10_19_011952_create_social_media_table', 1),
(13, '2024_10_19_012833_create_testimonials_table', 1),
(14, '2024_10_19_013100_create_feedback_table', 1),
(15, '2024_10_19_013220_create_inquiries_table', 1),
(16, '2024_10_19_014312_laratrust_setup_tables', 1),
(17, '2024_10_20_045712_create_events_table', 1),
(18, '2024_10_20_045730_create_news_table', 1),
(19, '2024_10_20_221642_create_staff_table', 1),
(20, '2024_10_21_003449_alter_content_in_news_table', 1),
(21, '2024_10_21_003537_alter_content_in_events_table', 1),
(22, '2024_10_21_004527_create_galleries_table', 1),
(23, '2024_10_22_032236_add_field_in_news_table', 1),
(24, '2024_10_22_033512_add_field_in_events_table', 1),
(25, '2025_02_10_111436_add_uuid_to_roles_and_permissions_tables', 1),
(26, '2025_02_10_111437_add_uuid_to_roles_and_permissions_tables', 1),
(27, '2025_02_10_145419_add_uuid_to_roles_user_tables', 1),
(28, '2025_02_17_094242_create_videos_table', 2),
(29, '2025_02_18_011849_add_field_to_videos_table', 2),
(30, '2025_02_01_000917_create_departments_table.php', 2),
(31, '2025_02_03_184657_create_designations_table.php', 2),
(32, '2025_02_03_190031_create_fiscal_years_table.php', 2),
(33, '2025_02_03_190057_create_fiscal_periods_table.php', 2),
(34, '2025_02_07_222806_add_status_to_fiscal_periods_table.php', 2),
(35, '2025_02_10_015446_add_type_to_designations_table.php', 2),
(36, '2025_02_11_032225_create_department_heads_table.php', 2),
(37, '2025_02_11_032552_create_department_activities_table.php', 2),
(38, '2025_02_11_032643_create_department_goals_table.php', 2),
(39, '2025_02_11_175205_create_department_requests_table.php', 2),
(40, '2025_02_23_014334_add_slug_and_soft_deletes_to_designations_table.php', 2),
(42, '2025_06_04_105317_create_courses_table', 3),
(43, '2025_06_04_141311_add_category_to_gallery_table', 4),
(44, '2025_06_04_153401_create_success_stories_table', 5),
(45, '2025_06_05_033359_add_achievement_to_success_stories_table', 6),
(46, '2025_06_05_083223_add_expertise_fields_to_staff_table', 7),
(47, '2025_06_06_031714_add_duration_category_views_to_videos_table', 8),
(48, '2025_06_06_112912_add_type_speakers_attendees_price_to_events_table', 9),
(49, '2025_06_11_032617_add_phone_number_and_subject_to_inquiries_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `publish_time` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `publish_time` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_user`
--

CREATE TABLE `permission_user` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `uuid`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, '74e4e05f-8642-4a69-bab9-7d13366245f9', 'administrator', 'Administrator', 'Administrator', '2025-05-27 18:12:12', '2025-05-27 18:12:14');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `uuid` char(36) DEFAULT NULL COMMENT '(DC2Type:guid)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`role_id`, `user_id`, `user_type`, `uuid`) VALUES
(1, 1, 'App\\Models\\User', '70b49e5e-99c1-4be0-8a19-292eeff4c71d'),
(1, 2, 'App\\Models\\User', '74174ad7-b851-4c88-bd0b-c5c0cdd94cd1');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('AWlA0wR818jCJsD7HRJvu53OTV93Lv4zWuMUr8PO', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZ0pFYWplRFZzRjhEZHYyMnVYalhIRUtmWDB5dXlqdm5CUXBDWE5mcSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwNy9sb2dpbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo3OiJwcm9maWxlIjtPOjI3OiJBcHBcTW9kZWxzXFN5c3RlbVxBZG1pblVzZXIiOjMwOntzOjEzOiIAKgBjb25uZWN0aW9uIjtzOjU6Im15c3FsIjtzOjg6IgAqAHRhYmxlIjtzOjExOiJhZG1pbl91c2VycyI7czoxMzoiACoAcHJpbWFyeUtleSI7czoyOiJpZCI7czoxMDoiACoAa2V5VHlwZSI7czozOiJpbnQiO3M6MTI6ImluY3JlbWVudGluZyI7YjoxO3M6NzoiACoAd2l0aCI7YTowOnt9czoxMjoiACoAd2l0aENvdW50IjthOjA6e31zOjE5OiJwcmV2ZW50c0xhenlMb2FkaW5nIjtiOjA7czoxMDoiACoAcGVyUGFnZSI7aToxNTtzOjY6ImV4aXN0cyI7YjoxO3M6MTg6Indhc1JlY2VudGx5Q3JlYXRlZCI7YjowO3M6Mjg6IgAqAGVzY2FwZVdoZW5DYXN0aW5nVG9TdHJpbmciO2I6MDtzOjEzOiIAKgBhdHRyaWJ1dGVzIjthOjM6e3M6Njoic3RhdHVzIjtpOjE7czo0OiJyb2xlIjtpOjE7czoxNjoicHJvZmlsZV9jYXRlZ29yeSI7czo1OiJhZG1pbiI7fXM6MTE6IgAqAG9yaWdpbmFsIjthOjI6e3M6Njoic3RhdHVzIjtpOjE7czo0OiJyb2xlIjtpOjE7fXM6MTA6IgAqAGNoYW5nZXMiO2E6MDp7fXM6ODoiACoAY2FzdHMiO2E6Mjp7czoxMDoiY3JlYXRlZF9hdCI7czoxMDoiZGF0ZTpkLU0tWSI7czoxMDoidXBkYXRlZF9hdCI7czoxMDoiZGF0ZTpkLU0tWSI7fXM6MTc6IgAqAGNsYXNzQ2FzdENhY2hlIjthOjA6e31zOjIxOiIAKgBhdHRyaWJ1dGVDYXN0Q2FjaGUiO2E6MDp7fXM6MTM6IgAqAGRhdGVGb3JtYXQiO047czoxMDoiACoAYXBwZW5kcyI7YTowOnt9czoxOToiACoAZGlzcGF0Y2hlc0V2ZW50cyI7YTowOnt9czoxNDoiACoAb2JzZXJ2YWJsZXMiO2E6MDp7fXM6MTI6IgAqAHJlbGF0aW9ucyI7YTowOnt9czoxMDoiACoAdG91Y2hlcyI7YTowOnt9czoxMDoidGltZXN0YW1wcyI7YjoxO3M6MTM6InVzZXNVbmlxdWVJZHMiO2I6MDtzOjk6IgAqAGhpZGRlbiI7YTowOnt9czoxMDoiACoAdmlzaWJsZSI7YTowOnt9czoxMToiACoAZmlsbGFibGUiO2E6MDp7fXM6MTA6IgAqAGd1YXJkZWQiO2E6MDp7fX19', 1749613451);

-- --------------------------------------------------------

--
-- Table structure for table `social_media`
--

CREATE TABLE `social_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `sequence` int(11) DEFAULT NULL,
  `publish_time` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `social_media`
--

INSERT INTO `social_media` (`id`, `uuid`, `name`, `link`, `status`, `sequence`, `publish_time`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'd78ac1e6-02ce-4366-9f8c-b3b83e98a6ee', 'Facebook', 'https://www.facebook.com/share/165WAopr5y/', 2, NULL, NULL, 'admin@novustechhub.com', NULL, '2025-06-04 04:26:43', '2025-06-04 04:26:43');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone_no` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `expertise` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`expertise`)),
  `experience` varchar(255) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `achievements` text DEFAULT NULL,
  `quote` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `facebook_link` varchar(255) DEFAULT NULL,
  `x_link` varchar(255) DEFAULT NULL,
  `linkedin_link` varchar(255) DEFAULT NULL,
  `youtube_link` varchar(255) DEFAULT NULL,
  `whatsapp_link` varchar(255) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `publish_time` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `uuid`, `name`, `email`, `phone_no`, `title`, `expertise`, `experience`, `education`, `achievements`, `quote`, `cover_image`, `facebook_link`, `x_link`, `linkedin_link`, `youtube_link`, `whatsapp_link`, `sequence`, `status`, `publish_time`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '7c56ec82-44e0-436c-98c9-a4bdf267c665', 'Prof. Michael Ochieng', 'mochieng@gmail.com', '0789456123', 'Senior Software Engineering Instructor', '[\"Full Stack Development\",\"Cloud Computing\",\"DevOps\"]', '8 years', 'MS Software Engineering - Stanford', 'Former Lead Engineer at Microsoft, Built systems serving 100M+ users', 'Great software is built by great teams, and great teams start with great education.', 'Novus_1749179400.jpeg', 'fb.com', 'x.com', 'lik.com', 'youtube.com', 'wap.me/mochi', 1, 2, NULL, 'admin@novustechhub.com', NULL, '2025-06-06 00:10:00', '2025-06-06 00:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `success_stories`
--

CREATE TABLE `success_stories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `achievement` text DEFAULT NULL,
  `student_name` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL,
  `graduation_year` year(4) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `sequence` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `publish_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `success_stories`
--

INSERT INTO `success_stories` (`id`, `uuid`, `title`, `description`, `achievement`, `student_name`, `course`, `graduation_year`, `cover_image`, `sequence`, `status`, `publish_time`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, '0b4d9422-ae23-4f08-8fd4-b225fcc9a080', 'Senior Software Engineer | MS 365 Consultant', 'From a curious student to a leading Microsoft Business Central 365 Consultant. Novus Institute provided the foundation for my tech career.', 'Led the development/customization of Business Central 365 ERP and  API integrations to MS 365', 'Cleophas Omwenga', 'Software Engineer', 2017, 'FS_1749095196.jpg', 1, 2, NULL, '2025-06-04 14:25:13', '2025-06-05 00:46:36', NULL),
(3, '72990f32-70e0-48e2-9284-a8b937e3ddd9', 'Data Scientist at Microsoft', 'The practical approach to machine learning at Novus prepared me for real-world data challenges.', 'Published 5 research papers in AI', 'Grace Wanjiku', 'Data Science', 2020, 'Novus_1749106981.jpeg', 1, 2, NULL, '2025-06-05 04:03:01', '2025-06-05 04:03:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `feedback` mediumtext DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL,
  `publish_time` datetime DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL DEFAULT '$2y$10$Vt7wL3MKTPP0wvI/yjvwkOHjMErlgwUN7iesDW9bt8uUqkp9qqimS',
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `user_category` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `sequence` int(11) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `user_category`, `status`, `sequence`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '67222327-86a2-485b-9a90-706af882cd15', 'Administrator', 'admin@novustechhub.com', NULL, '$2y$10$/3V8qGjdjGzZwJlt0aaDFuwftY8JpFFXGWwnwVYXpyxc5QYdqC7t6', NULL, NULL, NULL, NULL, NULL, NULL, 100, 2, NULL, 'admin@dcm-kenya.com', 'admin@dcm-kenya.com', NULL, NULL),
(2, 'c5c1b874-6c42-42df-9249-1090cc2e64af', 'Wenla', 'admin@wenlasystems.com', NULL, '$2y$10$4TxJG0eFOmcysAoPbJ4lW./thAttOFsKX2eaJ1AfDsfKbujbOqNme', NULL, NULL, NULL, NULL, NULL, NULL, 100, 2, NULL, 'admin@wenlasystems.com', 'admin@wenlasystems.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `video_path` varchar(255) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `views` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `uuid`, `title`, `description`, `video_path`, `cover_image`, `duration`, `category`, `views`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `sequence`) VALUES
(1, '2fc948b0-d425-4e91-b4cb-ee0f983bcb0e', 'Vue.js Table of Contents', 'Vue.js Table of Contents', '38bf6f14-4089-4d65-b984-ecfaec75e57d.mp4', '7e9ee93e-ed5f-4f28-99aa-643d3b182c2b.jpg', '13:43', 'Practicle', 0, 2, 'admin@novustechhub.com', NULL, '2025-06-06 02:46:15', '2025-06-06 02:46:15', 1),
(2, 'c9c4ad56-5d9b-48ce-b534-66dafea7947e', 'Site Tour', 'Corner stone church tour video', 'dab37257-f15c-480a-823e-646a6f162713.mp4', '9bd1c87e-391f-436d-88fa-3123facc3e8b.jpg', '0:53', 'Tour', 0, 2, 'admin@novustechhub.com', NULL, '2025-06-06 02:59:35', '2025-06-06 02:59:35', 2),
(3, '28ab0368-4a78-461e-af39-babe1b8a3e6c', 'Demo Video', 'Demo Software', '98ace1dc-64f6-4c6f-8753-7870ee05aecf.mp4', '379bddf4-f18a-4db5-b662-a84bbb0c7780.jpg', '0:38', 'Demo', 2, 2, 'admin@novustechhub.com', NULL, '2025-06-07 06:30:27', '2025-06-07 06:30:27', 3),
(4, '4d5787c1-9973-4589-a330-4232b00dd3f3', 'Code', 'Code review in vscode', '541d0f5c-d840-4082-aa8b-30a5b841909a.mp4', '66aa3367-a65b-4e61-8d68-d150dfb781b5.jpg', '0:28', 'Code', 12, 2, 'admin@novustechhub.com', NULL, '2025-06-07 06:41:27', '2025-06-07 06:41:27', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_users_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `admin_users_email_unique` (`email`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attachments_uuid_unique` (`uuid`);

--
-- Indexes for table `awards`
--
ALTER TABLE `awards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `awards_uuid_unique` (`uuid`);

--
-- Indexes for table `company_information`
--
ALTER TABLE `company_information`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `company_information_uuid_unique` (`uuid`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_uuid_unique` (`uuid`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `departments_name_unique` (`name`);

--
-- Indexes for table `department_activities`
--
ALTER TABLE `department_activities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_activities_uuid_unique` (`uuid`),
  ADD KEY `department_activities_department_id_foreign` (`department_id`),
  ADD KEY `department_activities_fiscal_period_id_foreign` (`fiscal_period_id`);

--
-- Indexes for table `department_goals`
--
ALTER TABLE `department_goals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_goals_uuid_unique` (`uuid`),
  ADD KEY `department_goals_department_id_foreign` (`department_id`),
  ADD KEY `department_goals_fiscal_period_id_foreign` (`fiscal_period_id`);

--
-- Indexes for table `department_heads`
--
ALTER TABLE `department_heads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_heads_uuid_unique` (`uuid`),
  ADD KEY `department_heads_department_id_foreign` (`department_id`),
  ADD KEY `department_heads_designation_id_foreign` (`designation_id`),
  ADD KEY `department_heads_user_id_foreign` (`user_id`),
  ADD KEY `department_heads_fiscal_period_id_foreign` (`fiscal_period_id`);

--
-- Indexes for table `department_requests`
--
ALTER TABLE `department_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_requests_uuid_unique` (`uuid`),
  ADD KEY `department_requests_from_department_id_foreign` (`from_department_id`),
  ADD KEY `department_requests_to_department_id_foreign` (`to_department_id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `designations_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `designations_slug_unique` (`slug`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `events_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `events_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `feedback_uuid_unique` (`uuid`);

--
-- Indexes for table `fiscal_periods`
--
ALTER TABLE `fiscal_periods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fiscal_periods_uuid_unique` (`uuid`),
  ADD KEY `fiscal_periods_fiscal_year_id_foreign` (`fiscal_year_id`);

--
-- Indexes for table `fiscal_years`
--
ALTER TABLE `fiscal_years`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fiscal_years_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `fiscal_years_year_unique` (`year`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `galleries_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `galleries_slug_unique` (`slug`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inquiries_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `news_slug_unique` (`slug`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `partners_uuid_unique` (`uuid`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`),
  ADD UNIQUE KEY `permissions_uuid_unique` (`uuid`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`user_id`,`permission_id`,`user_type`),
  ADD KEY `permission_user_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`),
  ADD UNIQUE KEY `roles_uuid_unique` (`uuid`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`,`user_type`),
  ADD UNIQUE KEY `role_user_uuid_unique` (`uuid`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `social_media`
--
ALTER TABLE `social_media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `social_media_uuid_unique` (`uuid`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staff_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `staff_email_unique` (`email`),
  ADD UNIQUE KEY `staff_phone_no_unique` (`phone_no`);

--
-- Indexes for table `success_stories`
--
ALTER TABLE `success_stories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `success_stories_uuid_unique` (`uuid`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `testimonials_uuid_unique` (`uuid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `videos_uuid_unique` (`uuid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `awards`
--
ALTER TABLE `awards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_information`
--
ALTER TABLE `company_information`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `department_activities`
--
ALTER TABLE `department_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `department_goals`
--
ALTER TABLE `department_goals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department_heads`
--
ALTER TABLE `department_heads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `department_requests`
--
ALTER TABLE `department_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fiscal_periods`
--
ALTER TABLE `fiscal_periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fiscal_years`
--
ALTER TABLE `fiscal_years`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `social_media`
--
ALTER TABLE `social_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `success_stories`
--
ALTER TABLE `success_stories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `department_activities`
--
ALTER TABLE `department_activities`
  ADD CONSTRAINT `department_activities_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `department_activities_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `department_goals`
--
ALTER TABLE `department_goals`
  ADD CONSTRAINT `department_goals_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `department_goals_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `department_heads`
--
ALTER TABLE `department_heads`
  ADD CONSTRAINT `department_heads_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `department_heads_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `department_heads_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `department_heads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `department_requests`
--
ALTER TABLE `department_requests`
  ADD CONSTRAINT `department_requests_from_department_id_foreign` FOREIGN KEY (`from_department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `department_requests_to_department_id_foreign` FOREIGN KEY (`to_department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fiscal_periods`
--
ALTER TABLE `fiscal_periods`
  ADD CONSTRAINT `fiscal_periods_fiscal_year_id_foreign` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
