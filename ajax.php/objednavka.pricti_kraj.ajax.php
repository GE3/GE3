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



$kraj = $_POST["kraj"];
$pricti = $_POST["pricti"];

$dopravne = zjisti_z("$CONF[sqlPrefix]kraje","priplatek","nazev='$kraj'");

Echo ($pricti+$dopravne);
?>

