<?php
include 'header.php';
include 'db/dbfuncs.php';
?>
</head>
<body>

  <?php
include 'menu.php';
?>
  
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1><?php echo utf8_encode(getSquadInfoLine($_GET['day'],$_GET['time'])) ?></h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
<?php
$squads = getSquadPlayers($_GET['day'], $_GET['time']);
if (count($squads)!=0)
  echo <<<EOT
<table>
<th>&nbsp;</th>
<th>Namn</th>
<th>Klubb</th>
EOT;
else
  echo "Inga spelare anmälda.";

$i = 1;
foreach ($squads as $arr) {
  $name = $arr['firstname'] == '' ? $arr['lastname'] : "$arr[firstname] $arr[lastname]";
  echo <<<EOT
<tr>
  <td style="padding-right:20px">$i.</td>
  <td style="padding-right:20px">$name</td>
  <td style="padding-right:20px">$arr[club]</td>
</tr>
EOT;
$i++;
}
if (count($squads)!=0)
  echo "</table>";
?>
        
      </div>
      <div class="col-md-6">
      </div>
    </div>
  </div>


  <?php
include 'footer.php';
?>
</body>
</html>
