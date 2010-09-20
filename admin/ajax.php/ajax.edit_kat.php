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
$podkat3 = $_POST["podkat3"];
?>


<form action="" method="post" enctype="multipart/form-data" style="margin: 0px 0px 0px 0xp; padding: 0px 0px 0px 0xp;">
<h3 style="margin-bottom: 0px;">Editace kategorie</h3>
<?php /* Zobrazení formuláře */

//Změna názvu
Echo 'Název: ';
If( $podkat3 ){
    Echo '<input type="text" name="podkat3" value="'.zjisti_z($tabulka,"podkat3","id='$podkat3'").'">';
}Elseif($podkat2){
        Echo '<input type="text" name="podkat2" value="'.zjisti_z($tabulka,"podkat2","id='$podkat2'").'">';
}Elseif($podkat1){
        Echo '<input type="text" name="podkat1" value="'.zjisti_z($tabulka,"podkat1","id='$podkat1'").'">';
}Elseif($kategorie){
        Echo '<input type="text" name="kategorie" value="'.zjisti_z($tabulka,"kategorie","id='$kategorie'").'">';
}

//Skryté inputy
Echo ( $podkat3 ? '<input type="hidden" name="staraPodkat3" value="'.zjisti_z($tabulka,"podkat3","id='$podkat3'").'">' : '' );
Echo ( $podkat2 ? '<input type="hidden" name="staraPodkat2" value="'.zjisti_z($tabulka,"podkat2","id='$podkat2'").'">' : '' );
Echo ( $podkat1 ? '<input type="hidden" name="staraPodkat1" value="'.zjisti_z($tabulka,"podkat1","id='$podkat1'").'">' : '' );
Echo ( $kategorie ? '<input type="hidden" name="staraKategorie" value="'.zjisti_z($tabulka,"kategorie","id='$kategorie'").'">' : '' );

//Tlačítko Odeslat
if($podkat3) $obrazek=zjisti_z($tabulka,"obrazekPodkat3","id='$podkat3'");
elseif($podkat2) $obrazek=zjisti_z($tabulka,"obrazekPodkat2","id='$podkat2'");
elseif($podkat1) $obrazek=zjisti_z($tabulka,"obrazekPodkat1","id='$podkat1'");
else $obrazek=zjisti_z($tabulka,"obrazekKategorie","id='$kategorie'");
Echo '<input type="submit" name="zmenit_kat" value="Změnit"> <br>
      '.( $obrazek? '<div id="divImg">Obrázek: <img src="../zbozi/obrazky/'.$obrazek.'" height="24"> &nbsp; <a href="javascript: void(0);" onClick="document.getElementById(\'divImgEdit\').style.display=\'block\';document.getElementById(\'divImg\').style.display=\'none\';">Změnit</a></div>': '' ).' 
      <div id="divImgEdit" style="display: '.( $obrazek? 'none': 'block' ).';">Nový obrázek: <br><input type="file" name="obrazek"> </div>
      '.( !$podkat1? 'Popis: <br>
      <textarea name="popisKategorie" style="width: 256px; height: 64px;">'.zjisti_z($tabulka,"popisKategorie","id='$kategorie'").'</textarea>': '' ).'
      ';
?>
</form>
