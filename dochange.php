<?php
require_once 'header.php';
require_once 'db/dbfuncs.php';
?>
</head>
<body>

  <?php
require_once 'menu.php';
?>
  
  <div class="container">
    <script language="javascript">actionifnotokbrowser();</script>
    <div class="row">
      <div class="col-md-12">
        <h1>Ändring/avanmälan steg 1</h1>
        <p>
        Om du inte finns med i listan nedan innebär det att du inte angett någon e-postadress när du anmält dig. Vill du då göra någon ändring behöver du <a href='contact.php'>kontakta oss</a>.
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6" id="players">

      </div>
      <div class="col-md-6">
      </div>
    </div>
  </div>


  <?php
require_once 'footer.php';
?>
<script src='js/common.js'></script>
<script>
<?php
$i = 0;
foreach (getAllPlayers() as $player) {
  if ($player['email'] != '') {
    $bgCol = $i%2==0? "#FFFFFF" : "#EEEEFA";
    echo <<<EOT
$('#players').append(build_panel_button('dochangestep2.php?id=$player[id]','$player[firstname] $player[lastname], $player[club] ($player[bitsid])','$bgCol',true,false));
EOT;
    $i++;
  }
}
?>
render_custom();
</script>
</body>
</html>
