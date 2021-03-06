<?php
include 'header.php';
require_once '../db/globals.php';
require_once('../recaptchalib.php');

$err = array();

if (isset($_POST['message'])) {
  $ok = true;
  if (!(strlen($_POST['message'])>1)) {
    $err[] = "Inget meddelande har angetts (eller så är meddelandet för kort).";
    $ok = false;
  }
  $privatekey = $globCaptchaPrivKey;
  $resp = recaptcha_check_answer ($privatekey,
                              $_SERVER["REMOTE_ADDR"],
                              $_POST["recaptcha_challenge_field"],
                              $_POST["recaptcha_response_field"]);
  if (!$resp->is_valid) {
    $err[] = "Du har angett fel ord/siffror.";
  } else if ($ok == true) {
    $name = htmlspecialchars($_POST['name']);
    $phonenumber = htmlspecialchars($_POST['phonenumber']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    ob_start();
    echo "Namn: $name<br>Telefonnummer: $phonenumber<br>E-post: $email<br><br>$message";
    $tomail = ob_get_clean();
    $success = mail($globMailReceivers, $globMailTag . "Nytt meddelande",$tomail, $globMailHeader);
    if ($success == TRUE) {
      header('Location: mailsent.php');
    } else {
      $err[] = 'Ett internt fel har uppstått. Försök igen.';
      echo "fail1";
    }
  }
}


?>
</head>
<body>
<a href="index.php">&lt;&lt; Tillbaka</a>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="contactform">
    <h1>Kontakt</h1>
    <p>
      Fyll i formuläret nedan så svarar vi förmodligen inom några timmar. Vill du vara anonym behöver du inte ange något namn eller några kontaktuppgifter. Men då svarar vi inte heller :)
    </p>
    <p>
      Föredrar du att ringa når du hallen på 031-221517 och webbansvarig på 0761-608725.
    </p>
        <?php
if (count($err)>0) {
  echo <<<EOT
    <p style="color:rgb(255,0,0)">
      Följande fel har hittats:
      <ul style="color:rgb(255,0,0)">
EOT;
  foreach ($err as $v) {
    echo "<li>$v</li>";
  }
  echo <<<EOT
      </ul>
    </p>
EOT;
}
        ?>
  <table>
    <tr>
      <td>
        Namn:
      </td>
      <td>
        <input name="name" type="text" value="<?php if (count($err)>0) echo $_POST['name'];?>">
      </td>
    </tr>
    <tr>
      <td>
        Telefonnummer:
      </td>
      <td>
        <input name="phonenumber" type="text" value="<?php if (count($err)>0) echo $_POST['phonenumber'];?>">
      </td>
    </tr>
    <tr>
      <td>
        E-postadress:
      </td>
      <td>
        <input name="email" type="text" value="<?php if (count($err)>0) echo $_POST['email'];?>">
      </td>
    </tr>
    <tr>
      <td>
        Meddelande:
      </td>
      <td>
        <textarea name="message" rows="10" cols="40"><?php if (count($err)>0) echo $_POST['message'];?></textarea>
      </td>
    </tr>
  </table>
        Skriv in orden/siffrorna nedan:
<?php
  $publickey = $globCaptchaPublKey;
  echo recaptcha_get_html($publickey);
?>
    <input type="submit" value="Skicka"/>
  </form>
  <?php
include 'footer.php';
?>


