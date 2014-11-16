<?php
require_once 'header.php';
require_once '../db/dbfuncs.php';
?>

</head>
<body>
    <a href="index.php">&lt;&lt; Tillbaka</a>
  <h1>Totala kvalresultat</h1>
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
      <th>Resultat</th>
      <th>Final?</th>
    </tr>
<?php
$squads_by_id = getNumberOfPlayedSquadsPerPlayer();
$i = 1;
foreach (getOrdinaryResults() as $result) {
  $squadnum = $squads_by_id[$result[id]];
  $reentry = $squadnum == 1 ? "" : ($squadnum == 2 ? "(R)" :"(R2)");
  $in_finals = "";
  switch($result[way]) {
    case "ordinary":
      $in_finals = "Ja";
      break;
    case "turbo5":
      $in_finals = "Ja, via Turbo 5";
      break;
    case "turbo6":
      $in_finals = "Ja, via Turbo 6";
      break;
    case "earlybird":
      $in_finals = "Ja, via Early Bird";
      break;
  }
  echo <<<EOT
<tr>
  <td>$i.</td>
  <td>$result[lastname] $reentry</td>
  <td>$result[club]</td>
  <td>$result[s1]</td>
  <td>$result[s2]</td>
  <td>$result[s3]</td>
  <td>$result[s4]</td>
  <td>$result[s5]</td>
  <td>$result[s6]</td>
  <td>$result[scratch]</td>
  <td>$result[hcp]</td>
  <td>$result[result]</td>
  <td><strong>$in_finals</strong></td>
</tr>
EOT;
  $i++;
}
?>
  </table>
<?php
require_once 'footer.php';
?>
