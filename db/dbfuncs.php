<?php

require_once dirname(__FILE__) . '/globals.php';
require_once dirname(__FILE__) . '/validate.php';
require_once dirname(__FILE__) . '/../Parsedown.php';

$dbh = null;
$dbhandlers = 0;

function openDB() {
    global $dbh, $dbhandlers, $globdbUser, $globdbName, $globdbPassword, $globdbHost;
    if ($dbhandlers == 0) {
        try {
            $dbh = new PDO("mysql:host=$globdbHost;dbname=$globdbName", $globdbUser, $globdbPassword);
        } catch(PDOException $e) {
            die('OOPS<br>' . $e->getMessage());
        }
    }
    $dbhandlers++;
    return $dbh;
}

function closeDB() {
    global $dbh, $dbhandlers;
    $dbhandlers--;
    if ($dbhandlers == 0) {
        //mysql_close($dbh); // this seems to be outdated
        $dbh = null;
    }
}

function getSETime() {
    $now = new DateTime();
    return $now;//->add(new DateInterval('PT1H'));
}

function okStartTime($day, $time) {
/*    $latest = new DateTime();
    $latest->setTime(substr($time, 0, 2), substr($time,2));
    $latest->setDate("20" . substr($day,0,2),substr($day,2,2),substr($day,4));
    $latest->sub(new DateInterval('PT15M')); // why not 30??????
    return $latest->diff(getSETime())->format('%R')=='+' ? false : true;*/
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT done FROM Squads WHERE day=:day AND time=:time");
    $stmt->bindParam("day",$day);
    $stmt->bindParam("time",$time);
    $stmt->execute();
    $res = $stmt->fetch();
    closeDB();
    return $res != null && $res[0]!=true;
}

function squadExists($day, $time) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT count(*) FROM Squads WHERE day=:day AND time=:time");
    $stmt->bindParam("day", $day);
    $stmt->bindParam("time", $time);
    $stmt->execute();
    $res = $stmt->fetch();
    closeDB();
    return $res[0]==1;
}

function registeredForSquad($id, $day, $time) {
    $dbh = openDB();
    if (!isset($bits_id) || $bits_id=='') {
      $bits_id = 'NOTABITSID__';
    }
    $stmt = $dbh->prepare("SELECT count(*) FROM PlayersInSquads WHERE (day=:day AND time=:time) AND id=:id");
    $stmt->bindParam("day", $day);
    $stmt->bindParam("time", $time);
    $stmt->bindParam("id", $id);
    $stmt->execute();
    $res = $stmt->fetch();
    closeDB();
    return $res[0]>0;
}

function getSquadInformation($day,$time) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT * FROM Squads WHERE day=:day AND time=:time");
    $stmt->bindParam("day", $day);
    $stmt->bindParam("time", $time);
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    closeDB();
    return $res;
}

function setSquad($day,$time,$info,$spots,$cancelled) {
  $dbh = openDB();
  $stmt = $dbh->prepare("UPDATE Squads SET info=:info, spots=:spots, cancelled=:cancelled WHERE day=:day AND time=:time");
  $stmt->bindParam("day",$day);
  $stmt->bindParam("time",$time);
  $stmt->bindParam("info",$info);
  $stmt->bindParam("spots",$spots);
  $stmt->bindParam("cancelled",$cancelled);
  $res = $stmt->execute();
  closeDB();
  return $res;
}

function removeSquad($day,$time) {
  $dbh = openDB();
  $stmt = $dbh->prepare("DELETE FROM Squads WHERE day=:day AND time=:time");
  $stmt->bindParam("day",$day);
  $stmt->bindParam("time",$time);
  $res = $stmt->execute();
  closeDB();
  return $res;
}

function insertSquad($day,$time,$info,$spots) {
echo "$day $time $info $spots";
  $dbh = openDB();
  $stmt = $dbh->prepare("INSERT INTO Squads (day,time,info,spots) VALUES (:day,:time,:info,:spots)");
  $stmt->bindParam("day",$day);
  $stmt->bindParam("time",$time);
  $stmt->bindParam("info",$info);
  $stmt->bindParam("spots",$spots);
  $res = $stmt->execute();
  closeDB();
  return $res;
}

function squadCancelled($day,$time) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT cancelled FROM Squads WHERE day=:day AND time=:time");
    $stmt->bindParam("day", $day);
    $stmt->bindParam("time", $time);
    $stmt->execute();
    $res = $stmt->fetch();
    closeDB();
    return $res[0]==true;
}

function squadFull($day, $time) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT count, spots FROM CountPlayers WHERE day=:day AND time=:time");
    $stmt->bindParam("day", $day);
    $stmt->bindParam("time", $time);
    $stmt->execute();
    $res = $stmt->fetch();
    closeDB();
    return $res!=false && $res[0]==$res[1];
}

function multipleEarlyBirdsChosen($squads) {
  return false;
}

// returns a 2d array containing info about all squads
// outer array is indexed by integers
// inner arrays are indexed by string keys: count, day, time, info, spots
function getSquadInfo() {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT * FROM CountPlayers ORDER BY day ASC, time ASC");
    $stmt->execute();
    $res = array();
    while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $tmp;
    }
    closeDB();
    return $res;
}

// set player details
function setPlayerDetails($id,$lastname,$club,$bitsid,$hcp) {
  $dbh = openDB();
  $stmt = $dbh->prepare("UPDATE Players SET lastname=:lastname, bitsid=:bitsid, club=:club, hcp=:hcp WHERE id=:id");
  $stmt->bindParam("lastname",$lastname);
  $stmt->bindParam("bitsid",$bitsid);
  $stmt->bindParam("club",$club);
  $stmt->bindParam("hcp",$hcp);
  $stmt->bindParam("id",$id);
  $res = $stmt->execute();
  closeDB();
  return $res;
}

// set player details
function setPlayerDetails2($id,$lastname,$club,$bitsid,$hcp,$phonenumber,$email) {
  $dbh = openDB();
  $stmt = $dbh->prepare("UPDATE Players SET lastname=:lastname, bitsid=:bitsid, club=:club, hcp=:hcp, phonenumber=:phonenumber, email=:email WHERE id=:id");
  $stmt->bindParam("lastname",$lastname);
  $stmt->bindParam("bitsid",$bitsid);
  $stmt->bindParam("club",$club);
  $stmt->bindParam("hcp",$hcp);
  $stmt->bindParam("id",$id);
  $stmt->bindParam("phonenumber",$phonenumber);
  $stmt->bindParam("email",$email);
  $res = $stmt->execute();
  closeDB();
  return $res;
}

