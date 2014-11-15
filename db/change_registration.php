<?php
require_once 'dbfuncs.php';
require_once 'globals.php';
require_once 'errormessages.php';

$squad1 = $_POST['squad1'] == '' ? 'none' : $_POST['squad1'];
$squad2 = $_POST['squad2'] == '' ? 'none' : $_POST['squad2'];
$squad3 = $_POST['squad3'] == '' ? 'none' : $_POST['squad3'];

$squads = array();

foreach (array($squad1,$squad2,$squad3) as $sq) {
  if ($sq != 'none') {
    $squads[] = $sq;
  }
}

$res = checkOkToChangeSquads($_POST['id'],$squads);

if ($res == "ok") {
  $squad1 = $squad1 == "none" ? "none" :  getSquadInfoLine(substr($squad1,0,6),substr($squad1,6,4));
  $squad2 = $squad2 == "none" ? "none" :  getSquadInfoLine(substr($squad2,0,6),substr($squad2,6,4));
  $squad3 = $squad3 == "none" ? "none" :  getSquadInfoLine(substr($squad3,0,6),substr($squad3,6,4));
  $MAC = sha1($_POST['id'] . $squad1
            . $squad2 . $squad3 . $globSalt);

  $redirect = "id=" . $_POST['id'] .
      "&squad1=". URLencode($squad1) .
      "&squad2=". URLencode($squad2) .
      "&squad3=". URLencode($squad3) .
      "&MAC=" . URLencode($MAC);
  echo $res."__linebreak".$redirect;
} else {
  $first = true;
  foreach ($res as $k => $v) {
    if ($first == true)
      $first = false;
    else
      echo "__linebreak";
    echo getSEReadableErrorMessage($k);
  }
}
