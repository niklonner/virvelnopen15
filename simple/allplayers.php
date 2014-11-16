<?php
require_once 'header.php';
require_once '../db/dbfuncs.php';
?>

</head>
<body>
<a href="index.php">&lt;&lt; Tillbaka</a>
<h1>Alla spelare</h1>
<table>
  <tr>
    <th>Spelare</th>
    <th>Klubb</th>
    <th>Info</th>
  </tr>
<?php
$previd = -1;
$players = array();
foreach (getAllPlayersWithSquads() as $player) {
  if ($player['id'] != $previd) {
    $player_object = array();
    $player_object['name'] = $player['firstname'] == '' || is_null($player['firstname']) ?
        $player['lastname'] : $player['firstname'] . " ". $player['lastname'];
    $player_object['club'] = $player['club'];
    $squads = array();
    $squads[] = array("day" => $player['day'], "time" => $player['time'], "info" => utf8_encode($player['info']));
    $player_object['squads'] = $squads;
    $players[] = $player_object;
  } else {
    $players[count($players)-1]['squads'][] =
        array("day" => $player['day'], "time" => $player['time'], "info" => utf8_encode($player['info']));
  }
  $previd = $player['id'];
}
$i=0;
foreach ($players as $id => $player) {
  $bgCol = $i%2 == 0 ? "style=\"background-color:#FAFAFF\"" : "";
  echo <<<EOT
  <tr $bgCol>
    <td>$player[name]</td>
    <td>$player[club]</td>
    <td>
EOT;
  $first = true;
  foreach ($player['squads'] as $squad) {
    if ($first == true) {
      $first = false;
    } else {
      echo "<br/>";
    }
    echo "<a href='showsquad.php?day=$squad[day]&time=$squad[time]'>$squad[info]</a>";
  }
  echo <<<EOT
    </td>
  </tr>
EOT;
  $i++;
}
?>
</table>
<?php
require_once 'footer.php';
?>

