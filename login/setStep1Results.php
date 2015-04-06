<?php
require_once 'checklogin.php';
require_once '../db/dbfuncs.php';
if (isset($_POST[ids])) {
  // split ids
  $ids = explode(",",$_POST[ids]);
  // for each id, save results
  openDB();
  foreach ($ids as $id) {
    $resultsarr = array($_POST["s1_$id"],$_POST["s2_$id"],$_POST["s3_$id"]);
    $res = registerStep1Result($id,$resultsarr,$_POST["tiebreaker_$id"]);
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
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta charset="utf-8"/>
<title>Gothia Open 2013 Adminsidor</title>
</head>
<body>
<a href="loggedin.php">Tillbaka till startsidan</a><br/>
<form method="post" action="setStep1Results.php">
<h2>Finalsteg 1</h2>
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
      Scratch
    </th>
    <th>
      Hcp/serie
    </th>
    <th>
      Resultat
    </th>
    <th>
      Tiebreaker
    </th>
  </tr>
<?php
  $ids_string = "";
  foreach (getStep1Results() as $player) {
    $scratch = 0;
    $result = 0;
    foreach (array($player[s1],$player[s2],$player[s3]) as $game) {
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
        $scratch
      </td>
      <td>
        $player[hcp]
      </td>
      <td>
        $result
      </td>
      <td>
        <input type="text" size="1" name="tiebreaker_$player[id]" value="$player[tiebreaker]"/>
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
<input type="submit" value="Spara"/>
</form>
</body>
</html>