// get players in squad
function getSquadPlayers($day, $time) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT id , bitsid, firstname , lastname , club , hcp , squadnumber, phonenumber,email FROM PlaysIn NATURAL JOIN Players NATURAL JOIN SquadNumbers WHERE DAY = :day AND time = :time ORDER BY lastname ASC");
    $stmt->bindParam("day", $day);
    $stmt->bindParam("time", $time);
    $stmt->execute();
    $res = array();
    while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $tmp;
    }
    closeDB();
    return $res;    
}

function getSquadPlayersInfo($day, $time) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT firstname, lastname, club, phonenumber, email, hcp, squadnumber FROM Players NATURAL JOIN PlaysIn NATURAL JOIN SquadNumbers WHERE day=:day AND time=:time ORDER BY firstname ASC, lastname ASC");
    $stmt->bindParam("day", $day);
    $stmt->bindParam("time", $time);
    $stmt->execute();
    $res = array();
    while($tmp = $stmt->fetch()) {
        $res[] = $tmp;
    }
    closeDB();
    return $res;    
}

// fethes all squad results, even unplayed squads
function getAllSquadResults($day,$time) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT * FROM PlaysIn pi join Players p ON pi.id=p.id WHERE pi.day=:day AND pi.time=:time");
    $stmt->bindParam("day",$day);
    $stmt->bindParam("time",$time);
    $stmt->execute();
    $res = array();
    while($tmp = $stmt->fetch()) {
        $res[] = $tmp;
    }
    closeDB();
    return $res;    
}

function getSquadResultsRaw($day,$time) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT * FROM ResultsRaw WHERE day=:day AND time=:time");
    $stmt->bindParam("day",$day);
    $stmt->bindParam("time",$time);
    $stmt->execute();
    $res = array();
    while($tmp = $stmt->fetch()) {
        $res[] = $tmp;
    }
    closeDB();
    return $res;    
}

function getChansenResults() {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT * FROM Chansen");
    $stmt->execute();
    $res = array();
    while($tmp = $stmt->fetch()) {
        $res[] = $tmp;
    }
    closeDB();
    return $res;    
}

function getSquadResults($day,$time) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT * FROM ResultsRaw r JOIN SquadNumbers n ON r.id=n.id AND r.day=n.day AND r.time=n.time  WHERE r.day=:day AND r.time=:time");
    $stmt->bindParam("day",$day);
    $stmt->bindParam("time",$time);
    $stmt->execute();
    $res = array();
    while($tmp = $stmt->fetch()) {
        $res[] = $tmp;
    }
    closeDB();
    return $res;    
}

// returns 2d array, outer indexed by player id
// inner arrays indexed by squad 0,1,2...
function getRawResultsSortedByPlayer() {
  $dbh = openDB();
  $stmt = $dbh->prepare("SELECT * FROM ResultsRaw r JOIN Squads s on r.day=s.day and r.time=s.time join SquadNumbers n on r.id=n.id and r.day=n.day and r.time=n.time ORDER BY r.id DESC, result DESC, s6hcp DESC, s5hcp DESC, s4hcp DESC, s3hcp DESC, s2hcp DESC, s1hcp DESC");
  $stmt->execute();
  $res = array();
  $previd = -1;
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($tmp[id] != $previd) {
      if ($previd != -1) {
        $res[$previd] = $player;
      }
      $player = array();
      $previd = $tmp[id];
    }
    $player[] = $tmp;
  }
  if ($previd != -1) {
    $res[$previd] = $player;
  }
  closeDB();
  return $res;
}

function getNumberOfPlayedSquadsPerPlayer() {
  $dbh = openDB();
  $stmt = $dbh->prepare("select id, count(*) as count from PlaysIn p join Squads s on s.day=p.day and s.time=p.time where done=1 group by id");
  $stmt->execute();
  $res = array();
  while ($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[$tmp[id]] = $tmp[count];
  }
  closeDB();
  return $res;
}

function getOrdinaryResults() {
  $dbh = openDB();
  $stmt = $dbh->prepare("SELECT * FROM OrdinaryResults ORDER BY   result desc,  LEAST(300,s6+hcp) desc, LEAST(300,s5+hcp) desc,  LEAST(300,s4+hcp) desc,  LEAST(300,s3+hcp) desc, LEAST(300,s2+hcp) desc,  LEAST(300,s1+hcp) desc;");
  $stmt->execute();
  $res = array();
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $tmp;
  }
  closeDB();
  return $res;
}

function getCompleteResults() {
  $results = getOrdinaryResults();
  $i=1;
  $juniorspottaken = false;
  $femalespottaken = false;
  foreach($results as $index => $result) {
    //annotate
    $results[$index][isfemale] = $isfemale = substr($result[bitsid],0,1) == "K";
    $results[$index][isjunior] = $isjunior = substr($result[bitsid],5,2) >= 94 || substr($result[bitsid],5,2) <= 15;
    // ordinary finalist?
    if ($i<=16) {
      $results[$index][infinals] = true;
      $results[$index][way] = "ordinary";
    }
    // if not, best junior?
    else if (!$juniorspottaken && $isjunior) {
      $results[$index][infinals] = true;
      $results[$index][way] = "junior";
      $juniorspottaken = true;
    }
    // if not, best female?
    else if (!$femalespottaken && $isfemale) {
      $results[$index][infinals] = true;
      $results[$index][way] = "female";
      $femalespottaken = true;
    }
    // no finals
    else {
      $results[$index][infinals] = false;
    }
    $i++;
  }
  // check for early bird finalists
  $earlybirdspotstaken = 0;
  $earlybirdresults = getEarlyBirdResultsRaw();
  foreach ($earlybirdresults as $index => $res) {
    // already in finals?
    foreach ($results as $jindex => $ordinaryres) {
      if ($res[id] == $ordinaryres[id]) {
        if (!$ordinaryres[infinals]) {
          $results[$jindex][infinals] = true;
          $results[$jindex][way] = "earlybird";
          $earlybirdspotstaken++;
        }
        break;
      }
      if ($earlybirdspotstaken >= 6) {
        break;
      }
    }
  }
  return $results;
}

