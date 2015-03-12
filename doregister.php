<?php
include 'header.php';
?>
</head>
<body>

  <?php
include 'menu.php';
?>
  
  <div class="container">
    <script language="javascript">actionifnotokbrowser();</script>
    <h1>Anmälan steg 1</h1>
    <p>
      Börja med att söka fram dig själv nedan (det är kopplat till BITS). OBS att inte alla fält behöver fyllas i för att göra en sökning. 
    </p>
      <form id="player_form" action="doregisterstep2.php" method="post">
        <input type="hidden" name="bits_name" id="bits_name" value=""/>
        <input type="hidden" name="bits_club" id="bits_club" value=""/>
        <input type="hidden" name="bits_id" id="bits_id" value=""/>
        <div class="row">
          <div class="col-md-4">
            <div class="input-group">
              <span class="input-group-addon">Förnamn</span>
              <input name="firstname" id="firstname" type="text" class="form-control bits-search-field" placeholder="Förnamn">
            </div>
          </div>
          <div class="col-md-8">

          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="input-group">
              <span class="input-group-addon">Efternamn</span>
              <input name="lastname" id="lastname" type="text" class="form-control bits-search-field" placeholder="Efternamn">
            </div>
          </div>
          <div class="col-md-8">

          </div>
        </div>

<!--        <div class="row">
          <div class="col-md-4">
            <div class="input-group">
              <span class="input-group-addon">Licensnummer</span>
              <input name="licens_number" id="licens_number" type="text" class="form-control bits-search-field" placeholder="Licensnummer">
            </div>
          </div>
          <div class="col-md-8">
          </div>
        </div>-->
        <div class="row">
          <div class="col-md-4">
            <a class="btn btn-default" href="javascript:void(0);" role="button" id="formbutton">Sök</a><span style="font-weight:bold" id="working"></span>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4" id="result_area">
          </div>
        </div>
        <p>
          <strong>Om du är utländsk spelare eller får problem med sökningen, använd istället den <a href="simple/doregister.php">förenklade anmälan</a>. </strong>
        </p>
      </form>
    </p>
    <p>
    </p>

  </div>


  <?php
include 'footer.php';
?>
<script src="js/common.js"></script>
<script>
$(document).ready(function(){
    $('#formbutton').click(function(){bits_search()});
    $('.bits-search-field').keyup(function(e) {
      if(e.keyCode == 13) {
        bits_search();
      }
    });

    function bits_search(){
        $("#working").html(" Söker...");
        $("#result_area").empty();
        $.post(
            "db/get_players_from_bits.php",
            {
                    firstname: document.getElementById("firstname").value,
                    lastname: document.getElementById("lastname").value,
                    licens_number: ""
            },
            function(data) {
                var bits_id_section = document.createElement("div");
                bits_id_section.id = "bits_id_section";
//                bits_id_section.className = "row";
//                var sel = document.createElement("select");
//                sel.id = "bits_id";
//                sel.name = "bits_id";
//                sel.style.height = "50px";
//font-family: inherit
//font-size: inherit
//line-height:inherit
//                sel.style.fontSize = "pt";
//                sel.style.lineHeight = "1em";
//text-transform: none
//margin: 0px
//-moz-box-sizing: border-box
//                sel.className = "form-control";
//                $(bits_id_section).append(sel);
                var arr = $.parseJSON(data);
                var bits_player_array = [];
 //               var defaultop = document.createElement("option");
//                defaultop.text = "Välj spelare";
//                defaultop.value = "-1";
//                $(sel).append(defaultop);
                var numberofplayers = 0;
                $(arr).each(function(index,value){
                    var button = build_panel_button('javascript:void(0);',value.name + ", " + value.club + " (" + value.licens_number +")",numberofplayers%2==0?'#FFFFFF':'#EEEEFA',true);
                    $(bits_id_section).append(button);
                    $(button).click(function() {
                      document.getElementById("bits_name").value = value.name;
                      document.getElementById("bits_club").value = value.club;
                      document.getElementById("bits_id").value = value.licens_number;
                      document.getElementById("player_form").submit();
                    });
                    numberofplayers++;
/*                    var player = {};
                    player["name"] = value["name"];
                    player["club"] = value["club"];
                    player["licens_number"] = value["licens_number"];
                    bits_player_array.push(player);*/
 /*                    var opt = document.createElement("option");
                    opt.text = value.name + ", " + value["club"] + " (" + value["licens_number"] + ")";
                    opt.value = value["licens_number"];
                   $(sel).append(opt);*/
                });
/*                $(sel).change(function(){
                  document.getElementById("bits_name").value = bits_player_array[sel.selectedIndex-1]["name"];
                  document.getElementById("bits_club").value = bits_player_array[sel.selectedIndex-1]["club"];
                });*/
                $("#result_area").append(bits_id_section);
                $("#working").html(" Söker... klar. Hittade " + numberofplayers + " spelare:");
                render_custom();
            }
        ).error(function(){
          $("#working").html("Söker... fick inget svar från BITS. Försök igen senare eller <a href='contact.php'>kontakta oss</a>.");
        });;        
    }
});
</script>
</body>
</html>

<!--
Test att ändra till jquery 1.x
Ordna koden så script är separerade från php-filerna och så vidare..
-->

