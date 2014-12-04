<?php
require_once 'header.php';
?>
</head>
<body>

  <?php
require_once 'menu.php';
?>
  
  <div class="container">
    <script language="javascript">actionifnotokbrowser();</script>
    <h1>Anmälan</h1>
    <div class="row">
      <div class="col-md-4" id="options">
      </div>
      <div class="col-md-8">
      <div>
    </div>
  </div>


  <?php
require_once 'footer.php';
?>
<script src='js/common.js'></script>
<script>
$('#options').append(build_panel_button('doregister.php','Jag vill anmäla mig',true,false));
$('#options').append(build_panel_button('dochange.php','Jag vill ändra min anmälan/avanmäla mig',true,false));
render_custom();
</script>
</body>
</html>
<?php
include 'header.php';
?>
</head>
<body>

  <?php
include 'menu.php';
?>
  