function getEarlyBirdResultsRaw() {
  $dbh = openDB();
  $stmt = $dbh->prepare("SELECT * FROM EarlyBirdResults ORDER BY   result desc,  LEAST(300,s6+hcp) desc, LEAST(300,s5+hcp) desc,  LEAST(300,s4+hcp) desc,  LEAST(300,s3+hcp) desc, LEAST(300,s2+hcp) desc,  LEAST(300,s1+hcp) desc;");
  $stmt->execute();
  $res = array();
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $tmp;
  }
  return $res;
}

function getEarlyBirdResults() {
  $results = getEarlyBirdResultsRaw();
  $completeresults = getCompleteResults();
  // add info from completeresults
  foreach ($results as $index => $r) {
    $playerindex = getPlayerIndexInCompleteResults($r[id]);
    $results[$index][isfemale] = $completeresults[$playerindex][isfemale];
    $results[$index][isjunior] = $completeresults[$playerindex][isjunior];
    $results[$index][infinals] = $completeresults[$playerindex][infinals];
    $results[$index][way] = $completeresults[$playerindex][way];
  }
  return $results;
}

function getFemaleResults() {
  $results = getCompleteResults();
  foreach ($results as $index => $r) {
    if (!$r[isfemale]) {
      unset($results[$index]);
    }
  }
  return $results;
}

function getJuniorResults() {
  $results = getCompleteResults();
  foreach ($results as $index => $r) {
    if (!$r[isjunior]) {
      unset($results[$index]);
    }
  }
  return $results;
}

function getPlayerIndexInCompleteResults($id) {
  foreach (getCompleteResults() as $i => $res) {
    if ($res[id] == $id) {
      return $i;
    }
  }
}

function getBitsReportStep2() {
  $dbh = openDB();
  $stmt = $dbh->prepare("select s.*,p.bitsid, p.id from Step2Results s join Players p on p.id=s.id order by result DESC, tiebreaker DESC, s8hcp DESC, s7hcp desc, s6hcp desc, s5hcp desc, s4hcp desc, s3hcp desc, s2hcp desc,s1hcp desc");
  $stmt->execute();
  $res = array();
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $tmp;
  }
  closeDB();
  return $res;
}

function getBitsReportStep1() {
  $dbh = openDB();
  $stmt = $dbh->prepare("select s.*,p.bitsid, p.id from Step1Results s join Players p on p.id=s.id order by result DESC, tiebreaker DESC, s6hcp desc, s5hcp desc, s4hcp desc, s3hcp desc, s2hcp desc,s1hcp desc");
  $stmt->execute();
  $res = array();
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $tmp;
  }
  closeDB();
  return $res;
}

/*function getNotInFinals() {
  $dbh = openDB();
  $stmt = $dbh->prepare("SELECT o.*, p.bitsid FROM OrdinaryResults o JOIN Players p ON o.id = p.id where p.id not in (SELECT id FROM Step1Results union (SELECT id FROM Step2Results)) order by result desc, s6hcp desc, s5hcp desc, s4hcp desc, s3hcp desc, s2hcp desc, s1hcp desc");
  $stmt->execute();
  $res = array();
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $tmp;
  }
  closeDB();
  return $res;
}*/

function getBitsReport() {
  $dbh = openDB();
  $stmt = $dbh->prepare("SELECT o.*, p.bitsid, p.id FROM OrdinaryResults o JOIN Players p ON o.id = p.id ORDER by result desc, s6hcp desc, s5hcp desc, s4hcp desc, s3hcp desc, s2hcp desc, s1hcp desc");
  $stmt->execute();
  $res = array();
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $tmp;
  }
  closeDB();
  return $res;
}

/*function getEarlyBirdResults() {
  $dbh = openDB();
  $stmt = $dbh->prepare("SELECT *,o.id as id FROM EarlyBirdResults o LEFT OUTER JOIN AllFinalists a ON o.id = a.id");
  $stmt->execute();
  $res = array();
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $tmp;
  }
  closeDB();
  return $res;
}*/

function getTurbo5Results() {
  $dbh = openDB();
  $stmt = $dbh->prepare("SELECT *,o.id as id FROM Turbo5ResultsSorted o LEFT OUTER JOIN AllFinalists a ON o.id = a.id");
  $stmt->execute();
  $res = array();
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $tmp;
  }
  closeDB();
  return $res;
}

function getTurbo6Results() {
  $dbh = openDB();
  $stmt = $dbh->prepare("SELECT *,o.id as id FROM Turbo6ResultsSorted o LEFT OUTER JOIN AllFinalists a ON o.id = a.id");
  $stmt->execute();
  $res = array();
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $tmp;
  }
  closeDB();
  return $res;
}


function getAllResults() {
    $dbh = openDB();
    $stmt = $dbh->prepare("select r.id,r.firstname,r.lastname,r.club,r.hcp,r.s1,r.s2,r.s3,r.s4,r.s5,r.s6,r.scratch,r.total,r.day,r.time,squadnumber, o.chansen as chansen from Resultat r, (SELECT id, max(total) as total,max(chansen) as chansen, count(*) as squadnumber FROM Resultat GROUP BY id) o where r.id=o.id and r.total=o.total");
    $stmt->execute();
    $res = array();
    while($tmp = $stmt->fetch()) {
        $res[] = $tmp;
    }
    closeDB();
    return $res;    
}

function getStep1Results() {
  $dbh = openDB();
  $stmt = $dbh->prepare("SELECT s.*, p.lastname, p.club FROM Step1Results s join Players p on p.id=s.id order by s.result desc, s.tiebreaker desc, s.s3hcp desc, s.s2hcp desc, s.s1hcp desc");
  $stmt->execute();
  $res = array();
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $tmp;
  }
  closeDB();
  return $res;
}

function registerStep1Result($id,$games,$tiebreaker) {
    $dbh = openDB();
    $stmt = $dbh->prepare("INSERT INTO FinalStep1Results(id,s1,s2,s3,tiebreaker) VALUES (:id,:s1,:s2,:s3,:tiebreaker) ON DUPLICATE KEY UPDATE s1 = :s1 , s2 = :s2 , s3 = :s3, tiebreaker = :tiebreaker");
    $stmt->bindParam("id",$id);
    $stmt->bindParam("s1",$games[0]);
    $stmt->bindParam("s2",$games[1]);
    $stmt->bindParam("s3",$games[2]);
    $stmt->bindParam("tiebreaker",$tiebreaker);
    $res = $stmt->execute();
    closeDB();
    return $res;
}

function getStep2Results() {
  $dbh = openDB();
  $stmt = $dbh->prepare("SELECT s.*, p.lastname, p.club FROM Step2Results s join Players p on p.id=s.id order by s.result desc, s.tiebreaker desc, s.s3hcp desc, s.s2hcp desc, s.s1hcp desc");
  $stmt->execute();
  $res = array();
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $tmp;
  }
  closeDB();
  return $res;
}

