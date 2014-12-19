<?php
$errormessages = array(
  "internal" => "Ett internt fel har uppstått. Om felet kvarstår, <a href='contact.php'>kontakta oss</a>.",
  "firstname" => "Förnamnet saknas eller innehåller ogiltiga tecken.",
  "lastname" => "Namnet saknas eller innehåller ogiltiga tecken.",
  "club" => "Klubbnamnet saknas eller innehåller ogiltiga tecken.",
  "phonenumber" => "Telefonnumret saknas eller innehåller ogiltiga tecken. Endast siffror, med ett eventuellt inledande +, är tillåtna.",
  "email" => "E-postadressen verkar inte vara giltig.",
  "email_repeat" => "De två fälten för e-postadress stämmer inte överens.",
  "bits_id" => "Ogiltigt licensnummer. Detta är troligen ett internt fel. Vänligen <a href='contact.php'>kontakta oss.</a>",
  "nonechosen" => "Ingen start har valts.",
  "alreadyonsquad1" => "Du är redan registrerad på start 1.",
  "squad1full" => "Start 1 är fulltecknad.",
  "squad1passed" => "Det går inte längre att anmäla sig till start 1.",
  "squad1cancelled" => "Start 1 har ställts in",
  "alreadyonsquad2" => "Du är redan registrerad på start 2.",
  "squad2full" => "Start 2 är fulltecknad.",
  "squad2passed" => "Det går inte längre att anmäla sig till start 2.",
  "squad2cancelled" => "Start 2 har ställts in",
  "alreadyonsquad3" => "Du är redan registrerad på start 3.",
  "squad3full" => "Start 3 är fulltecknad.",
  "squad3passed" => "Det går inte längre att anmäla sig till start 3.",
  "squad3cancelled" => "Start 3 har ställts in",
  "squadssame" => "Samma start har valts två eller flera gånger.",
  "samechosen" => "Samma start har valts två eller flera gånger.",
  "changealreadyperformed" => "Denna ändring har redan genomförts.",
  "multipleearlybirds" => "Du har valt mer än en early bird-start.",
  "nochange" => "Du är redan registrerad på alla starter du valt."
);
function getSEReadableErrorMessage($errormsg) {
  global $errormessages;
  return $errormessages[$errormsg];
}
?>
