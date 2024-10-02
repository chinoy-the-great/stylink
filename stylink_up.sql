-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2024 at 07:47 PM
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
-- Database: `stylink_up`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `municipality` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`id`, `name`, `municipality`) VALUES
(1, 'Barangay I (Poblacion)', 'Alaminos'),
(2, 'Barangay II (Poblacion)', 'Alaminos'),
(3, 'Barangay III (Poblacion)', 'Alaminos'),
(4, 'Barangay IV (Poblacion)', 'Alaminos'),
(5, 'Del Carmen', 'Alaminos'),
(6, 'Palma', 'Alaminos'),
(7, 'San Agustin', 'Alaminos'),
(8, 'San Andres', 'Alaminos'),
(9, 'San Benito', 'Alaminos'),
(10, 'San Gregorio', 'Alaminos'),
(11, 'San Ildefonso', 'Alaminos'),
(12, 'San Juan', 'Alaminos'),
(13, 'San Miguel', 'Alaminos'),
(14, 'San Roque', 'Alaminos'),
(15, 'Santa Rosa', 'Alaminos'),
(16, 'Bitin', 'Bay'),
(17, 'Calo', 'Bay'),
(18, 'Dila', 'Bay'),
(19, 'Maitim', 'Bay'),
(20, 'Masaya', 'Bay'),
(21, 'Paciano Rizal', 'Bay'),
(22, 'Puypuy', 'Bay'),
(23, 'San Agustin (Poblacion)', 'Bay'),
(24, 'San Antonio', 'Bay'),
(25, 'San Isidro', 'Bay'),
(26, 'San Nicolas (Poblacion)', 'Bay'),
(27, 'Santa Cruz', 'Bay'),
(28, 'Santo Domingo', 'Bay'),
(29, 'Tagumpay', 'Bay'),
(30, 'Tranca', 'Bay'),
(31, 'Biñan (Poblacion)', 'Biñan'),
(32, 'Bungahan', 'Biñan'),
(33, 'Canlalay', 'Biñan'),
(34, 'Casile', 'Biñan'),
(35, 'De La Paz', 'Biñan'),
(36, 'Ganado', 'Biñan'),
(37, 'Langkiwa', 'Biñan'),
(38, 'Loma', 'Biñan'),
(39, 'Malaban', 'Biñan'),
(40, 'Malamig', 'Biñan'),
(41, 'Mampalasan', 'Biñan'),
(42, 'Platero', 'Biñan'),
(43, 'Poblacion', 'Biñan'),
(44, 'San Antonio', 'Biñan'),
(45, 'San Francisco (Halang)', 'Biñan'),
(46, 'San Jose', 'Biñan'),
(47, 'San Vicente', 'Biñan'),
(48, 'Santo Domingo', 'Biñan'),
(49, 'Santo Niño', 'Biñan'),
(50, 'Santo Tomas (Calabuso)', 'Biñan'),
(51, 'Soro-soro', 'Biñan'),
(52, 'Timbao', 'Biñan'),
(53, 'Tubigan', 'Biñan'),
(54, 'Zapote', 'Biñan'),
(55, 'Baclaran', 'Cabuyao'),
(56, 'Banay-Banay', 'Cabuyao'),
(57, 'Banlic', 'Cabuyao'),
(58, 'Barangay Dos (Poblacion)', 'Cabuyao'),
(59, 'Barangay Tres (Poblacion)', 'Cabuyao'),
(60, 'Barangay Uno (Poblacion)', 'Cabuyao'),
(61, 'Bigaa', 'Cabuyao'),
(62, 'Butong', 'Cabuyao'),
(63, 'Casile', 'Cabuyao'),
(64, 'Diezmo', 'Cabuyao'),
(65, 'Gulod', 'Cabuyao'),
(66, 'Mamatid', 'Cabuyao'),
(67, 'Marinig', 'Cabuyao'),
(68, 'Niugan', 'Cabuyao'),
(69, 'Pittland', 'Cabuyao'),
(70, 'Pulo', 'Cabuyao'),
(71, 'Sala', 'Cabuyao'),
(72, 'San Isidro (Putol)', 'Cabuyao'),
(73, 'Bagong Kalsada', 'Calamba'),
(74, 'Bañadero', 'Calamba'),
(75, 'Banlic', 'Calamba'),
(76, 'Barandal', 'Calamba'),
(77, 'Barangay 1 (Poblacion)', 'Calamba'),
(78, 'Barangay 2 (Poblacion)', 'Calamba'),
(79, 'Barangay 3 (Poblacion)', 'Calamba'),
(80, 'Barangay 4 (Poblacion)', 'Calamba'),
(81, 'Barangay 5 (Poblacion)', 'Calamba'),
(82, 'Barangay 6 (Poblacion)', 'Calamba'),
(83, 'Barangay 7 (Poblacion)', 'Calamba'),
(84, 'Batino', 'Calamba'),
(85, 'Bubuyan', 'Calamba'),
(86, 'Bucal', 'Calamba'),
(87, 'Bunggo', 'Calamba'),
(88, 'Burol', 'Calamba'),
(89, 'Camaligan', 'Calamba'),
(90, 'Canlubang', 'Calamba'),
(91, 'Halang', 'Calamba'),
(92, 'Hornalan', 'Calamba'),
(93, 'Kay-Anlog', 'Calamba'),
(94, 'La Mesa', 'Calamba'),
(95, 'Laguerta', 'Calamba'),
(96, 'Lawa', 'Calamba'),
(97, 'Lecheria', 'Calamba'),
(98, 'Lingga', 'Calamba'),
(99, 'Looc', 'Calamba'),
(100, 'Mabato', 'Calamba'),
(101, 'Majada Labas', 'Calamba'),
(102, 'Makiling', 'Calamba'),
(103, 'Mapagong', 'Calamba'),
(104, 'Masili', 'Calamba'),
(105, 'Maunong', 'Calamba'),
(106, 'Mayapa', 'Calamba'),
(107, 'Milagrosa (Tulo)', 'Calamba'),
(108, 'Paciano Rizal', 'Calamba'),
(109, 'Palingon', 'Calamba'),
(110, 'Palo-Alto', 'Calamba'),
(111, 'Pansol', 'Calamba'),
(112, 'Parian', 'Calamba'),
(113, 'Prinza', 'Calamba'),
(114, 'Punta', 'Calamba'),
(115, 'Puting Lupa', 'Calamba'),
(116, 'Real', 'Calamba'),
(117, 'Saimsim', 'Calamba'),
(118, 'Sampiruhan', 'Calamba'),
(119, 'San Cristobal', 'Calamba'),
(120, 'San Jose', 'Calamba'),
(121, 'San Juan', 'Calamba'),
(122, 'Sirang Lupa', 'Calamba'),
(123, 'Sucol', 'Calamba'),
(124, 'Turbina', 'Calamba'),
(125, 'Ulango', 'Calamba'),
(126, 'Uwisan', 'Calamba'),
(127, 'Balayhangin', 'Calauan'),
(128, 'Bangyas', 'Calauan'),
(129, 'Dayap', 'Calauan'),
(130, 'Hanggan', 'Calauan'),
(131, 'Imok', 'Calauan'),
(132, 'Kanluran (Poblacion)', 'Calauan'),
(133, 'Lamot 1', 'Calauan'),
(134, 'Lamot 2', 'Calauan'),
(135, 'Limao', 'Calauan'),
(136, 'Mabacan', 'Calauan'),
(137, 'Masiit', 'Calauan'),
(138, 'Paliparan', 'Calauan'),
(139, 'Perez', 'Calauan'),
(140, 'Prinza', 'Calauan'),
(141, 'San Isidro', 'Calauan'),
(142, 'Santo Tomas', 'Calauan'),
(143, 'Silangan (Poblacion)', 'Calauan'),
(144, 'Anglas', 'Cavinti'),
(145, 'Bangco', 'Cavinti'),
(146, 'Bukal', 'Cavinti'),
(147, 'Bulajo', 'Cavinti'),
(148, 'Cansuso', 'Cavinti'),
(149, 'Duhat', 'Cavinti'),
(150, 'Inao-Awan', 'Cavinti'),
(151, 'Kanluran Talaongan', 'Cavinti'),
(152, 'Labayo', 'Cavinti'),
(153, 'Layasin', 'Cavinti'),
(154, 'Layug', 'Cavinti'),
(155, 'Mahipon', 'Cavinti'),
(156, 'Paowin', 'Cavinti'),
(157, 'Poblacion', 'Cavinti'),
(158, 'Silangan Talaongan', 'Cavinti'),
(159, 'Sisilmin', 'Cavinti'),
(160, 'Sumucab', 'Cavinti'),
(161, 'Tibatib', 'Cavinti'),
(162, 'Udia', 'Cavinti'),
(163, 'Asana (Poblacion)', 'Famy'),
(164, 'Bacong-Sigsigan', 'Famy'),
(165, 'Bagong Pag-Asa (Poblacion)', 'Famy'),
(166, 'Balitoc', 'Famy'),
(167, 'Banaba (Poblacion)', 'Famy'),
(168, 'Batuhan', 'Famy'),
(169, 'Bulihan', 'Famy'),
(170, 'Caballero (Poblacion)', 'Famy'),
(171, 'Calumpang (Poblacion)', 'Famy'),
(172, 'Cuebang Bato', 'Famy'),
(173, 'Damayan (Poblacion)', 'Famy'),
(174, 'Kapatalan', 'Famy'),
(175, 'Kataypuanan', 'Famy'),
(176, 'Liyang', 'Famy'),
(177, 'Maate', 'Famy'),
(178, 'Magdalo (Poblacion)', 'Famy'),
(179, 'Mayatba', 'Famy'),
(180, 'Minayutan', 'Famy'),
(181, 'Salangbato', 'Famy'),
(182, 'Tunhac', 'Famy'),
(183, 'Longos', 'Kalayaan'),
(184, 'San Antonio', 'Kalayaan'),
(185, 'San Juan (Poblacion)', 'Kalayaan'),
(186, 'Bagong Anyo (Poblacion)', 'Liliw'),
(187, 'Bayate', 'Liliw'),
(188, 'Bongkol', 'Liliw'),
(189, 'Bubukal', 'Liliw'),
(190, 'Cabuyao', 'Liliw'),
(191, 'Calumpang', 'Liliw'),
(192, 'Culoy', 'Liliw'),
(193, 'Dagatan', 'Liliw'),
(194, 'Daniw (Danliw)', 'Liliw'),
(195, 'Dita', 'Liliw'),
(196, 'Ibabang Palina', 'Liliw'),
(197, 'Ibabang San Roque', 'Liliw'),
(198, 'Ibabang Sungi', 'Liliw'),
(199, 'Ibabang Taykin', 'Liliw'),
(200, 'Ilayang Palina', 'Liliw'),
(201, 'Ilayang San Roque', 'Liliw'),
(202, 'Ilayang Sungi', 'Liliw'),
(203, 'Ilayang Taykin', 'Liliw'),
(204, 'Kanlurang Bukal', 'Liliw'),
(205, 'Laguan', 'Liliw'),
(206, 'Luquin', 'Liliw'),
(207, 'Malabo-Kalantukan', 'Liliw'),
(208, 'Masikap (Poblacion)', 'Liliw'),
(209, 'Maslun (Poblacion)', 'Liliw'),
(210, 'Mojon', 'Liliw'),
(211, 'Novaliches', 'Liliw'),
(212, 'Oples', 'Liliw'),
(213, 'Pag-Asa (Poblacion)', 'Liliw'),
(214, 'Palayan', 'Liliw'),
(215, 'Rizal (Poblacion)', 'Liliw'),
(216, 'San Isidro', 'Liliw'),
(217, 'Silangang Bukal', 'Liliw'),
(218, 'Tuy-Baanan', 'Liliw'),
(219, 'Anos', 'Los Baños'),
(220, 'Bagong Silang', 'Los Baños'),
(221, 'Bambang', 'Los Baños'),
(222, 'Batong Malake', 'Los Baños'),
(223, 'Baybayin (Poblacion)', 'Los Baños'),
(224, 'Bayog', 'Los Baños'),
(225, 'Lalakay', 'Los Baños'),
(226, 'Maahas', 'Los Baños'),
(227, 'Malinta', 'Los Baños'),
(228, 'Mayondon', 'Los Baños'),
(229, 'Putho Tuntungin', 'Los Baños'),
(230, 'San Antonio', 'Los Baños'),
(231, 'Tadlak', 'Los Baños'),
(232, 'Timugan (Poblacion)', 'Los Baños'),
(233, 'Barangay Zone I (Poblacion)', 'Luisiana'),
(234, 'Barangay Zone II (Poblacion)', 'Luisiana'),
(235, 'Barangay Zone III (Poblacion)', 'Luisiana'),
(236, 'Barangay Zone IV (Poblacion)', 'Luisiana'),
(237, 'Barangay Zone V (Poblacion)', 'Luisiana'),
(238, 'Barangay Zone VI (Poblacion)', 'Luisiana'),
(239, 'Barangay Zone VII (Poblacion)', 'Luisiana'),
(240, 'Barangay Zone VIII (Poblacion)', 'Luisiana'),
(241, 'De La Paz', 'Luisiana'),
(242, 'San Antonio', 'Luisiana'),
(243, 'San Buenaventura', 'Luisiana'),
(244, 'San Diego', 'Luisiana'),
(245, 'San Isidro', 'Luisiana'),
(246, 'San Jose', 'Luisiana'),
(247, 'San Juan', 'Luisiana'),
(248, 'San Luis', 'Luisiana'),
(249, 'San Pablo', 'Luisiana'),
(250, 'San Pedro', 'Luisiana'),
(251, 'San Rafael', 'Luisiana'),
(252, 'San Roque', 'Luisiana'),
(253, 'San Salvador', 'Luisiana'),
(254, 'Santo Domingo', 'Luisiana'),
(255, 'Santo Tomas', 'Luisiana'),
(256, 'Bagong Silang', 'Lumban'),
(257, 'Balimbingan (Poblacion)', 'Lumban'),
(258, 'Balubad', 'Lumban'),
(259, 'Caliraya', 'Lumban'),
(260, 'Concepcion', 'Lumban'),
(261, 'Lewin', 'Lumban'),
(262, 'Maracta (Poblacion)', 'Lumban'),
(263, 'Maytalang I', 'Lumban'),
(264, 'Maytalang II', 'Lumban'),
(265, 'Primera Parang (Poblacion)', 'Lumban'),
(266, 'Primera Pulo (Poblacion)', 'Lumban'),
(267, 'Salac (Poblacion)', 'Lumban'),
(268, 'Santo Niño (Poblacion)', 'Lumban'),
(269, 'Segunda Parang (Poblacion)', 'Lumban'),
(270, 'Segunda Pulo (Poblacion)', 'Lumban'),
(271, 'Wawa', 'Lumban'),
(272, 'Amuyong', 'Mabitac'),
(273, 'Bayanihan (Poblacion)', 'Mabitac'),
(274, 'Lambac (Poblacion)', 'Mabitac'),
(275, 'Libis ng Nayon (Poblacion)', 'Mabitac'),
(276, 'Lucong', 'Mabitac'),
(277, 'Maligaya (Poblacion)', 'Mabitac'),
(278, 'Masikap (Poblacion)', 'Mabitac'),
(279, 'Matalatala', 'Mabitac'),
(280, 'Nanguma', 'Mabitac'),
(281, 'Numero', 'Mabitac'),
(282, 'Paagahan', 'Mabitac'),
(283, 'Pag-Asa (Poblacion)', 'Mabitac'),
(284, 'San Antonio', 'Mabitac'),
(285, 'San Miguel', 'Mabitac'),
(286, 'Sinagtala (Poblacion)', 'Mabitac'),
(287, 'Alipit', 'Magdalena'),
(288, 'Baanan', 'Magdalena'),
(289, 'Balanac', 'Magdalena'),
(290, 'Bucal', 'Magdalena'),
(291, 'Buenavista', 'Magdalena'),
(292, 'Bungkol', 'Magdalena'),
(293, 'Buo', 'Magdalena'),
(294, 'Burlungan', 'Magdalena'),
(295, 'Cigaras', 'Magdalena'),
(296, 'Halayhayin', 'Magdalena'),
(297, 'Ibabang Atingay', 'Magdalena'),
(298, 'Ibabang Butnong', 'Magdalena'),
(299, 'Ilayang Atingay', 'Magdalena'),
(300, 'Ilayang Butnong', 'Magdalena'),
(301, 'Ilog', 'Magdalena'),
(302, 'Malaking Ambling', 'Magdalena'),
(303, 'Malinao', 'Magdalena'),
(304, 'Maravilla', 'Magdalena'),
(305, 'Munting Ambling', 'Magdalena'),
(306, 'Poblacion', 'Magdalena'),
(307, 'Sabang', 'Magdalena'),
(308, 'Salasad', 'Magdalena'),
(309, 'Tanawan', 'Magdalena'),
(310, 'Tipunan', 'Magdalena'),
(311, 'Amonoy', 'Majayjay'),
(312, 'Bakia', 'Majayjay'),
(313, 'Balanac', 'Majayjay'),
(314, 'Balayong', 'Majayjay'),
(315, 'Banilad', 'Majayjay'),
(316, 'Banti', 'Majayjay'),
(317, 'Bitaoy', 'Majayjay'),
(318, 'Botocan', 'Majayjay'),
(319, 'Bukal', 'Majayjay'),
(320, 'Burgos', 'Majayjay'),
(321, 'Burol', 'Majayjay'),
(322, 'Coralao', 'Majayjay'),
(323, 'Gagalot', 'Majayjay'),
(324, 'Ibabang Banga', 'Majayjay'),
(325, 'Ibabang Bayucain', 'Majayjay'),
(326, 'Ilayang Banga', 'Majayjay'),
(327, 'Ilayang Bayucain', 'Majayjay'),
(328, 'Isabang', 'Majayjay'),
(329, 'Malinao', 'Majayjay'),
(330, 'May-It', 'Majayjay'),
(331, 'Munting Kawayan', 'Majayjay'),
(332, 'Olla', 'Majayjay'),
(333, 'Oobi', 'Majayjay'),
(334, 'Origuel (Poblacion)', 'Majayjay'),
(335, 'Panalaban', 'Majayjay'),
(336, 'Pangil', 'Majayjay'),
(337, 'Panglan', 'Majayjay'),
(338, 'Piit', 'Majayjay'),
(339, 'Pook', 'Majayjay'),
(340, 'Rizal', 'Majayjay'),
(341, 'San Francisco (Poblacion)', 'Majayjay'),
(342, 'San Isidro', 'Majayjay'),
(343, 'San Miguel (Poblacion)', 'Majayjay'),
(344, 'San Roque', 'Majayjay'),
(345, 'Santa Catalina (Poblacion)', 'Majayjay'),
(346, 'Suba', 'Majayjay'),
(347, 'Talortor', 'Majayjay'),
(348, 'Tanawan', 'Majayjay'),
(349, 'Taytay', 'Majayjay'),
(350, 'Villa Nogales', 'Majayjay'),
(351, 'Abo', 'Nagcarlan'),
(352, 'Alibungbungan', 'Nagcarlan'),
(353, 'Alumbrado', 'Nagcarlan'),
(354, 'Balayong', 'Nagcarlan'),
(355, 'Balimbing', 'Nagcarlan'),
(356, 'Balinacon', 'Nagcarlan'),
(357, 'Bambang', 'Nagcarlan'),
(358, 'Banago', 'Nagcarlan'),
(359, 'Banca-Banca', 'Nagcarlan'),
(360, 'Bangcuro', 'Nagcarlan'),
(361, 'Banilad', 'Nagcarlan'),
(362, 'Bayaquitos', 'Nagcarlan'),
(363, 'Buboy', 'Nagcarlan'),
(364, 'Buenavista', 'Nagcarlan'),
(365, 'Buhanginan', 'Nagcarlan'),
(366, 'Bukal', 'Nagcarlan'),
(367, 'Bunga', 'Nagcarlan'),
(368, 'Cabuyew', 'Nagcarlan'),
(369, 'Calumpang', 'Nagcarlan'),
(370, 'Kanluran Kabubuhayan', 'Nagcarlan'),
(371, 'Kanluran Lazaan', 'Nagcarlan'),
(372, 'Labangan', 'Nagcarlan'),
(373, 'Lagulo', 'Nagcarlan'),
(374, 'Lawaguin', 'Nagcarlan'),
(375, 'Maiit', 'Nagcarlan'),
(376, 'Malaya', 'Nagcarlan'),
(377, 'Malinao', 'Nagcarlan'),
(378, 'Manaol', 'Nagcarlan'),
(379, 'Maravilla', 'Nagcarlan'),
(380, 'Nagcalbang', 'Nagcarlan'),
(381, 'Oples', 'Nagcarlan'),
(382, 'Palayan', 'Nagcarlan'),
(383, 'Palina', 'Nagcarlan'),
(384, 'Poblacion I (Poblacion)', 'Nagcarlan'),
(385, 'Poblacion II (Poblacion)', 'Nagcarlan'),
(386, 'Poblacion III (Poblacion)', 'Nagcarlan'),
(387, 'Sabang', 'Nagcarlan'),
(388, 'San Francisco', 'Nagcarlan'),
(389, 'Santa Lucia', 'Nagcarlan'),
(390, 'Sibulan', 'Nagcarlan'),
(391, 'Silangan Ilaya', 'Nagcarlan'),
(392, 'Silangan Kabubuhayan', 'Nagcarlan'),
(393, 'Silangan Lazaan', 'Nagcarlan'),
(394, 'Silangan Napapatid', 'Nagcarlan'),
(395, 'Sinipian', 'Nagcarlan'),
(396, 'Sulsuguin', 'Nagcarlan'),
(397, 'Talahib', 'Nagcarlan'),
(398, 'Talangan', 'Nagcarlan'),
(399, 'Taytay', 'Nagcarlan'),
(400, 'Tipacan', 'Nagcarlan'),
(401, 'Wakat', 'Nagcarlan'),
(402, 'Yukos', 'Nagcarlan'),
(403, 'Bagumbayan (Poblacion)', 'Paete'),
(404, 'Bangkusay (Poblacion)', 'Paete'),
(405, 'Ermita (Poblacion)', 'Paete'),
(406, 'Ibaba del Norte (Poblacion)', 'Paete'),
(407, 'Ibaba del Sur (Poblacion)', 'Paete'),
(408, 'Ilaya del Norte (Poblacion)', 'Paete'),
(409, 'Ilaya del Sur (Poblacion)', 'Paete'),
(410, 'Maytoong (Poblacion)', 'Paete'),
(411, 'Quinale (Poblacion)', 'Paete'),
(412, 'Anibong', 'Pagsanjan'),
(413, 'Barangay I (Poblacion)', 'Pagsanjan'),
(414, 'Barangay II (Poblacion)', 'Pagsanjan'),
(415, 'Biñan', 'Pagsanjan'),
(416, 'Buboy', 'Pagsanjan'),
(417, 'Cabanbanan', 'Pagsanjan'),
(418, 'Calusiche', 'Pagsanjan'),
(419, 'Dingin', 'Pagsanjan'),
(420, 'Lambac', 'Pagsanjan'),
(421, 'Layugan', 'Pagsanjan'),
(422, 'Magdapio', 'Pagsanjan'),
(423, 'Maulawin', 'Pagsanjan'),
(424, 'Pinagsanjan', 'Pagsanjan'),
(425, 'Sabang', 'Pagsanjan'),
(426, 'Sampaloc', 'Pagsanjan'),
(427, 'San Isidro', 'Pagsanjan'),
(428, 'Banilan', 'Pakil'),
(429, 'Baño (Poblacion)', 'Pakil'),
(430, 'Burgos (Poblacion)', 'Pakil'),
(431, 'Casa Real', 'Pakil'),
(432, 'Casinsin', 'Pakil'),
(433, 'Dorado', 'Pakil'),
(434, 'Gonzales (Poblacion)', 'Pakil'),
(435, 'Kabulusan', 'Pakil'),
(436, 'Matikiw', 'Pakil'),
(437, 'Rizal (Poblacion)', 'Pakil'),
(438, 'Saray', 'Pakil'),
(439, 'Taft (Poblacion)', 'Pakil'),
(440, 'Tavera (Poblacion)', 'Pakil'),
(441, 'Balian', 'Pangil'),
(442, 'Dambo', 'Pangil'),
(443, 'Galalan', 'Pangil'),
(444, 'Isla (Poblacion)', 'Pangil'),
(445, 'Mabato-Azufre', 'Pangil'),
(446, 'Natividad (Poblacion)', 'Pangil'),
(447, 'San Jose (Poblacion)', 'Pangil'),
(448, 'Sulib', 'Pangil'),
(449, 'Aplaya', 'Pila'),
(450, 'Bagong Pook', 'Pila'),
(451, 'Bukal', 'Pila'),
(452, 'Bulilan Norte (Poblacion)', 'Pila'),
(453, 'Bulilan Sur (Poblacion)', 'Pila'),
(454, 'Concepcion', 'Pila'),
(455, 'Labuin', 'Pila'),
(456, 'Linga', 'Pila'),
(457, 'Masico', 'Pila'),
(458, 'Mojon', 'Pila'),
(459, 'Pansol', 'Pila'),
(460, 'Pinagbayanan', 'Pila'),
(461, 'San Antonio', 'Pila'),
(462, 'San Miguel', 'Pila'),
(463, 'Santa Clara Norte (Poblacion)', 'Pila'),
(464, 'Santa Clara Sur (Poblacion)', 'Pila'),
(465, 'Tubuan', 'Pila'),
(466, 'Antipolo', 'Rizal'),
(467, 'East Poblacion', 'Rizal'),
(468, 'Entablado', 'Rizal'),
(469, 'Laguan', 'Rizal'),
(470, 'Paule 1', 'Rizal'),
(471, 'Paule 2', 'Rizal'),
(472, 'Pook', 'Rizal'),
(473, 'Tala', 'Rizal'),
(474, 'Talaga', 'Rizal'),
(475, 'Tuy', 'Rizal'),
(476, 'West Poblacion', 'Rizal'),
(477, 'Atisan', 'San Pablo'),
(478, 'Bagong Bayan II-A (Poblacion)', 'San Pablo'),
(479, 'Bagong Pook VI-C (Poblacion)', 'San Pablo'),
(480, 'Barangay I-A (Poblacion)', 'San Pablo'),
(481, 'Barangay I-B (Poblacion)', 'San Pablo'),
(482, 'Barangay II-A (Poblacion)', 'San Pablo'),
(483, 'Barangay II-B (Poblacion)', 'San Pablo'),
(484, 'Barangay II-C (Poblacion)', 'San Pablo'),
(485, 'Barangay II-D (Poblacion)', 'San Pablo'),
(486, 'Barangay II-E (Poblacion)', 'San Pablo'),
(487, 'Barangay II-F (Poblacion)', 'San Pablo'),
(488, 'Barangay III-A (Poblacion)', 'San Pablo'),
(489, 'Barangay III-B (Poblacion)', 'San Pablo'),
(490, 'Barangay III-C (Poblacion)', 'San Pablo'),
(491, 'Barangay III-D (Poblacion)', 'San Pablo'),
(492, 'Barangay III-E (Poblacion)', 'San Pablo'),
(493, 'Barangay III-F (Poblacion)', 'San Pablo'),
(494, 'Barangay IV-A (Poblacion)', 'San Pablo'),
(495, 'Barangay IV-B (Poblacion)', 'San Pablo'),
(496, 'Barangay IV-C (Poblacion)', 'San Pablo'),
(497, 'Barangay V-A (Poblacion)', 'San Pablo'),
(498, 'Barangay V-B (Poblacion)', 'San Pablo'),
(499, 'Barangay V-C (Poblacion)', 'San Pablo'),
(500, 'Barangay V-D (Poblacion)', 'San Pablo'),
(501, 'Barangay VI-A (Poblacion)', 'San Pablo'),
(502, 'Barangay VI-B (Poblacion)', 'San Pablo'),
(503, 'Barangay VI-D (Poblacion)', 'San Pablo'),
(504, 'Barangay VI-E (Poblacion)', 'San Pablo'),
(505, 'Barangay VII-A (Poblacion)', 'San Pablo'),
(506, 'Barangay VII-B (Poblacion)', 'San Pablo'),
(507, 'Barangay VII-C (Poblacion)', 'San Pablo'),
(508, 'Barangay VII-D (Poblacion)', 'San Pablo'),
(509, 'Barangay VII-E (Poblacion)', 'San Pablo'),
(510, 'Bautista', 'San Pablo'),
(511, 'Concepcion', 'San Pablo'),
(512, 'Del Remedio', 'San Pablo'),
(513, 'Dolores', 'San Pablo'),
(514, 'San Antonio 1', 'San Pablo'),
(515, 'San Antonio 2', 'San Pablo'),
(516, 'San Bartolome', 'San Pablo'),
(517, 'San Buenaventura', 'San Pablo'),
(518, 'San Crispin', 'San Pablo'),
(519, 'San Cristobal', 'San Pablo'),
(520, 'San Diego', 'San Pablo'),
(521, 'San Francisco (Calihan)', 'San Pablo'),
(522, 'San Gabriel', 'San Pablo'),
(523, 'San Gregorio', 'San Pablo'),
(524, 'San Ignacio', 'San Pablo'),
(525, 'San Isidro', 'San Pablo'),
(526, 'San Joaquin', 'San Pablo'),
(527, 'San Jose', 'San Pablo'),
(528, 'San Juan', 'San Pablo'),
(529, 'San Lorenzo', 'San Pablo'),
(530, 'San Lucas 1', 'San Pablo'),
(531, 'San Lucas 2', 'San Pablo'),
(532, 'San Marcos', 'San Pablo'),
(533, 'San Mateo', 'San Pablo'),
(534, 'San Miguel', 'San Pablo'),
(535, 'San Nicolas', 'San Pablo'),
(536, 'San Pedro', 'San Pablo'),
(537, 'San Rafael', 'San Pablo'),
(538, 'San Roque', 'San Pablo'),
(539, 'San Vicente', 'San Pablo'),
(540, 'Santa Ana', 'San Pablo'),
(541, 'Santa Catalina', 'San Pablo'),
(542, 'Santa Cruz', 'San Pablo'),
(543, 'Santa Elena', 'San Pablo'),
(544, 'Santa Felomina', 'San Pablo'),
(545, 'Santa Isabel', 'San Pablo'),
(546, 'Santa Maria', 'San Pablo'),
(547, 'Santa Maria Magdalena', 'San Pablo'),
(548, 'Santa Monica', 'San Pablo'),
(549, 'Santa Veronica', 'San Pablo'),
(550, 'Santiago I', 'San Pablo'),
(551, 'Santiago II', 'San Pablo'),
(552, 'Santisimo Rosario', 'San Pablo'),
(553, 'Santo Angel', 'San Pablo'),
(554, 'Santo Cristo', 'San Pablo'),
(555, 'Santo Niño', 'San Pablo'),
(556, 'Soledad', 'San Pablo'),
(557, 'Bagong Silang', 'San Pedro'),
(558, 'Calendola', 'San Pedro'),
(559, 'Chrysanthemum', 'San Pedro'),
(560, 'Cuyab', 'San Pedro'),
(561, 'Estrella', 'San Pedro'),
(562, 'Fatima', 'San Pedro'),
(563, 'G.S.I.S.', 'San Pedro'),
(564, 'Landayan', 'San Pedro'),
(565, 'Langgam', 'San Pedro'),
(566, 'Laram', 'San Pedro'),
(567, 'Magsaysay', 'San Pedro'),
(568, 'Maharlika', 'San Pedro'),
(569, 'Narra', 'San Pedro'),
(570, 'Nueva', 'San Pedro'),
(571, 'Pacita 1', 'San Pedro'),
(572, 'Pacita 2', 'San Pedro'),
(573, 'Poblacion', 'San Pedro'),
(574, 'Riverside', 'San Pedro'),
(575, 'Rosario', 'San Pedro'),
(576, 'Sampaguita Village', 'San Pedro'),
(577, 'San Antonio', 'San Pedro'),
(578, 'San Lorenzo Ruiz', 'San Pedro'),
(579, 'San Roque', 'San Pedro'),
(580, 'San Vicente', 'San Pedro'),
(581, 'Santo Niño', 'San Pedro'),
(582, 'United Bayanihan', 'San Pedro'),
(583, 'United Better Living', 'San Pedro'),
(584, 'Alipit', 'Santa Cruz'),
(585, 'Bagumbayan', 'Santa Cruz'),
(586, 'Barangay I (Poblacion)', 'Santa Cruz'),
(587, 'Barangay II (Poblacion)', 'Santa Cruz'),
(588, 'Barangay III (Poblacion)', 'Santa Cruz'),
(589, 'Barangay IV (Poblacion)', 'Santa Cruz'),
(590, 'Barangay V (Poblacion)', 'Santa Cruz'),
(591, 'Bubukal', 'Santa Cruz'),
(592, 'Calios', 'Santa Cruz'),
(593, 'Duhat', 'Santa Cruz'),
(594, 'Gatid', 'Santa Cruz'),
(595, 'Jasaan', 'Santa Cruz'),
(596, 'Labuin', 'Santa Cruz'),
(597, 'Malinao', 'Santa Cruz'),
(598, 'Oogong', 'Santa Cruz'),
(599, 'Pagsawitan', 'Santa Cruz'),
(600, 'Palasan', 'Santa Cruz'),
(601, 'Patimbao', 'Santa Cruz'),
(602, 'San Jose', 'Santa Cruz'),
(603, 'San Juan', 'Santa Cruz'),
(604, 'San Pablo Norte', 'Santa Cruz'),
(605, 'San Pablo Sur', 'Santa Cruz'),
(606, 'Santisima Cruz', 'Santa Cruz'),
(607, 'Santo Angel Central', 'Santa Cruz'),
(608, 'Santo Angel Norte', 'Santa Cruz'),
(609, 'Santo Angel Sur', 'Santa Cruz'),
(610, 'Adia', 'Santa Maria'),
(611, 'Bagong Pook', 'Santa Maria'),
(612, 'Bagumbayan', 'Santa Maria'),
(613, 'Barangay I (Poblacion)', 'Santa Maria'),
(614, 'Barangay II (Poblacion)', 'Santa Maria'),
(615, 'Barangay III (Poblacion)', 'Santa Maria'),
(616, 'Barangay IV (Poblacion)', 'Santa Maria'),
(617, 'Bubukal', 'Santa Maria'),
(618, 'Cabooan', 'Santa Maria'),
(619, 'Calangay', 'Santa Maria'),
(620, 'Cambuja', 'Santa Maria'),
(621, 'Coralan', 'Santa Maria'),
(622, 'Cueva', 'Santa Maria'),
(623, 'Inayapan', 'Santa Maria'),
(624, 'Jose Laurel, Sr.', 'Santa Maria'),
(625, 'Jose Rizal', 'Santa Maria'),
(626, 'Kayhakat', 'Santa Maria'),
(627, 'Macasipac', 'Santa Maria'),
(628, 'Masinao', 'Santa Maria'),
(629, 'Mataling-Ting', 'Santa Maria'),
(630, 'Pao-o', 'Santa Maria'),
(631, 'Parang Ng Buho', 'Santa Maria'),
(632, 'Santiago', 'Santa Maria'),
(633, 'Talangka', 'Santa Maria'),
(634, 'Tungkod', 'Santa Maria'),
(635, 'Aplaya', 'Santa Rosa'),
(636, 'Balibago', 'Santa Rosa'),
(637, 'Caingin', 'Santa Rosa'),
(638, 'Dila', 'Santa Rosa'),
(639, 'Dita', 'Santa Rosa'),
(640, 'Don Jose', 'Santa Rosa'),
(641, 'Ibaba', 'Santa Rosa'),
(642, 'Kanluran (Poblacion)', 'Santa Rosa'),
(643, 'Labas', 'Santa Rosa'),
(644, 'Macabling', 'Santa Rosa'),
(645, 'Malitlit', 'Santa Rosa'),
(646, 'Malusak (Poblacion)', 'Santa Rosa'),
(647, 'Market Area (Poblacion)', 'Santa Rosa'),
(648, 'Pook', 'Santa Rosa'),
(649, 'Pulong Santa Cruz', 'Santa Rosa'),
(650, 'Santo Domingo', 'Santa Rosa'),
(651, 'Sinalhan', 'Santa Rosa'),
(652, 'Tagapo', 'Santa Rosa'),
(653, 'Acevida', 'Siniloan'),
(654, 'Bagong Pag-Asa (Poblacion)', 'Siniloan'),
(655, 'Bagumbarangay (Poblacion)', 'Siniloan'),
(656, 'Buhay', 'Siniloan'),
(657, 'G. Redor (Poblacion)', 'Siniloan'),
(658, 'Gen. Luna', 'Siniloan'),
(659, 'Halayhayin', 'Siniloan'),
(660, 'J. Rizal (Poblacion)', 'Siniloan'),
(661, 'Kapatalan', 'Siniloan'),
(662, 'Laguio', 'Siniloan'),
(663, 'Liyang', 'Siniloan'),
(664, 'Llavac', 'Siniloan'),
(665, 'Macatad', 'Siniloan'),
(666, 'Magsaysay', 'Siniloan'),
(667, 'Mayatba', 'Siniloan'),
(668, 'Mendiola', 'Siniloan'),
(669, 'P. Burgos', 'Siniloan'),
(670, 'Pandeno', 'Siniloan'),
(671, 'Salubungan', 'Siniloan'),
(672, 'Wawa', 'Siniloan'),
(673, 'Banca-Banca', 'Victoria'),
(674, 'Daniw', 'Victoria'),
(675, 'Masapang', 'Victoria'),
(676, 'Nanhaya (Poblacion)', 'Victoria'),
(677, 'Pagalangan', 'Victoria'),
(678, 'San Benito', 'Victoria'),
(679, 'San Felix', 'Victoria'),
(680, 'San Francisco', 'Victoria'),
(681, 'San Roque (Poblacion)', 'Victoria');

