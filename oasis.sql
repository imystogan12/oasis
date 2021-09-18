-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2021 at 03:36 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oasis`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(99) NOT NULL,
  `department` varchar(99) NOT NULL,
  `reason` varchar(99) NOT NULL,
  `user_id` int(99) NOT NULL,
  `student_id` int(99) NOT NULL,
  `guest_id` int(99) NOT NULL,
  `date_time` datetime NOT NULL,
  `status` varchar(99) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `department`, `reason`, `user_id`, `student_id`, `guest_id`, `date_time`, `status`) VALUES
(19, 'faculty', 'consultation', 8, 65, 0, '2021-09-10 08:00:00', 'pending'),
(20, 'faculty', 'consultation', 11, 65, 0, '2021-09-13 08:00:00', 'pending'),
(21, 'faculty', 'consultation', 8, 72, 0, '2021-09-15 09:00:00', 'pending'),
(22, 'faculty', 'consultation', 8, 0, 36, '2021-09-13 08:00:00', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `guest`
--

CREATE TABLE `guest` (
  `id` int(11) NOT NULL,
  `guest_fname` varchar(99) NOT NULL,
  `guest_lname` varchar(99) NOT NULL,
  `guest_address` varchar(99) NOT NULL,
  `guest_number` int(11) NOT NULL,
  `guest_email` varchar(99) NOT NULL,
  `created_at` datetime NOT NULL,
  `guest_companion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `guest`
--

INSERT INTO `guest` (`id`, `guest_fname`, `guest_lname`, `guest_address`, `guest_number`, `guest_email`, `created_at`, `guest_companion`) VALUES
(31, 'Aldrin', 'Lopez', 'asdasdsa', 12121, 'asdas@asdda', '2021-09-13 03:14:24', 2),
(32, 'Paula', 'Lopez', 'asdasdsa', 2311, 'asdas@asdda', '2021-09-18 07:35:35', 0),
(33, 'John', 'Lopez', 'asdas', 2311, 'asdas@asdda', '2021-09-18 07:36:34', 0),
(34, 'Aldrin', 'Lopez', 'asdas', 2311, 'asdas@asdda', '2021-09-18 12:58:19', 0),
(35, 'Aldrin', 'sadasda', 'asdasdsa', 2311, 'asdas@asdda', '2021-09-18 12:59:14', 0),
(36, '', 'Lopez', 'asdasdsa', 2311, 'asdas@asdda', '2021-09-18 14:53:27', 2);

-- --------------------------------------------------------

--
-- Table structure for table `guest_companion`
--

