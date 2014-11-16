<?php
require_once 'header.php';
require_once '../db/dbfuncs.php';
?>

</head>
<body>

<p><a href="../index.php">Till vanliga webbsidan</a></p>

<?php
$textdata =  getPageTextFormatted('index.php');
echo $textdata['text'];
?>

<h2>Anmälan</h2>
<p>
<a href="doregister.php">Jag vill anmäla mig</a><br/>
<a href="dochange.php">Jag vill ändra min anmälan/avanmäla mig</a>
</p>

<h2>Information</h2>
<p>
<a href="format.php">Tävlingsformat</a>
</p>

<?php
$textdata =  getPageTextFormatted('contact.php');
echo $textdata['text'];
?>

<h2>Starter/resultat</h2>

<p>
  <a href="ordinaryresults.php">Alla resultat >>></a><br/>
<br/>
  <a href="allplayers.php">Visa samtliga spelare (<?php echo getPlayerCount(); ?> spelare, <?php echo getReentryCount(); ?> starter)</a>
<br/><br/>

<?php
$squads = getSquadInfo();
foreach ($squads as $sq) {
  $info = utf8_encode($sq[info]);
  echo <<<EOT
    <a href="showsquad.php?day=$sq[day]&time=$sq[time]">$info ($sq[count]/$sq[spots] spelare)</a><br/>
EOT;
/*  echo 
  foreach ($v as $k => $vv)
    echo "$k: $vv<br/>";
  echo "<br/>";*/
}
?>
</p>

<?php
require_once 'footer.php';
?>

