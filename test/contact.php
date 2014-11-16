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
<?php

include 'db/dbfuncs.php';

$textdata =  getPageTextFormatted('contact.php');
echo $textdata['text'];
?>
  </div>
  <?php
include 'footer.php';
?>
</body>
</html>


