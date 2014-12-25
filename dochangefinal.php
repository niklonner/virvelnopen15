<?php
require_once 'header.php';
require_once 'db/dbfuncs.php';
require_once 'db/globals.php';
require_once 'db/errormessages.php';
?>
</head>
<body>

  <?php
require_once 'menu.php';
?>

  <div class="container">
  
<?php
  //validate
  $tocheckhash = $_GET['id'];

  $squads = array();
  for ($i=1;$i<=3;$i++) {
    if (isset($_GET["squad$i"])) {
      $squads[] = $_GET["squad$i"];
      $tocheckhash .= $_GET["squad$i"]; 
    }
  }
  $tocheckhash .= $globProductionString;

  if ($_GET['MAC'] != sha1($tocheckhash . $globSalt)) {
    echo <<<EOT
  <h1>Oops...</h1>
  <p>Ett intern fel uppstod. Var god försök igen.</p>
EOT;
  } else {
      $ret = changeSquads($_GET['id'],$squads);
      if ($ret == "ok") {
        echo "<h1>Ändringar genomförda</h1>";
        if (empty($squads)) {
          echo "<p>Du är nu avanmäld från kvarvarande starter.</p>";
        } else {
          echo "<p>Du är nu anmäld till följande starter:</p><p>";
          $squads = getPlayerSquads($_GET['id']);
          foreach ($squads as $sq) {
            echo getSquadInfoLine($sq['day'],$sq['time']) . "<br/>";
          }
          echo "</p>";
        }

      } else {
        echo "<h1 style='color:rgb(255,0,0)'>Fel uppstod</h1><ul style='color:rgb(255,0,0)'>";
        foreach ($ret as $error => $v) {
          echo "<li>" . getSEReadableErrorMessage($error) . "</li>";
        }
        echo "</ul><p>Observera att felet/felen kan bero på att det förlöpt en viss tid sedan du begärde ändringarna.</p>";
      }
  }
?>

  </div>


  <?php
require_once 'footer.php';
?>
</body>
</html>
