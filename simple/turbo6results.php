<?php
require_once 'header.php';
require_once '../db/dbfuncs.php';
?>

</head>
<body>
<a href="index.php">&lt;&lt; Tillbaka</a>
<h1>Turbo serie 6-resultat</h1>
<table>
  <tr>
    <th>
      Pos.
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
      Totalt
    </th>
    <th>
      Turbo serie 6
    </th>
    <th>
      Final?
    </th>
  </tr>
<?php
$pos=0;
foreach (getTurbo6Results() as $result) {
  $pos++;
  if ($result[way] == "turbo6") {
    $color = "style='background-color:#ffff00'";
  } else {
    $color = "";
  }
  switch ($result[way]) {
  case 'earlybird':
    $in_finals = "Ja, via Early Bird";
    break;
  case 'ordinary':
    $in_finals = "Ja";
    break;
  case 'turbo5':
    $in_finals = "Ja, via Turbo 5";
    break;
  case 'turbo6':
    $in_finals = "Ja, via Turbo 6";
    break;
  default:
    $in_finals = "&nbsp;";
    break;
  }
  echo <<<EOT
<tr $color>
  <td>
    $pos
  </td>
  <td>
    $result[lastname]
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
  <td style="font-weight:bold">
    $result[s6hcp]
  </td>
  <td style="font-weight:bold">
    $in_finals
  </td>
</tr>
EOT;
}
?>
</table>
<?php
require_once 'footer.php';
?>



