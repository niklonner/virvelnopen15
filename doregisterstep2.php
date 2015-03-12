<?php
include 'header.php';
require_once 'db/dbfuncs.php';
require_once 'db/errormessages.php';
?>
</head>
<body>

  <?php
include 'menu.php';
?>
  
  <div class="container">
    <script language="javascript">actionifnotokbrowser();</script>
      <form id="player_form" action="doregisterfinal.php" method="post">
        <input type="hidden" name="bits_name" id="bits_name" value="<?php echo $_POST['bits_name'] ?>"/>
        <input type="hidden" name="bits_club" id="bits_club" value="<?php echo $_POST['bits_club'] ?>"/>
        <input type="hidden" name="bits_id" id="bits_id" value="<?php echo $_POST['bits_id'] ?>"/>
        <input type="hidden" name="squad1" id="squadfield1" value=""/>
        <input type="hidden" name="squad2" id="squadfield2" value=""/>
        <input type="hidden" name="squad3" id="squadfield3" value=""/>
        <div class="row">
          <div class="col-md-12">
            <h1>Anmälan steg 2</h1>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="row">
              <div class="col-md-12">
                <p>
                  <strong>Spelare: <?php echo $_POST['bits_name'].", ".$_POST['bits_club'];  ?></strong>
                </p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="input-group">
                  <span class="input-group-addon">Telefonnummer</span>
                  <input name="phonenumber" id="phonenumber" type="text" class="form-control" placeholder="Endast siffror">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="input-group">
                  <span class="input-group-addon">E-post</span>
                  <input name="email" id="email" type="email" class="form-control" placeholder="Valfritt, behövs för att ändra/avboka">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="input-group">
                  <span class="input-group-addon">Upprepa e-post</span>
                  <input name="email_repeat" id="email_repeat" type="email" class="form-control" placeholder="Upprepa e-postadress">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div id="errors" style="color:#ff0000; margin-top:20px"></div>
              </div>
            </div>
          </div>
          <div class="col-md-8" id="squads_area">
            <div class="row" style="font-weight:bold">
              <div class="col-md-12" id="res">
                <p>
                  <strong>Välj starter (markera/avmarkera genom att klicka). Max tre starter.</strong>
                </p>
              </div>
            </div>
<?php
$i = 0;
$i_inactive = 0;
foreach (getSquadInfo() as $squad) {
  if ($i%2==0) {
    echo "<div class='row'>";
  }
  if (!(!okStartTime($squad[day],$squad[time]) || squadFull($squad[day],$squad[time]) || squadCancelled($squad[day],$squad[time]))) {
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
        </div>
        <div class="row">
          <div class="col-md-12">
            <a class="btn btn-default" href="javascript:void(0);" role="button" id="submitbutton">Skicka anmälan!</a>
            <span id="errorsfound" style="color:#ff0000"></span>
          </div>
        </div>
      </form>

  </div>


  <?php
include 'footer.php';
?>
<script src="js/common.js"></script>
<script>
function doregister() {
  $('#errorsfound').empty();
  $('#submitbutton').html("Jobbar...");
  $('#submitbutton').unbind();
  $.post(
    "db/register_player.php",
    {
      bits_name: document.getElementById('bits_name').value,
      bits_club: document.getElementById('bits_club').value,
      bits_id: document.getElementById('bits_id').value,
      email: document.getElementById('email').value,
      email_repeat: document.getElementById('email_repeat').value,
      phonenumber: document.getElementById('phonenumber').value,
      squad1: document.getElementById('squadfield1').value,
      squad2: document.getElementById('squadfield2').value,
      squad3: document.getElementById('squadfield3').value
    },
    function(data) {
      var arr = data.split("__linebreak");
      if (arr[0].trim() == "ok") {
        window.location.href = "confirmregistration.php?" + arr[1];
      } else {
        $('#submitbutton').html("Skicka anmälan!");
        $('#submitbutton').click(function(){doregister();});
        $('#errors').empty();
        var ul = document.createElement('ul');
        $('#errors').append(ul);
        $('#errorsfound').html('Fel hittades, se ovan.');
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
  if (!(!okStartTime($arr[day],$arr[time]) || squadFull($arr[day],$arr[time]) || squadCancelled($arr[day],$arr[time]))) {
    $isearlybird = $arr['earlybird'] == 1 ? "true" : "false";
    echo "buttonarray[$i] = build_panel_button('javascript:handle_squad_click($i);','".$arr['info'].
        " ($arr[count]/$arr[spots] spelare)','#FFFFFF',false,true);";
    echo <<<EOT
    $('#squad$i').append(buttonarray[$i]);
    squadarray[$i] = {};
    squadarray[$i].id = $arr[day]$arr[time];
    squadarray[$i].selected = false;
    squadarray[$i].isearlybird = $isearlybird;
EOT;
    $i++;
  } else {
    $info = $arr['info'];
    echo <<<EOT
    var inactivebutton$i_inactive = build_panel_button('javascript:;','$info ($arr[count]/$arr[spots] spelare)','#FFFFFF',false,true);
    $('#inactivesquad$i_inactive').append(inactivebutton$i_inactive);
EOT;
    $to_disable[] = "inactivebutton{$i_inactive}.id";
    $i_inactive++;
  }
}
?>
render_custom();
<?php
foreach ($to_disable as $disable) {
  echo "disable_tick_button($disable);";
}
?>
})();
function handle_squad_click(i) {
  //  mark position in array
  squadarray[i].selected = squadarray[i].selected == true ? false : true;
  update_squads(squadarray[i].selected);
}
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
  if ((squadcount >= 3 && whatchange==true) || (squadcount == 2 && whatchange==false)) {
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
}
</script>
</body>
</html>
