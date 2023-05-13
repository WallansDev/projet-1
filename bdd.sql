-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 06 mai 2023 à 01:32
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE DATABASE cimetiere_bdd;
USE cimetiere_bdd;

--
-- Base de données : `lgc`
--

DELIMITER $$
--
-- Fonctions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `initCap` (`str` VARCHAR(8000)) RETURNS VARCHAR(8000) CHARSET utf8mb4 BEGIN
    DECLARE len      INT DEFAULT LENGTH(str);
    DECLARE pos      INT DEFAULT 1;
    DECLARE ch       CHAR(1);
    DECLARE ch_ascii INT;
 
    DECLARE out_str VARCHAR(8000) DEFAULT '';
    DECLARE prev_alphanum INT DEFAULT 0;
 
    WHILE pos <= len 
    DO
      SET ch = SUBSTRING(str, pos, 1);
      SET ch_ascii = ASCII(ch);
 
      IF prev_alphanum = 1 THEN
        SET out_str = CONCAT(RPAD(out_str, pos - 1), LOWER(ch));  -- RPAD is required to append ' ' 
      ELSE 
        SET out_str = CONCAT(RPAD(out_str, pos - 1), UPPER(ch));
      END IF;
 
      IF ch_ascii <= 47 OR (ch_ascii BETWEEN 58 AND 64) OR
	  (ch_ascii BETWEEN 91 AND 96) OR (ch_ascii BETWEEN 123 AND 126) THEN      
	  SET prev_alphanum = 0;
      ELSE
	  SET prev_alphanum = 1;
      END IF;
 
      SET pos = pos + 1;    
    END WHILE;  
 
    RETURN out_str;
  END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `NombreColumbarium` () RETURNS INT(11) BEGIN
DECLARE nombre_de_concession int;
SELECT COUNT(*) INTO nombre_de_concession
FROM COLUMBARIUM;
RETURN nombre_de_concession;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `NombreConcession` () RETURNS INT(11) BEGIN
DECLARE nombre_de_concession int;
SELECT COUNT(*) INTO nombre_de_concession
FROM CONCESSION;
RETURN nombre_de_concession;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `NombreMort` () RETURNS INT(11) BEGIN
DECLARE nombre_de_mort int;
SELECT COUNT(*) INTO nombre_de_mort
FROM MORT;
RETURN nombre_de_mort;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `NombreReposoir` () RETURNS INT(11) BEGIN
DECLARE nombre_de_concession int;
SELECT COUNT(*) INTO nombre_de_concession
FROM REPOSOIR;
RETURN nombre_de_concession;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `NombreTombeCivile` () RETURNS INT(11) BEGIN
DECLARE nombre_de_concession int;
SELECT COUNT(*) INTO nombre_de_concession
FROM TOMBECIVILE;
RETURN nombre_de_concession;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `NombreTombeCommunale` () RETURNS INT(11) BEGIN
DECLARE nombre_de_concession int;
SELECT COUNT(*) INTO nombre_de_concession
FROM TOMBECOMMUNALE;
RETURN nombre_de_concession;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `PlacesRestantes_select_communale` (`Id` INT(11)) RETURNS INT(11) BEGIN
DECLARE resultat varchar(66);
SELECT PlaceDispo into resultat FROM TOMBECOMMUNALE WHERE IdCommunale = Id;
RETURN resultat;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `PlacesRestantes_select_concession` (`Id` INT(11)) RETURNS INT(11) BEGIN
DECLARE resultat varchar(66);
SELECT PlaceDispo into resultat FROM CONCESSION WHERE IdConcession = Id;
RETURN resultat;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `PlacesRestantes_select_reposoir` (`Id` INT(11)) RETURNS INT(11) BEGIN
DECLARE resultat varchar(66);
SELECT PlaceDispo into resultat FROM REPOSOIR WHERE IdReposoir = Id;
RETURN resultat;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `columbarium`
--

