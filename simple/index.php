<?php
require_once 'header.php';
require_once '../db/dbfuncs.php';
?>

</head>
<body>

<h1>Gothia Open 2013 förenklad webbsida</h1>
<p><a href="../index.php">Till vanliga webbsidan</a></p>
<h2>Nyheter</h2>
    <p>
      <span style="font-weight:bold">12/1 - Klotlotteriet</span><br/>
      Vinnare blev Johanna Påhlsson, TGB (nummer 158). Priset är ett valfritt klot inkl. borrning sponsrat av Mats Karlsson Proshop.
    </p>
    <p>
      <span style="font-weight:bold">12/1 - Tävlingen färdigspelad</span><br/>
      Finalen vanns av vår nye förbundskapten på herrsidan, Christer Backe från Team Kungsbacka, som i utjämningsserien med säker hand spurtade sig förbi Ola Moberg, TGB. Ola fick sedan spela roll-off om andraplatsen mot Martin Johansson, Team Alingsås. Martin behövde strika i sista slaget för att ta hand om andraplatsen, vilket han också gjorde.<br/><br/>
      <span style="font-weight:bold">Topp 8 (resultat från <a href="step2.php">finalsteg 2</a>):</span><br/>
1. Christer Backe, BK Team Kungsbacka 1897<br/>
2. Martin Johansson, Team Alingsås BC 1872<br/>
3. Ola Moberg, TGB 1872<br/>
4. Björn Holton, BK Team Kungsbacka 1851<br/>
5. Andreas Eriksson, Team Gothia BC 1757<br/>
6. Sören Berggren, Kvänums BK 1684<br/>
7. Göran Kuhlin, BK Trubaduren 1646<br/>
8. Morgan Tellander, BK Skrufscha 1615<br/>
<br/>
Tack för denna gången! Det har varit särskilt kul att se såpass många nya ansikten i tävlingen. På återseende nästa år!
    </p>
    <p>
      <span style="font-weight:bold;color:#ff0000">10/1 - Ändrade handicap</span><br/>
      <span style="color:#ff0000">Det framkom idag att det har skett retroaktiva ändringar i handicapsystemet, och efter samtal med förbundskansliet har vi beslutat att ändra handicapen även i vår tävling. Resultatet blev att några som spelade Early Bird-starterna fick sitt hcp justerat nedåt, då dessa handicap var de sportsligt korrekta. Tyvärr får det effekt bland finaldeltagarna.</span>
    </p>
<p>
  <span style="font-weight:bold">6/1 - Extrastart insatt</span><br/>
  Fredagen 10/1 18.00.
</p>
<p>
  <span style="font-weight:bold">4/1 - Angående inställda starter</span><br/>
  Om en start inte har några spelare ställs den in två timmar innan spelstart. Tidiga starter (som börjar innan kl. 12) ställs in kl. 20 dagen innan.
</p>

<h2>Anmälan</h2>
<p>
<a href="doregister.php">Jag vill anmäla mig</a><br/>
<a href="dochange.php">Jag vill ändra min anmälan/avanmäla mig</a>
</p>

<h2>Information</h2>
<p>
<a href="../affisch2013_liten.pdf">Affisch</a><br/>
<a href="../affisch2013.pdf">Högupplöst affisch för utskrift</a><br/>
<a href="format.php">Tävlingsformat</a>
</p>

<h2>Kontakt</h2>
<p><a href="contact.php">Skicka ett meddelande till oss</a><br/>
Vill man hellre ringa är telefonnumret till hallen 031-221517 och webbansvarig 0761-608725.</p>

<h2>Starter/resultat</h2>

<p>
  <a href="step1.php">Finalsteg 1</a><br/>
  <a href="step2.php">Finalsteg 2</a><br/>
<br/>
  <a href="allplayers.php">Visa samtliga spelare (<?php echo getPlayerCount(); ?> spelare, <?php echo getReentryCount(); ?> starter)</a>
<br/>
<br/>
  <a href="ordinaryresults.php">Totala kvalresultat</a><br/>
  <a href="earlybirdresults.php">Early Bird-resultat</a><br/>
  <a href="turbo5results.php">Turbo serie 5</a><br/>
  <a href="turbo6results.php">Turbo serie 6</a>

<br/><br/>

<?php
$squads = getSquadInfo();
foreach ($squads as $sq) {
  $info = utf8_encode($sq[info]);
  echo <<<EOT
    <a href="showsquad.php?day=$sq[day]&time=$sq[time]">$info ($sq[count]/$sq[spots] spelare)</a><br/>
EOT;
/*  echo 
  foreach ($v as $k => $vv)
    echo "$k: $vv<br/>";
  echo "<br/>";*/
}
?>
</p>

<?php
require_once 'footer.php';
?>

