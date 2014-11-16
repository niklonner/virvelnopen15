<?php
require_once 'checklogin.php';
require_once '../db/dbfuncs.php';

?>
<html>
<head>
<title>Gothia Open 2014 Adminsidor</title>
</head>
<body>
<a href="loggedin.php">Tillbaka</a>
<h2>Ändra sidinfo</h2>
<form method="post" action="changepagestep2.php">
<select name="page">
<?php
foreach ( getAvailablePages() as $page) {
$selected = $modifypage && $_POST[page]==$page ? " selected=\"selected\" " : "";
echo <<<EOT
  <option value="$page">$page</option>
EOT;
}
?>
</select><input type="submit" value="Hämta">
</form>
</body>
</html>
