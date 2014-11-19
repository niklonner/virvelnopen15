<?php
require_once 'header.php';
require_once '../db/dbfuncs.php';
require_once '../db/errormessages.php';
require_once '../db/globals.php';
?>

<?php

if (isset($_POST['squad1'])) {
  $squadstosend = array();
  foreach (array($_POST[squad1],$_POST[squad2],$_POST[squad3]) as $squad) {
    if ($squad != "none") {
      $squadstosend[] = $squad;
    }
  }
  $res = checkOkToChangeSquads($_POST['id'],$squadstosend);
  if ($res == "ok") {
    $hashstring = $_POST[id];
    $querystring = "id=$_POST[id]";
    $i = 1;
    foreach ($squadstosend as $sq) {
      $hashstring .= $sq;
      $querystring .= "&squad$i=$sq";
      $i++;
    }
    header("Location:confirmchangerequest.php?$querystring&MAC=" . sha1($hashstring.$globSalt));
  } else {
    $error = $res;
  }
}
?>

</head>
<body>
<h1>Ändring/avanmälan steg 2</h1>
<form method="post" action="dochangestep2.php">
<input type="hidden" name="id" value="<?php echo $_POST[id]; ?>">
<?php
$player = getPlayerInfo($_POST['id']);
echo "<h3>Spelare: $player[lastname], $player[club]</h3>";

echo <<<EOT
<p>När du klickar på "begär ändring" nedan kommer ett mail skickas till den e-postadress du registrerat. <span style="font-weight:bold;color:rgb(255,0,0)">Du måste klicka på länken i mailet för att genomföra förändringarna!</span></p>
EOT;

if (isset($error)) {
  echo "<p style='color:#ff0000'>Följande fel hittades:</p><ul style='color:#ff0000'>";
  foreach ($error as $k => $v) {
    echo "<li>" . getSEReadableErrorMessage($k) . "</li>";
  }
  echo "</ul>";
}

$playersquads = getPlayerSquads($_POST[id]);
$squads = getSquadInfo();
for ($i=1;$i<=3;$i++) {
  echo "Start $i: <select name=\"squad$i\"><option value='none'>Ingen</option>";
  foreach ($squads as $squad) {
    $info = utf8_encode($squad[info]);
    if (isset($error)) {
      $selected = $squad[day] == substr($_POST["squad$i"],0,6) && $squad[time] == substr($_POST["squad$i"],6,4) ? " selected=\"selected\"" : "";
    } else {
      $selected = $squad[day] == $playersquads[$i-1][day] && $squad[time] == $playersquads[$i-1][time] ? " selected=\"selected\"" : "";
    }
    echo <<<EOT
    <option value="$squad[day]$squad[time]" $selected>$info ($squad[count]/$squad[spots] spelare)</option>
EOT;
  }
  echo "</select><br/>";
}
?>
<input type="submit" value="Begär ändring!"/>
</form>

<?php
require_once 'footer.php';
?>
