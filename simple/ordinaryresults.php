<?php
require_once 'header.php';
require_once '../db/dbfuncs.php';
?>

</head>
<body>
    <a href="index.php">&lt;&lt; Tillbaka</a>
  <h1>Totala resultat</h1>
  <p style="font-weight:bold">
    (D) = dam<br/>
    (J) = junior
  </p>
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
$earlybirdcounter = 1;
foreach (getCompleteResults() as $result) {
  $squadnum = $squads_by_id[$result[id]];
  $reentry = $squadnum == 1 ? "" : ($squadnum == 2 ? " (R)" :" (R2)");
  $femalestring = $result[isfemale] ? " (D)" : "";
  $juniorstring = $result[isjunior] ? " (J)" : "";
  $finalsstring = $result[infinals] ?
    ($result[way]=="ordinary" ? "Ja" : 
      ($result[way]=="junior" ? "Ja, bästa junior" : 
        ($result[way]=="female" ? "Ja, bästa dam" : 
          ($result[way]=="earlybird" ? "Ja, early bird ". $earlybirdcounter++ : "")
        )
      )
    ) : "";
  echo <<<EOT
<tr>
  <td>$i.</td>
  <td>$result[lastname]$femalestring$juniorstring$reentry</td>
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
  <td style="font-weight:bold">$finalsstring</td>
</tr>
EOT;
  $i++;
}
?>
  </table>
<?php
require_once 'footer.php';
?>