CREATE TABLE `columbarium` (
  `IdConcession` int(11) NOT NULL,
  `IdCasier` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `columbarium`
--

INSERT INTO `columbarium` (`IdConcession`, `IdCasier`) VALUES
(36, 1),
(37, 9);

-- --------------------------------------------------------

--
-- Structure de la table `concession`
--

CREATE TABLE `concession` (
  `IdConcession` int(11) NOT NULL,
  `PrixConcession` int(11) DEFAULT NULL,
  `PlaceDispo` int(11) DEFAULT NULL,
  `TailleConcession` enum('individuelle','collective','familiale') DEFAULT NULL,
  `TempsConcession` enum('Temporaire (5 ans - 15 ans)','Trentenaire (30 ans)','Cinquantenaire (50 ans)','Perpetuelle (pas de limite)') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `concession`
--

INSERT INTO `concession` (`IdConcession`, `PrixConcession`, `PlaceDispo`, `TailleConcession`, `TempsConcession`) VALUES
(34, 300, 1, 'familiale', 'Perpetuelle (pas de limite)'),
(35, 200, 1, 'collective', 'Perpetuelle (pas de limite)'),
(36, 400, 2, 'collective', 'Perpetuelle (pas de limite)'),
(37, 300, 3, 'familiale', 'Cinquantenaire (50 ans)'),
(38, 400, 4, 'familiale', 'Perpetuelle (pas de limite)'),
(39, 300, 4, 'familiale', 'Perpetuelle (pas de limite)');

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

CREATE TABLE `logs` (
  `IdLogs` int(11) NOT NULL,
  `IdUtilisateur` int(11) NOT NULL,
  `DateLogs` date NOT NULL,
  `HeureLogs` varchar(8) NOT NULL,
  `MessageLogs` varchar(500) NOT NULL,
  `StatusLogs` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `mort`
--

CREATE TABLE `mort` (
  `IdMort` int(11) NOT NULL,
  `NomMort` varchar(50) DEFAULT NULL,
  `NomJeuneMort` varchar(50) DEFAULT NULL,
  `PrenomMort` varchar(50) DEFAULT NULL,
  `SexeMort` enum('homme','femme','n/a') DEFAULT NULL,
  `DateNaissance` date DEFAULT NULL,
  `DateMort` date DEFAULT NULL,
  `DateObseques` date DEFAULT NULL,
  `IdConcession` int(11) DEFAULT NULL,
  `IdCommunale` int(11) DEFAULT NULL,
  `IdReposoir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `mort`
--

INSERT INTO `mort` (`IdMort`, `NomMort`, `NomJeuneMort`, `PrenomMort`, `SexeMort`, `DateNaissance`, `DateMort`, `DateObseques`, `IdConcession`, `IdCommunale`, `IdReposoir`) VALUES
(131, 'Savoie', NULL, 'Norris', 'homme', NULL, NULL, NULL, 34, NULL, NULL),
(132, 'Roussel', NULL, 'Anais', 'femme', NULL, NULL, NULL, 37, NULL, NULL),
(134, NULL, NULL, NULL, 'homme', NULL, NULL, NULL, 34, NULL, NULL),
(135, '--', NULL, '--', 'homme', NULL, NULL, NULL, 34, NULL, NULL),
(138, 'LANGELIER', NULL, 'Jerôme', 'homme', NULL, NULL, NULL, NULL, NULL, 1);

--
-- Déclencheurs `mort`
--
DELIMITER $$
CREATE TRIGGER `check_places_dispo` BEFORE INSERT ON `mort` FOR EACH ROW BEGIN
  DECLARE places_restantes INT;

  IF NEW.IdReposoir IS NULL AND NEW.IdCommunale IS NULL THEN
  BEGIN
    SET places_restantes = PlacesRestantes_select_concession(NEW.IdConcession);
    IF places_restantes < 1 THEN
      BEGIN
        SIGNAL SQLSTATE '20000'
        SET MESSAGE_TEXT = 'Il n y a pas assez de places disponibles';
      END;
    ELSE
      BEGIN
        UPDATE CONCESSION SET PlaceDispo = PlaceDispo - 1 WHERE IdConcession = NEW.IdConcession;
      END;
    END IF;
  END;

  ELSEIF NEW.IdConcession IS NULL AND NEW.IdReposoir IS NULL THEN
  BEGIN
    SET places_restantes = PlacesRestantes_select_communale(NEW.IdCommunale);
    IF places_restantes < 1 THEN
      BEGIN
        SIGNAL SQLSTATE '20000'
        SET MESSAGE_TEXT = 'Il n y a pas assez de places disponibles';
      END;
    ELSE
      BEGIN
        UPDATE TOMBECOMMUNALE SET PlaceDispo = PlaceDispo - 1 WHERE IdCommunale = NEW.IdCommunale;
      END;
    END IF;
  END;

  ELSE
    BEGIN
      SET places_restantes = PlacesRestantes_select_reposoir(NEW.IdReposoir);
      IF places_restantes < 1 THEN
        BEGIN
          SIGNAL SQLSTATE '20000'
          SET MESSAGE_TEXT = 'Il n y a pas assez de places disponibles';
        END;
      ELSE
        BEGIN
          UPDATE REPOSOIR SET PlaceDispo = PlaceDispo - 1 WHERE IdReposoir = NEW.IdReposoir;
        END;
      END IF;
    END;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delete_place` AFTER DELETE ON `mort` FOR EACH ROW BEGIN
  IF OLD.IdCommunale IS NULL AND OLD.IdReposoir IS NULL THEN
    BEGIN
      UPDATE CONCESSION SET PlaceDispo = PlaceDispo + 1 WHERE IdConcession = OLD.IdConcession;
    END;
  ELSEIF OLD.IdConcession IS NULL AND OLD.IdReposoir IS NULL THEN
    BEGIN
      UPDATE TOMBECOMMUNALE SET PlaceDispo = PlaceDispo + 1 WHERE IdCommunale = OLD.IdCommunale;
    END;
  ELSE
    BEGIN
      UPDATE REPOSOIR SET PlaceDispo = PlaceDispo + 1 WHERE IdReposoir = OLD.IdReposoir;
    END;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_delete_place` AFTER UPDATE ON `mort` FOR EACH ROW BEGIN
  IF OLD.IdCommunale IS NULL AND OLD.IdReposoir IS NULL THEN
    BEGIN
      UPDATE CONCESSION SET PlaceDispo = PlaceDispo + 1 WHERE IdConcession = OLD.IdConcession;
    END;
  ELSEIF OLD.IdConcession IS NULL AND OLD.IdReposoir IS NULL THEN
    BEGIN
      UPDATE TOMBECOMMUNALE SET PlaceDispo = PlaceDispo + 1 WHERE IdCommunale = OLD.IdCommunale;
    END;
  ELSE
    BEGIN
      UPDATE REPOSOIR SET PlaceDispo = PlaceDispo + 1 WHERE IdReposoir = OLD.IdReposoir;
    END;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_place` BEFORE UPDATE ON `mort` FOR EACH ROW BEGIN
  DECLARE places_restantes INT;
  IF NEW.IdReposoir IS NULL AND NEW.IdCommunale IS NULL THEN
    IF NEW.IdConcession = OLD.IdConcession THEN
      BEGIN
      END;
    ELSE
      BEGIN
        SET places_restantes = PlacesRestantes_select_concession(NEW.IdConcession);
        IF places_restantes < 1 THEN
          BEGIN
            SIGNAL SQLSTATE '20000'
            SET MESSAGE_TEXT = 'Il n y a pas assez de places disponibles';
          END;
        ELSE
          BEGIN
            UPDATE CONCESSION SET PlaceDispo = PlaceDispo - 1 WHERE IdConcession = NEW.IdConcession;
          END;
        END IF;
      END;
    END IF;

  ELSEIF NEW.IdConcession IS NULL AND NEW.IdReposoir IS NULL THEN
    IF NEW.IdCommunale = OLD.IdCommunale THEN
      BEGIN
      END;
    ELSE
      BEGIN
        SET places_restantes = PlacesRestantes_select_communale(NEW.IdCommunale);
        IF places_restantes < 1 THEN
          BEGIN
            SIGNAL SQLSTATE '20000'
            SET MESSAGE_TEXT = 'Il n y a pas assez de places disponibles';
          END;
        ELSE
          BEGIN
            UPDATE TOMBECOMMUNALE SET PlaceDispo = PlaceDispo - 1 WHERE IdCommunale = NEW.IdCommunale;
          END;
        END IF;
      END;
    END IF;

  ELSE
    IF NEW.IdReposoir = OLD.IdReposoir THEN
      BEGIN
      END;
    ELSE
      BEGIN
        SET places_restantes = PlacesRestantes_select_reposoir(NEW.IdReposoir);
        IF places_restantes < 1 THEN
          BEGIN
            SIGNAL SQLSTATE '20000'
            SET MESSAGE_TEXT = 'Il n y a pas assez de places disponibles';
          END;
        ELSE
          BEGIN
            UPDATE REPOSOIR SET PlaceDispo = PlaceDispo - 1 WHERE IdReposoir = NEW.IdReposoir;
          END;
        END IF;
      END;
    END IF;
  END IF;
 END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `reposoir`
--

CREATE TABLE `reposoir` (
  `IdReposoir` int(11) NOT NULL,
  `PlaceDispo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `reposoir`
--

INSERT INTO `reposoir` (`IdReposoir`, `PlaceDispo`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `tombecivile`
--

CREATE TABLE `tombecivile` (
  `IdConcession` int(11) NOT NULL,
  `NumeroPlan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tombecivile`
--

INSERT INTO `tombecivile` (`IdConcession`, `NumeroPlan`) VALUES
(34, 1),
(35, 141),
(39, 150),
(38, 151);

-- --------------------------------------------------------

--
-- Structure de la table `tombecommunale`
--

CREATE TABLE `tombecommunale` (
  `IdCommunale` int(11) NOT NULL,
  `PlaceDispo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tombecommunale`
--

INSERT INTO `tombecommunale` (`IdCommunale`, `PlaceDispo`) VALUES
(1, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `IdUser` int(11) NOT NULL,
  `Username` varchar(30) DEFAULT NULL,
  `Poste` enum('administrateur','maire','adjoint','secretaire') DEFAULT NULL,
  `MdpHash` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`IdUser`, `Username`, `Poste`, `MdpHash`) VALUES
(1, 'maire', 'maire', '9169bf3e501fea19614cacd6d646b50b63aa822bc2360a4db06aee4cd504cb4f'),
(2, 'admin', 'administrateur', '9169bf3e501fea19614cacd6d646b50b63aa822bc2360a4db06aee4cd504cb4f'),
(3, 'secretaire', 'secretaire', '9169bf3e501fea19614cacd6d646b50b63aa822bc2360a4db06aee4cd504cb4f');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `columbarium`
--
ALTER TABLE `columbarium`
  ADD PRIMARY KEY (`IdConcession`),
  ADD UNIQUE KEY `IdCasier` (`IdCasier`);

--
-- Index pour la table `concession`
--
ALTER TABLE `concession`
  ADD PRIMARY KEY (`IdConcession`);

--
-- Index pour la table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`IdLogs`),
  ADD KEY `IdUtilisateur` (`IdUtilisateur`);

--
-- Index pour la table `mort`
--
ALTER TABLE `mort`
  ADD PRIMARY KEY (`IdMort`),
  ADD KEY `IdConcession` (`IdConcession`),
  ADD KEY `IdCommunale` (`IdCommunale`),
  ADD KEY `IdReposoir` (`IdReposoir`);

--
-- Index pour la table `reposoir`
--
ALTER TABLE `reposoir`
  ADD PRIMARY KEY (`IdReposoir`);

--
-- Index pour la table `tombecivile`
--
ALTER TABLE `tombecivile`
  ADD PRIMARY KEY (`IdConcession`),
  ADD UNIQUE KEY `NumeroPlan` (`NumeroPlan`);

--
-- Index pour la table `tombecommunale`
--
ALTER TABLE `tombecommunale`
  ADD PRIMARY KEY (`IdCommunale`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`IdUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `concession`
--
ALTER TABLE `concession`
  MODIFY `IdConcession` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `logs`
--
ALTER TABLE `logs`
  MODIFY `IdLogs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=865;

--
-- AUTO_INCREMENT pour la table `mort`
--
ALTER TABLE `mort`
  MODIFY `IdMort` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `IdUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `columbarium`
--
ALTER TABLE `columbarium`
  ADD CONSTRAINT `columbarium_ibfk_1` FOREIGN KEY (`IdConcession`) REFERENCES `concession` (`IdConcession`);

--
-- Contraintes pour la table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`IdUtilisateur`) REFERENCES `users` (`IdUser`);

--
-- Contraintes pour la table `mort`
--
ALTER TABLE `mort`
  ADD CONSTRAINT `mort_ibfk_1` FOREIGN KEY (`IdConcession`) REFERENCES `concession` (`IdConcession`),
  ADD CONSTRAINT `mort_ibfk_2` FOREIGN KEY (`IdCommunale`) REFERENCES `tombecommunale` (`IdCommunale`),
  ADD CONSTRAINT `mort_ibfk_3` FOREIGN KEY (`IdReposoir`) REFERENCES `reposoir` (`IdReposoir`);

--
-- Contraintes pour la table `tombecivile`
--
ALTER TABLE `tombecivile`
  ADD CONSTRAINT `tombecivile_ibfk_1` FOREIGN KEY (`IdConcession`) REFERENCES `concession` (`IdConcession`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
