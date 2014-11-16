<?php
include 'header.php';
include 'db/dbfuncs.php';
?>
</head>
<body>

  <?php
include 'menu.php';
?>
<script src="js/common.js"></script> 
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1><?php echo utf8_encode(getSquadInfoLine($_GET['day'],$_GET['time'])) ?></h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5" id="results">
<?php
if (okStartTime($_GET[day],$_GET[time])) {
  $notplayed = true;
  $squads = getSquadPlayers($_GET['day'], $_GET['time']);
  if (count($squads)!=0)
    echo <<<EOT
  <table>
  <th>&nbsp;</th>
  <th>Namn</th>
  <th>Klubb</th>
  <th>Reentry?</th>
EOT;
  else
    echo "Inga spelare anmälda.";

  $i = 1;
  foreach ($squads as $arr) {
    $name = $arr['firstname'] == '' ? $arr['lastname'] : "$arr[firstname] $arr[lastname]";
    $reentry = $arr[squadnumber] == 1 ? "&nbsp;" : ($arr[squadnumber] == 2 ? "(R)" : "(R2)");
    echo <<<EOT
  <tr>
    <td style="padding-right:20px">$i.</td>
    <td style="padding-right:20px">$name</td>
    <td style="padding-right:20px">$arr[club]</td>
    <td style="padding-right:20px">$reentry</td>
  </tr>
EOT;
  $i++;
}
  if (count($squads)!=0)
    echo "</table>";
}
?>        
      </div>
      <div class="col-md-7">
      </div>
    </div>
  </div>


  <?php
include 'footer.php';
?>
<?php
$i = 1;
if (!$notplayed) {
  echo "<script>";
  $results_by_id = getRawResultsSortedByPlayer();
  foreach(getSquadResults($_GET[day],$_GET[time]) as $result) {
  $reentry = $result[squadnumber] == 1 ? "" : ($result[squadnumber] == 2 ? " (R)" : " (R2)");
  $outer_text = <<<EOT
  <table width="100%"> \
    <tr> \
      <td style="width:10%">$i.</td> \
      <td style="width:75%"><strong>$result[lastname]$reentry</strong> ($result[hcp])<br/>$result[club]</td> \
      <td style="width:15%;text-align:right"><strong>$result[result]</strong></td> \
    </tr> \
  </table>
EOT;
  $inner_text = "Hcp/serie: $result[hcp]";
  $inner_text .= "<table width=\"100%\"><tr><th>Start</th><th>Serier (ren slagning)</th><th>Turbo</th><th>Res.</th></tr>";
  foreach ($results_by_id[$result[id]] as $squad) {
    $turbo = $squad[turbo] ? "Ja" : "Nej";
    $squadstring = substr(utf8_encode($squad[info]),0,3) . "...";
    if (count($results_by_id[$result[id]])>1 && $squad[day] == $_GET[day] && $squad[time] == $_GET[time]) {
      $style = " style=\"font-weight:bold\"";
    } else {
      $style = "";
    }
    $inner_text .= <<<EOT
    <tr$style>\
      <td><a href="showsquad.php?day=$squad[day]&time=$squad[time]">$squadstring</a></td>\
      <td>$squad[s1] $squad[s2] $squad[s3] $squad[s4] $squad[s5] $squad[s6]</td>\
      <td>$turbo</td>\
      <td>$squad[result]</td>\
    </tr>
EOT;
  }
  $color = $i%2 == 0 ? "#FFFFFF" : "#EEEEFA";
  echo <<<EOT
  $('#results').append(build_expand_button('$outer_text','$inner_text','$color'));
EOT;
  $i++;

  }
  echo "</script>";
}
?>
</body>
</html>
