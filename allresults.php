<?php
include 'header.php';
require_once 'db/dbfuncs.php';
?>
</head>
<body>

  <?php
include 'menu.php';
?>
  
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>Resultat</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5" id="combined">
      </div>
      <div class="col-md-7">
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <h1>Anmälda spelare</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5" id="allplayers">
      </div>
      <div class="col-md-7">
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <h2>Detaljer per start</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <p>
          När en start har spelats visas resultaten i länkarna nedan. Innan dess visas startlistan.
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5" id="squadsdone">
        <strong>Spelade:</strong>
      </div>
      <div class="col-md-7">
      </div>
    </div>
    <div class="row">
      <div class="col-md-5" id="squadsnotplayed">
        <br/><strong>Kommande:</strong>
        <br/>
      </div>
      <div class="col-md-7">
      </div>
    </div>

  </div>


  <?php
include 'footer.php';
?>
<script src="js/common.js"></script>
<script>
$('#combined').append(build_panel_button('ordinaryresults.php','<strong>Resultat</strong>','#FFFFFF',true,false));
$('#allplayers').append(build_panel_button('allplayers.php','<strong>Visa samtliga spelare (<?php echo getPlayerCount(); ?> spelare, <?php echo getReentryCount();?> starter)</strong>','#FFFFFF',true,false));
<?php
$squads = getSquadInfo();
$i=0;
foreach ($squads as $squad) {
  if ($squad[done] != true && $squad[cancelled] != true) {
    break;
  }
  echo "\$('#squadsdone').append(build_panel_button('showsquad.php?day=$squad[day]&time=$squad[time]','".utf8_encode($squad['info']).
        " ($squad[count]/$squad[spots] spelare)','".($i%2==1?"#FFFFF":"#EEEEFA")."',true));";
  $i++;
}
for (;$i<count($squads);$i++) {
  $squad = $squads[$i];
  echo "\$('#squadsnotplayed').append(build_panel_button('showsquad.php?day=$squad[day]&time=$squad[time]','".utf8_encode($squad['info']).
        " ($squad[count]/$squad[spots] spelare)','".($i%2==1?"#FFFFF":"#EEEEFA")."',true));";
}
?>
render_custom();
</script>
</body>
</html>

