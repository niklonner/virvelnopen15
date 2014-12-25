<?php
require_once 'checklogin.php';
require_once '../db/dbfuncs.php';
require_once '../db/errormessages.php';

$id = isset($_GET[id]) ? $_GET[id] : $_POST[idtochange];

if (isset($_POST[idtochange])) {
  openDB();
  $res = setPlayerSquadsUnchecked($id,array($_POST[squad1],$_POST[squad2],$_POST[squad3]));
  if ($res == "ok") {
    $allok = true;
  } else {
    $err = $res;
  }
  closeDB();
  // om allt ok markera att ändring skett
}

$playerinfo = getPlayerInfo($id);
$playersquads = getPlayerSquads($id);

include 'header.php';
?>
</head>
<body>
<a href="loggedin.php">Tillbaka till startsidan</a><br/>
<form method="post" action="<?php echo $_SERVER[PHP_SELF]?>">
<input type="hidden" name="idtochange" value="<?php echo $id?>">
<h2><?php echo "Ändra starter för $playerinfo[lastname], $playerinfo[club] ($playerinfo[bitsid])";?></h2>
<p>Var försiktig! Det görs inga kontroller här. Det är till exempel möjligt att anmäla en spelare till en redan full start.</p>
<p>Om du tar bort en spelare från en redan spelad start försvinner spelarens resultat på den starten.</p>
<?php
if ($allok) {
  echo "<span style='color:#ff0000'>Sparades " . date("Y-m-d H:i:s") . "</span><br/>";
} else if (isset($err)) {
  echo "<p>Fel hittades:</p><ul style='color:#ff0000'>";
  foreach ($err as $k => $v) {
    echo "<li>" . getSEReadableErrorMessage($k) . "</li>";
  }
  echo "</ul>";
}

$squads = getSquadInfo();
for ($i=1;$i<=3;$i++) {
  echo "Start $i: <select name=\"squad$i\"><option value='none'>Ingen</option>";
  foreach ($squads as $squad) {
    $info = $squad[info];
    $selected = $squad[day] == $playersquads[$i-1][day] && $squad[time] == $playersquads[$i-1][time] ? " selected=\"selected\"" : "";
    echo <<<EOT
    <option value="$squad[day]$squad[time]" $selected>$info ($squad[count]/$squad[spots] spelare)</option>
EOT;
  }
  echo "</select><br/>";
}
?>
<input type="submit" value="Spara"/>
</form>
</body>
</html>

