-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Dic 16, 2019 alle 18:16
-- Versione del server: 10.1.38-MariaDB
-- Versione PHP: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestore_blog`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `allegato`
--

CREATE TABLE `allegato` (
  `post_idAl` int(11) NOT NULL,
  `img_idAl` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `allegato`
--

INSERT INTO `allegato` (`post_idAl`, `img_idAl`) VALUES
(2, 1),
(6, 2),
(15, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `appunto`
--

CREATE TABLE `appunto` (
  `commento_idAp` smallint(6) NOT NULL,
  `post_idAp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `appunto`
--

INSERT INTO `appunto` (`commento_idAp`, `post_idAp`) VALUES
(1, 1),
(4, 7),
(5, 1),
(6, 6),
(8, 7),
(9, 7),
(10, 16);

-- --------------------------------------------------------

--
-- Struttura della tabella `argomento`
--

CREATE TABLE `argomento` (
  `nomeTema` varchar(20) NOT NULL,
  `blog_idArg` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `argomento`
--

INSERT INTO `argomento` (`nomeTema`, `blog_idArg`) VALUES
('Alimentazione', 11),
('Basi di dati', 1),
('Spazio', 2),
('Spazio', 9),
('Tecnologia', 5),
('Tecnologia', 10),
('Videogiochi', 3),
('Videogiochi', 4),
('Videogiochi', 7),
('Videogiochi', 8);

-- --------------------------------------------------------

--
-- Struttura della tabella `articolazione`
--

CREATE TABLE `articolazione` (
  `blog_idAr` smallint(6) NOT NULL,
  `post_idAr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `articolazione`
--

INSERT INTO `articolazione` (`blog_idAr`, `post_idAr`) VALUES
(1, 1),
(2, 2),
(5, 4),
(5, 6),
(5, 7),
(7, 13),
(8, 14),
(9, 15),
(10, 16),
(11, 17);

-- --------------------------------------------------------

--
-- Struttura della tabella `blog`
--

CREATE TABLE `blog` (
  `blog_id` smallint(6) NOT NULL,
  `nomeBlog` varchar(20) NOT NULL,
  `graficaBlog` varchar(20) NOT NULL DEFAULT 'Default.css'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `blog`
--

INSERT INTO `blog` (`blog_id`, `nomeBlog`, `graficaBlog`) VALUES
(1, 'Progetto', 'Dark.css'),
(2, 'Moon', 'Default.css'),
(3, 'Super Mario', 'Artistic.css'),
(4, 'Call of Duty', 'Default.css'),
(5, 'Tech news', 'Technology.css'),
(7, 'Half-Life 2', 'Default.css'),
(8, 'Age of Empires', 'Default.css'),
(9, 'Marte', 'Default.css'),
(10, 'Mondo Apple', 'Technology.css'),
(11, 'Ricette di cucina', 'Default.css');

-- --------------------------------------------------------

--
-- Struttura della tabella `commento`
--

CREATE TABLE `commento` (
  `commento_id` smallint(6) NOT NULL,
  `dataCommento` date NOT NULL,
  `oraCommento` time NOT NULL,
  `testoCommento` varchar(140) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `commento`
--

INSERT INTO `commento` (`commento_id`, `dataCommento`, `oraCommento`, `testoCommento`) VALUES
(1, '2019-12-15', '18:17:51', 'Prova commento'),
(4, '2019-12-16', '15:55:16', 'Corro subito ad aggiornarlo!'),
(5, '2019-12-16', '15:55:46', 'Anch\'io provo un commento'),
(6, '2019-12-16', '15:57:01', 'Wow! ChissÃ  se Chrome riesce a saturare anche 1TB di RAM..'),
(8, '2019-12-16', '15:58:55', 'Anch\'io!'),
(9, '2019-12-16', '16:09:53', 'Io no perchÃ© uso Opera :P'),
(10, '2019-12-16', '18:11:15', 'Buuuu');

-- --------------------------------------------------------

--
-- Struttura della tabella `creazione`
--

CREATE TABLE `creazione` (
  `blog_idC` smallint(6) NOT NULL,
  `autoreC` varchar(20) NOT NULL,
  `coautoreC` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `creazione`
--

INSERT INTO `creazione` (`blog_idC`, `autoreC`, `coautoreC`) VALUES
(1, 'Profilo1', 'Profilo2'),
(2, 'Profilo3', NULL),
(3, 'Profilo3', 'Profilo5'),
(4, 'Profilo3', NULL),
(5, 'Profilo2', NULL),
(7, 'Profilo7', NULL),
(8, 'Profilo7', NULL),
(9, 'Profilo7', NULL),
(10, 'Profilo7', NULL),
(11, 'Profilo7', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `discussione`
--

CREATE TABLE `discussione` (
  `commento_idD` smallint(6) NOT NULL,
  `autoreD` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `discussione`
--

INSERT INTO `discussione` (`commento_idD`, `autoreD`) VALUES
(1, 'Profilo4'),
(4, 'Profilo3'),
(5, 'Profilo3'),
(6, 'Profilo2'),
(8, 'Profilo2'),
(9, 'Profilo4'),
(10, 'Profilo3');

-- --------------------------------------------------------

--
-- Struttura della tabella `immagine`
--

CREATE TABLE `immagine` (
  `img_id` int(11) NOT NULL,
  `dataImg` date DEFAULT NULL,
  `oraImg` time DEFAULT NULL,
  `pathImg` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `immagine`
--

INSERT INTO `immagine` (`img_id`, `dataImg`, `oraImg`, `pathImg`) VALUES
(1, '2019-12-15', '18:16:03', 'photo/2.FullMoon2010.jpg'),
(2, '2019-12-16', '15:36:35', 'photo/6.asrock-x299-taichi-clx-68287.1920x1080.jpg'),
(3, '2019-12-16', '18:00:07', 'photo/15.260px-OSIRIS_Mars_true_color.jpg');

-- --------------------------------------------------------

--
-- Struttura della tabella `mipiace`
--

CREATE TABLE `mipiace` (
  `nicknameL` varchar(20) NOT NULL,
  `post_idL` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `mipiace`
--

INSERT INTO `mipiace` (`nicknameL`, `post_idL`) VALUES
('Profilo1', 4),
('Profilo1', 6),
('Profilo1', 7),
('Profilo2', 1),
('Profilo2', 6),
('Profilo3', 2),
('Profilo4', 1),
('Profilo4', 2),
('Profilo4', 4),
('Profilo4', 6),
('Profilo4', 7),
('Profilo5', 2),
('Profilo5', 4),
('Profilo5', 6),
('Profilo6', 16),
('Profilo6', 17),
('Profilo7', 14),
('Profilo7', 17),
('Profilo9', 14),
('Profilo9', 17);

-- --------------------------------------------------------

--
-- Struttura della tabella `nonmipiace`
--

CREATE TABLE `nonmipiace` (
  `nicknameDL` varchar(20) NOT NULL,
  `post_idDL` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `nonmipiace`
--

INSERT INTO `nonmipiace` (`nicknameDL`, `post_idDL`) VALUES
('Profilo3', 16),
('Profilo5', 1),
('Profilo5', 7),
('Profilo7', 16),
('Profilo9', 16);

-- --------------------------------------------------------

--
-- Struttura della tabella `post`
--

CREATE TABLE `post` (
  `post_id` int(11) NOT NULL,
  `dataPost` date DEFAULT NULL,
  `oraPost` time DEFAULT NULL,
  `titoloPost` varchar(40) NOT NULL,
  `nLike` smallint(6) DEFAULT '0',
  `nDislike` smallint(6) DEFAULT '0',
  `testoPost` varchar(400) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `post`
--

INSERT INTO `post` (`post_id`, `dataPost`, `oraPost`, `titoloPost`, `nLike`, `nDislike`, `testoPost`) VALUES
(1, '2019-12-15', '18:06:49', 'Prova post', 2, 1, 'Testo post'),
(2, '2019-12-15', '18:16:03', 'Foto della Luna', 3, 0, 'Solitaria e silenziosa'),
(4, '2019-12-16', '15:29:11', 'Intel svela un chip criogenico', 3, 0, 'Si chiama Horse Ridge, un chip di controllo criogenico unico nel suo genere e dovrebbe accelerare lo sviluppo di computer quantistici.'),
(6, '2019-12-16', '15:36:35', '1TB di RAM su una motherboard da 256GB', 4, 0, 'Un overclocker, Nick Shih, Ã¨ riuscito a far funzionare 1TB di RAM su una motherboard X299 limitata a 256GB. La scheda madre workstation Ã¨ una ASRock X299 Taichi CLX, che ufficialmente supporta &quot;solamente&quot; fino a 256 GB di memoria.'),
(7, '2019-12-16', '15:40:02', 'Chrome si aggiorna alla versione 79', 2, 1, 'Vi sono diversi miglioramenti che riguardano sicurezza, bug fix e non solo. Sono state introdotte nuove funzioni come ad esempio il supporto integrato al controllo delle password e la possibilitÃ  di mettere in tempo reale, in una blacklist, i siti malevoli.'),
(13, '2019-12-16', '17:28:36', 'Annunciato Half-Life Alyx!', 0, 0, 'SarÃ  un gioco in VR, prima grande produzione nel suo genere. I fatti narrati saranno antecedenti alla storia di Half-Life 2. UscirÃ  a marzo 2020.'),
(14, '2019-12-16', '17:58:36', 'Age of Empires 4 mostrato in un trailer', 2, 0, 'All\'X019 Ã¨ stato mostrato un trailer di 1 minuto circa sul nuovo capitolo della saga. Purtroppo ancora non Ã¨ stata comunicata una data di uscita ufficiale, anche se molti sono i rumors a riguardo.'),
(15, '2019-12-16', '18:00:07', 'Marte', 0, 0, 'Immagine del pianeta rosso'),
(16, '2019-12-16', '18:04:33', 'Disponibile il nuovo Mac Pro', 1, 3, 'Disponibile all\'acquisto il nuovo Mac Pro direttamente sul sito ufficiale Apple. La configurazione base parte da 6.599â‚¬, fino ad arrivare a piÃ¹ di 60.000â‚¬.'),
(17, '2019-12-16', '18:08:52', 'Pasta al forno con zucca e funghi', 3, 0, '250 g di pasta integrale\r\n500 g di zucca mantovana\r\n250 g di funghi pioppini\r\nuna noce di burro\r\n50 g di Parmigiano Reggiano\r\n100 g di Primosale\r\n2 cucchiai di olio extravergine di oliva\r\n4 cucchiai di olio dâ€™oliva\r\nun mazzetto di aromi misti (timo, maggiorana, origano, salvia)\r\nuna presa di sale grosso\r\nsale e pepe qb');

-- --------------------------------------------------------

--
-- Struttura della tabella `tema`
--

CREATE TABLE `tema` (
  `nome` varchar(20) NOT NULL,
  `popolarita` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `tema`
--

INSERT INTO `tema` (`nome`, `popolarita`) VALUES
('Alimentazione', 1),
('Basi di dati', 1),
('Spazio', 2),
('Tecnologia', 2),
('Videogiochi', 5);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `nickname` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(30) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `nDocumento` varchar(20) NOT NULL,
  `dataDocumento` date NOT NULL,
  `luogoDocumento` varchar(20) NOT NULL,
  `enteDocumento` varchar(20) NOT NULL,
  `inizioAbb` date DEFAULT NULL,
  `fineAbb` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`nickname`, `password`, `email`, `telefono`, `nDocumento`, `dataDocumento`, `luogoDocumento`, `enteDocumento`, `inizioAbb`, `fineAbb`) VALUES
('Profilo1', '$2y$10$Ltp98pId1hPGNHsmaFjAI.eA0m4Kil16x7NLfJcXfR88/NlYNwBGm', 'prova@gmail.com', '3390496788', 'AR5812503', '2005-01-01', 'Pisa', 'Comune di Pisa', '2019-12-15', '2020-01-15'),
('Profilo2', '$2y$10$hbv9EBDStkwkXvqGRqBQNe/aqVnYKHIgnnYN/VLyn6wpbsM/5jNO6', 'prova2@gmail.com', '3451200980', 'AR2817456', '2005-01-01', 'Pisa', 'Comune di Pisa', '2019-12-16', '2020-01-16'),
('Profilo3', '$2y$10$P4XyUxYD63VXQ8i76nVFBufPTGR2psRI3w0gWq7zU1a46JpQl0zqS', 'prova3@gmail.com', '3339645172', 'AR3455672', '2005-01-01', 'Pisa', 'Comune di Pisa', '2019-12-15', '2020-01-15'),
('Profilo4', '$2y$10$.IuyCFnmLLV6gGVtKJbxC.TdjMN5ZbWf/ns8vB/PLG/HSRloVgoAS', 'prova4@gmail.com', '3980511390', 'AR2300918', '2005-01-01', 'Pisa', 'Comune di Pisa', '2019-11-10', '2019-12-10'),
('Profilo5', '$2y$10$TrtooSFSEwF7RtwHmGMnge4Of0n8lBjmlthL//nhs64w.IlzPFRRq', 'prova5@gmail.com', '3263049221', 'AR4267182', '2005-01-01', 'Pisa', 'Comune di Pisa', NULL, NULL),
('Profilo6', '$2y$10$F4bJ5qYr39aE08N6aKXrlepqosDHDPK16dbJZaJAClQ26o8gCj1CG', 'prova6@gmail.com', '3902954079', 'AR2019586', '2005-01-01', 'Pisa', 'Comune di Pisa', NULL, NULL),
('Profilo7', '$2y$10$pliKEn1MOfa/EZm1I5VFkujq2Oi0dyjfkGZqQgIq.FN.qCenyTm0S', 'prova7@gmail.com', '3347691120', 'AR2233059', '2005-01-01', 'Pisa', 'Comune di Pisa', '2019-12-16', '2020-01-16'),
('Profilo8', '$2y$10$TjX6LV9Sn2euxOhmIQZJuuj/syVNwxLhFBTZsNnDzjHMtKDZ.xGHq', 'prova8@gmail.com', '3371628029', 'AR2611769', '2005-01-01', 'Pisa', 'Comune di Pisa', NULL, NULL),
('Profilo9', '$2y$10$08BY/l1FlKfE9rdrJnif6uEM.lzW/cCBX9Wuratn4D5pv/mdSC/tq', 'prova9@gmail.com', '3312090003', 'AR4812445', '2005-01-01', 'Pisa', 'Comune di Pisa', NULL, NULL);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `allegato`
--
ALTER TABLE `allegato`
  ADD PRIMARY KEY (`post_idAl`,`img_idAl`),
  ADD KEY `img_idAl` (`img_idAl`);

--
-- Indici per le tabelle `appunto`
--
ALTER TABLE `appunto`
  ADD PRIMARY KEY (`commento_idAp`,`post_idAp`),
  ADD KEY `post_idAp` (`post_idAp`);

--
-- Indici per le tabelle `argomento`
--
ALTER TABLE `argomento`
  ADD PRIMARY KEY (`nomeTema`,`blog_idArg`),
  ADD KEY `blog_idArg` (`blog_idArg`);

--
-- Indici per le tabelle `articolazione`
--
ALTER TABLE `articolazione`
  ADD PRIMARY KEY (`blog_idAr`,`post_idAr`),
  ADD KEY `post_idAr` (`post_idAr`);

--
-- Indici per le tabelle `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`blog_id`),
  ADD UNIQUE KEY `nomeBlog` (`nomeBlog`);

--
-- Indici per le tabelle `commento`
--
ALTER TABLE `commento`
  ADD PRIMARY KEY (`commento_id`);

--
-- Indici per le tabelle `creazione`
--
ALTER TABLE `creazione`
  ADD PRIMARY KEY (`blog_idC`,`autoreC`),
  ADD KEY `autoreC` (`autoreC`);

--
-- Indici per le tabelle `discussione`
--
ALTER TABLE `discussione`
  ADD PRIMARY KEY (`commento_idD`,`autoreD`),
  ADD KEY `autoreD` (`autoreD`);

--
-- Indici per le tabelle `immagine`
--
ALTER TABLE `immagine`
  ADD PRIMARY KEY (`img_id`);

--
-- Indici per le tabelle `mipiace`
--
ALTER TABLE `mipiace`
  ADD PRIMARY KEY (`nicknameL`,`post_idL`),
  ADD KEY `post_idL` (`post_idL`);

--
-- Indici per le tabelle `nonmipiace`
--
ALTER TABLE `nonmipiace`
  ADD PRIMARY KEY (`nicknameDL`,`post_idDL`),
  ADD KEY `post_idDL` (`post_idDL`);

--
-- Indici per le tabelle `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`post_id`);

--
-- Indici per le tabelle `tema`
--
ALTER TABLE `tema`
  ADD PRIMARY KEY (`nome`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`nickname`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `blog`
--
ALTER TABLE `blog`
  MODIFY `blog_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `commento`
--
ALTER TABLE `commento`
  MODIFY `commento_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `immagine`
--
ALTER TABLE `immagine`
  MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `post`
--
ALTER TABLE `post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `allegato`
--
ALTER TABLE `allegato`
  ADD CONSTRAINT `allegato_ibfk_1` FOREIGN KEY (`post_idAl`) REFERENCES `post` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `allegato_ibfk_2` FOREIGN KEY (`img_idAl`) REFERENCES `immagine` (`img_id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `appunto`
--
ALTER TABLE `appunto`
  ADD CONSTRAINT `appunto_ibfk_1` FOREIGN KEY (`commento_idAp`) REFERENCES `commento` (`commento_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appunto_ibfk_2` FOREIGN KEY (`post_idAp`) REFERENCES `post` (`post_id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `argomento`
--
ALTER TABLE `argomento`
  ADD CONSTRAINT `argomento_ibfk_1` FOREIGN KEY (`nomeTema`) REFERENCES `tema` (`nome`) ON DELETE CASCADE,
  ADD CONSTRAINT `argomento_ibfk_2` FOREIGN KEY (`blog_idArg`) REFERENCES `blog` (`blog_id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `articolazione`
--
ALTER TABLE `articolazione`
  ADD CONSTRAINT `articolazione_ibfk_1` FOREIGN KEY (`blog_idAr`) REFERENCES `blog` (`blog_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articolazione_ibfk_2` FOREIGN KEY (`post_idAr`) REFERENCES `post` (`post_id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `creazione`
--
ALTER TABLE `creazione`
  ADD CONSTRAINT `creazione_ibfk_1` FOREIGN KEY (`blog_idC`) REFERENCES `blog` (`blog_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `creazione_ibfk_2` FOREIGN KEY (`autoreC`) REFERENCES `utenti` (`nickname`) ON DELETE CASCADE;

--
-- Limiti per la tabella `discussione`
--
ALTER TABLE `discussione`
  ADD CONSTRAINT `discussione_ibfk_1` FOREIGN KEY (`commento_idD`) REFERENCES `commento` (`commento_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discussione_ibfk_2` FOREIGN KEY (`autoreD`) REFERENCES `utenti` (`nickname`) ON DELETE CASCADE;

--
-- Limiti per la tabella `mipiace`
--
ALTER TABLE `mipiace`
  ADD CONSTRAINT `mipiace_ibfk_1` FOREIGN KEY (`nicknameL`) REFERENCES `utenti` (`nickname`) ON DELETE CASCADE,
  ADD CONSTRAINT `mipiace_ibfk_2` FOREIGN KEY (`post_idL`) REFERENCES `post` (`post_id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `nonmipiace`
--
ALTER TABLE `nonmipiace`
  ADD CONSTRAINT `nonmipiace_ibfk_1` FOREIGN KEY (`nicknameDL`) REFERENCES `utenti` (`nickname`) ON DELETE CASCADE,
  ADD CONSTRAINT `nonmipiace_ibfk_2` FOREIGN KEY (`post_idDL`) REFERENCES `post` (`post_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
