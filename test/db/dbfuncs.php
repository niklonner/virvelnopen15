<?php

require_once 'globals.php';
require_once 'validate.php';

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
    return $now->add(new DateInterval('PT1H'));
}

function okStartTime($day, $time) {
    $latest = new DateTime();
    $latest->setTime(substr($time, 0, 2), substr($time,2));
    $latest->setDate("20" . substr($day,0,2),substr($day,2,2),substr($day,4));
    $latest->sub(new DateInterval('PT15M')); // why not 30??????
    return $latest->diff(getSETime())->format('%R')=='+' ? false : true;
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

function registeredForSquad($id, $bits_id, $day, $time) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT count(*) FROM PlayersInSquads WHERE (day=:day AND time=:time) AND (id=:id OR bitsid=:bitsid)");
    $stmt->bindParam("day", $day);
    $stmt->bindParam("time", $time);
    $stmt->bindParam("id", $id);
    $stmt->bindParam("bitsid", $bits_id);
    $stmt->execute();
    $res = $stmt->fetch();
    closeDB();
    return $res[0]>0;
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

// get players in squad
function getSquadPlayers($day, $time) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT id , firstname , lastname , club , hcp , squadnumber FROM PlaysIn NATURAL JOIN Players NATURAL JOIN SquadNumbers WHERE DAY = :day AND time = :time");
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

function getSquadResultsRaw($day,$time) {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT * FROM AllaResultat NATURAL JOIN SquadNumbers WHERE day=:day AND time=:time");
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
    $stmt = $dbh->prepare("SELECT * FROM Resultat NATURAL JOIN SquadNumbers WHERE day=:day AND time=:time");
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
    return $res[0] - getPlayerCount();
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
    $stmt = $dbh->prepare("SELECT DISTINCT id, firstname, lastname, club FROM PlayersInSquads ORDER BY firstname ASC, lastname ASC");
    $stmt->execute();
    $res = array();
    while ($tmp = $stmt->fetch()) {
        $tmp2["id"] = $tmp["id"];
        $tmp2["firstname"] = $tmp["firstname"];
        $tmp2["lastname"] = $tmp["lastname"];
        $tmp2["club"] = $tmp["club"];
        $res[] = $tmp2;
    }
    closeDB();
    return $res;
}

function getAllPlayersWithSquads() {
    $dbh = openDB();
    $stmt = $dbh->prepare("SELECT DISTINCT id, firstname, lastname, day, time, info, club FROM PlayersInSquads ORDER BY firstname ASC, lastname ASC, day ASC, time ASC");
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
    $stmt = $dbh->prepare("SELECT day, time FROM PlaysIn WHERE id=:id");
    $stmt->bindParam("id", $id);
    $stmt->execute();
    $res = array();
    while ($tmp = $stmt->fetch()) {
        unset($tmp[0]);
        unset($tmp[1]);
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
    $res = $stmt->fetch();
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

function registerResult($day, $time, $id, $games, $chansen) {
    $dbh = openDB();
    $stmt = $dbh->prepare("UPDATE PlaysIn SET s1 = :s1 , s2 = :s2 , s3 = :s3 , s4 = :s4 , s5 = :s5 , s6 = :s6, chansen = :chansen WHERE id = :id AND day = :day AND time = :time");
    $stmt->bindParam("id",$id);
    $stmt->bindParam("day",$day);
    $stmt->bindParam("time",$time);
    $stmt->bindParam("s1",$games[0]);
    $stmt->bindParam("s2",$games[1]);
    $stmt->bindParam("s3",$games[2]);
    $stmt->bindParam("s4",$games[3]);
    $stmt->bindParam("s5",$games[4]);
    $stmt->bindParam("s6",$games[5]);
    $stmt->bindParam("chansen",$chansen);
    if (!$stmt->execute()) {
        closeDB();
        return "insert failed";
    }
    closeDB();
    return "ok";
}

function registerPlayer($firstname, $lastname, $club, $bits_id, $phonenumber, $email, $email_repeat, $squad1, $squad2, $squad3) {
    global $globMailReceivers, $globMailTag, $globMailHeader;
    // validate all fields
    if ($bits_id != '' && !verify_bits_id($bits_id)) {
	$error["bits_id"] = true;
    } else if ($bits_id != '') {
      $firstname = "";
    } else {
      if (!verify_firstname($firstname))
        $error["firstname"] = true;
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

    if ($squad1 != "none") {
        $day = substr($squad1,0,6);
        $time = substr($squad1,6,4);
        // player already registered?
        if (registeredForSquad('',$bits_id,$day,$time)) {
          $error["alreadyonsquad1"] = true;
        }
        // squad exists?
        if (!squadExists($day, $time)) {
            $error["internal"] = true;
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
        if (registeredForSquad('',$bits_id,$day,$time)) {
          $error["alreadyonsquad2"] = true;
        }
        if (!squadExists($day, $time)) {
            $error["internal"] = true;
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
        if (registeredForSquad('',$bits_id,$day,$time)) {
          $error["alreadyonsquad3"] = true;
        }
        if (!squadExists($day, $time)) {
            $error["internal"] = true;
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
        return $error;
    }
  
    // get id of player
/*    $stmt = $dbh->prepare("SELECT id FROM Players WHERE firstname=:firstname AND lastname=:lastname AND club=:club AND phonenumber=:phonenumber AND email=:email;");
    $stmt->bindParam("firstname", $firstname);
    $stmt->bindParam("lastname", $lastname);
    $stmt->bindParam("club", $club);
    $stmt->bindParam("password", md5($password . $globSalt));
    $stmt->bindParam("phonenumber", $phonenumber);
    $stmt->bindParam("email", $email);
    if(!$stmt->execute()) {
        $error["internal"] = true;
        return $error;
    }*/

    

//    $idarr = $stmt->fetch();
    $id = $dbh->lastInsertId();
  
    // insert into table playsin
    foreach ($squads as $squad) {
        $stmt = $dbh->prepare("INSERT INTO PlaysIn (id, day, time) VALUES (:id, :day, :time)");
        $stmt->bindParam("id", $id);
        $stmt->bindParam("day", substr($squad,0,6));
        $stmt->bindParam("time", substr($squad,6,4));
        if (!$stmt->execute()) {
            $error["internal"] = true;
            return $error;
        }
    }
    
    closeDB();
    
    ob_start();
    echo "$firstname $lastname\r\n$club\r\n$phonenumber\r\n$email\r\n$squad1\r\n$squad2\r\n$squad3\r\n";
    $message = ob_get_clean();
    mail($globMailReceivers, $globMailTag . "New registration",$message, $globMailHeader);

    if ($email != '' && isset($email)) {
      $squadstring = utf8_encode(getSquadInfoLine(substr($squad1,0,6),substr($squad1,6.4)))."<br>";
      if ($squad2!='none')
        $squadstring .= utf8_encode(getSquadInfoLine(substr($squad2,0,6),substr($squad2,6.4)))."<br>";
      if ($squad3!='none')
        $squadstring .= utf8_encode(getSquadInfoLine(substr($squad3,0,6),substr($squad3,6.4)))."<br>";
    
      if ($firstname != '' && isset($firstname)) {
        $namestring = "$firstname $lastname";
      } else {
        $namestring = $lastname;
      }
  
      ob_start();
      echo "Hej, <br>$namestring är nu anmäld till följande start(er):<br>$squadstring<br>Mvh Team Gothia BC";
      $message = ob_get_clean();
      mail($email, $globMailTag . "Tack för din anmälan",$message, $globMailHeader);
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
    
    //var_dump($squads);
    //echo "<br>";
    // for each squad
    // if player already registered, add to dont unregister-list
    // else check availability and not passed    
    foreach ($squads as $squad) {
        $day = substr($squad,0,6);
        $time = substr($squad,6,4);
        //echo "$day $time<br>";
        if (!registeredForSquad($id,$day,$time)) {
            // if squad passed
            //echo "not registered<br>";
            if (!okStartTime($day,$time)) {
                if (!isset($error['squadpassed'])) {
                    $error['squadpassed'] = array();
                }
                $error['squadpassed'][] = array("day" => $day, "time" => $time);
            } else if (squadFull($day,$time)) { // if squad full
                if (!isset($error['squadfull'])) {
                    $error['squadfull'] = array();
                }
                $error['squadfull'][] = array("day" => $day, "time" => $time);
            } else if (squadCancelled($day,$time)) {
                if (!isset($error['squadcancelled'])) {
                    $error['squadcancelled'] = array();
                }
                $error['squadcancelled'][] = array("day" => $day, "time" => $time);
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
//    var_dump($chosensquads);
//    echo "<br>";
    foreach ($chosensquads as $k => $chosensquad) {
        foreach($dontunregister as $dont) {
            if ($chosensquad['day']==$dont['day'] && $chosensquad['time']==$dont['time']) {
                unset($chosensquads[$k]);
            }
        }
    }
    
    if (count($dontunregister) + count($toregister) > 3) {
        $error['toomanysquads'] = count($dontunregister) + count($toregister);
        return $error;
    }
    
//    var_dump($chosensquads);
//    echo "<br>";
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

    ob_start();
    var_dump(func_get_args());
    $message = ob_get_clean();
    mail($globMailReceivers, $globMailTag . "Changed registration",$message, $globMailHeader);
    
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

?>
