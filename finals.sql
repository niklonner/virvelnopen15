-- TODO hardcode finalist tables
CREATE TABLE IF NOT EXISTS Finalists (
  id int,
  type varchar(10),
  primary key(id)
); 

CREATE TABLE IF NOT EXISTS FinalStep1Results (
  id int NOT NULL,
  s1 int(3),
  s2 int(3),
  s3 int(3),
  tiebreaker int(3),
  PRIMARY KEY (id)
); 

CREATE TABLE IF NOT EXISTS FinalStep2Results (
  id int NOT NULL,
  s1 int(3),
  s2 int(3),
  s3 int(3),
  tiebreaker int(3),
  PRIMARY KEY (id)
); 

CREATE TABLE IF NOT EXISTS FinalStep3AResults (
  id int NOT NULL,
  res int(3),
  gamenum int NOT NULL,
  PRIMARY KEY (gamenum, id)
); 

CREATE TABLE IF NOT EXISTS FinalStep3Matches (
  seed1 int NOT NULL,
  seed2 int NOT NULL,
  gamenum int NOT NULL,
  lane int NOT NULL,
  PRIMARY KEY (gamenum, seed1, seed2)
); 

create or replace view Step1Results as
select f2.id,
  coalesce(p.hcp, 0) as hcp,
  LEAST(300,coalesce(s1,0)+coalesce(hcp,0)*sign(coalesce(s1,0)))+
  LEAST(300,coalesce(s2,0)+coalesce(hcp,0)*sign(coalesce(s2,0)))+
  LEAST(300,coalesce(s3,0)+coalesce(hcp,0)*sign(coalesce(s3,0))) as result,
  coalesce(s1,0)+coalesce(s2,0)+coalesce(s3,0) as scratch,
  LEAST(300,coalesce(s1,0)+coalesce(p.hcp,0)) as s1hcp,
  LEAST(300,coalesce(s2,0)+coalesce(p.hcp,0)) as s2hcp,
  LEAST(300,coalesce(s3,0)+coalesce(p.hcp,0)) as s3hcp,
  coalesce(s1,0) as s1,
  coalesce(s2,0) as s2,
  coalesce(s3,0) as s3,
  coalesce(f.tiebreaker,0) as tiebreaker
from Finalists f2 left outer join FinalStep1Results f on f.id=f2.id left outer join Players p on f.id=p.id
where f2.type <> 'top8'
order by
  result,
  tiebreaker,
  s3hcp,
  s2hcp,
  s1hcp;

