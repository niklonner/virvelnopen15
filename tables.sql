-- --------------------------------------------------------

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
  pi.turbo as turbo,
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

-- i would like to have first() here...
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

create or replace view OrdinaryFinalists as
select * from OrdinaryResults limit 14;



create or replace view Turbo5Results as
select
  id,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(firstname AS CHAR)), ',', 1 ) as firstname,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(lastname AS CHAR)), ',', 1) as lastname,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(club AS CHAR)), ',', 1) as club,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(hcp AS CHAR)), ',', 1) as SIGNED) as hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(day AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as day,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(time AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as time,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(earlybird AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as earlybird,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(info AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as info,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(result AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as result,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(scratch AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as scratch,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s1 AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s1,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s2 AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s2,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s3 AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s3,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s4 AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s4,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s5 AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s5,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s6 AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s6,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(turbo AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as turbo,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s1hcp AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s1hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s2hcp AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s2hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s3hcp AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s3hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s4hcp AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s4hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s5hcp AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s5hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s6hcp AS CHAR) ORDER BY s5hcp DESC, result DESC, s6hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s6hcp
from ResultsRaw
where turbo=true
group by id;

create or replace view Turbo5ResultsSorted as
select * from Turbo5Results
order by
  LEAST(300,s5+hcp) desc,
  result desc,
  LEAST(300,s6+hcp) desc,
  LEAST(300,s4+hcp) desc,
  LEAST(300,s3+hcp) desc,
  LEAST(300,s2+hcp) desc,
  LEAST(300,s1+hcp) desc;

create or replace view Turbo5Finalist as
select * from Turbo5Results
where id not in (select id from OrdinaryFinalists)
order by
  LEAST(300,s5+hcp) desc,
  result desc,
  LEAST(300,s6+hcp) desc,
  LEAST(300,s4+hcp) desc,
  LEAST(300,s3+hcp) desc,
  LEAST(300,s2+hcp) desc,
  LEAST(300,s1+hcp) desc
limit 1;

create or replace view Turbo6Results as
select
  id,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(firstname AS CHAR)), ',', 1 ) as firstname,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(lastname AS CHAR)), ',', 1) as lastname,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(club AS CHAR)), ',', 1) as club,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(hcp AS CHAR)), ',', 1) as SIGNED) as hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(day AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as day,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(time AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as time,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(earlybird AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as earlybird,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(info AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as info,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(result AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as result,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(scratch AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as scratch,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s1 AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s1,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s2 AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s2,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s3 AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s3,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s4 AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s4,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s5 AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s5,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s6 AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s6,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(turbo AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as turbo,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s1hcp AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s1hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s2hcp AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s2hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s3hcp AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s3hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s4hcp AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s4hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s5hcp AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s5hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s6hcp AS CHAR) ORDER BY s6hcp DESC, result DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s6hcp
from ResultsRaw
where turbo=true
group by id;

create or replace view Turbo6ResultsSorted as
select * from Turbo6Results
order by
  LEAST(300,s6+hcp) desc,
  result desc,
  LEAST(300,s5+hcp) desc,
  LEAST(300,s4+hcp) desc,
  LEAST(300,s3+hcp) desc,
  LEAST(300,s2+hcp) desc,
  LEAST(300,s1+hcp) desc;

create or replace view Turbo6Finalist as
select * from Turbo6Results
where id not in (select id from OrdinaryFinalists)
  and id not in (select id from Turbo5Finalist)
order by
  LEAST(300,s6+hcp) desc,
  result desc,
  LEAST(300,s5+hcp) desc,
  LEAST(300,s4+hcp) desc,
  LEAST(300,s3+hcp) desc,
  LEAST(300,s2+hcp) desc,
  LEAST(300,s1+hcp) desc
limit 1;

create or replace view EarlyBirdResults as
select
  id,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(firstname AS CHAR)), ',', 1 ) as firstname,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(lastname AS CHAR)), ',', 1) as lastname,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(club AS CHAR)), ',', 1) as club,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(hcp AS CHAR)), ',', 1) as SIGNED) as hcp,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(day AS CHAR) ORDER BY result DESC, s6hcp DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as day,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(time AS CHAR) ORDER BY result DESC, s6hcp DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as time,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(earlybird AS CHAR) ORDER BY result DESC, s6hcp DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as earlybird,
  SUBSTRING_INDEX(GROUP_CONCAT(CAST(info AS CHAR) ORDER BY result DESC, s6hcp DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as info,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(result AS CHAR) ORDER BY result DESC, s6hcp DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as result,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(scratch AS CHAR) ORDER BY result DESC, s6hcp DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as scratch,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s1 AS CHAR) ORDER BY result DESC, s6hcp DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s1,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s2 AS CHAR) ORDER BY result DESC, s6hcp DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s2,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s3 AS CHAR) ORDER BY result DESC, s6hcp DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s3,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s4 AS CHAR) ORDER BY result DESC, s6hcp DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s4,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s5 AS CHAR) ORDER BY result DESC, s6hcp DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s5,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(s6 AS CHAR) ORDER BY result DESC, s6hcp DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as s6,
  CAST(SUBSTRING_INDEX(GROUP_CONCAT(CAST(turbo AS CHAR) ORDER BY result DESC, s6hcp DESC, s5hcp DESC,s4hcp DESC,s3hcp DESC,s2hcp DESC,s1hcp DESC), ',', 1) as SIGNED) as turbo
