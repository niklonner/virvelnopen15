<?php
require_once 'checklogin.php';
require_once '../db/dbfuncs.php';

include 'header.php';

$changeinprogress = isset($_POST[text]);

if ($changeinprogress) {
  $changeok = setPageText($_POST[page],$_POST[text],$_POST[comment]);
}
$pagetext = getPageText($_POST[page]);
?>
</head>
<body>
<a href="changepage.php">Tillbaka</a>
<h2>Ändra <?php echo $_POST[page] ?></h2>
<?php
if ($changeinprogress) {
  $time = date("Y-m-d H:i:s");
  if ($changeok) {
    echo "<font style=\"color:#ff0000;font-weight:bold\">$time: Ok, ändringen med kommentar \"$_POST[comment]\" sparades!</font>";
  } else {
    echo "<font style=\"color:#ff0000;font-weight:bold\">$time: Oops, ett internt fel inträffade. Ingen aning om något sparades.</font>";
  }
  echo "</br>";
}
?>
<form method="post" action="<?php echo $_SERVER[PHP_SELF]?>">
<input type="hidden" name="page" value="<?php echo $_POST[page] ?>">
<?php
echo <<<EOT
  <textarea rows="50" cols="150" name="text">$pagetext[text]</textarea>
  <br>
  Skriv gärna en kommentar till dina ändringar: <input type="text" name="comment" width="50">
  <input type="submit" value="Spara">
EOT;
?>
</form>
</body>
</html>
