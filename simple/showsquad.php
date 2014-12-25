<?php
require_once 'header.php';
require_once '../db/dbfuncs.php';
?>

</head>
<body>
<a href="index.php">&lt;&lt; Tillbaka</a>
<?php
echo "<h1>".getSquadInfoLine($_GET[day],$_GET[time])."</h1>";
if (okStartTime($_GET[day],$_GET[time])) {
  $players = getSquadPlayers($_GET[day],$_GET[time]);
  if (count($players) == 0) {
    echo "Inga startande.";
  } else {
    echo "<table><tr><th>&nbsp;</th><th>Spelare</th><th>Klubb</th></tr>";
    $i = 1;
    foreach ($players as $p) {
      $name = ($p[firstname] == '' or is_null($p[firstname])) ? $p[lastname] : "$p[firstname] $p[lastname]";
      echo <<<EOT
    <tr>
      <td style="padding-right:10px">$i.</td>
      <td style="padding-right:10px">$name</td>
      <td>$p[club]</td>
    </tr>
EOT;
      $i++;
    }
  echo "</table>";
  }
} else {
  echo <<<EOT
  <table>
    <tr>
        <th>Pos.</th>
        <th>Spelare</th>
        <th>Klubb</th>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>Scratch</th>
        <th>Hcp/serie</th>
        <th>Totalt</th>
  </tr>
EOT;
  $pos = 1;
  foreach (getSquadresults($_GET[day],$_GET[time]) as $result) {
    $reentry = $result[squadnumber] == 1 ? "" : ($result[squadnumber]==2 ? "(R)" : "(R2)");
    echo <<<EOT
    <tr>
      <td>
        $pos
      </td>
      <td>
        $result[lastname] $reentry
      </td>
      <td>
        $result[club]
      </td>
      <td>
        $result[s1]
      </td>
      <td>
        $result[s2]
      </td>
      <td>
        $result[s3]
      </td>
      <td>
        $result[s4]
      </td>
      <td>
        $result[s5]
      </td>
      <td>
        $result[s6]
      </td>
      <td>
        $result[scratch]
      </td>
      <td>
        $result[hcp]
      </td>
      <td>
        $result[result]
      </td>
  </tr>
EOT;
    $pos++;
  }
  echo "</table>";
}
?>
<?php
require_once 'footer.php';
?>
