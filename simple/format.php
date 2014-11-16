<?php
require_once 'header.php';
require_once '../db/dbfuncs.php';
?>
</head>
<body>
    <a href="index.php">&lt;&lt; Tillbaka</a>

<?php
$textdata =  getPageTextFormatted('format.php');
echo $textdata['text'];
?>
  <?php
require_once 'footer.php';
?>
