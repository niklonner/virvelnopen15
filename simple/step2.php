<?php
require_once 'header.php';
require_once '../db/dbfuncs.php';
?>

</head>
<body>
<table>
  <tr>
    <th style="text-align:left">
      Pos
    </th>
    <th style="text-align:left">
      Spelare
    </th>
    <th style="text-align:left">
      Klubb
    </th>
    <th style="text-align:left">
      1
    </th>
    <th style="text-align:left">
      2
    </th>
    <th style="text-align:left">
      3
    </th>
    <th style="text-align:left">
      4
    </th>
    <th style="text-align:left">
      5
    </th>
    <th style="text-align:left">
      6
    </th>
    <th style="text-align:left">
      7
    </th>
    <th style="text-align:left">
      8
    </th>
    <th style="text-align:left">
      Scratch
    </th>
    <th style="text-align:left">
      V
    </th>
    <th style="text-align:left">
      O
    </th>
    <th style="text-align:left">
      F
    </th>
    <th style="text-align:left">
      Bonus
    </th>
    <th style="text-align:left">
      Hcp
    </th>
    <th style="text-align:left">
      Totalt
    </th>
    <th style="text-align:left">
      Roll-off
    </th>
  </tr>
<?php
// Visa totalställning, varje serie + v o f + scratch + bonus + total
// Visa varje individuell match, uppdelade i omgångar
$i = 1;
foreach (getStep2Results() as $result) {
  echo "<tr>";
  $gamestrings = array();
  for ($j=1;$j<=8;$j++) {
    $gamestrings[$j] = $result["s$j"] == 0 ? "" : $result["s$j"];
  }
  echo <<<EOT
  <tr>
    <td style="padding-right:10px">
      $i
    </td>
    <td style="padding-right:10px">
      $result[lastname]
    </td>
    <td style="padding-right:10px">
      $result[club]
    </td>
    <td style="padding-right:10px">
      $gamestrings[1]
    </td>
    <td style="padding-right:10px">
      $gamestrings[2]
    </td>
    <td style="padding-right:10px">
      $gamestrings[3]
    </td>
    <td style="padding-right:10px">
      $gamestrings[4]
    </td>
    <td style="padding-right:10px">
      $gamestrings[5]
    </td>
    <td style="padding-right:10px">
      $gamestrings[6]
    </td>
    <td style="padding-right:10px">
      $gamestrings[7]
    </td>
    <td style="padding-right:10px">
      $gamestrings[8]
    </td>
    <td style="padding-right:10px">
      $result[scratch]
    </td>
    <td style="padding-right:10px">
      $result[wins]
    </td>
    <td style="padding-right:10px">
      $result[ties]
    </td>
    <td style="padding-right:10px">
      $result[losses]
    </td>
    <td style="padding-right:10px">
      $result[bonus]
    </td>
    <td style="padding-right:10px">
      $result[hcp]
    </td>
    <td style="padding-right:10px">
      $result[result]
    </td>
    <td style="padding-right:10px">
      $result[tiebreaker]
    </td>
  </tr>
EOT;
  $i++;
}
?>
</table>
<table>
<?php
  $prevgamenum = -1;
  foreach (getStep2Matches() as $match) {
    if ($prevgamenum != $match[gamenum]) {
      echo "<tr><td colspan='11' style='text-align:center'><h2>Serie $match[gamenum]</h2></td></tr>";
      echo "  <tr> <th>      Spelare    </th>        <th>      Res    </th> <td>&nbsp;</td>   <th>      Res    </th>    <th>      Spelare    </th>     </tr>";
    }
    $prevgamenum = $match[gamenum];
    //TODO: fill in values below (match results)
    if ($match[res1hcp] > $match[res2hcp]) {
      $res1col = "#009900";
      $res2col = "#ff0000";
    } else if ($match[res1hcp] < $match[res2hcp]) {
      $res1col = "#ff0000";
      $res2col = "#009900";
    } else {
      $res1col = "#000000";
      $res2col = "#000000";
    }
    echo <<<EOT
    <tr>
      <td>
        <span style="font-weight:bold;color:$res1col">$match[lastname1]</span><br/>
        $match[club1]
      </td>
      <td style="font-weight:bold;color:$res1col">
        $match[res1hcp]
      </td>
      <td style="font-weight:bold">
        -
      </td>
      <td style="font-weight:bold;color:$res2col">
        $match[res2hcp]
      </td>
      <td style="padding-left:20px">
        <span style="font-weight:bold;color:$res2col">$match[lastname2]</span><br/>
        $match[club2]
      </td>
    </tr>
EOT;
  }
  if ($ids_string != "") {
    $ids_string = substr($ids_string,0,strlen($ids_string)-1);
  }
?>
</table>
<?php
require_once 'footer.php';
?>

