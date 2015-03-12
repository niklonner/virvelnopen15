<?php
require_once 'header.php';
require_once 'db/dbfuncs.php';
require_once 'db/errormessages.php';
?>
</head>
<body>
  <input type="hidden" name="squad1" id="squadfield1" value=""/>
  <input type="hidden" name="squad2" id="squadfield2" value=""/>
  <input type="hidden" name="squad3" id="squadfield3" value=""/>
  <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>"/>

  <?php
require_once 'menu.php';
$player = getPlayerInfo($_GET['id']);
?>
  
  <div class="container">
    <script language="javascript">actionifnotokbrowser();</script>
    <div class="row">
      <div class="col-md-12">
        <h1>Ändring/avanmälan steg 2</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <strong><?php echo "$player[firstname] $player[lastname], $player[club]"; ?></strong>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <p>
          Markera dina starter nedan. Max tre starter.
        </p>      </div>
    </div>
    <div class="row">
      <div class="col-md-8" id="squadarea">
<?php
$i = 0;
$i_inactive = 0;
$playersquads = getPlayerSquads($_GET['id']);
foreach (getSquadInfo() as $squad) {
  $chosensquad = false;
  foreach ($playersquads as $ps) {
    if ($squad[day]==$ps[day] && $squad[time]==$ps[time]) {
      $chosensquad = true;
    }
  }
  if ($i%2==0) {
    echo "<div class='row'>";
  }
  if (!(!okStartTime($squad[day],$squad[time]) || (squadFull($squad[day],$squad[time]) && !$chosensquad) || squadCancelled($squad[day],$squad[time]))) {
    $divid = "squad".($i-$i_inactive);
  } else {
    $divid = "inactivesquad".($i_inactive++);
  }
  echo <<<EOT
  <div class='col-md-6' id='$divid'>
  </div>
EOT;
  if ($i%2!=0) {
    echo "</div>";
  }
  $i++;
}
?>
      </div>
      <div class="col-md-4">
      </div>
    </div>
    <div class="row">
      <div class="col-md-8" style="margin-top:0.5em;margin-bottom:0.5em">
        När du klickar på "begär ändring" nedan kommer ett mail skickas till den e-postadress du registrerat. <span style="font-weight:bold;color:rgb(255,0,0)">Du måste klicka på länken i mailet för att genomföra förändringarna!</span>
      </div>
      <div class="col-md-4">
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <a class="btn btn-default" href="javascript:void(0);" role="button" id="submitbutton">Begär ändring!</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <span id="errors" style="color:rgb(255,0,0)"></span>
      </div>
    </div>
  </div>


<?php
require_once 'footer.php';
?>
<script src="js/common.js"></script>
<script>
function doregister() {
  $('#submitbutton').html("Jobbar...");
  $.post(
    "db/change_registration.php",
    {
      id : document.getElementById('id').value,
      squad1: document.getElementById('squadfield1').value,
      squad2: document.getElementById('squadfield2').value,
      squad3: document.getElementById('squadfield3').value
    },
    function(data) {
      var arr = data.split("__linebreak");
      if (arr[0].trim() == "ok") {
        window.location.href = "confirmchangerequest.php?" + arr[1];
      } else {
        $('#submitbutton').html("Begär ändring!");
        $('#errors').empty();
        $('#errors').append('<p>Följande fel hittades:</p>');
        var ul = document.createElement('ul');
        $('#errors').append(ul);
        for (err in arr) {
          $('#errors ul').append('<li>'+arr[err]+'</li>');
        }
      }
    }
  ); 
}

$('#submitbutton').click(function() {
  doregister(); 
});

