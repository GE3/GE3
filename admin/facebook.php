<?php
$TmplModul = new GlassTemplate("templates/$CONF[vzhled]/modul.html");
$TmplFacebook = new GlassTemplate("templates/$CONF[vzhled]/facebook.html");

//Hlavička
$TmplModul->prirad("hlavicka.ikona", "templates/$CONF[vzhled]/images/facebook.png");
$TmplModul->prirad("hlavicka.nadpis", "Facebook");
$TmplModul->prirad("hlavicka.popis", "Modul umožňující sdílení a publikování jednotlivých stránek Vašeho webu vč. náhledového obrázku na síti FACEBOOK.");

//Editace informací
if( $_POST["action"] ){
    Mysql_query("UPDATE $CONF[sqlPrefix]facebook SET hodnota='".str_replace('\'','\\\'',$_POST["facebook_clanky"])."' WHERE typ='facebook_clanky'");
    Mysql_query("UPDATE $CONF[sqlPrefix]facebook SET hodnota='".str_replace('\'','\\\'',$_POST["facebook_produkty"])."' WHERE typ='facebook_produkty'");
}

//Zobrazení aktuálních informací
$dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]facebook");
while($radek=mysql_fetch_assoc($dotaz)){
      $TmplFacebook->prirad($radek["typ"], $radek["hodnota"]);
}


$TmplModul->prirad("obsah", $TmplFacebook->getHtml());
Echo $TmplModul->getHtml();
?>