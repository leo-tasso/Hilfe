-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 04, 2024 at 12:44 PM
-- Server version: 10.1.44-MariaDB-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `HilfeDb`
--

-- --------------------------------------------------------

--
-- Table structure for table `Accessi`
--

CREATE TABLE `Accessi` (
  `idUser` int(11) NOT NULL,
  `tempo` bigint(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Commento`
--

CREATE TABLE `Commento` (
  `Autore` int(11) NOT NULL,
  `RelativoA` int(11) NOT NULL,
  `Testo` varchar(1000) DEFAULT NULL,
  `idCommento` int(11) NOT NULL,
  `DataPubblicazione` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Commento`
--

INSERT INTO `Commento` (`Autore`, `RelativoA`, `Testo`, `idCommento`, `DataPubblicazione`) VALUES
(30, 14, 'credo di essermi innamorato', 1, '2024-01-04 05:55:12'),
(29, 14, 'anche io mi sono innamorata', 2, '2024-01-04 05:56:36'),
(28, 16, 'WOW stupendi!!', 3, '2024-01-04 06:08:39');

-- --------------------------------------------------------

--
-- Table structure for table `Interventi`
--

CREATE TABLE `Interventi` (
  `idPostInterventi` int(11) NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Interventi`
--

INSERT INTO `Interventi` (`idPostInterventi`, `idUser`) VALUES
(1, 28),
(2, 5),
(3, 16),
(4, 7),
(5, 1),
(6, 17),
(7, 19),
(8, 15),
(9, 2),
(10, 9),
(11, 8),
(12, 18),
(13, 3),
(14, 6),
(15, 11),
(16, 14),
(17, 10),
(18, 13),
(19, 12),
(20, 29),
(20, 31),
(22, 29),
(22, 30),
(22, 31),
(22, 32);

-- --------------------------------------------------------

--
-- Table structure for table `Like`
--

CREATE TABLE `Like` (
  `idEmettitore` int(11) NOT NULL,
  `PostRelativo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Like`
--

INSERT INTO `Like` (`idEmettitore`, `PostRelativo`) VALUES
(1, 3),
(1, 4),
(1, 5),
(2, 10),
(2, 11),
(2, 12),
(3, 1),
(3, 2),
(3, 3),
(4, 8),
(4, 9),
(4, 10),
(5, 5),
(5, 6),
(5, 7),
(6, 12),
(6, 13),
(7, 1),
(7, 4),
(7, 5),
(9, 6),
(9, 7),
(9, 8),
(11, 1),
(11, 2),
(12, 10),
(12, 11),
(13, 8),
(13, 9),
(14, 6),
(16, 3),
(16, 4),
(17, 12),
(17, 13),
(29, 14),
(29, 15),
(29, 16),
(30, 14),
(30, 15),
(31, 14),
(31, 15),
(32, 16);

-- --------------------------------------------------------

--
-- Table structure for table `Materiale`
--

CREATE TABLE `Materiale` (
  `idMateriale` int(11) NOT NULL,
  `DescrizioneMateriale` varchar(200) DEFAULT NULL,
  `Unita` int(10) UNSIGNED DEFAULT NULL,
  `idPostIntervento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Materiale`
--

INSERT INTO `Materiale` (`idMateriale`, `DescrizioneMateriale`, `Unita`, `idPostIntervento`) VALUES
(1, 'Secchi di Vernice', 20, 1),
(2, 'Rulli da Pittura', 10, 1),
(3, 'Martelli', 15, 1),
(4, 'Chiodi', 100, 1),
(5, 'Sacchi di Calce', 5, 1),
(6, 'Generi Alimentari in Scatola', 50, 2),
(7, 'Pasta', 20, 2),
(8, 'Riso', 15, 2),
(9, 'Lattine di Pomodoro', 30, 2),
(10, 'Olio dOliva', 10, 2),
(11, 'Viti', 200, 3),
(12, 'Legname', 50, 3),
(13, 'Attrezzi da Elettricista', 8, 3),
(14, 'Tubi idraulici', 15, 3),
(15, 'Manodopera specializzata', 5, 3),
(16, 'Mattoni', 100, 4),
(17, 'Cemento', 30, 4),
(18, 'Malta', 15, 4),
(19, 'Trapani', 5, 4),
(20, 'Tende da sole', 10, 4),
(21, 'Pitture', 25, 5),
(22, 'Pallet di cibo', 40, 5),
(23, 'Coperte', 30, 5),
(24, 'Asciugamani', 50, 5),
(25, 'Lampadine LED', 30, 6),
(26, 'Cavi elettrici', 20, 6),
(27, 'Pitture murali', 15, 6),
(30, 'forza di volontà', 2, 22),
(31, 'pallone', 1, 23),
(32, 'pala', 1, 26),
(33, 'secchio', 1, 26);

-- --------------------------------------------------------

--
-- Table structure for table `Notifica`
--

CREATE TABLE `Notifica` (
  `idNotifica` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `TestoNotifica` varchar(500) DEFAULT NULL,
  `Letta` tinyint(4) NOT NULL,
  `DataCreazione` datetime NOT NULL,
  `idUserGeneratore` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Notifica`
--

INSERT INTO `Notifica` (`idNotifica`, `idUser`, `TestoNotifica`, `Letta`, `DataCreazione`, `idUserGeneratore`) VALUES
(1, 1, 'Il tuo post ha ricevuto un like!', 0, '2023-02-15 14:45:00', 5),
(2, 1, 'Nuovo commento sul tuo post', 0, '2023-03-05 16:30:00', 5),
(3, 1, 'Hai ricevuto un messaggio privato', 0, '2023-04-10 09:15:00', 5),
(4, 2, 'Il tuo post è stato condiviso!', 0, '2023-05-20 11:00:00', 5),
(5, 2, 'Il tuo post ha ricevuto un like!', 0, '2023-06-25 10:30:00', 5),
(6, 2, 'Nuovo commento sul tuo post', 0, '2023-07-12 15:45:00', 5),
(7, 3, 'Il tuo post è stato condiviso!', 0, '2023-08-18 12:00:00', 5),
(8, 3, 'Hai ricevuto un messaggio privato', 0, '2023-09-03 08:30:00', 17),
(9, 3, 'Il tuo post ha ricevuto un like!', 0, '2023-10-15 14:15:00', 1),
(10, 1, 'Il tuo post ha ricevuto un like!', 0, '2023-11-20 16:45:00', 2),
(11, 2, 'Il tuo post è stato condiviso!', 0, '2023-12-05 09:00:00', 3),
(12, 3, 'Hai ricevuto un messaggio privato', 0, '2024-01-10 11:30:00', 4),
(13, 1, 'Il tuo post è stato condiviso!', 0, '2024-02-25 15:00:00', 5),
(14, 2, 'Hai ricevuto un messaggio privato', 0, '2024-03-08 10:45:00', 6),
(15, 3, 'Il tuo post ha ricevuto un like!', 0, '2024-04-12 13:30:00', 7),
(16, 1, 'Nuovo commento sul tuo post', 0, '2024-05-20 16:15:00', 9),
(17, 2, 'Il tuo post è stato condiviso!', 0, '2024-06-28 09:45:00', 11),
(18, 3, 'Hai ricevuto un messaggio privato', 0, '2024-07-15 11:00:00', 12),
(19, 1, 'Il tuo post ha ricevuto un like!', 0, '2024-08-22 14:30:00', 13),
(20, 2, 'Nuovo commento sul tuo post', 0, '2024-09-10 08:00:00', 16),
(21, 3, 'Il tuo post è stato condiviso!', 0, '2024-10-18 12:45:00', 17),
(22, 28, 'Ha commentato il tuo post', 1, '2024-01-03 16:26:08', 28),
(23, 1, 'Parteciperà all\'evento', 0, '2024-01-03 17:53:11', 28),
(24, 30, 'Ha salvato un tuo annuccio', 1, '2024-01-04 05:54:02', 30),
(25, 30, 'Parteciperà all\'evento', 1, '2024-01-04 05:54:03', 30),
(26, 28, 'Piace il tuo post', 1, '2024-01-04 05:54:31', 30),
(27, 28, 'Ha commentato il tuo post', 1, '2024-01-04 05:55:12', 30),
(28, 28, 'Piace il tuo post', 1, '2024-01-04 05:55:16', 30),
(29, 28, 'Piace il tuo post', 1, '2024-01-04 05:55:39', 29),
(30, 28, 'Ha commentato il tuo post', 1, '2024-01-04 05:56:36', 29),
(31, 28, 'Piace il tuo post', 1, '2024-01-04 05:56:48', 29),
(32, 28, 'Parteciperà all\'evento', 1, '2024-01-04 05:57:06', 29),
(33, 28, 'Ha salvato un tuo annuccio', 1, '2024-01-04 05:57:09', 29),
(34, 30, 'Parteciperà all\'evento', 1, '2024-01-04 05:57:22', 29),
(35, 30, 'Ha salvato un tuo annuccio', 1, '2024-01-04 05:57:51', 29),
(36, 29, 'Piace il tuo post', 1, '2024-01-04 05:58:47', 29),
(37, 30, 'Non parteciperà più all\'evento', 1, '2024-01-04 06:03:50', 29),
(38, 30, 'Parteciperà all\'evento', 1, '2024-01-04 06:04:16', 29),
(39, 29, 'Ha commentato il tuo post', 1, '2024-01-04 06:08:39', 28),
(40, 30, 'Parteciperà all\'evento', 1, '2024-01-04 10:47:26', 31),
(41, 30, 'Non parteciperà più all\'evento', 1, '2024-01-04 10:47:38', 31),
(42, 30, 'Parteciperà all\'evento', 1, '2024-01-04 10:47:46', 31),
(43, 28, 'Piace il tuo post', 1, '2024-01-04 11:59:48', 31),
(44, 28, 'Piace il tuo post', 1, '2024-01-04 11:59:49', 31),
(45, 28, 'Parteciperà all\'evento', 1, '2024-01-04 11:59:54', 31),
(46, 28, 'Ha modificato il post a cui partecipi', 0, '2024-01-04 12:27:53', 29),
(47, 28, 'Ha modificato il post a cui partecipi', 0, '2024-01-04 12:27:53', 31),
(48, 28, 'Ha modificato il post a cui partecipi', 0, '2024-01-04 12:31:35', 29),
(49, 28, 'Ha modificato il post a cui partecipi', 0, '2024-01-04 12:31:35', 31),
(50, 28, 'Ha salvato un tuo annuccio', 0, '2024-01-04 12:32:24', 32),
(51, 28, 'Ha modificato il post a cui partecipi', 0, '2024-01-04 12:32:32', 29),
(52, 28, 'Ha modificato il post a cui partecipi', 0, '2024-01-04 12:32:32', 31),
(53, 29, 'Piace il tuo post', 0, '2024-01-04 12:33:42', 32),
(54, 30, 'Parteciperà all\'evento', 0, '2024-01-04 12:40:52', 32);

-- --------------------------------------------------------

--
-- Table structure for table `PostComunicazioni`
--

CREATE TABLE `PostComunicazioni` (
  `idPostComunicazione` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `TitoloPost` varchar(100) NOT NULL,
  `DescrizionePost` varchar(500) NOT NULL,
  `Foto` varchar(100) NOT NULL,
  `DataPubblicazione` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `PostComunicazioni`
--

INSERT INTO `PostComunicazioni` (`idPostComunicazione`, `idUser`, `TitoloPost`, `DescrizionePost`, `Foto`, `DataPubblicazione`) VALUES
(1, 1, 'Grazie ai Volontari!', 'Un grande ringraziamento a tutti coloro che hanno partecipato al progetto di ristrutturazione del centro comunitario!', 'centro_comunitario.jpg', '0000-00-00 00:00:00'),
(2, 2, 'Successo della Raccolta Fondi', 'Grazie alla vostra generosità, abbiamo raccolto cibo sufficiente per supportare il rifugio locale per il prossimo mese!', 'raccolta_fondi.jpg', '0000-00-00 00:00:00'),
(3, 3, 'Completato il Progetto Architettura', 'Sono felice di annunciare che il nostro ultimo progetto architettonico è stato completato con successo! Grazie a tutti per il supporto.', 'progetto_architettura.jpg', '0000-00-00 00:00:00'),
(4, 4, 'Concerto Memorabile', 'Grazie a tutti coloro che hanno partecipato al concerto! È stata un esperienza indimenticabile. Aspettatevi nuove date in futuro!', 'concerti_musicali.jpg', '0000-00-00 00:00:00'),
(5, 5, 'Guida Turistica Napoli', 'Ringrazio tutti coloro che hanno apprezzato la guida turistica su Napoli. Se avete altri suggerimenti o richieste, fatemelo sapere!', 'guida_turistica.jpg', '0000-00-00 00:00:00'),
(6, 6, 'Successo nella Ricerca Medica', 'Condivido con gioia i risultati positivi della mia ultima ricerca medica. Grazie a tutti i colleghi che hanno contribuito a questo successo.', 'ricerca_medica.jpg', '0000-00-00 00:00:00'),
(7, 7, 'Lezioni di Pittura: Un Esperienza Artistica', 'Ringrazio tutti gli studenti che hanno partecipato alle lezioni di pittura. Le vostre opere sono state straordinarie!', 'lezioni_pittura.jpg', '0000-00-00 00:00:00'),
(8, 8, 'Grazie per le Apprezzate Ricette della Nonna', 'Un caloroso ringraziamento a tutti coloro che hanno apprezzato e provato le ricette della nonna. Continuate a cucinare con amore!', 'ricette_nonna.jpg', '0000-00-00 00:00:00'),
(9, 9, 'Esperienze di Volontariato con Animali', 'Ringrazio tutti i volontari che hanno dedicato il loro tempo al rifugio per animali. Le vostre azioni fanno la differenza!', 'volontariato_animali.jpg', '0000-00-00 00:00:00'),
(10, 10, 'Suggerimenti per lo Studio: Condividiamo il Successo', 'Grazie a tutti coloro che hanno condiviso il loro successo nell applicare i suggerimenti per lo studio. Continuate a brillare!', 'studio_suggerimenti.jpg', '0000-00-00 00:00:00'),
(11, 11, 'Esperienze di Viaggio: Alla Scoperta di Nuovi Orizzonti', 'Ringrazio tutti coloro che mi hanno ispirato a esplorare nuove destinazioni. Avventure incredibili e nuovi orizzonti!', 'viaggio_esperienze.jpg', '0000-00-00 00:00:00'),
(12, 12, 'Yoga Online: Benessere e Gratitudine', 'Grazie a tutti coloro che partecipano alle lezioni di yoga online. La pratica continua a portare benessere e gratitudine nelle nostre vite.', 'lezioni_yoga.jpg', '0000-00-00 00:00:00'),
(13, 13, 'Imparare a Suonare la Chitarra: Celebrare la Musica', 'Un ringraziamento speciale a tutti gli appassionati che stanno imparando a suonare la chitarra insieme. Celebrare la magia della musica!', 'tutorial_chitarra.jpg', '0000-00-00 00:00:00'),
(14, 28, 'testh1', 'gfegge', '', '2024-01-04 12:41:48'),
(15, 28, 'Io', 'dt', '', '2024-01-04 12:39:05'),
(16, 29, 'Family', 'che bella famiglia e soprattutto che bel cane ', 'IMG_2028.HEIC_page-0001.jpg', '2024-01-04 05:58:34'),
(17, 32, 'Pulizia Parco Urbano', 'Dopo giorni di lavoro finalmente il parco è tornato come prima, missione compiuta :) ', 'parco.jpg', '2024-01-04 12:39:09');

-- --------------------------------------------------------

--
-- Table structure for table `PostInterventi`
--

CREATE TABLE `PostInterventi` (
  `idPostIntervento` int(11) NOT NULL,
  `TitoloPost` varchar(100) DEFAULT NULL,
  `DescrizionePost` varchar(500) DEFAULT NULL,
  `DataIntervento` datetime DEFAULT NULL,
  `DataPubblicazione` datetime DEFAULT NULL,
  `PersoneRichieste` int(10) UNSIGNED DEFAULT NULL,
  `PosizioneLongitudine` decimal(14,10) DEFAULT NULL,
  `PosizioneLatitudine` decimal(14,10) DEFAULT NULL,
  `Indirizzo` varchar(500) DEFAULT NULL,
  `Autore_idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `PostInterventi`
--

INSERT INTO `PostInterventi` (`idPostIntervento`, `TitoloPost`, `DescrizionePost`, `DataIntervento`, `DataPubblicazione`, `PersoneRichieste`, `PosizioneLongitudine`, `PosizioneLatitudine`, `Indirizzo`, `Autore_idUser`) VALUES
(1, 'Ristrutturazione Centro Comunitario', 'Cerchiamo volontari per aiutare nella ristrutturazione del centro comunitario. Portate i vostri attrezzi!', '2023-02-01 10:00:00', '2023-01-15 12:00:00', 5, '41.9028000000', '12.4964000000', 'Via dei Mille 789, Roma', 1),
(2, 'Raccolta Fondi per il Rifugio Locale', 'Stiamo raccogliendo cibo non deperibile per il rifugio per senza tetto. Unitevi a noi!', '2023-03-10 14:30:00', '2023-03-01 10:00:00', 3, '45.4642000000', '9.1900000000', 'Via della Solidarietà 123, Milano', 2),
(3, 'Progetto Piantumazione Alberi', 'Partecipa al progetto di piantumazione alberi nel parco cittadino. Abbiamo bisogno di mani volontarie!', '2023-02-15 09:30:00', '2023-02-01 15:30:00', 8, '45.0703000000', '7.6869000000', 'Parco Cavour, Torino', 3),
(4, 'Supporto Didattico per Bambini', 'Offri supporto didattico ai bambini della scuola elementare. Aiutaci a creare un ambiente educativo positivo!', '2023-03-05 16:00:00', '2023-02-20 08:45:00', 4, '45.4642000000', '9.1900000000', 'Via dei Licei 456, Milano', 4),
(5, 'Corso di Cucina per Anziani', 'Organizziamo un corso di cucina per anziani. Cerchiamo cuochi volontari per condividere le proprie competenze!', '2023-02-10 11:00:00', '2023-01-25 14:30:00', 6, '40.8522000000', '14.2681000000', 'Centro Sociale, Napoli', 5),
(6, 'Spettacolo di Musica dal Vivo', 'Unisciti a noi per uno spettacolo di musica dal vivo nella piazza principale. Cerchiamo musicisti e volontari!', '2023-03-20 20:00:00', '2023-03-05 18:30:00', 10, '44.4949000000', '11.3426000000', 'Piazza Maggiore, Bologna', 6),
(7, 'Supporto Legale per Associazioni', 'Avvocati volontari richiesti per fornire consulenza legale gratuita alle associazioni locali. Aiutaci a sostenere la comunità!', '2023-02-25 14:30:00', '2023-02-10 09:15:00', 3, '45.4342000000', '12.3388000000', 'Ponte degli Scalzi, Venezia', 7),
(8, 'Incontro Psicologico di Gruppo', 'Partecipa all incontro psicologico di gruppo per condividere esperienze e supportarci a vicenda. Tutti sono i benvenuti!', '2023-03-15 18:00:00', '2023-02-28 16:45:00', 6, '44.4056000000', '8.9463000000', 'Via XX Settembre 505, Genova', 8),
(9, 'Concerto Benefico per Ospedale', 'Organizziamo un concerto benefico per raccogliere fondi per l ospedale locale. Cerchiamo artisti e volontari!', '2023-02-12 19:30:00', '2023-01-27 17:00:00', 8, '38.1157000000', '13.3615000000', 'Via Maqueda 606, Palermo', 9),
(10, 'Supporto Sanitario per Emergenza', 'Personale medico volontario richiesto per fornire supporto sanitario in caso di emergenza. Aiutaci a salvare vite!', '2023-03-01 08:00:00', '2023-02-15 12:30:00', 5, '37.5079000000', '15.0830000000', 'Via Etnea 707, Catania', 10),
(11, 'Progetto di Architettura Sostenibile', 'Architetti volontari necessari per il progetto di costruzione di un edificio sostenibile. Contribuisci alla nostra visione verde!', '2023-02-18 14:00:00', '2023-02-03 10:45:00', 7, '40.7850000000', '14.3735000000', 'Corso Vittorio Emanuele 808, Torre del Greco', 11),
(12, 'Laboratorio di Pittura per Bambini', 'Artisti volontari cercati per condurre un laboratorio di pittura per bambini. Stimoliamo la creatività dei più giovani!', '2023-03-10 16:30:00', '2023-02-25 14:15:00', 4, '38.1938000000', '15.5522000000', 'Via Garibaldi 909, Messina', 12),
(13, 'Lezioni di Volo per Bambini', 'Piloti volontari richiesti per offrire lezioni di volo per bambini. Facciamo volare i sogni!', '2023-02-22 11:00:00', '2023-02-07 09:30:00', 6, '40.6822000000', '14.7681000000', 'Corso Umberto I 010, Salerno', 13),
(14, 'Corsi di Cucina Salutare', 'Nutrizionisti e cuochi volontari cercati per tenere corsi di cucina salutare. Promuoviamo uno stile di vita sano!', '2023-03-05 15:45:00', '2023-02-18 13:15:00', 5, '44.6478000000', '10.9254000000', 'Via Emilia 111, Modena', 14),
(15, 'Presentazione Letteraria', 'Scrittori e appassionati di libri volontari richiesti per la presentazione di nuovi romanzi locali. Unisciti a noi per una serata letteraria!', '2023-02-28 18:00:00', '2023-02-13 16:45:00', 7, '44.8272000000', '11.5795000000', 'Via della Libertà 707, Palermo', 15),
(16, 'Assistenza Tecnologica per Anziani', 'Appassionati di tecnologia volontari richiesti per fornire assistenza agli anziani nell uso di dispositivi digitali. Aiutaci a connettere le generazioni!', '2023-03-15 10:00:00', '2023-03-01 08:30:00', 4, '44.4056000000', '8.9327000000', 'Corso Vittorio Emanuele 505, Genova', 16),
(17, 'Corso di Giardinaggio Urbano', 'Giardinieri volontari necessari per insegnare il giardinaggio urbano ai residenti. Rendiamo la città più verde!', '2023-02-10 14:30:00', '2023-01-26 12:15:00', 6, '37.9838000000', '15.2083000000', 'Via Roma 606, Palermo', 17),
(18, 'Supporto Logistico per Emergenza', 'Volontari logistici richiesti per coordinare le risorse durante situazioni di emergenza. Unisciti al nostro team!', '2023-03-01 09:00:00', '2023-02-15 13:45:00', 5, '41.9028000000', '12.4964000000', 'Via dei Mille 789, Roma', 18),
(19, 'Workshop di Fotografia', 'Fotografi e appassionati di fotografia volontari richiesti per condurre un workshop per principianti. Condividi la tua passione!', '2023-02-18 16:45:00', '2023-02-04 14:30:00', 8, '45.4642000000', '9.1899000000', 'Via della Solidarietà 123, Milano', 19),
(20, 'aiuto a forlì', 'serve molto aiuto', '2020-10-20 00:00:00', '0000-00-00 00:00:00', 5, '44.2223807000', '12.0400206000', 'piazza saffi forlì', 28),
(22, 'esame', 'Ho bisogno di aiuto per studiare per il mio esame del 10/01', '2024-05-01 10:00:00', '2024-01-04 05:53:40', 5, '44.2223807000', '12.0400206000', 'piazza saffi', 30),
(23, 'partita di Basket', 'si cercano partecipante per una partita di basket, il campetto vi aspetta', '2024-02-12 15:30:00', '2024-01-04 11:37:46', 6, '44.3457856000', '11.7177062000', 'Via Alfredo Oriani, 2/4, 40026 Imola BO', 29),
(24, 'yoga', 'Apre un nuovo corso di Yoga nel centro di Imola. vi aspettiamo numerosi per l\'inaugurazione durante la quale ci sarà una lezione gratuita e un buffet in seguito', '2024-01-30 16:00:00', '2024-01-04 11:59:10', 8, '44.3708521000', '11.7353354000', 'Via Ercolani, 15, 40026 Imola BO', 31),
(25, 'Cena di beneficienza', 'Siete tutti invitati alla serata di beneficienza per i bambini poveri in africa. ', '2024-02-15 20:30:00', '2024-01-04 12:03:13', 15, NULL, NULL, 'Viale Dante Alighieri, 20, Imola, BO', 31),
(26, 'Pulizia Parco Urbano', 'L\'alluvione ha causato parecchi danni al parco urbano di Forlì e c\'è bisogno di molto aiuto. Organizziamo un bel gruppo per andare ad aiutare nel weekend? ', '2023-12-17 14:30:00', '0000-00-00 00:00:00', 30, '44.2140910500', '12.0299409524', 'Parco Urbano Franco Agosto, Forlì', 32);

-- --------------------------------------------------------

--
-- Table structure for table `PostSalvati`
--

CREATE TABLE `PostSalvati` (
  `idPostInterventi` int(11) NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `PostSalvati`
--

INSERT INTO `PostSalvati` (`idPostInterventi`, `idUser`) VALUES
(20, 29),
(20, 32),
(22, 29),
(22, 30);

-- --------------------------------------------------------

--
-- Table structure for table `Seguiti`
--

CREATE TABLE `Seguiti` (
  `idSeguace` int(11) NOT NULL,
  `idSeguito` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Seguiti`
--

INSERT INTO `Seguiti` (`idSeguace`, `idSeguito`) VALUES
(1, 7),
(1, 13),
(1, 19),
(2, 10),
(2, 15),
(2, 17),
(3, 5),
(3, 8),
(3, 11),
(4, 6),
(4, 8),
(4, 12),
(5, 1),
(5, 9),
(5, 12),
(6, 4),
(6, 15),
(7, 9),
(7, 17),
(8, 2),
(8, 4),
(8, 13),
(9, 11),
(9, 17),
(9, 19),
(10, 3),
(10, 14),
(11, 6),
(12, 6),
(12, 14),
(13, 10),
(13, 16),
(14, 9),
(14, 11),
(15, 3),
(15, 5),
(16, 13),
(16, 19),
(17, 2),
(17, 7),
(18, 4),
(18, 14),
(19, 1),
(19, 16),
(28, 29),
(29, 28),
(29, 30),
(30, 28),
(30, 29),
(31, 28),
(31, 29),
(31, 30),
(32, 28),
(32, 29),
(32, 30);

-- --------------------------------------------------------

--
-- Table structure for table `Token`
--

CREATE TABLE `Token` (
  `idUser` int(11) NOT NULL,
  `TokenValue` varchar(100) NOT NULL,
  `CreationTime` bigint(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Token`
--

INSERT INTO `Token` (`idUser`, `TokenValue`, `CreationTime`) VALUES
(28, '656d90c6df48f1b5bc21a9c990a617eb0334d5fb04e746d8597300a00093b9cc', 1704370070),
(28, '874e3677f4fb79633b0bd7efabb2860f53570a11c201817d6a5809a7f96e59db', 1704381903),
(28, '90bd273d96a2c3b16d02cb1b120c7c00224e9e39b196222977a7b714f1ca8d27', 1704391856),
(29, '21a8e6901d83241ec55f6f3df0808baded947217c1fe9bc533e6fae922cd5419', 1704391815),
(29, '2ca6e93cc6ffb81744208e3752d0c6453186132581b0a39de6026974b47f3f7c', 1704368558),
(29, 'a9fccf166d617a34ff8923c3a0b1a291ab0fc54c3499e7e0675fb3970082cb7b', 1704389732),
(29, 'f1f0e7512c02f7ec81a9f7f9117e2ceb2bd4b4bb30a2f669b67e0b46a4cdc6ed', 1704369331),
(30, '604b364b04d7295f49777ce8d01c88f51362bfea8a5f6bd03e96dc3a46983c80', 1704391485),
(30, '83266eb85c21248ebabe4cdb8c0aac67e7b02ab865ae8e7b05aaf827a011a45b', 1704391687),
(31, '53a955a1987a2048a930a44cb92daf631a99b9db0f543854a85f1442a0e29883', 1704391651),
(31, 'a139a39192393c44609a0e4a9e9e3084603cc05cac8949f92a4c506a86dd0692', 1704392003),
(31, 'eea08b45eb274565c850f7070bed785591c0b64859801c3e9a30f541be9e7ba0', 1704390903);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `idUser` int(11) NOT NULL,
  `Name` varchar(45) DEFAULT NULL,
  `Surname` varchar(45) DEFAULT NULL,
  `PhoneNumber` varchar(45) DEFAULT NULL,
  `Email` varchar(45) NOT NULL,
  `Salt` varchar(100) DEFAULT NULL,
  `Password` varchar(500) DEFAULT NULL,
  `Bio` varchar(1000) DEFAULT NULL,
  `Birthday` date DEFAULT NULL,
  `PubKey` varchar(45) DEFAULT NULL,
  `FotoProfilo` varchar(100) DEFAULT NULL,
  `Username` varchar(45) NOT NULL,
  `Address` varchar(500) DEFAULT NULL,
  `AddressLat` decimal(14,10) DEFAULT NULL,
  `AddressLong` decimal(14,10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`idUser`, `Name`, `Surname`, `PhoneNumber`, `Email`, `Salt`, `Password`, `Bio`, `Birthday`, `PubKey`, `FotoProfilo`, `Username`, `Address`, `AddressLat`, `AddressLong`) VALUES
(1, 'Mario', 'Rossi', '3331234567', 'mario.rossi@example.com', 'salt123', 'hashedpassword123', 'Sviluppatore Software', '1980-06-15', 'pubblica123', NULL, 'mario_rossi', 'Via Roma 123, Roma', '41.9028000000', '12.4964000000'),
(2, 'Laura', 'Bianchi', '3499876543', 'laura.bianchi@example.com', 'salt456', 'hashedpassword456', 'Specialista in Marketing', '1992-03-20', 'pubblica456', NULL, 'laura_bianchi', 'Via Verdi 456, Milano', '45.4642000000', '9.1900000000'),
(3, 'Giovanni', 'Verdi', '3333333333', 'giovanni.verdi@example.com', 'salt789', 'hashedpassword789', 'Ingegnere Elettrico', '1988-12-10', 'pubblica789', NULL, 'giovanni_verdi', 'Via Garibaldi 789, Torino', '45.0703000000', '7.6869000000'),
(4, 'Francesca', 'Neri', '3334445555', 'francesca.neri@example.com', 'salt101', 'hashedpassword101', 'Infermiera', '1985-09-05', 'pubblica101', NULL, 'francesca_neri', 'Via Dante 101, Firenze', '43.7696000000', '11.2558000000'),
(5, 'Paolo', 'Ricci', '3335556666', 'paolo.ricci@example.com', 'salt202', 'hashedpassword202', 'Architetto', '1975-11-28', 'pubblica202', NULL, 'paolo_ricci', 'Via Vittorio Emanuele 202, Palermo', '38.1157000000', '13.3615000000'),
(6, 'Elena', 'Conti', '3336667777', 'elena.conti@example.com', 'salt303', 'hashedpassword303', 'Docente Universitaria', '1983-08-18', 'pubblica303', NULL, 'elena_conti', 'Via Milano 303, Bologna', '44.4949000000', '11.3426000000'),
(7, 'Marco', 'Moretti', '3337778888', 'marco.moretti@example.com', 'salt404', 'hashedpassword404', 'Consulente Finanziario', '1990-05-03', 'pubblica404', NULL, 'marco_moretti', 'Via Mazzini 404, Torino', '45.0703000000', '7.6869000000'),
(8, 'Giulia', 'Marchi', '3338889999', 'giulia.marchi@example.com', 'salt505', 'hashedpassword505', 'Psicologa', '1986-12-22', 'pubblica505', NULL, 'giulia_marchi', 'Corso Italia 505, Genova', '44.4056000000', '8.9463000000'),
(9, 'Alessandro', 'Barbieri', '3339990000', 'alessandro.barbieri@example.com', 'salt606', 'hashedpassword606', 'Ingegnere Civile', '1981-04-11', 'pubblica606', NULL, 'alessandro_barbieri', 'Via delle Rose 606, Roma', '41.9028000000', '12.4964000000'),
(10, 'Federica', 'Pellegrini', '3330001111', 'federica.pellegrini@example.com', 'salt707', 'hashedpassword707', 'Atleta Olimpica', '1988-08-05', 'pubblica707', NULL, 'federica_pellegrini', 'Via dei Campioni 707, Milano', '45.4642000000', '9.1900000000'),
(11, 'Luca', 'Rizzo', '3331112222', 'luca.rizzo@example.com', 'salt808', 'hashedpassword808', 'Avvocato', '1984-09-30', 'pubblica808', NULL, 'luca_rizzo', 'Via del Corso 808, Napoli', '40.8522000000', '14.2681000000'),
(12, 'Sabrina', 'Ferrari', '3332223333', 'sabrina.ferrari@example.com', 'salt909', 'hashedpassword909', 'Insegnante Elementare', '1993-02-14', 'pubblica909', NULL, 'sabrina_ferrari', 'Via della Scuola 909, Firenze', '43.7696000000', '11.2558000000'),
(13, 'Andrea', 'Martini', '3333334444', 'andrea.martini@example.com', 'salt010', 'hashedpassword010', 'Fotografo', '1977-07-08', 'pubblica010', NULL, 'andrea_martini', 'Corso Vittorio Emanuele 010, Torino', '45.0703000000', '7.6869000000'),
(14, 'Valentina', 'Ricci', '3334445555', 'valentina.ricci@example.com', 'salt111', 'hashedpassword111', 'Graphic Designer', '1989-11-23', 'pubblica111', NULL, 'valentina_ricci', 'Via dei Graphic 111, Milano', '45.4642000000', '9.1900000000'),
(15, 'Stefano', 'Conte', '3335556666', 'stefano.conte@example.com', 'salt212', 'hashedpassword212', 'Chef', '1982-04-17', 'pubblica212', NULL, 'stefano_conte', 'Via del Gusto 212, Bologna', '44.4949000000', '11.3426000000'),
(16, 'Roberta', 'Marini', '3336667777', 'roberta.marini@example.com', 'salt313', 'hashedpassword313', 'Psichiatra', '1986-01-30', 'pubblica313', NULL, 'roberta_marini', 'Via delle Menti 313, Torino', '45.0703000000', '7.6869000000'),
(17, 'Gianluca', 'Ferraro', '3337778888', 'gianluca.ferraro@example.com', 'salt414', 'hashedpassword414', 'Giornalista', '1991-06-10', 'pubblica414', NULL, 'gianluca_ferraro', 'Via della Notizia 414, Roma', '41.9028000000', '12.4964000000'),
(18, 'Martina', 'Piazza', '3338889999', 'martina.piazza@example.com', 'salt515', 'hashedpassword515', 'Pilota', '1980-09-25', 'pubblica515', NULL, 'martina_piazza', 'Via del Volo 515, Milano', '45.4642000000', '9.1900000000'),
(19, 'Luigi', 'Rossetti', '3339990000', 'luigi.rossetti@example.com', 'salt616', 'hashedpassword616', 'Geologo', '1987-12-15', 'pubblica616', NULL, 'luigi_rossetti', 'Via delle Rocce 616, Napoli', '40.8522000000', '14.2681000000'),
(21, 'Davide', 'Fabbri', '3331112222', 'davide.fabbri@example.com', 'salt818', 'hashedpassword818', 'Ingegnere Meccanico', '1979-04-20', 'pubblica818', NULL, 'davide_fabbri', 'Via della Meccanica 818, Torino', '45.0703000000', '7.6869000000'),
(22, 'Simona', 'Santoro', '3332223333', 'simona.santoro@example.com', 'salt919', 'hashedpassword919', 'Musicista', '1990-09-12', 'pubblica919', NULL, 'simona_santoro', 'Via della Musica 919, Milano', '45.4642000000', '9.1900000000'),
(23, 'Emanuele', 'Lombardi', '3333334444', 'emanuele.lombardi@example.com', 'salt020', 'hashedpassword020', 'Ricercatore', '1984-11-05', 'pubblica020', NULL, 'emanuele_lombardi', 'Corso della Ricerca 020, Bologna', '44.4949000000', '11.3426000000'),
(24, 'Arianna', 'Galli', '3334445555', 'arianna.galli@example.com', 'salt121', 'hashedpassword121', 'Medico', '1988-06-27', 'pubblica121', NULL, 'arianna_galli', 'Via della Salute 121, Torino', '45.0703000000', '7.6869000000'),
(25, 'Andrea', 'Ferri', '3335556666', 'andrea.ferri@example.com', 'salt222', 'hashedpassword222', 'Scrittore', '1981-03-14', 'pubblica222', NULL, 'andrea_ferri', 'Via delle Pagine 222, Milano', '45.4642000000', '9.1900000000'),
(26, 'Silvia', 'Rinaldi', '3336667777', 'silvia.rinaldi@example.com', 'salt323', 'hashedpassword323', 'Nutrizionista', '1985-08-08', 'pubblica323', NULL, 'silvia_rinaldi', 'Via della Nutrizione 323, Bologna', '44.4949000000', '11.3426000000'),
(27, 'Paolo', 'Vitali', '3337778888', 'paolo.vitali@example.com', 'salt424', 'hashedpassword424', 'Astronauta', '1992-01-02', 'pubblica424', NULL, 'paolo_vitali', 'Via della Galassia 424, Roma', '41.9028000000', '12.4964000000'),
(28, 'Leonardo', 'Tassinari', '054336610', 'leotassoleo@gmail.com', 'NaHlV', 'cd897699034769b702861b4b6e742fe609af2a7a0128b295e386c3a675a8e5dfba0184abe8e507f0be54c4492ae9efa9af8e34b216707e469cedcbd4887a2e08', 'Studente di ingegenria e scienze informatiche', '2000-10-04', NULL, 'IMG_6742 copia.jpg', 'leotassoleo@gmail.com', 'via Trento 13 Forlì', NULL, NULL),
(29, 'Lucrezia', 'Luccaroni', '3922335573', 'lucre.lucca00@gmail.com', 'mCtJY', '739d949a167c72ddc8ab2433587023931bbf93f9945d78c1dd4f09ffbabaa1f7c1b272869279363251f9316448f75374501d74ac9dc7f374db1d2d1fd4d43572', 'mi piacciono i cani', '2000-12-06', NULL, 'WhatsApp Image 2021-11-15 at 16.22.50.jpeg', 'its.lucr3', 'via Battuti Verdi 13', NULL, NULL),
(30, 'Simone', 'Stagni', '3450606493', 'stagnisimone2000@gmail.com', 'hcgAQ', 'e9fbcfbce04f38ad3bcdd87ac5e743187dcedc1067c4db64027432a07757f8c795b03e32de5e9041cd9cea529d0f8561837b13f9da899a5107c260e28ee2040f', 'Mi piace Lucrezia', '2000-12-12', NULL, 'WhatsApp Image 2021-11-15 at 16.28.05.jpeg', 'ciaociao', 'Via Carlo Bricè 19', NULL, NULL),
(31, 'Camilla', 'Valente', '3352385722', 'cami.valente19@gmail.com', 'RLXw9', 'd2dd94eb995353acd17df4fc15bc23dfde08ad37dc530f55c9a50b9eb3581f47c62d9b368186f130afa827d35c96cb5c924bfb36fbf607ca18c065baa5f38f97', 'Ciao, sono Camilla. Attualmente studio ingegneria gestionale all’Università di Bologna (UniBo), ma a breve inizierò il mio tirocinio in Olanda presso l’azienda AWETA, fa melanzane e cetrioli', '2000-10-19', NULL, 'image.jpg', 'camivalente', 'Via Aldo Garzanti 2, Forlì ', NULL, NULL),
(32, 'Alessandra', 'Versari', '3348833949', 'alr.vers01@gmail.com', '5611O', '912f2237fb1d40c78746935696738724b8d5cfc840868cc74a3304ee981e7d90af3a19b786a542cfcae1bcf182da87726f2cc8c5076387460d1fa05a40a47448', 'Studentessa di Ingegneria e scienze informatiche (UniBo Cesena).', '2001-05-15', NULL, 'WhatsApp Image 2021-01-26 at 15.02.47 (27).jpeg', 'sandra_01', 'Via Giordano Bruno, 23', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Accessi`
--
ALTER TABLE `Accessi`
  ADD PRIMARY KEY (`idUser`,`tempo`);

--
-- Indexes for table `Commento`
--
ALTER TABLE `Commento`
  ADD PRIMARY KEY (`idCommento`),
  ADD KEY `fk_Commento_User1_idx` (`Autore`),
  ADD KEY `fk_Commento_PostComunicazioni1_idx` (`RelativoA`);

--
-- Indexes for table `Interventi`
--
ALTER TABLE `Interventi`
  ADD PRIMARY KEY (`idPostInterventi`,`idUser`),
  ADD KEY `fk_Interventi_PostInterventi1_idx` (`idPostInterventi`),
  ADD KEY `fk_Interventi_User1_idx` (`idUser`);

--
-- Indexes for table `Like`
--
ALTER TABLE `Like`
  ADD PRIMARY KEY (`idEmettitore`,`PostRelativo`),
  ADD KEY `fk_Like_PostComunicazioni1_idx` (`PostRelativo`);

--
-- Indexes for table `Materiale`
--
ALTER TABLE `Materiale`
  ADD PRIMARY KEY (`idMateriale`),
  ADD KEY `fk_Materiale_PostInterventi1_idx` (`idPostIntervento`);

--
-- Indexes for table `Notifica`
--
ALTER TABLE `Notifica`
  ADD PRIMARY KEY (`idNotifica`),
  ADD KEY `fk_Notifica_User1_idx` (`idUser`),
  ADD KEY `fk_Notifica_User2_idx` (`idUserGeneratore`);

--
-- Indexes for table `PostComunicazioni`
--
ALTER TABLE `PostComunicazioni`
  ADD PRIMARY KEY (`idPostComunicazione`),
  ADD KEY `fk_PostComunicazioni_User1` (`idUser`);

--
-- Indexes for table `PostInterventi`
--
ALTER TABLE `PostInterventi`
  ADD PRIMARY KEY (`idPostIntervento`),
  ADD KEY `fk_PostInterventi_User1_idx` (`Autore_idUser`);

--
-- Indexes for table `PostSalvati`
--
ALTER TABLE `PostSalvati`
  ADD PRIMARY KEY (`idPostInterventi`,`idUser`),
  ADD KEY `fk_Interventi_PostInterventi1_idx` (`idPostInterventi`),
  ADD KEY `fk_Interventi_User1_idx` (`idUser`);

--
-- Indexes for table `Seguiti`
--
ALTER TABLE `Seguiti`
  ADD PRIMARY KEY (`idSeguace`,`idSeguito`),
  ADD KEY `fk_Seguiti_User2_idx` (`idSeguito`);

--
-- Indexes for table `Token`
--
ALTER TABLE `Token`
  ADD PRIMARY KEY (`idUser`,`TokenValue`),
  ADD KEY `fk_Token_User1_idx` (`idUser`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `Username_UNIQUE` (`Username`),
  ADD UNIQUE KEY `Email_UNIQUE` (`Email`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Accessi`
--
ALTER TABLE `Accessi`
  ADD CONSTRAINT `fk_Accessi_User1` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Commento`
--
ALTER TABLE `Commento`
  ADD CONSTRAINT `fk_Commento_PostComunicazioni1` FOREIGN KEY (`RelativoA`) REFERENCES `PostComunicazioni` (`idPostComunicazione`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Commento_User1` FOREIGN KEY (`Autore`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Interventi`
--
ALTER TABLE `Interventi`
  ADD CONSTRAINT `fk_Interventi_PostInterventi1` FOREIGN KEY (`idPostInterventi`) REFERENCES `PostInterventi` (`idPostIntervento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Interventi_User1` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Like`
--
ALTER TABLE `Like`
  ADD CONSTRAINT `fk_Like_PostComunicazioni1` FOREIGN KEY (`PostRelativo`) REFERENCES `PostComunicazioni` (`idPostComunicazione`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Like_User1` FOREIGN KEY (`idEmettitore`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Materiale`
--
ALTER TABLE `Materiale`
  ADD CONSTRAINT `fk_Materiale_PostInterventi1` FOREIGN KEY (`idPostIntervento`) REFERENCES `PostInterventi` (`idPostIntervento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Notifica`
--
ALTER TABLE `Notifica`
  ADD CONSTRAINT `fk_Notifica_User1` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Notifica_User2` FOREIGN KEY (`idUserGeneratore`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `PostComunicazioni`
--
ALTER TABLE `PostComunicazioni`
  ADD CONSTRAINT `fk_PostComunicazioni_User1` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `PostInterventi`
--
ALTER TABLE `PostInterventi`
  ADD CONSTRAINT `fk_PostInterventi_User1` FOREIGN KEY (`Autore_idUser`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `PostSalvati`
--
ALTER TABLE `PostSalvati`
  ADD CONSTRAINT `fk_Interventi_PostInterventi10` FOREIGN KEY (`idPostInterventi`) REFERENCES `PostInterventi` (`idPostIntervento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Interventi_User10` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Seguiti`
--
ALTER TABLE `Seguiti`
  ADD CONSTRAINT `fk_Seguiti_User1` FOREIGN KEY (`idSeguace`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Seguiti_User2` FOREIGN KEY (`idSeguito`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Token`
--
ALTER TABLE `Token`
  ADD CONSTRAINT `fk_Token_User1` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
