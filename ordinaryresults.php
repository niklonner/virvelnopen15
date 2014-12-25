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
    <div class="row">
      <div class="col-md-12">
        <h1>Totala resultat</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5">
        <p>
          Klicka på ett resultat för att se detaljer.
        </p>
      </div>
      <div class="col-md-7">
        
      </div>
    </div>
    <div class="row">
      <div class="col-md-5" id="results">
      </div>
      <div class="col-md-7">
      </div>
    </div>
  </div>


  <?php
require_once 'footer.php';
?>
<script src="js/common.js"></script>
<script>
<?php
$i=1;
$squads_by_id = getNumberOfPlayedSquadsPerPlayer();
$results_by_id = getRawResultsSortedByPlayer();
foreach (getOrdinaryResults() as $result) {
  $numsquads = $squads_by_id[$result[id]];
  $reentry = $numsquads == 1 ? "" : ($numsquads==2 ? " (R)" : " (R2)");
  $outer_text = <<<EOT
  <table width="100%"> \
    <tr> \
      <td style="width:10%">$i.</td> \
      <td style="width:75%"><strong>$result[lastname]$reentry</strong> ($result[hcp])<br/>$result[club]</td> \
      <td style="width:15%;text-align:right"><strong>$result[result]</strong></td> \
    </tr> \
  </table>
EOT;
  $inner_text = "";
  $inner_text .= "Hcp/serie: $result[hcp]";
  $inner_text .= "<table width=\"100%\"><tr><th>Start</th><th>Serier (ren slagning)</th><th>Res.</th></tr>";
  foreach ($results_by_id[$result[id]] as $squad) {
    $squadstring = substr($squad[info],0,12) . "...";
    $inner_text .= <<<EOT
    <tr>\
      <td><a href="showsquad.php?day=$squad[day]&time=$squad[time]">$squadstring</a></td>\
      <td>$squad[s1] $squad[s2] $squad[s3] $squad[s4] $squad[s5] $squad[s6]</td>\
      <td>$squad[result]</td>\
    </tr>
EOT;
  }
  $color = $i%2 == 0 ? "#FFFFFF" : "#EEEEFA";
  echo <<<EOT
  $('#results').append(build_expand_button('$outer_text','$inner_text','$color'));
EOT;
  $i++;
} 

?>
</script>
</body>
</html>

