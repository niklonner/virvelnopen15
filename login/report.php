<?php
require_once 'checklogin.php';
require_once '../db/dbfuncs.php';
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta charset="iso-8859-1"/>
<title>Gothia Open 2014 Adminsidor</title>
</head>
<body>
<?php
foreach (getBitsReport() as $result) {
  $qresults[$result[id]] = array (
    bitsid => utf8_decode($result[bitsid]),
    hcp => $result[hcp],
    result => $result[result]
  );
}
$i = 1;
foreach (getBitsReportStep2() as $result) {
  $qres = $qresults[$result[id]];
  echo <<<EOT
  $qres[bitsid];$i;$qres[hcp];$qres[result];6;0;0;0;0;$result[result];8;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0<br/>
EOT;
  unset($qresults[$result[id]]);
  $i++;
}
foreach (getBitsReportStep1() as $result) {
  if (!isset($qresults[$result[id]]))
    continue;
  $qres = $qresults[$result[id]];
  echo <<<EOT
  $qres[bitsid];$i;$qres[hcp];$qres[result];6;0;0;$result[result];6;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0<br/>
EOT;
  unset($qresults[$result[id]]);
  $i++;
}
foreach (getBitsReport() as $result) {
  if (!isset($qresults[$result[id]]))
    continue;
  $qres = $qresults[$result[id]];
  echo <<<EOT
  $qres[bitsid];$i;$qres[hcp];$qres[result];6;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0<br/>
EOT;
  unset($qresults[$result[id]]);
  $i++;
}
?>
</body>
</html>