function registerStep2Result($id,$games,$tiebreaker) {
    $dbh = openDB();
    $stmt = $dbh->prepare("INSERT INTO FinalStep2Results(id,s1,s2,s3,tiebreaker) VALUES (:id,:s1,:s2,:s3,:tiebreaker) ON DUPLICATE KEY UPDATE s1 = :s1 , s2 = :s2 , s3 = :s3, tiebreaker = :tiebreaker");
    $stmt->bindParam("id",$id);
    $stmt->bindParam("s1",$games[0]);
    $stmt->bindParam("s2",$games[1]);
    $stmt->bindParam("s3",$games[2]);
    $stmt->bindParam("tiebreaker",$tiebreaker);
    $res = $stmt->execute();
    closeDB();
    return $res;
}

function getNumberOfStep3Games() {
  $dbh = openDB();
  $stmt = $dbh->prepare("SELECT MAX(gamenum) from FinalStep3Matches");
  $stmt->execute();
  $res = $stmt->fetch();
  closeDB();
  return $res[0];
}

function getStep3Matches() {
  $dbh = openDB();
  $stmt = $dbh->prepare("SELECT * FROM Step3Matchups ORDER BY gamenum ASC, lane ASC");
  $stmt->execute();
  $res = array();
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $tmp;
  }
  closeDB();
  return $res;
}

function getStep3Players() {
  $dbh = openDB();
  $stmt = $dbh->prepare("SELECT id FROM Step3Seedings");
  $stmt->execute();
  $res = array();
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $tmp;
  }
  closeDB();
  return $res;
}

function registerStep3Result($id, $gamenum, $result) {
    $dbh = openDB();
    $stmt = $dbh->prepare("INSERT INTO FinalStep3AResults (id,gamenum,res) VALUES (:id, :gamenum, :result) ON DUPLICATE KEY UPDATE res=VALUES(res)");
    $stmt->bindParam("id",$id);
    $stmt->bindParam("gamenum",$gamenum);
    $stmt->bindParam("result",$result);
    $res = $stmt->execute();
    closeDB();
    return $res;
}

function registerStep2BResult($id, $result, $tiebreaker) {
    $dbh = openDB();
    $stmt = $dbh->prepare("INSERT INTO FinalStep2BResults (id,res,tiebreaker) VALUES (:id, :result, :tiebreaker) ON DUPLICATE KEY UPDATE res=VALUES(res), tiebreaker=VALUES(tiebreaker)");
    $stmt->bindParam("id",$id);
    $stmt->bindParam("result",$result);
    $stmt->bindParam("tiebreaker",$tiebreaker);
    $res = $stmt->execute();
    closeDB();
    return $res;
}

function getStep2BResults() {
  $dbh = openDB();
  $stmt = $dbh->prepare("select s.id, p.lastname, p.club, p.hcp, f.res, f.tiebreaker from Step2Seedings s left join FinalStep2BResults f on s.id=f.id join Players p on s.id = p.id");
  $stmt->execute();
  $res = array();
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $tmp;
  }
  closeDB();
  return $res;
}

function getStep3Results() {
  $dbh = openDB();
  $stmt = $dbh->prepare("select s.*,p.lastname, p.club from Step3Results s join Players p on p.id=s.id order by result DESC, s7hcp desc, s6hcp desc, s5hcp desc, s4hcp desc, s3hcp desc, s2hcp desc,s1hcp desc");
  $stmt->execute();
  $res = array();
  while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $tmp;
  }
  closeDB();
  return $res;
}

// return an array $arr containing all results in $results but total score only
// contains hcp for played (non-zero) games
// are all copies below really deep-copy?
function onlyDoneResults($results) {
    $ret = array();
    foreach ($results as $result) {
        $total = 0;
        foreach (array($result['s1'],$result['s2'],$result['s3'],$result['s4'],$result['s5'],$result['s6']) as $game) {
            if ($game != 0) {
                $total += $result['hcp'] + $game;
            }
        }
        if ($result['chansen']!=0 && $result['s6']== 0) {
            if ($result['s4'] == 0) {
                $result['chansen'] = 0;
            } else {
                $result['chansen'] = $result['s4']+$result['hcp'];
            }
        }
        $result['total'] = $total;
        $ret[] = $result;
    }
    // sort by total
//    uasort($ret,function($post1,$post2){ return ($post1['total']>$post2['total'] ? -1 : ($post1['total']==$post2['total'] ? 0 : 1));});
    return $ret;
}

function getPlayerCount() {
    $dbh = openDB();
    $stmt = $dbh->prepare("select count(*) from (SELECT * FROM PlayersInSquads GROUP BY id) r");
    $stmt->execute();
    $res = $stmt->fetch();
    closeDB();
    return $res[0];
}

function getReentryCount() {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT count(*) FROM PlayersInSquads");
    $stmt->execute();
    $res = $stmt->fetch();
    closeDB();
    return $res[0];
}

function getAllPrizesInfo() {
    global $globPrizeDistribution;
    $playergivesprizefund = 250; // should use some settings file really
    $reentrygivesprizefund = 200;
    $ret = array();
    $ret['playercount'] = getPlayerCount();
    $ret['reentrycount'] = getReentryCount();
    $ret['playergivesprizefund'] = $playergivesprizefund;
    $ret['reentrygivesprizefund'] = $reentrygivesprizefund;
    
    $prizefund = $ret['playercount'] * $playergivesprizefund + $ret['reentrycount'] * $reentrygivesprizefund;
    $ret['prizefund'] = $prizefund;
    $totalparticipation = $ret['playercount'] + $ret['reentrycount'];
    if ($prizefund >= 24000) {
        // select prize distribution (index into global array)
        if ($totalparticipation <= 100) {
            $pindex = 0;
        } else if ($totalparticipation <=120) {
            $pindex = 1;
        } else {
            $pindex = 2;
        }
        $ret['prizes'] = array();
        $ret['distribution'] = array();
        foreach ($globPrizeDistribution[$pindex] as $prize) {
            $ret['prizes'][] = round($prize/100 * $prizefund);
            $ret['distribution'][] = $prize;
        }
        // check that minimum prizes are not violated
        $ret['prizes'][0] = $ret['prizes'][0] < 12000 ? 12000 : $ret['prizes'][0];
        $ret['prizes'][1] = $ret['prizes'][1] < 6000 ? 6000 : $ret['prizes'][1];
        $ret['prizes'][2] = $ret['prizes'][2] < 3000 ? 3000 : $ret['prizes'][2];
        $ret['prizes'][3] = $ret['prizes'][3] < 2000 ? 2000 : $ret['prizes'][3];
        $ret['prizes'][4] = $ret['prizes'][4] < 1000 ? 1000 : $ret['prizes'][4];
    }
    else { // default prizes
        $ret['fixed'] = true;
        $ret['prizes'] = array(12000,6000,3000,2000,1000);
        $ret['distribution'] = array(-1,-1,-1,-1,-1);
    }
    // free squads
    for ($i = sizeof($ret['prizes'])+1;$i<=ceil($totalparticipation/10);$i++) {
        $ret['prizes'][] = -1;
        $ret['distribution'][] = -1;
    }
    return $ret;
}