CREATE TABLE `guest_companion` (
  `id` int(99) NOT NULL,
  `gCompanion_fname` varchar(99) NOT NULL,
  `gCompanion_lname` varchar(99) NOT NULL,
  `gCompanion_email` varchar(99) NOT NULL,
  `gCompanion_number` int(99) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `guest_companion`
--

INSERT INTO `guest_companion` (`id`, `gCompanion_fname`, `gCompanion_lname`, `gCompanion_email`, `gCompanion_number`, `created_at`) VALUES
(8, 'asdsa', 'csadfs', 'das@sda', 12121, '2021-09-13 03:14:59'),
(9, 'asd', 'dsada', 'sad@asd', 45325, '2021-09-13 03:14:59'),
(10, 'asdsa', 'Lop', 'das@sda', 12121, '2021-09-18 13:21:52'),
(11, 'asd', 'sacd', 'sad@asd', 45325, '2021-09-18 13:21:52'),
(12, 'asdsa', 'csadfs', 'asds@dasd', 12121, '2021-09-18 14:53:28'),
(13, 'asd', 'sacd', 'dasd@sfsdf', 45325, '2021-09-18 14:53:28');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(99) NOT NULL,
  `name` varchar(99) NOT NULL,
  `role` varchar(99) NOT NULL,
  `reporting_user_id` int(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `role`, `reporting_user_id`) VALUES
(1, 'Christian Torres', 'faculty', 8),
(2, 'Jennilyn Silva', 'faculty', 8),
(3, 'Allan Badillo', 'faculty', 10),
(4, 'Rome Olympia', 'faculty', 11),
(5, ' Mary Grace Pangilinan', 'faculty', 12),
(6, 'Christina Mendoza', 'faculty', 12),
(7, 'Reynaldo Merced', 'faculty', 8),
(8, 'Neth Portugues', 'faculty', 11),
(9, 'Maam Kathleen', 'faculty', 10);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `student_fname` varchar(99) NOT NULL,
  `student_lname` varchar(99) NOT NULL,
  `student_num` int(11) NOT NULL,
  `student_email` varchar(99) NOT NULL,
  `student_course` varchar(99) NOT NULL,
  `student_section` varchar(99) NOT NULL,
  `created_at` datetime NOT NULL,
  `student_companion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `student_fname`, `student_lname`, `student_num`, `student_email`, `student_course`, `student_section`, `created_at`, `student_companion`) VALUES
(58, 'Aldrin', 'Lopez', 23123, 'lopezaldrin@gmail.com', 'BGFH12', 'bt321', '2021-09-13 02:31:58', 0),
(59, 'Aldrin', 'Lopez', 23123, 'lopezaldrin@gmail.com', 'BGFH12', 'bt321', '2021-09-13 02:32:44', 0),
(60, 'John', 'Lopez', 1231321, 'lopezaldrin@gmail.com', 'BGFH12', 'bt321', '2021-09-13 02:33:25', 0),
(61, 'John', 'Lopez', 1231321, 'lopezaldrin@gmail.com', 'BGFH12', 'bt321', '2021-09-13 02:35:10', 0),
(62, 'Aldrin', 'Lopez', 1231321, 'lopezaldrin@gmail.com', 'BGFH12', 'adsadasdas', '2021-09-13 02:38:59', 0),
(63, 'Aldrin', 'Lopez', 23123, 'lopezaldrin@gmail.com', 'BGFH12', 'bt321', '2021-09-13 02:42:41', 0),
(64, 'John', 'sadasda', 23123, 'lopezaldrin@gmail.com', 'BGFH12', 'bt321', '2021-09-13 02:51:49', 0),
(65, 'Paula', 'Garcia', 123213, 'afssdaef@asdad', 'BGFH12', 'bt321', '2021-09-13 03:05:02', 1),
(66, '', '', 0, '', '', '', '2021-09-18 14:05:43', 0),
(67, 'Jennilyn ', 'Silva', 1231321, 'asdadsad@casda.com', 'BGFH12', 'adsadasdas', '2021-09-18 14:06:04', 2),
(68, 'Jennilyn ', 'Silva', 1231321, 'asdadsad@casda.com', 'BGFH12', 'adsadasdas', '2021-09-18 14:06:42', 2),
(69, 'Jennilyn ', 'Silva', 1231321, 'asdadsad@casda.com', 'BGFH12', 'adsadasdas', '2021-09-18 14:08:23', 2),
(70, 'Jennilyn ', 'Silva', 1231321, 'asdadsad@casda.com', 'BGFH12', 'adsadasdas', '2021-09-18 14:20:05', 2),
(71, 'Jennilyn ', 'Silva', 1231321, 'asdadsad@casda.com', 'BGFH12', 'adsadasdas', '2021-09-18 14:30:03', 2),
(72, 'Jennilyn ', 'Silva', 1231321, 'asdadsad@casda.com', 'BGFH12', 'adsadasdas', '2021-09-18 14:30:27', 2);

-- --------------------------------------------------------

--
-- Table structure for table `student_companion`
--

CREATE TABLE `student_companion` (
  `id` int(11) NOT NULL,
  `sCompanion_fname` varchar(99) NOT NULL,
  `sCompanion_lname` varchar(99) NOT NULL,
  `sCompanion_email` varchar(99) NOT NULL,
  `sCompanion_number` int(99) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_companion`
--

INSERT INTO `student_companion` (`id`, `sCompanion_fname`, `sCompanion_lname`, `sCompanion_email`, `sCompanion_number`, `created_at`) VALUES
(19, 'aldous', 'csadfs', 'das@sda', 12121, '2021-09-18 14:20:05'),
(20, 'asd', 'sacd', 'sad@asd', 45325, '2021-09-18 14:20:05'),
(21, 'aldous', 'csadfs', 'das@sda', 12121, '2021-09-18 14:30:03'),
(22, 'asd', 'sacd', 'sad@asd', 45325, '2021-09-18 14:30:03'),
(23, 'aldous', 'csadfs', 'das@sda', 12121, '2021-09-18 14:30:27'),
(24, 'asd', 'sacd', 'sad@asd', 45325, '2021-09-18 14:30:27');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(99) NOT NULL,
  `username` varchar(99) NOT NULL,
  `password` varchar(99) NOT NULL,
  `role` varchar(99) NOT NULL,
  `salt` varchar(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `role`, `salt`) VALUES
(1, 'admin1', '9c97c6001a4b4ad39f96696241f48303', 'cashier', 'fds32f'),
(2, 'admin2', '9c97c6001a4b4ad39f96696241f48303', 'registrar', 'fds32f'),
(3, 'admin3', '9c97c6001a4b4ad39f96696241f48303', 'admission', 'fds32f'),
(4, 'admin4', '9c97c6001a4b4ad39f96696241f48303', 'guidance', 'fds32f'),
(5, 'admin5', '9c97c6001a4b4ad39f96696241f48303', 'clinic', 'fds32f'),
(6, 'admin6', '9c97c6001a4b4ad39f96696241f48303', 'ojt', 'fds32f'),
(7, 'admin7', '9c97c6001a4b4ad39f96696241f48303', 'prowear', 'fds32f'),
(8, 'rmerced', '9c97c6001a4b4ad39f96696241f48303', 'faculty', 'fds32f'),
(9, 'admin9', '9c97c6001a4b4ad39f96696241f48303', 'guard', 'fds32f'),
(10, 'abadillo', '9c97c6001a4b4ad39f96696241f48303', 'faculty', 'fds32f'),
(11, 'rolympia', '9c97c6001a4b4ad39f96696241f48303', 'faculty', 'fds32f'),
(12, 'mpangilinan', '9c97c6001a4b4ad39f96696241f48303', 'faculty', 'fds32f');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guest`
--
ALTER TABLE `guest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guest_companion`
--
ALTER TABLE `guest_companion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`,`student_fname`);

--
-- Indexes for table `student_companion`
--
ALTER TABLE `student_companion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `guest`
--
ALTER TABLE `guest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `guest_companion`
--
ALTER TABLE `guest_companion`
  MODIFY `id` int(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `student_companion`
--
ALTER TABLE `student_companion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
