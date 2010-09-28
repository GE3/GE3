<?php
// Include functions
include "../config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");
?>



<?php
$podminky = "(LOWER(cislo) LIKE LOWER('%$_POST[search]%') OR 
              LOWER(kategorie) LIKE LOWER('%$_POST[search]%') OR 
              LOWER(popisKategorie) LIKE LOWER('%$_POST[search]%') OR
              LOWER(podkat1) LIKE LOWER('%$_POST[search]%') OR 
              LOWER(podkat2) LIKE LOWER('%$_POST[search]%') OR 
              LOWER(podkat3) LIKE LOWER('%$_POST[search]%') OR 
              LOWER(produkt) LIKE LOWER('%$_POST[search]%') OR 
              LOWER(varianta) LIKE LOWER('%$_POST[search]%') OR 
              LOWER(vyrobce) LIKE LOWER('%$_POST[search]%') OR 
              LOWER(prilohy) LIKE LOWER('%$_POST[search]%') OR 
              LOWER(popis) LIKE LOWER('%$_POST[search]%') 
              ) ";
$dotaz = mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE $podminky ORDER BY vahaProduktu DESC LIMIT 25");
while($radek=mysql_fetch_assoc($dotaz)){
      echo '[<a style="color: blue;" href="javascript: void(0);" onClick="document.getElementById(\'inputPodobne'.$_POST["input_id"].'\').value=\''.$radek['produkt'].'\'; checkPodobne(); popupDivOff();">přidat</a>] 
            '.$radek['produkt'].' <br>';
}
?>
