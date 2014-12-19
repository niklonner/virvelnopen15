<?php
require_once 'checklogin.php';
require_once '../db/dbfuncs.php';

$id = isset($_GET[id]) ? $_GET[id] : $_POST[idtochange];
$playerinfo = getPlayerInfo($id);
$playersquads = getPlayerSquads($id);

if (isset($_POST[idtochange])) {
  openDB();
  $res = setPlayerSquadsUnchecked($id,array($_POST[squad1],$_POST[squad2],$_POST[squad3]));
echo $res;
  if (!$res) {
    $err[] = $id;
  }
  closeDB();
  // om allt ok markera att ändring skett
  if (!isset($err)) {
    $allok = true;
  }
}

include 'header.php';
?>
</head>
<body>
<a href="loggedin.php">Tillbaka till startsidan</a><br/>
<form method="post" action="<?php echo $_SERVER[PHP_SELF]?>">
<input type="hidden" name="idtochange" value="<?php echo $id?>">
<h2><?php echo "Ändra starter för $playerinfo[lastname], $playerinfo[club] ($playerinfo[bitsid])";?></h2>
<p>Var försiktig! Det görs inga kontroller här. Det är till exempel möjligt att anmäla en spelare till en redan full start.</p>
<?php
if ($allok) {
  echo "<span style='color:#ff0000'>Sparades " . date("Y-m-d H:i:s") . "</span><br/>";
} else if (isset($err)) {
  echo "<span style='color:#ff0000'>". date("Y-m-d H:i:s") .": Fel hittades för följande idn:<br/>";
  foreach ($err as $e) {
    echo "- $e<br/>";
  }
  echo "</span><br/>";
}

$squads = getSquadInfo();
for ($i=1;$i<=3;$i++) {
  echo "Start $i: <select name=\"squad$i\"><option value='none'>Ingen</option>";
  foreach ($squads as $squad) {
    $info = utf8_encode($squad[info]);
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

