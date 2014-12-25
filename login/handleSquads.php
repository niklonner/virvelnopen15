<?php
require_once 'checklogin.php';
require_once '../db/dbfuncs.php';

if (isset($_POST[day])) {
  $res = insertSquad($_POST[day],$_POST[time],$_POST[info],$_POST[spots]);
}

include 'header.php';
?>
</head>
<body>
  <a href="loggedin.php">Tillbaka till startsidan</a><br/>
  <form method="post" action="handleSquad.php">
    Välj start:
    <select name="squad">
      <?php
        foreach (getSquadInfo() as $squad) {
          echo "<option value='$squad[day]$squad[time]'>". $squad[info] ."</option>";
        }
      ?>
    </select>
    <br/>
    <input type="submit" value="Välj">
  </form>
  <br/>
  Eller lägg till en ny start:
<form method="post" action="<?php echo $_SERVER[PHP_SELF]?>">
<table>
<tr>
  <th>Dag (ååmmdd)</th>
  <th>Tid (ttmm)</th>
  <th>Info</th>
  <th>Antal platser</th>
</tr>
<tr>
  <td><input type="text" name="day" size="6"/></td>
  <td><input type="text" name="time" size="6"/></td>
  <td><input type="text" name="info"/></td>
  <td><input type="text" name="spots" size="2"/></td>
</tr>
</table>
<input type="submit" value="Spara"/>
</form>
<br/>
<?php
if (isset($res)) {
  echo "<span style=\"color:red\"><strong>";
  if ($res) {
    echo "Sparades " . date("Y-m-d H:i:s"); 
  } else {
    echo "Något gick fel!";
  }
  echo "</strong></span>";
}
?>
</body>
</html>

