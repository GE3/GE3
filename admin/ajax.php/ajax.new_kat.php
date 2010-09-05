<?php
// Include functions
Include '../../fce/fce.inc';

/* -- Include configu -- */
Include "../../config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");

$tabulka = $CONF["sqlPrefix"]."zbozi";

$kategorie = $_POST["kategorie"];
$podkat1 = $_POST["podkat1"];
$podkat2 = $_POST["podkat2"];
?>


<form action="" method="post" enctype="multipart/form-data" style="margin: 0px 0px 0px 0xp; padding: 0px 0px 0px 0xp;">
<?php /* Zobrazení formuláře */

// Nadpis
If( $podkat2 ){
    $kategorieNazev = zjisti_z($tabulka,"kategorie","id=$podkat2");
    $podkat1Nazev = zjisti_z($tabulka,"podkat1","id=$podkat2");
    $podkat2Nazev = zjisti_z($tabulka,"podkat2","id=$podkat2");
}ElseIf( $podkat1 ){
    $kategorieNazev = zjisti_z($tabulka,"kategorie","id=$podkat1");
    $podkat1Nazev = zjisti_z($tabulka,"podkat1","id=$podkat1");
}ElseIf( $kategorie ){
    $kategorieNazev = zjisti_z($tabulka,"kategorie","id=$kategorie");
}Else{
    $nadpis = "<b>Nová kategorie</b> ";
}
$nadpis = "<b>Nová kategorie</b> ";
Echo $nadpis."<br>";

// Skryté inputy
If( $podkat2 ){
    Echo '<input type="hidden" name="kategorie" value="'.$kategorieNazev.'">';
    Echo '<input type="hidden" name="podkat1" value="'.$podkat1Nazev.'">';
    Echo '<input type="hidden" name="podkat2" value="'.$podkat2Nazev.'">';
}ElseIf( $podkat1 ){
    Echo '<input type="hidden" name="kategorie" value="'.$kategorieNazev.'">';
    Echo '<input type="hidden" name="podkat1" value="'.$podkat1Nazev.'">';
}ElseIf( $kategorie ){
    Echo '<input type="hidden" name="kategorie" value="'.$kategorieNazev.'">';
}

// Nový název a tlačítko Přidat
Echo 'Název: <input type="text" name="novyNazev" value="">
      <input type="submit" name="pridat_kat" value="Vytvořit"> <br>
      <div id="divImgEdit">Obrázek: <br><input type="file" name="obrazek"> </div>
      '.( !$kategorie? 'Popis kategorie: <br>
      <textarea name="popisKategorie" style="width: 256px; height: 96px;"></textarea>': '' ).'
      ';
?>
</form>