squadarray = [];
buttonarray = [];
(function(){
<?php
$i = 0;
$i_inactive = 0;
$to_disable = array();
foreach (getSquadInfo() as $arr) {
  $chosensquad = false;
  foreach ($playersquads as $ps) {
    if ($arr[day]==$ps[day] && $arr[time]==$ps[time]) {
      $chosensquad = true;
    }
  }
  if (!(!okStartTime($arr[day],$arr[time]) ||
      (squadFull($arr[day],$arr[time]) && !$chosensquad) ||
      squadCancelled($arr[day],$arr[time]))) {
    $isearlybird = $arr['earlybird'] == 1 ? "true" : "false";
    echo "buttonarray[$i] = build_panel_button('javascript:handle_squad_click($i);','".$arr['info']." ($arr[count]/$arr[spots] spelare)','#FFFFFF',false,true);";
    echo <<<EOT
    $('#squad$i').append(buttonarray[$i]);
    squadarray[$i] = {};
    squadarray[$i].id = $arr[day]$arr[time];
    squadarray[$i].selected = false;
    squadarray[$i].isearlybird = $isearlybird;
EOT;
    $squadidtoindex[$arr['day'].$arr['time']] = $i;
    $i++;
  } else {
    $info = $arr['info'];
    echo <<<EOT
    var inactivebutton$i_inactive = build_panel_button('javascript:;','$info ($arr[count]/$arr[spots] spelare)','#FFFFFF',false,true);
    $('#inactivesquad$i_inactive').append(inactivebutton$i_inactive);
EOT;
    $to_disable[] = "inactivebutton{$i_inactive}.id";
    $inactivesquadidtoindex[$arr[day].$arr[time]] = $i_inactive;
    $i_inactive++;
  }
}
echo "render_custom();";
foreach ($to_disable as $disable) {
  echo "disable_tick_button($disable);";
}
$playedsquads = 0;
foreach ($playersquads as $sq) {
  if ($sq['done'] == true) {
    $playedsquads++;
    // change glyphicon
    $index = $inactivesquadidtoindex[$sq[day].$sq[time]];
    echo <<<EOT
var glyphtd = $('#inactivesquad$index').find(".glyph-td");
$(glyphtd).empty();
$(glyphtd).append('<span class="glyphicon glyphicon-ok"></span>');
EOT;
  } else {
    // not especially elegant to use both of these, but... i dont know...
    echo "handle_squad_click(".$squadidtoindex[$sq['day'].$sq['time']].");";
    echo "$(buttonarray[".$squadidtoindex[$sq['day'].$sq['time']]."]).find('a').trigger('click');";
  }
}
?>
})();
function handle_squad_click(i) {
  //  mark position in array
  squadarray[i].selected = squadarray[i].selected == true ? false : true;
  update_squads(squadarray[i].selected);
}
var chooseablesquads = <?php echo 3-$playedsquads;?>;

update_squads(true); // in case we should disable all
function update_squads(whatchange) {
  // reset hidden squad fields
  document.getElementById('squadfield1').value = "";
  document.getElementById('squadfield2').value = "";
  document.getElementById('squadfield3').value = "";
  // run through array, if marked then update hidden squad fields
  var squadcount = 0;
  for (i=0;i<squadarray.length;i++) {
    if (squadarray[i].selected == true) {
      squadcount++;
      document.getElementById('squadfield'+squadcount).value = squadarray[i].id;
    }
  }

  // disable/activate buttons if needed
  if ((squadcount >= chooseablesquads && whatchange==true) || (squadcount == (chooseablesquads-1) && whatchange==false)) {
    for (i=0;i<squadarray.length;i++) {
     if (squadarray[i].selected == false) {
        if (whatchange == true) {
          disable_tick_button(buttonarray[i].id);
        } else {
          enable_tick_button(buttonarray[i].id);
        }
      }
    }
  }
  // special early bird handling (only works for max two eb's)
  // always consider, except when going to three selected squads
  // both early birds have been played now, so screw this code
/*  if (!(squadcount >= 3 && whatchange==true)) {
    var earlybird = [];
    // count squads
    for (i=0;i<squadarray.length;i++) {
      if (squadarray[i].isearlybird == true) {
        var index = earlybird.length>0 ? 1 : 0;
        earlybird[index] = squadarray[i];
        earlybird[index].realid = i;
      }
    } 
    // only handle if more than one eb
    // if one of them is selected
    if (earlybird.length>1 && (earlybird[0].selected == true || earlybird[1].selected == true)) {
      var notselected = earlybird[0].selected==true ? 1 : 0;
      disable_tick_button(buttonarray[notselected].id);
    } else if (earlybird.length > 1) { // if none is selected
      for (i = 0;i<earlybird.length;i++) {
        enable_tick_button(buttonarray[earlybird[i].realid].id);
      }
    }
  }*/
}
</script>

</body>
</html>
