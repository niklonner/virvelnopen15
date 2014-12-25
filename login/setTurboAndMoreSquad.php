<?php
require_once 'checklogin.php';
require_once '../db/dbfuncs.php';
$day = substr($_POST[squad],0,6);
$time = substr($_POST[squad],6,4);
if (isset($_POST[ids])) {
  // splitta idn
  $ids = explode(",",$_POST[ids]);
  // för varje id, spara ändring
  openDB();
  foreach ($ids as $id) {
    $res = setPlayerDetails($id,$_POST["lastname_$id"],$_POST["club_$id"],$_POST["bitsid_$id"],$_POST["hcp_$id"]);
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
</head>
<body>
<a href="loggedin.php">Tillbaka till startsidan</a><br/>
<form method="post" action="setTurboAndMoreSquad.php">
<h2><?php echo getSquadInfoLine($day,$time);?></h2>
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
      BITS-id
    </th>
    <th>
      Spelare
    </th>
    <th>
      Klubb
    </th>
    <th>
      Hcp
    </th>
    <th>
      Telefon
    </th>
    <th>
      E-post
    </th>
  </tr>
<?php
  $ids_string = "";
  foreach (getSquadPlayers($day,$time) as $player) {
    $ids_string .= $player[id] . ",";
    echo <<<EOT
    <tr>
      <td>
        $player[id]
      </td>
      <td>
        <input type="text" name="bitsid_$player[id]" value="$player[bitsid]"/>
      </td>
      <td>
        <input type="text" name="lastname_$player[id]" value="$player[lastname]"/>
      </td>
      <td>
        <input type="text" name="club_$player[id]" value="$player[club]"/>
      </td>
      <td>
        <input type="text" name="hcp_$player[id]" value="$player[hcp]" size="2"/>
      </td>
      <td>
        $player[phonenumber]
      </td>
      <td>
        $player[email]
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
<input type="hidden" name="squad" value="<?php echo $_POST[squad]?>"/>
<input type="submit" value="Spara"/>
</form>
</body>
</html>
