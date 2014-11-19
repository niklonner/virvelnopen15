<?php
include 'header.php';
?>
</head>
<body>

  <?php
include 'menu.php';
?>
  
  <div class="container">
    <strong>Vissa webbläsare kan ha problem med den här sidan. Använd i så fall <a href="simple/index.php">den förenklade sidan</a>.</strong>
<?php

include 'db/dbfuncs.php';

$textdata =  getPageTextFormatted('index.php');
echo $textdata['text'];
?>
  </div>


  <?php
include 'footer.php';
?>
</body>
</html>
