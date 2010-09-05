<?php
$TmplModul = new GlassTemplate("templates/$CONF[vzhled]/modul.html");
$TmplMakleri = new GlassTemplate("templates/$CONF[vzhled]/makler.html");

//Hlavička
$TmplModul->prirad("hlavicka.ikona", "templates/$CONF[vzhled]/images/makler.png");
$TmplModul->prirad("hlavicka.nadpis", "Obchodní zástupce");
$TmplModul->prirad("hlavicka.popis", "Umožňuje vytváření, správu a zobrazování údajů Vaší databáze obchodních zástupců.");

//Nový makléř
if( $_POST["jmeno"] AND $_POST["prijmeni"] AND $_POST["email"] AND $_POST["telefon"] AND !$_POST["id"] ){
    mysql_query("INSERT INTO $CONF[sqlPrefix]makleri(jmeno,prijmeni,email,telefon) VALUES ('$_POST[jmeno]', '$_POST[prijmeni]', '$_POST[email]', '$_POST[telefon]')") or die("Neznámá chyba v SQL dotazu: ".mysql_error());
    $TmplMakleri->prirad("zprava.obsah", "Úspěšně přidáno.");
}

//Smazání makléře
if( $_GET["smazat"] ){
    mysql_query("DELETE FROM $CONF[sqlPrefix]makleri WHERE id='$_GET[smazat]'") or die("Neznámá chyba v SQL dotazu: ".mysql_error());
    $TmplMakleri->prirad("zprava.obsah", "Úspěšně smazáno.");
}

//Editace informací
if( $_POST["jmeno"] AND $_POST["prijmeni"] AND $_POST["email"] AND $_POST["telefon"] AND $_POST["id"] ){
    Mysql_query("UPDATE $CONF[sqlPrefix]makleri SET jmeno='$_POST[jmeno]', prijmeni='$_POST[prijmeni]', email='$_POST[email]', telefon='$_POST[telefon]' WHERE id='$_POST[id]'");
    $TmplMakleri->prirad("zprava.obsah", "Úspěšně upraveno.");
}

//Zobrazení aktuálních informací
$dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]makleri");
$i=0;
while($radek=mysql_fetch_assoc($dotaz)){
      $i++;
      $TmplMakleri->newBlok("makler");
      $TmplMakleri->prirad("makler.i", $i);
      $TmplMakleri->prirad("makler.id", $radek["id"]);
      $TmplMakleri->prirad("makler.jmeno", $radek["jmeno"]);
      $TmplMakleri->prirad("makler.prijmeni", $radek["prijmeni"]);
      $TmplMakleri->prirad("makler.email", $radek["email"]);
      $TmplMakleri->prirad("makler.telefon", $radek["telefon"]);
}


$TmplModul->prirad("obsah", $TmplMakleri->getHtml());
Echo $TmplModul->getHtml();
?>