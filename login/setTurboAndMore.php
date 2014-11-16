<?php
require_once 'checklogin.php';
require_once '../db/dbfuncs.php';
?>
<html>
<head>
<title>Gothia Open 2013Adminsidor</title>
</head>
<body>
  <a href="loggedin.php">Tillbaka till startsidan</a><br/>
  <form method="post" action="setTurboAndMoreSquad.php">
    Välj start:
    <select name="squad">
      <?php
        foreach (getSquadInfo() as $squad) {
          echo "<option value='$squad[day]$squad[time]'>". utf8_encode($squad[info]) ."</option>";
        }
      ?>
    </select>
    <br/>
    <input type="submit" value="Välj">
  </form>
</body>
</html>
