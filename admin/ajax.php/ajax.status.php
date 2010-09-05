<?php
/* -- Include configu -- */
Include "../../config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");

$tabulka = $CONF["sqlPrefix"]."zbozi";

/* -- Include functions -- */
Include '../../fce/fce.inc';
?>



<?php
$prefix=$CONF["sqlPrefix"];
$objednavka = $_POST["objednavka"];

$stav=zjisti_z("$CONF[sqlPrefix]objednavky","stav","id=$objednavka");
$stav=$stav?$stav:'nevyřízeno';

If( eregi('nevyřízeno',$stav) ){
    $vysledek='<span style="color: #008000;">odesláno</span>';
}
If( eregi('odesláno',$stav) ){
    $vysledek='nevyřízeno';
}
Mysql_query("UPDATE $CONF[sqlPrefix]objednavky SET stav='$vysledek' WHERE id=$objednavka ");


Echo mysql_error().$vysledek;
?>

