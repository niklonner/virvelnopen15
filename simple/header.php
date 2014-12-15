<!DOCTYPE html>
<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">-->

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />

    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Gothia Open 2014 förenklad webbsida</title>
    <link href="style.css" rel="stylesheet" type="text/css"/>
<?php
// if header exists, array with header at [0] and rest at [1]
// if not, return false
// header exists if the string starts with [leading whitespace allowed]<hX>[anything]</hX>
// or more precisely; if it matches the pattern ^\s*<h(\d)>.*?</h\1>
function getHeaderDelimiter($string) {
  $matches = array();
  if (preg_match("/^\\s*<h(\\d)>.*?<\/h\\1>/",$string,$matches)) {
    $headerend = strlen($matches[0]);
    $ret = array();
    //4 and -9 to remove leading and trailing tags
    $ret[0] = substr($string,4,$headerend-9);
    $ret[1] = substr($string,$headerend);
    return $ret;
  }
  else {
    return false;
  }
}
?>
