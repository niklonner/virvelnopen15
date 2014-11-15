<?php
include 'header.php';
require_once 'db/globals.php';
require_once('recaptchalib.php');

$err = array();

if (isset($_POST['message'])) {
  $ok = true;
  if (!(strlen($_POST['message'])>1 && $_POST['message']!="Skriv ditt meddelande här...")) {
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

  <?php
include 'menu.php';
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="contactform" name="contactform">
  <div class="container">
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
    <div class="row">
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-addon">Namn</span>
          <input name="name" id="name" type="text" class="form-control bits-search-field" placeholder="Namn" value="<?php if (count($err)>0) echo $_POST['name'];?>">
        </div>
      </div>
      <div class="col-md-8">

      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-addon">Telefonnummer</span>
          <input name="phonenumber" id="phonenumber" type="text" class="form-control bits-search-field" placeholder="Telefonnummer" value="<?php if (count($err)>0) echo $_POST['phonenumber'];?>">
        </div>
      </div>
      <div class="col-md-8">

      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-addon">E-post</span>
          <input name="email" id="email" type="text" class="form-control bits-search-field" placeholder="E-postadress" value="<?php if (count($err)>0) echo $_POST['email'];?>">
        </div>
      </div>
      <div class="col-md-8">

      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <textarea class="form-control" name="message" id="message" rows="10" cols="40" onfocus="javascript:if(this.value=='Skriv ditt meddelande här...'){this.value='';}"><?php if (count($err)>0) echo $_POST['message']; else echo "Skriv ditt meddelande här...";?></textarea>
      </div>
      <div class="col-md-8">

      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        Skriv in orden/siffrorna nedan:
      </div>
      <div class="col-md-8">
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
<?php
  $publickey = $globCaptchaPublKey;
  echo recaptcha_get_html($publickey);
?>
      </div>
      <div class="col-md-8">
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <a class="btn btn-default" href="javascript:document.getElementById('contactform').submit();" role="button" id="formbutton">Skicka!</a>
      </div>
      <div class="col-md-8">
      </div>
    </div>
  </div>
</form>
  <?php
include 'footer.php';
?>
</body>
</html>


