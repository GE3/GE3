<?php
$TmplModul = new GlassTemplate("templates/$CONF[vzhled]/modul.html");
$TmplNastaveni = new GlassTemplate("templates/$CONF[vzhled]/podobne_produkty.html");

//Hlavička
$TmplModul->prirad("hlavicka.ikona", "templates/$CONF[vzhled]/images/podobne_produkty.png");
$TmplModul->prirad("hlavicka.nadpis", "Podobné produkty");
$TmplModul->prirad("hlavicka.popis", "Modul, který umožní přidávat k produktům podobné produkty.");

//Editace nastavení
If( $_POST["akce"]=='podobne-produkty' ){
    Mysql_query("UPDATE $CONF[sqlPrefix]nastaveni 
                 SET podobne_produkty_active='$_POST[podobne_produkty_active]' 
                 WHERE id=1") or die(Mysql_error());
}

//Zobrazení aktuálních informací
$radek = mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]nastaveni") );
foreach($radek as $key=>$value){
      $TmplNastaveni->prirad($key, $value);
}


$TmplModul->prirad("obsah", $TmplNastaveni->getHtml());
Echo $TmplModul->getHtml();
?>