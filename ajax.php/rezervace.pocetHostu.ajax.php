<?php
/* -- Include functions -- */
Include '../fce/fce.inc';

/* -- Include configu -- */
Include "../config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");



/*----------------------------------------------------------------------------*/

/* -- Když není zadaný pokoj -- */
If(!$_POST["pokoj"] ){
    $radek = mysql_fetch_assoc( Mysql_query("SELECT id FROM $CONF[sqlPrefix]rezervPokoje ORDER BY id ASC LIMIT 1") );
    $pokoj = $radek["id"];    
}
Else $pokoj = $_POST["pokoj"];

/* -- Výpis možností počtu hostů -- */
$radek = mysql_fetch_assoc( Mysql_query("SELECT maxHostu FROM $CONF[sqlPrefix]rezervPokoje WHERE id=$pokoj") );
$maxHostu = $radek["maxHostu"];

Echo '<select id="selectPocetHostu" name="pocetHostu">';
For($i=1; $i<=$maxHostu; $i++){
    Echo '<option value="'.$i.'">'.$i.'</option>';
}
Echo '</select>';
?>