-- --------------------------------------------------------

--
-- Table structure for table `checkout_order`
--

CREATE TABLE `checkout_order` (
  `id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `product_id` varchar(255) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `product_quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `modePayment` varchar(50) DEFAULT NULL,
  `total_overallprice` decimal(10,2) DEFAULT NULL,
  `total_count` int(11) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `checkout_status` varchar(50) DEFAULT NULL,
  `shop_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_address`
--

CREATE TABLE `customer_address` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `municipality` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `full_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_cart`
--

CREATE TABLE `customer_cart` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_size` varchar(255) DEFAULT NULL,
  `product_color` varchar(255) DEFAULT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_typeclothes` varchar(255) NOT NULL,
  `product_style` varchar(255) NOT NULL,
  `wardrobe_status` varchar(255) NOT NULL,
  `checkout_status` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `shop_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_wardrobe`
--

CREATE TABLE `customer_wardrobe` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_size` varchar(255) DEFAULT NULL,
  `product_color` varchar(255) DEFAULT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_typeclothes` varchar(255) NOT NULL,
  `product_style` varchar(255) NOT NULL,
  `wardrobe_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorite_bottoms`
--

CREATE TABLE `favorite_bottoms` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `bottom_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `archive_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorite_outfits`
--

CREATE TABLE `favorite_outfits` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `top_id` int(11) NOT NULL,
  `bottom_id` int(11) NOT NULL,
  `random_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `archive_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorite_product`
--

CREATE TABLE `favorite_product` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `top_id` int(11) DEFAULT NULL,
  `top_color` varchar(50) DEFAULT NULL,
  `bottom_id` int(11) DEFAULT NULL,
  `bottom_color` varchar(50) DEFAULT NULL,
  `archive_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorite_tops`
--

CREATE TABLE `favorite_tops` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `top_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `archive_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `municipalities`
--

CREATE TABLE `municipalities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `district` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `municipalities`
--

INSERT INTO `municipalities` (`id`, `name`, `district`, `province`) VALUES
(1, 'Alaminos', '4', 'Laguna'),
(2, 'Bay', '4', 'Laguna'),
(3, 'Biñan', '4', 'Laguna'),
(4, 'Cabuyao', '4', 'Laguna'),
(5, 'Calamba', '4', 'Laguna'),
(6, 'Calauan', '4', 'Laguna'),
(7, 'Cavinti', '4', 'Laguna'),
(8, 'Famy', '4', 'Laguna'),
(9, 'Kalayaan', '4', 'Laguna'),
(10, 'Liliw', '4', 'Laguna'),
(11, 'Low Baños', '4', 'Laguna'),
(12, 'Luisiana', '4', 'Laguna'),
(13, 'Lumban', '4', 'Laguna'),
(14, 'Mabitac', '4', 'Laguna'),
(15, 'Magdalena', '4', 'Laguna'),
(16, 'Majayjay', '4', 'Laguna'),
(17, 'Nagcarlan', '4', 'Laguna'),
(18, 'Paete', '4', 'Laguna'),
(19, 'Pagsanjan', '4', 'Laguna'),
(20, 'Pakil', '4', 'Laguna'),
(21, 'Pangil', '4', 'Laguna'),
(22, 'Pila', '4', 'Laguna'),
(23, 'San Pablo', '4', 'Laguna'),
(24, 'Rizal', '4', 'Laguna'),
(25, 'San Pedro', '4', 'Laguna'),
(26, 'Santa Cruz', '4', 'Laguna'),
(27, 'Santa Maria', '4', 'Laguna'),
(28, 'Santa Rosa', '4', 'Laguna'),
(29, 'Siniloan', '4', 'Laguna'),
(30, 'Victoria', '4', 'Laguna');

-- --------------------------------------------------------

--
-- Table structure for table `product_allcolors`
--

CREATE TABLE `product_allcolors` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `color_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_allsizes`
--

CREATE TABLE `product_allsizes` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `size_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_colors`
--

CREATE TABLE `product_colors` (
  `id` int(11) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `product_color` varchar(255) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_style` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_list`
--

CREATE TABLE `product_list` (
  `id` int(11) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_brand` varchar(255) DEFAULT NULL,
  `product_type` varchar(255) NOT NULL,
  `product_category` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_description` longtext NOT NULL,
  `product_style` varchar(255) NOT NULL,
  `product_typeclothes` varchar(255) NOT NULL,
  `product_stocks` int(255) NOT NULL,
  `product_status` varchar(255) NOT NULL,
  `new_arrival` varchar(255) NOT NULL,
  `featured_product` varchar(255) NOT NULL,
  `shop_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` int(11) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `product_size` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller_information`
--

CREATE TABLE `seller_information` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `shop_id` varchar(255) NOT NULL,
  `registered_name` varchar(255) NOT NULL,
  `seller_type` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `tin_id` varchar(16) DEFAULT NULL,
  `tax_status` varchar(255) DEFAULT NULL,
  `bir_image` varchar(255) DEFAULT NULL,
  `sworn_declaration` varchar(3) NOT NULL,
  `trade_mark` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller_register`
--

CREATE TABLE `seller_register` (
  `seller_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `store_type` varchar(255) NOT NULL,
  `contact_no` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `shop_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `profile_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wardrobe_bottom`
--

CREATE TABLE `wardrobe_bottom` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `clothes_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wardrobe_top`
--

CREATE TABLE `wardrobe_top` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `clothes_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `checkout_order`
--
ALTER TABLE `checkout_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_address`
--
ALTER TABLE `customer_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_cart`
--
ALTER TABLE `customer_cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_wardrobe`
--
ALTER TABLE `customer_wardrobe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorite_bottoms`
--
ALTER TABLE `favorite_bottoms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorite_outfits`
--
ALTER TABLE `favorite_outfits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorite_product`
--
ALTER TABLE `favorite_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorite_tops`
--
ALTER TABLE `favorite_tops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `municipalities`
--
ALTER TABLE `municipalities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_allcolors`
--
ALTER TABLE `product_allcolors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_allsizes`
--
ALTER TABLE `product_allsizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_colors`
--
ALTER TABLE `product_colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_list`
--
ALTER TABLE `product_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_information`
--
ALTER TABLE `seller_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_register`
--
ALTER TABLE `seller_register`
  ADD PRIMARY KEY (`seller_id`),
  ADD UNIQUE KEY `shop_name` (`shop_name`),
  ADD UNIQUE KEY `contact_no` (`contact_no`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `wardrobe_bottom`
--
ALTER TABLE `wardrobe_bottom`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wardrobe_top`
--
ALTER TABLE `wardrobe_top`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=682;

--
-- AUTO_INCREMENT for table `checkout_order`
--
ALTER TABLE `checkout_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `customer_address`
--
ALTER TABLE `customer_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `customer_cart`
--
ALTER TABLE `customer_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `customer_wardrobe`
--
ALTER TABLE `customer_wardrobe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `favorite_bottoms`
--
ALTER TABLE `favorite_bottoms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `favorite_outfits`
--
ALTER TABLE `favorite_outfits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `favorite_product`
--
ALTER TABLE `favorite_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `favorite_tops`
--
ALTER TABLE `favorite_tops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `municipalities`
--
ALTER TABLE `municipalities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `product_allcolors`
--
ALTER TABLE `product_allcolors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `product_allsizes`
--
ALTER TABLE `product_allsizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `product_category`
--
ALTER TABLE `product_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `product_colors`
--
ALTER TABLE `product_colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=631;

--
-- AUTO_INCREMENT for table `product_list`
--
ALTER TABLE `product_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT for table `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=612;

--
-- AUTO_INCREMENT for table `seller_information`
--
ALTER TABLE `seller_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `seller_register`
--
ALTER TABLE `seller_register`
  MODIFY `seller_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `wardrobe_bottom`
--
ALTER TABLE `wardrobe_bottom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `wardrobe_top`
--
ALTER TABLE `wardrobe_top`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
