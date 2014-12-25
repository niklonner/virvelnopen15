<?php
include 'header.php';
require_once 'db/globals.php';
?>
</head>
<body>

<?php
include 'menu.php';
?>
  
  <div class="container">
    <div class="col-md-12">
<?php
$name = $_GET['name'];
$club = $_GET['club'];
$squad1 = $_GET['squad1'];
$squad2 = $_GET['squad2'];
$squad3 = $_GET['squad3'];
$MAC = $_GET['MAC'];
if (sha1("$name$club$squad1$squad2$squad3$globSalt")==$MAC) {
echo <<<EOT
      <h1>Tack för din anmälan</h1>
      <p>
         $name, $club är nu anmäld till följande start(er):<br/>
EOT;
  if ($squad1 != "none") {
    echo $squad1 . "<br/>";
  }
  if ($squad2 != "none") {
    echo $squad2 . "<br/>";
  }
  if ($squad3 != "none") {
    echo $squad3 . "<br/>";
  }
} else {
  echo <<<EOT
  <h1>OOPS. Något gick fel.</h1>
  <p>Vänligen försök igen.</p>
EOT;
}

?>
    </div>
  </div>


  <?php
include 'footer.php';
?>
</body>
</html>