create or replace view Step2Players as
(select s1.id from Step1Results s1 left join Step1Results s2
on
  s2.result > s1.result or
    (s2.result = s1.result and 
      (s2.tiebreaker > s1.tiebreaker or
        (s2.tiebreaker = s1.tiebreaker and
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
group by s1.id
having count(s2.id)+1 < 9)
union
(select id from Finalists where type='top8');

create or replace view Step2Results as
select s.id,
  coalesce(p.hcp, 0) as hcp,
  LEAST(300,coalesce(s1,0)+coalesce(hcp,0)*sign(coalesce(s1,0)))+
  LEAST(300,coalesce(s2,0)+coalesce(hcp,0)*sign(coalesce(s2,0)))+
  LEAST(300,coalesce(s3,0)+coalesce(hcp,0)*sign(coalesce(s3,0))) as result,
  coalesce(s1,0)+coalesce(s2,0)+coalesce(s3,0) as scratch,
  LEAST(300,coalesce(s1,0)+coalesce(p.hcp,0)) as s1hcp,
  LEAST(300,coalesce(s2,0)+coalesce(p.hcp,0)) as s2hcp,
  LEAST(300,coalesce(s3,0)+coalesce(p.hcp,0)) as s3hcp,
  coalesce(s1,0) as s1,
  coalesce(s2,0) as s2,
  coalesce(s3,0) as s3,
  coalesce(f.tiebreaker,0) as tiebreaker
from Step2Players s left outer join FinalStep2Results f on s.id=f.id left outer join Players p on s.id=p.id
order by
  result,
  tiebreaker,
  s3hcp,
  s2hcp,
  s1hcp;

create or replace view Step3Seedings as
(select s1.id, count(s2.id)+1 as seed from Step2Results s1 left join Step2Results s2
on
  s2.result > s1.result or
    (s2.result = s1.result and 
      (s2.tiebreaker > s1.tiebreaker or
        (s2.tiebreaker = s1.tiebreaker and
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
group by s1.id
having count(s2.id)+1 < 9);

create or replace view Step3WinsHelper as
(select s1.id,least(300,f1.res+coalesce(p1.hcp,0)*sign(f1.res)) as res1,
least(300,f2.res+coalesce(p2.hcp,0)*sign(f2.res)) as res2
from FinalStep3Matches sm
  join Step3Seedings s1 on sm.seed1 = s1.seed
  join Step3Seedings s2 on sm.seed2 = s2.seed
  join FinalStep3AResults f1 on f1.id = s1.id and sm.gamenum = f1.gamenum
  join FinalStep3AResults f2 on f2.id=s2.id and sm.gamenum=f2.gamenum
  join Players p1 on p1.id = s1.id
  join Players p2 on p2.id = s2.id
)
union
(select s2.id,least(300,f2.res+coalesce(p2.hcp,0)*sign(f2.res)) as res1,
least(300,f1.res+coalesce(p1.hcp,0)*sign(f1.res)) as res2
from FinalStep3Matches sm
  join Step3Seedings s1 on sm.seed1 = s1.seed
  join Step3Seedings s2 on sm.seed2 = s2.seed
  join FinalStep3AResults f1 on f1.id = s1.id and sm.gamenum = f1.gamenum
  join FinalStep3AResults f2 on f2.id=s2.id and sm.gamenum=f2.gamenum
  join Players p1 on p1.id = s1.id
  join Players p2 on p2.id = s2.id);

create or replace view Step3WinsHelperWins as
select id, count(*) as wins from Step3WinsHelper where res1 > res2 and res1 != 0 and res2 != 0
group by id;

create or replace view Step3WinsHelperTies as
select id, count(*) as ties from Step3WinsHelper where res1 = res2 and res1 != 0 and res2 != 0
group by id;

create or replace view Step3WinsHelperLosses as
select id, count(*) as losses from Step3WinsHelper where res1 < res2 and res1 != 0 and res2 != 0
group by id;

create or replace view Step3Wins as
select s.id, coalesce(wins,0) as wins, coalesce(ties,0) as ties, coalesce(losses,0) as losses
from Step3Seedings s
  left join Step3WinsHelperWins d1
    on d1.id = s.id
  left join Step3WinsHelperTies d2
    on d2.id = s.id
  left join Step3WinsHelperLosses d3
    on d3.id = s.id;

create or replace view Step3Matchups as
select f1.seed1, f1.seed2, f1.gamenum, f1.lane,
  p1.id as id1, p1.lastname as lastname1, p1.club as club1, p1.hcp as hcp1, coalesce(fr1.res,0) as res1,
  p2.id as id2, p2.lastname as lastname2, p2.club as club2, p2.hcp as hcp2, coalesce(fr2.res,0) as res2,
  least(300,coalesce(fr1.res,0)+coalesce(p1.hcp,0)*sign(coalesce(fr1.res))) as res1hcp,
  least(300,coalesce(fr2.res,0)+coalesce(p2.hcp,0)*sign(coalesce(fr2.res))) as res2hcp
from
  Step3Seedings s1 join Players p1 on s1.id=p1.id
  join FinalStep3Matches f1 on s1.seed = f1.seed1
  join Step3Seedings s2 on f1.seed2=s2.seed
  join Players p2 on s2.id=p2.id
  left join FinalStep3AResults fr1 on fr1.id=s1.id and fr1.gamenum = f1.gamenum
  left join FinalStep3AResults fr2 on fr2.id=s2.id and fr2.gamenum = f1.gamenum;

create or replace view Step3Results as
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
  least(sign(coalesce(f1.res,0))*(coalesce(f1.res,0)+coalesce(p.hcp,0)),300) as s1hcp,
  least(sign(coalesce(f2.res,0))*(coalesce(f2.res,0)+coalesce(p.hcp,0)),300) as s2hcp,
  least(sign(coalesce(f3.res,0))*(coalesce(f3.res,0)+coalesce(p.hcp,0)),300) as s3hcp,
  least(sign(coalesce(f4.res,0))*(coalesce(f4.res,0)+coalesce(p.hcp,0)),300) as s4hcp,
  least(sign(coalesce(f5.res,0))*(coalesce(f5.res,0)+coalesce(p.hcp,0)),300) as s5hcp,
  least(sign(coalesce(f6.res,0))*(coalesce(f6.res,0)+coalesce(p.hcp,0)),300) as s6hcp,
  least(sign(coalesce(f7.res,0))*(coalesce(f7.res,0)+coalesce(p.hcp,0)),300) as s7hcp,
  coalesce(f1.res,0) +
  coalesce(f2.res,0) +
  coalesce(f3.res,0) +
  coalesce(f4.res,0) +
  coalesce(f5.res,0) +
  coalesce(f6.res,0) +
  coalesce(f7.res,0) as scratch,
  least(sign(coalesce(f1.res,0))*(coalesce(f1.res,0)+coalesce(p.hcp,0)),300) +
  least(sign(coalesce(f2.res,0))*(coalesce(f2.res,0)+coalesce(p.hcp,0)),300) +
  least(sign(coalesce(f3.res,0))*(coalesce(f3.res,0)+coalesce(p.hcp,0)),300) +
  least(sign(coalesce(f4.res,0))*(coalesce(f4.res,0)+coalesce(p.hcp,0)),300) +
  least(sign(coalesce(f5.res,0))*(coalesce(f5.res,0)+coalesce(p.hcp,0)),300) +
  least(sign(coalesce(f6.res,0))*(coalesce(f6.res,0)+coalesce(p.hcp,0)),300) +
  least(sign(coalesce(f7.res,0))*(coalesce(f7.res,0)+coalesce(p.hcp,0)),300) +
  s2w.wins * 30 + s2w.ties * 15 as result,
  s2w.wins,
  s2w.ties,
  s2w.losses,
  s2w.wins * 30 + s2w.ties * 15 as bonus
from 
  Step3Wins s2w
  join Players p on s2w.id = p.id
  left join FinalStep3AResults f1 on s2w.id=f1.id and f1.gamenum=1
  left join FinalStep3AResults f2 on s2w.id=f2.id and f2.gamenum=2
  left join FinalStep3AResults f3 on s2w.id=f3.id and f3.gamenum=3
  left join FinalStep3AResults f4 on s2w.id=f4.id and f4.gamenum=4
  left join FinalStep3AResults f5 on s2w.id=f5.id and f5.gamenum=5
  left join FinalStep3AResults f6 on s2w.id=f6.id and f6.gamenum=6
  left join FinalStep3AResults f7 on s2w.id=f7.id and f7.gamenum=7;

INSERT INTO `FinalStep3Matches` (`seed1`, `seed2`, `gamenum`, `lane`) VALUES
(6, 3, 1, 1),
(2, 7, 1, 2),
(1, 4, 1, 3),
(5, 8, 1, 4),

(8, 1, 2, 1),
(4, 5, 2, 2),
(7, 6, 2, 3),
(3, 2, 2, 4),

(5, 7, 3, 1),
(1, 3, 3, 2),
(2, 8, 3, 3),
(6, 4, 3, 4),

(4, 2, 4, 1),
(8, 6, 4, 2),
(3, 5, 4, 3),
(7, 1, 4, 4),

(6, 1, 5, 1),
(2, 5, 5, 2),
(4, 7, 5, 3),
(8, 3, 5, 4),

(7, 8, 6, 1),
(3, 4, 6, 2),
(1, 2, 6, 3),
(5, 6, 6, 4),

(5, 1, 7, 1),
(6, 2, 7, 2),
(8, 4, 7, 3),
(7, 3, 7, 4);

