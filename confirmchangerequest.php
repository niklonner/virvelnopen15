<?php
require_once 'header.php';
require_once 'db/globals.php';
require_once 'db/dbfuncs.php';
?>
</head>
<body>
  <div class="container">

  <?php
require_once 'menu.php';
?>
<?php
  $tocheckhash = $_GET['id'];

  $squads = array();
  for ($i=1;$i<=3;$i++) {
    if (isset($_GET["squad$i"])) {
      $squads[] = $_GET["squad$i"];
      $tocheckhash .= $_GET["squad$i"]; 
    }
  }
  if ($_GET['MAC'] == sha1($tocheckhash . $globSalt)) {
    $player = getPlayerInfo($_GET['id']);
    $mailarr= explode('@',$player['email']);
    $mailstring = $mailarr[count($mailarr)-1];
    $xes = "";
    for($i=0;$i<strlen($mailarr[0]);$i++) {
      $xes .= 'x';
    }
    $mailstring = $xes . "@" . $mailstring;
    echo <<<EOT
    <h1>Begäran om ändring mottagen</h1>
    <p style="color:rgb(255,0,0);font-weight:bold">Du måste bekräfta ändringarna genom att klicka på länken i det mail som skickats till $mailstring.</p>
EOT;
  } else {
    echo <<<EOT
    <h1>Oops</h1>
    <p>Ett internt fel uppstod. Vänligen försök igen.</p>
EOT;
  }
?>
  
  </div>

  <?php
require_once 'footer.php';
?>
</body>
</html>
