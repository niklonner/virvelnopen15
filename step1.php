<?php
require_once 'header.php';
require_once 'db/dbfuncs.php';
?>
</head>
<body>

  <?php
require_once 'menu.php';
?>
  
  <div class="container">
<h1>Finalsteg 1</h1>
<table style="width:800px!important">
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
  if ($i==9) {
    echo "<tr><td colspan='12'><hr/></td></tr>";
  }
  for ($j=1;$j<=3;$j++) {
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

  </div>


  <?php
require_once 'footer.php';
?>
</body>
</html>
