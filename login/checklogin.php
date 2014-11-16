<?php
session_start();
if ($_SESSION['auth'] != 'aukl') {
  header('Location:index.php');
}
?>