function getAllPlayers() {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT DISTINCT id, firstname, lastname, club, bitsid, email FROM PlayersInSquads ORDER BY firstname ASC, lastname ASC");
    $stmt->execute();
    $res = array();
    while ($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $tmp;
    }
    closeDB();
    return $res;
}

function playerExists($id) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT * FROM PlaysIn where id=:id");
    $stmt->bindParam("id",$id);
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    closeDB();
    return $res != null;

}
function getAllPlayersWithSquads() {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT DISTINCT id, firstname, lastname, day, time, info, club FROM PlayersInSquads ORDER BY firstname ASC, lastname ASC, id ASC, day ASC, time ASC");
    $stmt->execute();
    $res = array();
    while ($tmp = $stmt->fetch()) {
        $tmp2["id"] = $tmp["id"];
        $tmp2["firstname"] = $tmp["firstname"];
        $tmp2["lastname"] = $tmp["lastname"];
        $tmp2["club"] = $tmp["club"];
        $tmp2["day"] = $tmp["day"];
        $tmp2["time"] = $tmp["time"];
        $tmp2["info"] = $tmp["info"];
        $res[] = $tmp2; // only necessary line really...
    }
    closeDB();
    return $res;
}

function getSquadInfoLine($day, $time) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT info FROM Squads WHERE day=:day AND time=:time ORDER BY day ASC, time ASC");
    $stmt->bindParam("day", $day);
    $stmt->bindParam("time", $time);
    $stmt->execute();
    $res = $stmt->fetch();
    closeDB();
    return $res[0];    
}

function getPlayerSquads($id) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT Squads.day, Squads.time, info, done FROM PlaysIn JOIN Squads ON PlaysIn.day = Squads.day AND PlaysIn.time = Squads.time WHERE id=:id ORDER BY Squads.day ASC, Squads.time ASC");
    $stmt->bindParam("id", $id);
    $stmt->execute();
    $res = array();
    while ($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $tmp;
    }
    closeDB();
    return $res;
}

function getPlayerInfo($id) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT * FROM Players WHERE id=:id");
    $stmt->bindParam("id", $id);
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    closeDB();
    return $res;
}

function setPlayerHcp($id, $hcp) {
    $dbh = openDB();
    $stmt = $dbh->prepare("UPDATE Players SET hcp = :hcp WHERE id = :id");
    $stmt->bindParam("hcp",$hcp);
    $stmt->bindParam("id",$id);
    if (!$stmt->execute()) {
        closeDB();
        die("Oops, nu gick något allvarligt fel.");
    }
    closeDB();
}

function squadIsVisible($day,$time) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT done FROM Squads WHERE day=:day AND time=:time");
    $stmt->bindParam("day",$day);
    $stmt->bindParam("time",$time);
    $stmt->execute();
    $res = $stmt->fetch();
    return $res[0];
}

function toggleSquadVisibility($day,$time,$on) {
    $dbh = openDB();
    $stmt = $dbh->prepare("UPDATE Squads SET done=:on WHERE day=:day AND time=:time");
    $stmt->bindParam("day",$day);
    $stmt->bindParam("time",$time);
    $stmt->bindParam("on",$on);
    if (!$stmt->execute()) {
        closeDB();
        return "squad visibility could not be changed";
    }
    closeDB();
    return "ok";
}

function registerResult($id, $day, $time, $games) {
    $dbh = openDB();
    $stmt = $dbh->prepare("UPDATE PlaysIn SET s1 = :s1 , s2 = :s2 , s3 = :s3 , s4 = :s4 , s5 = :s5 , s6 = :s6 WHERE id = :id AND day = :day AND time = :time");
    $stmt->bindParam("id",$id);
    $stmt->bindParam("day",$day);
    $stmt->bindParam("time",$time);
    $stmt->bindParam("s1",$games[0]);
    $stmt->bindParam("s2",$games[1]);
    $stmt->bindParam("s3",$games[2]);
    $stmt->bindParam("s4",$games[3]);
    $stmt->bindParam("s5",$games[4]);
    $stmt->bindParam("s6",$games[5]);
    $res = $stmt->execute();
    closeDB();
    return $res;
}

