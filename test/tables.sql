-- --------------------------------------------------------

-- 
-- Struktur för tabell `Players`
-- 

CREATE TABLE `Players` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bitsid` char(12) DEFAULT NULL,
  `firstname` varchar(25) COLLATE utf8_swedish_ci DEFAULT NULL,
  `lastname` varchar(25) COLLATE utf8_swedish_ci DEFAULT NULL,
  `club` varchar(30) COLLATE utf8_swedish_ci DEFAULT NULL,
  `phonenumber` varchar(15) COLLATE utf8_swedish_ci DEFAULT NULL,
  `hcp` int(11) DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=283 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=283;

-- 
-- Struktur för tabell `PlaysIn`
-- 

CREATE TABLE `PlaysIn` (
  `id` int(4) NOT NULL,
  `day` int(6) NOT NULL,
  `time` int(4) NOT NULL,
  `s1` int(3) NOT NULL,
  `s2` int(3) NOT NULL,
  `s3` int(3) NOT NULL,
  `s4` int(3) NOT NULL,
  `s5` int(3) NOT NULL,
  `s6` int(3) NOT NULL,
  `turbo` tinyint(4) NOT NULL DEFAULT '0'
) CHARSET=utf8 COLLATE=utf8_swedish_ci;
- --------------------------------------------------------

-- 
-- Struktur för tabell `Squads`
-- 

CREATE TABLE `Squads` (
  `day` int(6) NOT NULL,
  `time` int(4) NOT NULL,
  `info` varchar(40) NOT NULL,
  `spots` int(2) NOT NULL,
  `done` tinyint(4) NOT NULL DEFAULT '0',
  `cancelled` tinyint(4) NOT NULL DEFAULT '0',
  `earlybird` tinyint(4) NOT NULL DEFAULT '0'
) CHARSET=utf8 COLLATE=utf8_swedish_ci; 

-- 
-- Data i tabell `Squads`
-- 

INSERT INTO `Squads` VALUES (131226, 1000, 'Torsdagen 26/12 10.00 EARLY BIRD', 16, 0, 0,1);
INSERT INTO `Squads` VALUES (131226, 1300, 'Torsdagen 26/12 13.00 EARLY BIRD', 16, 0, 0,1);
INSERT INTO `Squads` VALUES (131228, 1000, 'Lördagen 28/12 10.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (131228, 1300, 'Lördagen 28/12 13.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (131229, 1000, 'Söndagen 29/12 10.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (131229, 1300, 'Söndagen 29/12 13.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (131230, 1900, 'Måndagen 30/12 19.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (140101, 1900, 'Torsdagen 2/1 19.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (140104, 1000, 'Lördagen 4/1 10.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (140104, 1300, 'Lördagen 4/1 13.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (140105, 1000, 'Söndagen 5/1 10.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (140105, 1300, 'Söndagen 5/1 13.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (140106, 1000, 'Måndagen 6/1 10.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (140106, 1300, 'Måndagen 6/1 13.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (140109, 1900, 'Torsdagen 9/1 19.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (140111, 1000, 'Lördagen 11/1 10.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (140111, 1300, 'Lördagen 11/1 13.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (140111, 1600, 'Lördagen 11/1 16.00 ', 16, 0, 0,0);


-- 
-- Struktur för tabell `CountPlayers`
-- 

create view CountPlayers as select count(PlaysIn.id) as count, Squads.day as day, Squads.time as time, Squads.info as info, Squads.spots as spots, Squads.cancelled as cancelled, Squads.earlybird as earlybird from Squads left join PlaysIn on (((PlaysIn.day=Squads.day) and (PlaysIn.time = Squads.time))) group by Squads.day, Squads.time;


-- 
-- Struktur för tabell `SquadNumbersHelper`
-- 

CREATE VIEW `SquadNumbersHelper` AS select `PlaysIn`.`id` AS `id2`,`PlaysIn`.`time` AS `time2`,`PlaysIn`.`day` AS `day2`,(select (count(0) + 1) AS `count(*) + 1` from `PlaysIn` where ((`PlaysIn`.`id` = `id2`) and ((`day2` > `PlaysIn`.`day`) or ((`day2` = `PlaysIn`.`day`) and (`time2` > `PlaysIn`.`time`))))) AS `squadnumber` from `PlaysIn`;

-- 
-- Struktur för tabell `SquadNumbers`
-- 
-- squadnumber is used for reentry detection

CREATE VIEW `SquadNumbers` AS select `SquadNumbersHelper`.`id2` AS `id`,`SquadNumbersHelper`.`day2` AS `day`,`SquadNumbersHelper`.`time2` AS `time`,`SquadNumbersHelper`.`squadnumber` AS `squadnumber` from `SquadNumbersHelper`;


-- 
-- Struktur för tabell `PlayersInSquads`
-- 

CREATE VIEW `PlayersInSquads` AS select `PlaysIn`.`day` AS `day`,`PlaysIn`.`time` AS `time`,`Players`.`id` AS `id`,Players.bitsid AS bitsid,`Players`.`firstname` AS `firstname`,`Players`.`lastname` AS `lastname`,`Players`.`club` AS `club`,`Players`.`phonenumber` AS `phonenumber`,`Players`.`hcp` AS `hcp`,`PlaysIn`.`s1` AS `s1`,`PlaysIn`.`s2` AS `s2`,`PlaysIn`.`s3` AS `s3`,`PlaysIn`.`s4` AS `s4`,`PlaysIn`.`s5` AS `s5`,`PlaysIn`.`s6` AS `s6`,`Squads`.`info` AS `info`,`Squads`.`spots` AS `spots`,`Squads`.`done` AS `done` from ((`Players` join `PlaysIn` on((`Players`.`id` = `PlaysIn`.`id`))) join `Squads` on(((`PlaysIn`.`day` = `Squads`.`day`) and (`PlaysIn`.`time` = `Squads`.`time`))));



