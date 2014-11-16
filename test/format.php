<?php
include 'header.php';
?>
</head>
<body>

  <?php
include 'menu.php';
?>
  

  <div class="container">
<?php
include 'db/dbfuncs.php';

$textdata =  getPageTextFormatted('format.php');
echo $textdata['text'];
?>
  </div>

  <?php
include 'footer.php';
?>
</body>
</html>