from ResultsRaw
where earlybird=true
group by id
order by
  result desc,
  LEAST(300,s6+hcp) desc,
  LEAST(300,s5+hcp) desc,
  LEAST(300,s4+hcp) desc,
  LEAST(300,s3+hcp) desc,
  LEAST(300,s2+hcp) desc,
  LEAST(300,s1+hcp) desc;


create or replace view EarlyBirdFinalists as
select * from EarlyBirdResults 
where
  id not in (select id from OrdinaryFinalists)
  and id not in (select id from Turbo5Finalist)
  and id not in (select id from Turbo6Finalist)
  and earlybird=true
order by
  result desc,
  LEAST(300,s6+hcp) desc,
  LEAST(300,s5+hcp) desc,
  LEAST(300,s4+hcp) desc,
  LEAST(300,s3+hcp) desc,
  LEAST(300,s2+hcp) desc,
  LEAST(300,s1+hcp) desc
limit 2;

create or replace view AllFinalists as
(select id, 'ordinary' as way from OrdinaryFinalists)
union
(select id, 'turbo5' as way from Turbo5Finalist)
union
(select id, 'turbo6' as way from Turbo6Finalist)
union
(select id, 'earlybird' as way from EarlyBirdFinalists);



---
--- Finals tables
---

CREATE TABLE IF NOT EXISTS FinalStep1Results (
  id int NOT NULL,
  s1 int(3),
  s2 int(3),
  s3 int(3),
  s4 int(3),
  s5 int(3),
  s6 int(3),
  tiebreaker int(3),
  PRIMARY KEY (id)
); 

CREATE TABLE IF NOT EXISTS FinalStep2AResults (
  id int NOT NULL,
  res int(3),
  gamenum int NOT NULL,
  PRIMARY KEY (gamenum, id)
); 