function registerPlayer($firstname, $lastname, $club, $bits_id, $phonenumber, $email, $email_repeat, $squad1, $squad2, $squad3) {
    global $globMailReceivers, $globMailTag, $globMailHeader;
    // validate all fields
/*    if ($bits_id != '' && !verify_bits_id($bits_id)) {
      $error["bits_id"] = true;
    } else if ($bits_id != '') {
      $firstname = "";
    } else {
      if (!verify_firstname($firstname))
        $error["firstname"] = true;
    }*/
    if ($firstname != '' && !verify_firstname($firstname)) {
      $error["firstname"] = true;
    }
    if ($bits_id != '' && !verify_bits_id($bits_id)) {
      $error['bits_id'] = true;
    }
    if (!verify_lastname($lastname))
      $error["lastname"] = true;
    if (!verify_club($club))
      $error["club"] = true;
    if (!verify_phonenumber($phonenumber))
      $error["phonenumber"] = true;
    if (!verify_email($email))
      $error["email"] = true;
    if ($email!=$email_repeat)
      $error["email_repeat"] = true;
        
    $squads = array();
        
    // one must be chosen
    if ($squad1=="none" && $squad2=="none" && $squad3=="none")
        $error["nonechosen"] = true;

    if (multipleEarlyBirdsChosen(array($squad1,$squad2,$squad3))) {
      $error['multipleearlybirds'] = true;
    }

    if (isset($error)) {
      return $error;
    }

    if ($squad1 != "none") {
        $day = substr($squad1,0,6);
        $time = substr($squad1,6,4);
        // player already registered?
        if (registeredForSquad('',$day,$time)) {
          $error["alreadyonsquad1"] = true;
        }
        // squad exists?
        if (!squadExists($day, $time)) {
            $error["internal"] = true;
echo "internal 1";
        }
        // squad not full?
        if (squadFull($day,$time)) {
            $error["squad1full"] = true;
        }
        if (squadCancelled($day,$time)) {
            $error["squad1cancelled"] = true;
        }
        // start time not passed
        if (!okStartTime($day,$time)) {
            $error["squad1passed"] = true;
        }
        $squads[] = $squad1;
    }
    if ($squad2 != "none") {
        $day = substr($squad2,0,6);
        $time = substr($squad2,6,4);
        // player already registered?
        if (registeredForSquad('',$day,$time)) {
          $error["alreadyonsquad2"] = true;
        }
        if (!squadExists($day, $time)) {
            $error["internal"] = true;
echo "internal 2";
        }
        if (squadFull($day,$time)) {
            $error["squad2full"] = true;
        }
        // no two same time?
        if ($squad2==$squad1 || $squad2==$squad3) {
            $error["squadssame"] = true;
        }
        if (squadCancelled($day,$time)) {
            $error["squad2cancelled"] = true;
        }
        if (!okStartTime($day,$time)) {
            $error["squad2passed"] = true;
        }
        $squads[] = $squad2;
    }
    if ($squad3 != "none") {
        $day = substr($squad3,0,6);
        $time = substr($squad3,6,4);
        // player already registered?
        if (registeredForSquad('',$day,$time)) {
          $error["alreadyonsquad3"] = true;
        }
        if (!squadExists($day, $time)) {
            $error["internal"] = true;
echo "internal 3";
        }
        if (squadFull($day,$time)) {
            $error["squad3full"] = true;
        }
        if ($squad3==$squad1 || $squad2==$squad3) {
            $error["squadssame"] = true;
        }
        if (squadCancelled($day,$time)) {
            $error["squad3cancelled"] = true;
        }
        if (!okStartTime($day,$time)) {
            $error["squad3passed"] = true;
        }
        $squads[] = $squad3;
    }
  
    if (isset($error))
        return $error;

    // do the actual insertion
    
    global $globSalt;
    
    $dbh = openDB();
    // insert into table players
    $stmt = $dbh->prepare("INSERT into Players (firstname, lastname, club, phonenumber, bitsid, email) VALUES (:firstname, :lastname, :club, :phonenumber, :bitsid, :email);");
    $stmt->bindParam("firstname", $firstname);
    $stmt->bindParam("lastname", $lastname);
    $stmt->bindParam("club", $club);
    $stmt->bindParam("phonenumber", $phonenumber);
    $stmt->bindParam("bitsid", $bits_id);
    $stmt->bindParam("email", $email);
    if(!$stmt->execute()) {
        $error["internal"] = true;
echo "internal 4";
        return $error;
    }
  
    $id = $dbh->lastInsertId();
  
    // insert into table playsin
    foreach ($squads as $squad) {
        $stmt = $dbh->prepare("INSERT INTO PlaysIn (id, day, time) VALUES (:id, :day, :time)");
        $stmt->bindParam("id", $id);
        $dummya = substr($squad,0,6); // why do I need this?
        $dummyb = substr($squad,6,4); // why do I need this?
        $stmt->bindParam("day", $dummya);
        $stmt->bindParam("time", $dummyb);
        if (!$stmt->execute()) {
            $error["internal"] = true;
echo "internal 5";
            return $error;
        }
    }
    
    closeDB();
    
    ob_start();
    echo "$firstname $lastname\r\n$club\r\n$phonenumber\r\n$email\r\n$squad1\r\n$squad2\r\n$squad3\r\n";
    $message = ob_get_clean();
    mail($globMailReceivers, $globMailTag . "New registration",$message, $globMailHeader);

    if ($email != '' && isset($email)) {
      $squadstring = getSquadInfoLine(substr($squad1,0,6),substr($squad1,6.4))."<br>";
      if ($squad2!='none')
        $squadstring .= getSquadInfoLine(substr($squad2,0,6),substr($squad2,6.4))."<br>";
      if ($squad3!='none')
        $squadstring .= getSquadInfoLine(substr($squad3,0,6),substr($squad3,6.4))."<br>";
    
      if ($firstname != '' && isset($firstname)) {
        $namestring = "$firstname $lastname";
      } else {
        $namestring = $lastname;
      }
  
      ob_start();
      echo "Hej, <br>$namestring är nu anmäld till följande start(er):<br>$squadstring<br>Mvh BK Virveln";
      $message = ob_get_clean();
      $ret = mail($email, "=?utf-8?B?" . base64_encode($globMailTag . "Tack för din anmälan") . "?=",$message, $globMailHeader);
/*      if ($ret != TRUE) {
        $error['internal'] = true;
      }*/
      if (isset($error)) {
        return $error;
      }
    }    
    return "ok";
}

function sortSquads($s1,$s2) {
  if ($s1[day] < $s2[day]) {
    return -1;
  } else if ($s1[day] == $s2[day]) {
    if ($s1[time] < $s2[time]) {
      return -1;
    } else if ($s1[time] == $s2[time]) {
      return 0; 
    } else {
      return 1;
    }
  } else {
    return 1;
  }
}

