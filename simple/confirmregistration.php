<?php
require_once 'header.php';
require_once '../db/globals.php';
require_once '../db/dbfuncs.php';
?>

</head>
<body>

<?php

$hashstring = "$_GET[name]$_GET[club]$_GET[squad1]$_GET[squad2]$globProductionString$_GET[squad3]"; 
if (sha1($hashstring) == $_GET[MAC]) {
  echo <<<EOT
  <h1>Tack för din anmälan</h1>
  <p>$_GET[name], $_GET[club] är nu anmäld till följande start(er):</p>
  <ul>
EOT;
  foreach (array($_GET[squad1],$_GET[squad2],$_GET[squad3]) as $squad) {
    if ($squad != "none") {
      $day = substr($squad,0,6);
      $time = substr($squad,6,4);
      echo "<li>" . utf8_encode(getSquadInfoLine($day,$time)) . "</li>";
    }
  }
  echo "</ul>";
} else {
  echo <<<EOT
  <h1>Oops</h1>
  <p>Ett internt fel uppstod. Vänligen försök igen eller <a href="../contact.php">kontakta oss</a> om problemet kvarstår.</p>
EOT;
}


?>

<p><a href="index.php">&lt;&lt Tillbaka till startsidan</a></p>

<?php
require_once 'footer.php';
?>

