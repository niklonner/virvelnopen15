<?php
require_once 'header.php';
require_once '../db/dbfuncs.php';
?>

</head>
<body>
<h1>Finalsteg1</h1>
<table>
  <tr>
    <th>
      Pos
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
      Hcp
    </th>
    <th>
      Total
    </th>
  </tr>
<?php
$i=1;
foreach (getStep1Results() as $result) {
  if ($i==7) {
    echo "<tr><td colspan='12'><hr/></td></tr>";
  }
  for ($j=1;$j<=6;$j++) {
    $gamestring[$j] = $result["s$j"] == 0 ? "" : $result["s$j"];
  }
echo <<<EOT
  <tr>
    <td>
      $i
    </td>
    <td>
      $result[lastname]
    </td>
    <td>
      $result[club]
    </td>
    <td>
      $gamestring[1]
    </td>
    <td>
      $gamestring[2]
    </td>
    <td>
      $gamestring[3]
    </td>
    <td>
      $gamestring[4]
    </td>
    <td>
      $gamestring[5]
    </td>
    <td>
      $gamestring[6]
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
  $i++;
}
?>
</table>
<?php
require_once 'footer.php';
?>

