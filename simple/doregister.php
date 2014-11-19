<?php
require_once 'header.php';
require_once '../db/dbfuncs.php';
require_once '../db/errormessages.php';
require_once '../db/globals.php';
?>

</head>
<body>
  <a href="index.php">&lt;&lt; Tillbaka</a>
  <h2>Anmälan</h2>
<form method="post" action="doregister.php">
<?php

if (isset($_POST['name'])) {
  $res = registerPlayer('',$_POST[name],$_POST[club],$_POST[bitsid],$_POST[phonenumber],
    $_POST[email], $_POST[email_repeat], $_POST[squad1], $_POST[squad2], $_POST[squad3]);
  if ($res == "ok") {
    $hashstring = "$_POST[name]$_POST[club]$_POST[squad1]$_POST[squad2]$globProductionString$_POST[squad3]";
    header("Location:confirmregistration.php?name=$_POST[name]&club=$_POST[club]&squad1=$_POST[squad1]"
      . "&squad2=$_POST[squad2]&squad3=$_POST[squad3]&MAC=" . sha1($hashstring));
  } else {
    $error = $res;
    echo "<p>Fel hittades:</p><ul style='color:#ff0000'>";
    foreach ($error as $k => $v) {
      echo "<li>" . getSEReadableErrorMessage($k) . "</li>";
    }
    echo "</ul>";
  }
}

$fields = array();
$fields[] = array("label" => "Namn", "name" => "name");
$fields[] = array("label" => "Licensnummer (ej obligatoriskt)", "name" => "bitsid");
$fields[] = array("label" => "Klubb", "name" => "club");
$fields[] = array("label" => "Telefonnummer", "name" => "phonenumber");
$fields[] = array("label" => "E-postadress (valfritt, behövs för ändring/avanmälan)", "name" => "email");
$fields[] = array("label" => "Upprepa e-postadress", "name" => "email_repeat");

echo "<table>";
foreach ($fields as $field) {
  $value = isset($error) ? $_POST[$field[name]] : "";
  echo <<<EOT
  <tr>
    <td>$field[label]</td>
    <td><input type="text" value="$value" name="$field[name]"/></td>
  </tr>
EOT;
}

echo "</table><table>";
for ($i=1;$i<=3;$i++) {
  echo "<tr><td>Start $i:</td><td><select name=\"squad$i\"><option value=\"none\">Ingen</option>";
  foreach (getSquadInfo() as $squad) {
    $info = utf8_encode($squad[info]);
    $selected = (isset($error) and $squad[day]==substr($_POST["squad$i"],0,6) and $squad[time]==substr($_POST["squad$i"],6,4))
      ? " selected=\"selected\"" : "";
    echo <<<EOT
      <option value="$squad[day]$squad[time]" $selected>$info ($squad[count]/$squad[spots] spelare)</option>
EOT;
  }
  echo "</select></td></tr>";
}
echo "</table>";
?>

<input type="submit" value="Skicka!"/>
</form>
<?php
require_once 'footer.php';
?>

