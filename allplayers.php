<?php
include 'header.php';
require_once 'db/dbfuncs.php';
?>
</head>
<body>

  <?php
include 'menu.php';
?>
  
  <div class="container">
    <h1>
      Alla spelare
    </h1>
<div class="row">
  <div class="col-md-7">
    <div class="row">
      <div class="col-md-3">
        <strong>Spelare</strong> 
      </div>
      <div class="col-md-3">
        <strong>Klubb</strong> 
      </div>
      <div class="col-md-6">
        <strong>Start</strong> 
      </div>
      <div class="col-md-5">
      </div>
    </div>
  </div>
  <div class="col-md-5">
    &nbsp;
  </div>
</div>
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
<div class="row">
  <div class="col-md-7" $bgCol>
    <div class="row">
      <div class="col-md-3">
        $player[name]
      </div>
      <div class="col-md-3">
        $player[club]
      </div>
      <div class="col-md-6">
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
      </div>
    </div>
  </div>
  <div class="col-md-5">
    &nbsp;
  </div>
</div>
EOT;
  $i++;
}

?>
  </div>


  <?php
include 'footer.php';
?>
</body>
</html>