// $squads need to be a "normal" array meaning $squads[0] .. $squads[n-1] should be set
function checkOkToChangeSquads($id, $squads) {
    global $globMailReceivers, $globMailTag, $globMailHeader, $globSalt, $globProductionString, $globWebsiteAddress;

    // check if all unique
    if (isset($squads[1]) && ($squads[1]==$squads[0] || $squads[1]==$squads[2] || $squads[0]==$squads[2])) {
        $error['samechosen'] = true;
    }
/*
    Early birds have been played now
    if (multipleEarlyBirdsChosen($squads)) {
      $error['multipleearlybirds'] = true;
    }*/

    if (isset($error)) {
      return $error;
    }

    if (!playerExists($id)) {
      $error['internal'] = true;
      return $error;
    }

    // add player's previous squad(s), if not already present
    $playersquads = getPlayerSquads($id);
    foreach ($playersquads as $playersquad) {
        // if squad has been played
        if (!okStartTime($playersquad[day],$playersquad[time])) {
          $alreadyexists = false;
          foreach($squads as $sq) {
              if ($playersquad['day']==substr($sq,0,6) && $playersquad['time']==substr($sq,6,4)) {
                  $alreadyexists = true;
                  break;
              }
          }
          if (!$alreadyexists) {
            $squads[] = "$playersquad[day]$playersquad[time]";
          }
        }
    }

    // if too many squads, set error and return
    if (count($squads) > 3) {
      $error[toomanysquads] = true;
      return $error;
    }

    asort($squads);

    // check if $playersquads == $playersquad
    $eqhelper = array();
    foreach ($playersquads as $sq) {
      $eqhelper[] = "$sq[day]$sq[time]";
    }
    if ($squads==$eqhelper) {
      $error[nochange] = true;
      return $error;
    }

    // for each squad
    // if player not already registered
    // check availability and other criteria
    $i = 0; 
    foreach ($squads as $squad) {
        $i++;
        $day = substr($squad,0,6);
        $time = substr($squad,6,4);
        //echo "$day $time<br>";
        if (!registeredForSquad($id,$day,$time)) {
            // if squad exists and is not passed
            if (!okStartTime($day,$time)) {
                if (!isset($error["squad{$i}passed"])) {
                    $error["squad{$i}passed"] = array();
                }
                $error["squad{$i}passed"][] = array("day" => $day, "time" => $time);
            } else if (squadFull($day,$time)) { // if squad full
                if (!isset($error["squad{$i}full"])) {
                    $error["squad{$i}full"] = array();
                }
                $error["squad{$i}full"][] = array("day" => $day, "time" => $time);
            } else if (squadCancelled($day,$time)) {
                if (!isset($error["squad{$i}cancelled"])) {
                    $error["squad{$i}cancelled"] = array();
                }
                $error["squad{$i}cancelled"][] = array("day" => $day, "time" => $time);
            } 
        }
    }
    
    if (isset($error)) {
        return $error;
    }

    $player = getPlayerInfo($id);

    ob_start();
    echo $player[lastname];
    echo "<br/>";
    var_dump(func_get_args());
    $message = ob_get_clean();
    mail($globMailReceivers, $globMailTag . "Starting to change registration",$message, $globMailHeader);
   
    $name = is_null($player['bitsid']) ? $player['firstname'] . " " . $player['lastname'] : $player['lastname'];
    $link = $globWebsiteAddress . "/dochangefinal.php?id=$id";
    $hashstring = $id;
    $i = 1;
    foreach ($squads as $sq) {
      $link .= "&squad$i=$sq";
      $hashstring .= $sq;
      $i++;
    }
    $hashstring .= $globProductionString;
    $MAC = sha1($hashstring.$globSalt);
    $link .= "&MAC=$MAC";

    ob_start();

    echo "Hej $name,<br/><br/>";

    if (empty($squads)) {
      echo "Du har begärt avanmälan från tävlingen.<br/>";
    } else {
      echo "Du har begärt ändring av dina starter. Du vill spela följande starter:<br/>";
      foreach ($squads as $sq) {
        echo getSquadInfoLine(substr($sq,0,6),substr($sq,6,4)) . "<br/>";
      }

    }
EOT;
    echo <<<EOT
<br/>
<strong>För att genomföra ändringarna måste du bekräfta dem genom att klicka på följande länk:</strong>
    <a href="$link">$link</a>.<br/><br/>

    Med vänliga hälsningar,<br/>
    BK Virveln
EOT;

    $message = ob_get_clean(); 
    $ret = mail($player['email'], "=?utf-8?B?" . base64_encode($globMailTag . "Ändring begärd") . "?=",$message, $globMailHeader);
    if ($ret != TRUE) {
      $error['internal'] = true;
    }
    if (isset($error)) {
      return $error;
    }
 
    // return ok
    return "ok";
}

function setPlayerSquadsUnchecked($id,$squads) {
  // fix the format of squads
  foreach ($squads as $k => $s) {
    if ($s == "none") {
      unset($squads[$k]);
    } else {
      $squads[$k] = array(day => substr($s,0,6), time => substr($s,6,4));
    }
  }
  $dbh = openDB();
  //get old squads
  $oldsquads = getPlayerSquads($id);
  //for each old squad s
  foreach ($oldsquads as $k => $oldsquad) {
    //  if s is in new squads
    foreach($squads as $j => $squad) {
      if ($oldsquad['day']==$squad['day'] && $oldsquad['time']==$squad['time']) {
        // delete s from new and old squads
        unset($oldsquads[$k]);
        unset($squads[$j]);
      }
    }
  }

  //now, old squads = squads to remove player from
  $removesquads = $oldsquads;
  //and new squads = squads to add player to
  $newsquads = $squads;

  //foreach new squads s, add player to s
  foreach ($newsquads as $regsquad) {
    $stmt = $dbh->prepare("INSERT into PlaysIn (day,time,id) VALUES (:day,:time,:id)");
    $stmt->bindParam("day",$regsquad['day']);
    $stmt->bindParam("time",$regsquad['time']);
    $stmt->bindParam("id",$id);
    $ok = $stmt->execute();
    if (!$ok) {
      $error['internal'] = true;
    }
  }

  if (isset($error)) {
    return $error;
  }

  //foreach old squad s, remove player from s
  foreach ($oldsquads as $chosensquad) {
    $day = $chosensquad['day'];
    $time = $chosensquad['time'];
    $stmt = $dbh->prepare("DELETE FROM PlaysIn WHERE id=:id AND day=:day AND time=:time");
    $stmt->bindParam("id",$id);
    $stmt->bindParam("day",$day);
    $stmt->bindParam("time",$time);
    $ok = $stmt->execute();
    if (!$ok) {
      $error['internal'] = true;
    }
  }

  if (isset($error)) {
    return $error;
  }

  return "ok";
}

