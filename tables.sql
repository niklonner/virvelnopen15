
-- 
-- Struktur för tabell `Players`
-- 

CREATE TABLE `Players` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bitsid` varchar(20) DEFAULT NULL,
  `firstname` varchar(25) COLLATE utf8_swedish_ci DEFAULT NULL,
  `lastname` varchar(25) COLLATE utf8_swedish_ci DEFAULT NULL,
  `club` varchar(30) COLLATE utf8_swedish_ci DEFAULT NULL,
  `phonenumber` varchar(15) COLLATE utf8_swedish_ci DEFAULT NULL,
  `hcp` int(11) DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=283 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=0;

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
  `chansen` tinyint(4) NOT NULL DEFAULT '0'
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
  `earlybird` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`day`,`time`)
) CHARSET=utf8 COLLATE=utf8_swedish_ci; 

--
-- Struktur för tabell `Pages`
--

CREATE TABLE `Pages` (
  `page` varchar(40) NOT NULL,
  `time` DATETIME NOT NULL,
  `comment` varchar(40),
  `text` text NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`page`,`time`)
) CHARSET=utf8 COLLATE=utf8_swedish_ci; 


insert into Pages(page,time,comment,text,active) values ('index.php',now(),'initial commit','Välkommen, detta är hemsidan för Gothia Open 2014. Sidan är under uppbyggnad.',1);
insert into Pages(page,time,comment,text,active) values ('format.php',now(),'initial commit','Formatet kommer att beskrivas här',1);
insert into Pages(page,time,comment,text,active) values ('contact.php',now(),'inital commit','Kontaktuppgifter...',1);

-- 
-- Data i tabell `Squads`
-- 

INSERT INTO `Squads` VALUES (141220, 1000, 'Lördagen 20/12 10.00 EARLY BIRD', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (141220, 1300, 'Lördagen 20/12 13.00 EARLY BIRD', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (141221, 1000, 'Söndagen 20/12 10.00 EARLY BIRD', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (141221, 1300, 'Söndagen 20/12 13.00 EARLY BIRD', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (141226, 1000, 'Fredagen 26/12 10.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (141226, 1300, 'Fredagen 26/12 13.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (141227, 1000, 'Lördagen 27/12 10.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (141227, 1300, 'Lördagen 27/12 13.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (141228, 1000, 'Söndagen 28/12 10.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (141228, 1300, 'Söndagen 28/12 13.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (150103, 1000, 'Lördagen 3/1 10.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (150103, 1300, 'Lördagen 3/1 13.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (150104, 1000, 'Söndagen 4/1 10.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (150104, 1300, 'Söndagen 4/1 13.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (150106, 1000, 'Tisdagen 6/1 10.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (150106, 1300, 'Tisdagen 6/1 13.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (150110, 1000, 'Lördagen 10/1 10.00', 16, 0, 0,0);
INSERT INTO `Squads` VALUES (150110, 1300, 'Lördagen 10/1 13.00', 16, 0, 0,0);



-- 
-- Struktur för tabell `CountPlayers`
-- 

create or replace view CountPlayers as select count(PlaysIn.id) as count, Squads.day as day, Squads.time as time, Squads.info as info, Squads.spots as spots, Squads.cancelled as cancelled, Squads.earlybird as earlybird, Squads.done as done from Squads left join PlaysIn on (((PlaysIn.day=Squads.day) and (PlaysIn.time = Squads.time))) group by Squads.day, Squads.time;


-- 
-- Struktur för tabell `SquadNumbersHelper`
-- 

create or replace view `SquadNumbersHelper` AS select `PlaysIn`.`id` AS `id2`,`PlaysIn`.`time` AS `time2`,`PlaysIn`.`day` AS `day2`,(select (count(0) + 1) AS `count(*) + 1` from `PlaysIn` where ((`PlaysIn`.`id` = `id2`) and ((`day2` > `PlaysIn`.`day`) or ((`day2` = `PlaysIn`.`day`) and (`time2` > `PlaysIn`.`time`))))) AS `squadnumber` from `PlaysIn`;

-- 
-- Struktur för tabell `SquadNumbers`
-- 
-- squadnumber is used for reentry detection

create or replace view `SquadNumbers` AS select `SquadNumbersHelper`.`id2` AS `id`,`SquadNumbersHelper`.`day2` AS `day`,`SquadNumbersHelper`.`time2` AS `time`,`SquadNumbersHelper`.`squadnumber` AS `squadnumber` from `SquadNumbersHelper`;

create or replace view SquadNumbers as
select p1.id as id, p1.day as day, p1.time as time, count(*) as squadnumber from PlaysIn p1 join PlaysIn p2 on p1.id=p2.id where p2.day<p1.day or (p1.day=p2.day and (p2.time<p1.time or p2.time=p1.time)) group by p1.id, p1.day, p1.time
-- 
-- Struktur för tabell `PlayersInSquads`
-- 

create or replace view `PlayersInSquads` AS select `PlaysIn`.`day` AS `day`,`PlaysIn`.`time` AS `time`,`Players`.`id` AS `id`,Players.email as email,Players.bitsid AS bitsid,`Players`.`firstname` AS `firstname`,`Players`.`lastname` AS `lastname`,`Players`.`club` AS `club`,`Players`.`phonenumber` AS `phonenumber`,`Players`.`hcp` AS `hcp`,`PlaysIn`.`s1` AS `s1`,`PlaysIn`.`s2` AS `s2`,`PlaysIn`.`s3` AS `s3`,`PlaysIn`.`s4` AS `s4`,`PlaysIn`.`s5` AS `s5`,`PlaysIn`.`s6` AS `s6`,`Squads`.`info` AS `info`,`Squads`.`spots` AS `spots`,`Squads`.`done` AS `done` from ((`Players` join `PlaysIn` on((`Players`.`id` = `PlaysIn`.`id`))) join `Squads` on(((`PlaysIn`.`day` = `Squads`.`day`) and (`PlaysIn`.`time` = `Squads`.`time`))));


---
--- VY ResultsRaw
---

create or replace view ResultsRaw as
select
  p.id as id,
  p.firstname as firstname,
  p.lastname as lastname,
  p.bitsid as bitsid,
  p.club as club,
  p.email as email,
  coalesce(p.hcp,0) as hcp,
  p.phonenumber as phonenumber,
  p.turbo as turbo,
  s.day as day,
  s.time as time,
  s.info as info,
  s.earlybird as earlybird,
  LEAST(300,coalesce(s1,0)+coalesce(hcp,0)*sign(coalesce(s1,0)))+
    LEAST(300,coalesce(s2,0)+coalesce(hcp,0)*sign(coalesce(s2,0)))+
    LEAST(300,coalesce(s3,0)+coalesce(hcp,0)*sign(coalesce(s3,0)))+
    LEAST(300,coalesce(s4,0)+coalesce(hcp,0)*sign(coalesce(s4,0)))+
    LEAST(300,coalesce(s5,0)+coalesce(hcp,0)*sign(coalesce(s5,0)))+
    LEAST(300,coalesce(s6,0)+coalesce(hcp,0)*sign(coalesce(s6,0))) as result,
  coalesce(s1,0)+coalesce(s2,0)+coalesce(s3,0)+coalesce(s4,0)+coalesce(s5,0)+coalesce(s6,0) as scratch,
  LEAST(300,coalesce(s1,0)+coalesce(p.hcp,0)) as s1hcp,
  LEAST(300,coalesce(s2,0)+coalesce(p.hcp,0)) as s2hcp,
  LEAST(300,coalesce(s3,0)+coalesce(p.hcp,0)) as s3hcp,
  LEAST(300,coalesce(s4,0)+coalesce(p.hcp,0)) as s4hcp,
  LEAST(300,coalesce(s5,0)+coalesce(p.hcp,0)) as s5hcp,
  LEAST(300,coalesce(s6,0)+coalesce(p.hcp,0)) as s6hcp,
  coalesce(s1,0) as s1,
  coalesce(s2,0) as s2,
  coalesce(s3,0) as s3,
  coalesce(s4,0) as s4,
  coalesce(s5,0) as s5,
  coalesce(s6,0) as s6
from PlaysIn pi, Players p, Squads s
where pi.id = p.id and pi.day= s.day and pi.time=s.time and s.done=true
order by
  result desc,
  s6hcp desc,
  s5hcp desc,
  s4hcp desc,
  s3hcp desc,
  s2hcp desc,
  s1hcp desc;


create or replace view OrdinaryResults as
select
  id,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(firstname AS CHAR)), ',', 1 ) as firstname,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(lastname AS CHAR)), ',', 1) as lastname,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(club AS CHAR)), ',', 1) as club,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(hcp AS CHAR)), ',', 1) as SIGNED) as hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(day AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as day,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(time AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as time,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(earlybird AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as earlybird,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(info AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as info,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(result AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as result,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(scratch AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as scratch,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s1 AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s1,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s2 AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s2,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s3 AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s3,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s4 AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s4,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s5 AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s5,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s6 AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s6,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(turbo AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as turbo,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s1hcp AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s1hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s2hcp AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s2hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s3hcp AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s3hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s4hcp AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s4hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s5hcp AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s5hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s6hcp AS CHAR) ORDER BY result DESC,s6hcp DESC,s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s6hcp
from ResultsRaw
group by id
order by
  result desc,
  LEAST(300,s6+hcp) desc,
  LEAST(300,s5+hcp) desc,
  LEAST(300,s4+hcp) desc,
  LEAST(300,s3+hcp) desc,
  LEAST(300,s2+hcp) desc,
  LEAST(300,s1+hcp) desc;
