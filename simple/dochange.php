<?php
require_once 'header.php';
require_once '../db/dbfuncs.php';
?>

</head>
<body>

<h1>Ändring/avanmälan steg 1</h1>

<p>Välj spelare nedan. 
        Om du inte finns med i listan nedan innebär det att du inte angett någon e-postadress när du anmält dig. Vill du då göra någon ändring behöver du <a href='../contact.php'>kontakta oss</a>.
</p>

<form method="post" action="dochangestep2.php">

<select name="id">
<?php
foreach (getAllPlayers() as $player) {
  if ($player[email] != '' && !is_null($player[email])) {
    echo "<option value='$player[id]'>$player[lastname], $player[club] ($player[bitsid])</option>";
  }
}

?>

</select>
<input type="submit" value="Välj">
</form>

<?php
require_once 'footer.php';
?>

