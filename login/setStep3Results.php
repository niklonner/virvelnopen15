<?php
require_once 'checklogin.php';
require_once '../db/dbfuncs.php';
if (isset($_POST[ids])) {
  // TODO set visibility
  // split ids
  $ids = explode(",",$_POST[ids]);
  $numgames = getNumberOfStep3Games();
  // for each id, save results
  openDB();
  foreach ($ids as $id) {
    for ($i=1;$i<=$numgames;$i++) {
      // TODO db function
      $tmpstr = "s{$i}_$id";
      echo "$id $i $_POST[$tmpstr]";
      $res = registerStep3Result($id,$i,$_POST["s{$i}_$id"]);
      if (!$res) {
        $err[] = "$id serie $i";
      }
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
<form method="post" action="setStep3Results.php">
<h2>Finalsteg 3 - Round Robin</h2>
<?php
if ($allok) {
  echo "<span style='color:#ff0000'>Sparades " . date("Y-m-d H:i:s") . "</span><br/>";
} else if (isset($err)) {
  echo "<span style='color:#ff0000'>". date("Y-m-d H:i:s") .": Fel hittades för följande idn/serier:<br/>";
  foreach ($err as $e) {
    echo "- $e<br/>";
  }
  echo "</span><br/>";
}
?>
<table>
<?php
  $ids_string = "";
  // TODO
  $players = getStep3Players();
  foreach ($players as $player) {
    $ids_string .= $player[id] . ",";
  }
  $prevgamenum = -1;
  foreach (getStep3Matches() as $match) {
    if ($prevgamenum != $match[gamenum]) {
      echo "<tr><td colspan='11' style='text-align:center'><h2>Serie $match[gamenum]</h2></td></tr>";
      echo "  <tr>   <th>Bana</th> <th>      Internt id    </th>    <th>      Spelare    </th>    <th>      Hcp    </th>    <th>      Res    </th>    <th>      Res    </th>    <th>      Spelare    </th>    <th>      Hcp    </th>    <th>      Internt id    </th>  </tr>";
    }
    $prevgamenum = $match[gamenum];
    //TODO: fill in values below (match results)
    echo <<<EOT
    <tr>
      <td style='font-weight:bold'>
        $match[lane]
      </td>
      <td>
        $match[id1]
      </td>
      <td>
        $match[lastname1]<br/>
        $match[club1]
      </td>
      <td>
        $match[hcp1]
      </td>
      <td>
        <input type="text" size="1" name="s$match[gamenum]_$match[id1]" value="$match[res1]"/>
      </td>
      <td>
        <input type="text" size="1" name="s$match[gamenum]_$match[id2]" value="$match[res2]"/>
      </td>
      <td>
        $match[lastname2]<br/>
        $match[club2]
      </td>
      <td>
        $match[hcp2]
      </td>
      <td>
        $match[id2]
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



