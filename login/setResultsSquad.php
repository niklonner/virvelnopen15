<?php
require_once 'checklogin.php';
require_once '../db/dbfuncs.php';
$day = substr($_POST[squad],0,6);
$time = substr($_POST[squad],6,4);
if (isset($_POST[ids])) {
  // set squad visibility
  toggleSquadVisibility($day,$time,$_POST[visibility]=="visible");
  // split ids
  $ids = explode(",",$_POST[ids]);
  // for each id, save results
  openDB();
  foreach ($ids as $id) {
    // TODO setPlayerResults
    $resultsarr = array($_POST["s1_$id"],$_POST["s2_$id"],$_POST["s3_$id"],$_POST["s4_$id"],$_POST["s5_$id"],$_POST["s6_$id"]);
    $res = registerResult($id,$day,$time,$resultsarr);
    if (!$res) {
      $err[] = $id;
    }
  }
  closeDB();
  // om allt ok markera att ändring skett
  if (!isset($err)) {
    $allok = true;
  }
}

include 'header.php';
?>
<title>Gothia Open 2014 Adminsidor</title>
</head>
<body>
<a href="loggedin.php">Tillbaka till startsidan</a><br/>
<form method="post" action="setResultsSquad.php">
<h2><?php echo utf8_encode(getSquadInfoLine($day,$time));?></h2>
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
?>
<table>
  <tr>
    <th>
      Internt id
    </th>
    <th>
      Spelare
    </th>
    <th>
      Klubb
    </th>
    <th>
      1
    </th>
    <th>
      2
    </th>
    <th>
      3
    </th>
    <th>
      4
    </th>
    <th>
      5
    </th>
    <th>
      6
    </th>
    <th>
      Scratch
    </th>
    <th>
      Hcp/serie
    </th>
    <th>
      Resultat
    </th>
  </tr>
<?php
  $ids_string = "";
  // TODO getSquadResults, maybe this works?
  foreach (getAllSquadResults($day,$time) as $player) {
    $scratch = 0;
    $result = 0;
    foreach (array($player[s1],$player[s2],$player[s3],$player[s4],$player[s5],$player[s6]) as $game) {
      if (!is_null($game) && $game != 0) {
        $scratch += $game;
        $result += $game+$player[hcp];
      }
    }
    $ids_string .= $player[id] . ",";
    echo <<<EOT
    <tr>
      <td>
        $player[id]
      </td>
      <td>
        $player[lastname]
      </td>
      <td>
        $player[club]
      </td>
      <td>
        <input type="text" size="1" name="s1_$player[id]" value="$player[s1]"/>
      </td>
      <td>
        <input type="text" size="1" name="s2_$player[id]" value="$player[s2]"/>
      </td>
      <td>
        <input type="text" size="1" name="s3_$player[id]" value="$player[s3]"/>
      </td>
      <td>
        <input type="text" size="1" name="s4_$player[id]" value="$player[s4]"/>
      </td>
      <td>
        <input type="text" size="1" name="s5_$player[id]" value="$player[s5]"/>
      </td>
      <td>
        <input type="text" size="1" name="s6_$player[id]" value="$player[s6]"/>
      </td>
      <td>
        $scratch
      </td>
      <td>
        $player[hcp]
      </td>
      <td>
        $result
      </td>
    </tr>
EOT;
  }
  if ($ids_string != "") {
    $ids_string = substr($ids_string,0,strlen($ids_string)-1);
  }
?>
</table>
<input type="hidden" name="ids" value="<?php echo $ids_string?>"/>
<input type="hidden" name="squad" value="<?php echo $_POST[squad]?>"/><br/> 
<input type="checkbox" name="visibility" value="visible" <?php echo squadIsVisible($day,$time) ? "checked=\"checked\"" : "" ?>/>Visa start som spelad i resultatlistor<br/>
<input type="submit" value="Spara"/>
</form>
</body>
</html>