CREATE TABLE IF NOT EXISTS FinalStep2BResults (
  id int NOT NULL,
  res int(3),
  tiebreaker int(3),
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS FinalStep2Matches (
  seed1 int NOT NULL,
  seed2 int NOT NULL,
  gamenum int NOT NULL,
  lane int NOT NULL,
  PRIMARY KEY (gamenum, seed1, seed2)
); 

CREATE TABLE IF NOT EXISTS Finalists (
  id int,
  type varchar(10),
  primary key(id)
); 

-- use to fill Finalists table (too slow to use as a view)
create or replace view FinalistsView as
(select id, 'ordinary' as type from OrdinaryFinalists
ORDER BY result DESC, s6hcp DESC, s5hcp DESC, s4hcp DESC, s3hcp DESC, s2hcp DESC, s1hcp DESC
LIMIT 2,12)
union
(select id, 'first' as type from OrdinaryFinalists
ORDER BY result DESC, s6hcp DESC, s5hcp DESC, s4hcp DESC, s3hcp DESC, s2hcp DESC, s1hcp DESC
LIMIT 1)
union
(select id, 'second' as type from OrdinaryFinalists
ORDER BY result DESC, s6hcp DESC, s5hcp DESC, s4hcp DESC, s3hcp DESC, s2hcp DESC, s1hcp DESC
LIMIT 1,1)
union
(select id, 'turbo5' as type from Turbo5Finalist)
union
(select id, 'turbo6' as type from Turbo6Finalist)
union
(select id, 'earlybird' as type from EarlyBirdFinalists);

create or replace view Step1Results as
select f.id,
  coalesce(p.hcp, 0) as hcp,
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
  coalesce(s6,0) as s6,
  coalesce(f.tiebreaker,0) as tiebreaker
from FinalStep1Results f join Players p on f.id=p.id
order by
  result,
  tiebreaker,
  s6hcp,
  s5hcp,
  s4hcp,
  s3hcp,
  s2hcp,
  s1hcp;

create or replace view Step2Seedings as
(select id, 1 as seed from Finalists where type='first')
union
(select id, 2 as seed from Finalists where type='second')
union
(select s1.id, count(s2.id)+3 as seed from Step1Results s1 left join Step1Results s2
on
  s2.result > s1.result or
    (s2.result = s1.result and 
      (s2.tiebreaker > s1.tiebreaker or
        (s2.tiebreaker = s1.tiebreaker and
          (s2.s6hcp > s1.s6hcp or
            (s2.s6hcp = s1.s6hcp and
              (s2.s5hcp > s1.s5hcp or 
                (s2.s5hcp = s1.s5hcp and
                  (s2.s4hcp > s1.s4hcp or
                    (s2.s4hcp = s1.s4hcp and
                      (s2.s3hcp > s1.s3hcp or
                        (s2.s3hcp = s1.s3hcp and
                          (s2.s2hcp > s1.s2hcp or
                            (s2.s2hcp = s1.s2hcp and s2.s1hcp > s1.s1hcp)
                          )
                        )
                      )
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
group by s1.id
having count(s2.id)+3 < 9);

create or replace view Step2WinsHelper as
(select s1.id,least(300,f1.res+coalesce(p1.hcp,0)*sign(f1.res)) as res1,
least(300,f2.res+coalesce(p2.hcp,0)*sign(f2.res)) as res2
from FinalStep2Matches sm
  join Step2Seedings s1 on sm.seed1 = s1.seed
  join Step2Seedings s2 on sm.seed2 = s2.seed
  join FinalStep2AResults f1 on f1.id = s1.id and sm.gamenum = f1.gamenum
  join FinalStep2AResults f2 on f2.id=s2.id and sm.gamenum=f2.gamenum
  join Players p1 on p1.id = s1.id
  join Players p2 on p2.id = s2.id
)
union
(select s2.id,least(300,f2.res+coalesce(p2.hcp,0)*sign(f2.res)) as res1,
least(300,f1.res+coalesce(p1.hcp,0)*sign(f1.res)) as res2
from FinalStep2Matches sm
  join Step2Seedings s1 on sm.seed1 = s1.seed
  join Step2Seedings s2 on sm.seed2 = s2.seed
  join FinalStep2AResults f1 on f1.id = s1.id and sm.gamenum = f1.gamenum
  join FinalStep2AResults f2 on f2.id=s2.id and sm.gamenum=f2.gamenum
  join Players p1 on p1.id = s1.id
  join Players p2 on p2.id = s2.id);

create or replace view Step2WinsHelperWins as
select id, count(*) as wins from Step2WinsHelper where res1 > res2 and res1 != 0 and res2 != 0
group by id;

create or replace view Step2WinsHelperTies as
select id, count(*) as ties from Step2WinsHelper where res1 = res2 and res1 != 0 and res2 != 0
group by id;

create or replace view Step2WinsHelperLosses as
select id, count(*) as losses from Step2WinsHelper where res1 < res2 and res1 != 0 and res2 != 0
group by id;

create or replace view Step2Wins as
select s.id, coalesce(wins,0) as wins, coalesce(ties,0) as ties, coalesce(losses,0) as losses
from Step2Seedings s
  left join Step2WinsHelperWins d1
    on d1.id = s.id
  left join Step2WinsHelperTies d2
    on d2.id = s.id
  left join Step2WinsHelperLosses d3
    on d3.id = s.id;

create or replace view Step2Matchups as
select f1.seed1, f1.seed2, f1.gamenum, f1.lane,
  p1.id as id1, p1.lastname as lastname1, p1.club as club1, p1.hcp as hcp1, coalesce(fr1.res,0) as res1,
  p2.id as id2, p2.lastname as lastname2, p2.club as club2, p2.hcp as hcp2, coalesce(fr2.res,0) as res2,
  least(300,coalesce(fr1.res,0)+coalesce(p1.hcp,0)*sign(coalesce(fr1.res))) as res1hcp,
  least(300,coalesce(fr2.res,0)+coalesce(p2.hcp,0)*sign(coalesce(fr2.res))) as res2hcp
from
  Step2Seedings s1 join Players p1 on s1.id=p1.id
  join FinalStep2Matches f1 on s1.seed = f1.seed1
  join Step2Seedings s2 on f1.seed2=s2.seed
  join Players p2 on s2.id=p2.id
  left join FinalStep2AResults fr1 on fr1.id=s1.id and fr1.gamenum = f1.gamenum
  left join FinalStep2AResults fr2 on fr2.id=s2.id and fr2.gamenum = f1.gamenum;

create or replace view Step2Results as
select
  f1.id,
  coalesce(p.hcp,0) as hcp,
  coalesce(f1.res,0) as s1,
  coalesce(f2.res,0) as s2,
  coalesce(f3.res,0) as s3,
  coalesce(f4.res,0) as s4,
  coalesce(f5.res,0) as s5,
  coalesce(f6.res,0) as s6,
  coalesce(f7.res,0) as s7,
  coalesce(s2b.res,0) as s8,
  least(sign(coalesce(f1.res,0))*(coalesce(f1.res,0)+coalesce(p.hcp,0)),300) as s1hcp,
  least(sign(coalesce(f2.res,0))*(coalesce(f2.res,0)+coalesce(p.hcp,0)),300) as s2hcp,
  least(sign(coalesce(f3.res,0))*(coalesce(f3.res,0)+coalesce(p.hcp,0)),300) as s3hcp,
  least(sign(coalesce(f4.res,0))*(coalesce(f4.res,0)+coalesce(p.hcp,0)),300) as s4hcp,
  least(sign(coalesce(f5.res,0))*(coalesce(f5.res,0)+coalesce(p.hcp,0)),300) as s5hcp,
  least(sign(coalesce(f6.res,0))*(coalesce(f6.res,0)+coalesce(p.hcp,0)),300) as s6hcp,
  least(sign(coalesce(f7.res,0))*(coalesce(f7.res,0)+coalesce(p.hcp,0)),300) as s7hcp,
  least(sign(coalesce(s2b.res,0))*(coalesce(s2b.res,0)+coalesce(p.hcp,0)),300) as s8hcp,
  coalesce(f1.res,0) +
  coalesce(f2.res,0) +
  coalesce(f3.res,0) +
  coalesce(f4.res,0) +
  coalesce(f5.res,0) +
  coalesce(f6.res,0) +
  coalesce(f7.res,0) +
  coalesce(s2b.res,0) as scratch,
  least(sign(coalesce(f1.res,0))*(coalesce(f1.res,0)+coalesce(p.hcp,0)),300) +
  least(sign(coalesce(f2.res,0))*(coalesce(f2.res,0)+coalesce(p.hcp,0)),300) +
  least(sign(coalesce(f3.res,0))*(coalesce(f3.res,0)+coalesce(p.hcp,0)),300) +
  least(sign(coalesce(f4.res,0))*(coalesce(f4.res,0)+coalesce(p.hcp,0)),300) +
  least(sign(coalesce(f5.res,0))*(coalesce(f5.res,0)+coalesce(p.hcp,0)),300) +
  least(sign(coalesce(f6.res,0))*(coalesce(f6.res,0)+coalesce(p.hcp,0)),300) +
  least(sign(coalesce(f7.res,0))*(coalesce(f7.res,0)+coalesce(p.hcp,0)),300) +
  least(sign(coalesce(s2b.res,0))*(coalesce(s2b.res,0)+coalesce(p.hcp,0)),300) +
  s2w.wins * 20 + s2w.ties * 10 as result,
  s2w.wins,
  s2w.ties,
  s2w.losses,
  s2w.wins * 20 + s2w.ties * 10 as bonus,
  coalesce(s2b.tiebreaker,0) as tiebreaker
from 
  Step2Wins s2w
  join Players p on s2w.id = p.id
  left join FinalStep2AResults f1 on s2w.id=f1.id and f1.gamenum=1
  left join FinalStep2AResults f2 on s2w.id=f2.id and f2.gamenum=2
  left join FinalStep2AResults f3 on s2w.id=f3.id and f3.gamenum=3
  left join FinalStep2AResults f4 on s2w.id=f4.id and f4.gamenum=4
  left join FinalStep2AResults f5 on s2w.id=f5.id and f5.gamenum=5
  left join FinalStep2AResults f6 on s2w.id=f6.id and f6.gamenum=6
  left join FinalStep2AResults f7 on s2w.id=f7.id and f7.gamenum=7
  left join FinalStep2BResults s2b on s2w.id = s2b.id;
