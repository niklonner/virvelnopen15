<?php
require_once '../db/globals.php';

if (isset($_POST[user]) && isset($_POST[password])) {
  if ($_POST[user] == $globAdminName && $_POST[password] == $globAdminPassword) {
    session_start();
    $_SESSION['auth'] = 'aukl';
    header('Location:loggedin.php');
  }
}

include 'header.php';
?>

</head>
<body>
  <form method="post" action="index.php">
    <table>
      <tr>
        <td>
          Användarnamn:
        </td>
        <td>
          <input type="text" name="user"/>
        </td>
      </tr>
      <tr>
        <td>
          Lösenord:
        </td>
        <td>
          <input type="password" name="password"/>
        </td>
      </tr>
    </table>
    <input type="submit" value="Logga in"/>
  </form>
</body>
<html>
