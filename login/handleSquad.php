<?php
require_once 'checklogin.php';
require_once '../db/dbfuncs.php';
$day = substr($_POST[squad],0,6);
$time = substr($_POST[squad],6,4);
if (isset($_POST[remove])) {
  openDB();
  $res = removeSquad($_POST[day],$_POST[time]);
  closeDB();
  header('Location: handleSquads.php');
} else if (isset($_POST[day])) { // just check that one of the fields exists
  $day = $_POST[day];
  $time = $_POST[time];
  openDB();
  $res = setSquad($_POST[day],$_POST[time],$_POST[info],$_POST[spots],$_POST[cancelled]=="cancelled");
  if ($res) {
    $allok = true;
  } else {
    $err = $res;
  }
  closeDB();
  // om allt ok markera att ändring skett
}

include 'header.php';
$squadinfo = getSquadInformation($day,$time);
?>
<title>Gothia Open 2014 Adminsidor</title>
</head>
<body>
<a href="loggedin.php">Tillbaka till startsidan</a><br/>
<h2><?php echo $squadinfo[info];?></h2>
<?php
if ($allok) {
  echo "<span style='color:#ff0000'>Sparades " . date("Y-m-d H:i:s") . "</span><br/>";
} else if (isset($err)) {
  echo "<span style='color:#ff0000'>". date("Y-m-d H:i:s") .": Något gick fel...<br/>";
  echo "</span><br/>";
}
?>
<form method="post" action="<?php echo $_SERVER[PHP_SELF]?>">
  <input type="hidden" name="day" value="<?php echo $day?>"/>
  <input type="hidden" name="time" value="<?php echo $time?>"/>
<table>
<tr>
  <th>Dag</th>
  <th>Tid</th>
  <th>Info</th>
  <th>Antal platser</th>
  <th>Struken</th>
</tr>
<tr>
  <td><?php echo $day ?></td>
  <td><?php echo $time ?></td>
  <td><input type="text" name="info" value="<?php echo $squadinfo[info]?>"/></td>
  <td><input type="text" name="spots" value="<?php echo $squadinfo[spots]?>" size="2"/></td>
  <td><input type="checkbox" name="cancelled" value="cancelled" <?php echo $squadinfo[cancelled] ? "checked=\"checked\"" : "" ?>/></td>
</tr>
</table>
<input type="submit" value="Spara"/>
</form>
<br/><br/>
<form method="post" action="<?php echo $_SERVER[PHP_SELF]?>">
<input type="hidden" name="remove" value=""/>
<input type="hidden" name="day" value="<?php echo $day?>"/>
<input type="hidden" name="time" value="<?php echo $time?>"/><br/> 
Eller... <input type="submit" value="Ta bort start"/>
</form>
</body>
</html>
