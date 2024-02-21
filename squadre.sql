-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 16, 2024 alle 11:54
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `squadre`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `allenatore`
--

CREATE TABLE `allenatore` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(255) DEFAULT NULL,
  `Cognome` varchar(255) DEFAULT NULL,
  `Data_di_Nascita` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `allenatore`
--

INSERT INTO `allenatore` (`ID`, `Nome`, `Cognome`, `Data_di_Nascita`) VALUES
(1, 'Carlo', 'Ancelotti', '1959-06-10'),
(2, 'Pep', 'Guardiola', '1971-01-18'),
(3, 'Jurgen', 'Klopp', '1967-06-16');

-- --------------------------------------------------------

--
-- Struttura della tabella `contratto`
--

CREATE TABLE `contratto` (
  `ID` int(11) NOT NULL,
  `Sponsor_ID` int(11) DEFAULT NULL,
  `Squadra_ID` int(11) DEFAULT NULL,
  `Cifra` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `contratto`
--

INSERT INTO `contratto` (`ID`, `Sponsor_ID`, `Squadra_ID`, `Cifra`) VALUES
(1, 1, 1, 5000000.00),
(2, 2, 2, 4000000.00),
(3, 3, 3, 3000000.00);

-- --------------------------------------------------------

--
-- Struttura della tabella `giocatore`
--

CREATE TABLE `giocatore` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(255) DEFAULT NULL,
  `Cognome` varchar(255) DEFAULT NULL,
  `Data_di_Nascita` date DEFAULT NULL,
  `Squadra_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `giocatore`
--

INSERT INTO `giocatore` (`ID`, `Nome`, `Cognome`, `Data_di_Nascita`, `Squadra_ID`) VALUES
(1, 'Cristiano', 'Ronaldo', '1985-02-05', 1),
(2, 'Lionel', 'Messi', '1987-06-24', 2),
(3, 'Mohamed', 'Salah', '1992-06-15', 3),
(4, 'Giorgio', 'Chiellini', '1984-08-14', 1),
(5, 'Federico', 'Chiesa', '1997-10-25', 1),
(6, 'Paulo', 'Dybala', '1993-11-15', 1),
(7, 'Gerard', 'Piqué', '1987-02-02', 2),
(8, 'Sergio', 'Busquets', '1988-07-16', 2),
(9, 'Ansu', 'Fati', '2002-10-31', 2),
(10, 'Alisson', 'Becker', '1992-10-02', 3),
(11, 'Sadio', 'Mané', '1992-04-10', 3),
(12, 'Trent', 'Alexander-Arnold', '1998-10-07', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `sponsor`
--

CREATE TABLE `sponsor` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `sponsor`
--

INSERT INTO `sponsor` (`ID`, `Nome`) VALUES
(1, 'Nike'),
(2, 'Adidas'),
(3, 'Puma');

-- --------------------------------------------------------

--
-- Struttura della tabella `squadra`
--

CREATE TABLE `squadra` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(255) DEFAULT NULL,
  `Anno_Nascita` int(11) DEFAULT NULL,
  `Citta` varchar(255) DEFAULT NULL,
  `Allenatore` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `squadra`
--

INSERT INTO `squadra` (`ID`, `Nome`, `Anno_Nascita`, `Citta`, `Allenatore`) VALUES
(1, 'Juventus', 1897, 'Torino', 1),
(2, 'Barcelona', 1899, 'Barcellona', 2),
(3, 'Liverpool', 1892, 'Liverpool', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`username`, `password`) VALUES
('Dome', '777');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `allenatore`
--
ALTER TABLE `allenatore`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `contratto`
--
ALTER TABLE `contratto`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Sponsor_ID` (`Sponsor_ID`),
  ADD KEY `Squadra_ID` (`Squadra_ID`);

--
-- Indici per le tabelle `giocatore`
--
ALTER TABLE `giocatore`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Squadra_ID` (`Squadra_ID`);

--
-- Indici per le tabelle `sponsor`
--
ALTER TABLE `sponsor`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `squadra`
--
ALTER TABLE `squadra`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Allenatore` (`Allenatore`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `contratto`
--
ALTER TABLE `contratto`
  ADD CONSTRAINT `contratto_ibfk_1` FOREIGN KEY (`Sponsor_ID`) REFERENCES `sponsor` (`ID`),
  ADD CONSTRAINT `contratto_ibfk_2` FOREIGN KEY (`Squadra_ID`) REFERENCES `squadra` (`ID`);

--
-- Limiti per la tabella `giocatore`
--
ALTER TABLE `giocatore`
  ADD CONSTRAINT `giocatore_ibfk_1` FOREIGN KEY (`Squadra_ID`) REFERENCES `squadra` (`ID`);

--
-- Limiti per la tabella `squadra`
--
ALTER TABLE `squadra`
  ADD CONSTRAINT `squadra_ibfk_1` FOREIGN KEY (`Allenatore`) REFERENCES `allenatore` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
