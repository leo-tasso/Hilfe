-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2023 at 06:07 PM
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
-- Database: `hilfedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `postinterventi`
--

CREATE TABLE `postinterventi` (
  `idPostIntervento` int(11) NOT NULL,
  `TitoloPost` varchar(100) DEFAULT NULL,
  `DescrizionePost` varchar(500) DEFAULT NULL,
  `DataIntervento` datetime DEFAULT NULL,
  `DataPubblicazione` datetime DEFAULT NULL,
  `PersoreRichieste` int(10) UNSIGNED DEFAULT NULL,
  `PosizioneLongitudine` decimal(6,0) DEFAULT NULL,
  `PosizioneLatitudine` decimal(6,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `postinterventi`
--

INSERT INTO `postinterventi` (`idPostIntervento`, `TitoloPost`, `DescrizionePost`, `DataIntervento`, `DataPubblicazione`, `PersoreRichieste`, `PosizioneLongitudine`, `PosizioneLatitudine`) VALUES
(1, 'Sgombro acqua', 'Ciao! Avrei bisogno di qualcuno che venga ad aiutare a togliere l\'acqua accumulata nel garage di casa mia.\r\n', '2023-12-07 17:51:18', '2023-12-05 17:51:18', 20, 42, 12),
(2, 'Sgombro casa', 'Buongiorno! C\'è una signora in fondo alla mia via che ha subito molti danni a causa dell\'alluvione e avrebbe bisogno per svuotare casa e pulirla a fondo da tutto il fango. Qualcuno sarebbe disponibile per andare in gruppo ad aiutarla? \r\n', '2023-12-21 18:00:48', '2023-12-01 18:00:48', 2, 42, 15),
(3, 'Pulizia casa', 'Ciao a tutti, la mia via di casa è completamente ricoperta dal fango, qualcuno è disponibile per venire ad aiutarci a ripulirla?\r\n', '2023-12-09 18:00:48', '2023-12-04 18:00:48', 20, 42, 13),
(4, 'Richiesta rifocillamento', 'Ciao! Nella mia via siamo ormai tanti ad aiutare a ripulire però se qualcuno si offrisse di portare qualche panino, piadina o un pasto qualsiasi ci farebbe un grande favore. Sarebbero graditissime anche delle bottiglie d\'acqua. Grazie. \r\n\r\n', '2023-12-14 18:02:42', '2023-12-03 18:02:42', 1, 42, 15),
(6, 'Aiuto materiali', 'Buongiorno! La mia via è stata particolarmente danneggiata e molte persone stanno venendo ad aiutare però i materiali non sono sufficienti. Chiunque avesse qualcuno dei materiali elencati sotto ci farebbe un favore enorme se ce li portasse. Grazie in anticipo. \r\n', '2023-12-22 18:04:34', '2023-12-03 18:04:34', 5, 42, 12),
(7, 'Pulizia parco', 'Qualcuno oggi è disponibile per venire a ripulire il parco urbano?', '2023-12-08 18:04:34', '2023-12-04 18:04:34', 20, 42, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `postinterventi`
--
ALTER TABLE `postinterventi`
  ADD PRIMARY KEY (`idPostIntervento`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
