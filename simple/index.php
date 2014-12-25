<?php
require_once 'header.php';
require_once '../db/dbfuncs.php';

?>

</head>
<body>

<p><strong style="color:red">OBS du använder den förenklade versionen av sidan.</strong> <a href="../index.php">Till vanliga webbsidan >>></a></p>

<?php
$textdata =  getPageTextFormatted('index.php');
echo $textdata[text];
?>

<div class="boxcontainer">
  <div class="boxheader">Anmälan</div>
  <div class="boxcontent">
<p>
<a href="doregister.php">Jag vill anmäla mig</a><br/>
<a href="dochange.php">Jag vill ändra min anmälan/avanmäla mig</a>
</p>
  </div>
</div>

<div class="boxcontainer">
  <div class="boxheaderalt">Information</div>
  <div class="boxcontent">
<p>
<a href="format.php">Tävlingsformat</a>
</p>
  </div>
</div>

<?php
$textdata =  getPageTextFormatted('contact.php');
$boxcontent = getHeaderDelimiter($textdata[text]);
?>

<div class="boxcontainer">
                            <?php //                        4 and -9 to remove leading and trailing tags  ?>
  <div class="boxheader"><?php echo $boxcontent != false ? $boxcontent[0] : "Kontakt" ?></div> 
  <div class="boxcontent">
<?php
echo $boxcontent != false ? $boxcontent[1] : $textdata[text];
?>
</div>

<div class="boxcontainer">
  <div class="boxheaderalt">Starter/resultat</div>
  <div class="boxcontent">
<h2>Starter/resultat</h2>

<p>
  <a href="ordinaryresults.php">Alla resultat >>></a><br/>
<br/>
  <a href="allplayers.php">Visa samtliga spelare (<?php echo getPlayerCount(); ?> spelare, <?php echo getReentryCount(); ?> starter)</a>
<br/><br/>

<?php
$squads = getSquadInfo();
foreach ($squads as $sq) {
  $info = $sq[info];
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
  </div>
</div>
<?php
require_once 'footer.php';
?>