function changeSquads($id, $squads) {
    global $globMailReceivers, $globMailTag, $globMailHeader;

    $toregister = array();
    $dontunregister = array();
    
    // check if all unique
    if (isset($squads[1]) && ($squads[1]==$squads[0] || $squads[1]==$squads[2] || $squads[0]==$squads[2])) {
        $error['samechosen'] = true;
    }

/*  Early birds have been played now
    if (multipleEarlyBirdsChosen($squads)) {
      $error['multipleearlybirds'] = true;
    }*/

    // add player's previous squad(s), if not already present
    $playersquads = getPlayerSquads($id);
    foreach ($playersquads as $playersquad) {
        // if squad has been played
        if (!okStartTime($playersquad[day],$playersquad[time])) {
          $alreadyexists = false;
          foreach($squads as $sq) {
              if ($playersquad['day']==substr($sq,0,6) && $playersquad['time']==substr($sq,6,4)) {
                  $alreadyexists = true;
                  break;
              }
          }
          if (!$alreadyexists) {
            $squads[] = "$playersquad[day]$playersquad[time]";
          }
        }
    }

    // if too many squads, set error and return
    if (count($squads) > 3) {
      $error[toomanysquads] = true;
      return $error;
    }

    if (isset($error)) {
      return $error;
    }

    
    //var_dump($squads);
    //echo "<br>";
    // for each squad
    // if player already registered, add to dont unregister-list
    // else check availability and not passed    
    $i = 0;
    foreach ($squads as $squad) {
        $i++;
        $day = substr($squad,0,6);
        $time = substr($squad,6,4);
        //echo "$day $time<br>";
        if (!registeredForSquad($id,$day,$time)) {
            // if squad passed
            if (!okStartTime($day,$time)) {
                if (!isset($error["squad{$i}passed"])) {
                    $error["squad{$i}passed"] = array();
                }
                $error["squad{$i}passed"][] = array("day" => $day, "time" => $time);
            } else if (squadFull($day,$time)) { // if squad full
                if (!isset($error["squad{$i}full"])) {
                    $error["squad{$i}full"] = array();
                }
                $error["squad{$i}full"][] = array("day" => $day, "time" => $time);
            } else if (squadCancelled($day,$time)) {
                if (!isset($error["squad{$i}cancelled"])) {
                    $error["squad{$i}cancelled"] = array();
                }
                $error["squad{$i}cancelled"][] = array("day" => $day, "time" => $time);
            } else {
                //echo "ok to register";
                $toregister[] = array("day" => $day, "time" => $time);
            }
        } else {
            $dontunregister[] = array("day" => $day, "time" => $time);
        }
    }
    
    if (isset($error)) {
        return $error;
    }

    $dbh = openDB();
    // unregister player from current non-passed squads
    $chosensquads = getPlayerSquads($id);
    foreach ($chosensquads as $k => $chosensquad) {
        foreach($dontunregister as $dont) {
            if ($chosensquad['day']==$dont['day'] && $chosensquad['time']==$dont['time']) {
                unset($chosensquads[$k]);
            }
        }
    }
    
    if (empty($chosensquads) && empty($toregister)) {
      $error['changealreadyperformed'] = true;
      return $error;
    }

    if (!playerExists($id)) {
      $error['internal'] = true;
      return $error;
    }
 
    foreach ($chosensquads as $chosensquad) {
        $day = $chosensquad['day'];
        $time = $chosensquad['time'];
        if (okStartTime($day,$time)) {
            // unregister
            //echo "unregister from $chosensquad[day] $chosensquad[time]<br>";
            $stmt = $dbh->prepare("DELETE FROM PlaysIn WHERE id=:id AND day=:day AND time=:time");
            $stmt->bindParam("id",$id);
            $stmt->bindParam("day",$day);
            $stmt->bindParam("time",$time);
            $ok = $stmt->execute();
            if (!$ok) {
                $error['internal'] = true;
            }
        }
    }
    
    // register player on new squads
    foreach ($toregister as $regsquad) {
        // register
        //echo "register for $regsquad[day] $regsquad[time]<br>";
        $stmt = $dbh->prepare("INSERT into PlaysIn (day,time,id) VALUES (:day,:time,:id)");
        $stmt->bindParam("day",$regsquad['day']);
        $stmt->bindParam("time",$regsquad['time']);
        $stmt->bindParam("id",$id);
        $ok = $stmt->execute();
        if (!$ok) {
            $error['internal'] = true;
        }
    }

    closeDB();

    // if any error
    if (isset($error)) {
        return $error;
    }

    $player = getPlayerInfo($id);

    ob_start();
    echo $player[lastname];
    echo "<br/>";
    var_dump(func_get_args());
    $message = ob_get_clean();
    mail($globMailReceivers, $globMailTag . "Changed registration",$message, $globMailHeader);

    ob_start();

    $name = is_null($player['bitsid']) ? $player['firstname'] . " " . $player['lastname'] : $player['lastname'];
    echo "Hej $name,<br/><br/>";

    if (empty($squads)) {
      echo "Du är nu avanmäld från tävlingen.<br/>";
    } else {
      echo "Du är nu anmäld till följande starter:<br/>";
      $squads = getPlayerSquads($id);
      foreach ($squads as $sq) {
        echo getSquadInfoLine($sq['day'],$sq['time']) . "<br/>";
      }
    }

    echo "<br/>";
    echo "Med vänliga hälsningar,<br/>BK Virveln";

    $message = ob_get_clean();
    mail($player['email'], "=?utf-8?B?" . base64_encode($globMailTag . "Ändringar genomförda") . "?=", $message, $globMailHeader);

    // return ok
    return "ok";
}

function verifyPassword($id,$trypassword) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT password FROM Players WHERE id=:id");
    $stmt->bindParam("id",$id);
    if(!$stmt->execute()) {
        return false;
    }
    $passwordarr = $stmt->fetch();
    if ($passwordarr == false) {
        return false;
    }
    $password = $passwordarr[0];
    global $globSalt;
    return md5($trypassword . $globSalt) == $password;
}

function getPageText($page) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT * FROM Pages WHERE page=:page AND active=1");
    $stmt->bindParam("page", $page);
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    closeDB();
    return $res;
}

function getPageTextFormatted($page) {
    $res = getPageText($page);
    $pd = new Parsedown();
    $res[text] = $pd->text($res[text]);
    return $res;
}

function setPageText($page, $text, $comment) {
    $dbh = openDB();
    $stmt = $dbh->prepare("UPDATE Pages set active=0 where page=:page");
    $stmt->bindParam("page",$page);
    $res1 = $stmt->execute();
    $stmt = $dbh->prepare("INSERT INTO Pages (page,time,comment,text,active) VALUES (:page,NOW(),:comment,:text,1)");
    $stmt->bindParam("page",$page);
    $stmt->bindParam("comment",$comment);
    $stmt->bindParam("text",$text);
    $res2 = $stmt->execute();
    closeDB();
    return $res1 && $res2;
}

function getAvailablePages() {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT page FROM Pages GROUP BY page order by page ASC");
    $stmt->bindParam("page", $page);
    $stmt->execute();
    $res = array();
    while($tmp = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $tmp[page];
    }
    closeDB();
    return $res;    
}

?>
