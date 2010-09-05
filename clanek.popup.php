<?php
/*********************/
/* POČÁTEČNÍ PŘÍKAZY */
/*********************/

/* -- Sessions -- */
session_start();

/* -- Include funkcí -- */
Include "fce/fce.inc";

/* -- Include configu -- */
Include "config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");



/* -- Zobrazení článku -- */
If( $_GET["a"]=="clanky" AND $_GET["clanek"] ){
    $clanekId = ereg_replace("^([0-9]*)-.*$", "\\1", $_GET["clanek"]);
    $clanek = mysql_fetch_assoc(Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE id=$clanekId LIMIT 1"));
    Echo mysql_error().'
          <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
          <html>
            <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8">
            <meta name="generator" content="PSPad editor, www.pspad.com">
            <title></title>
            </head>
            <body>          
              <h1>'.$clanek["nazev"].'</h1>
              <!--<p>'.$clanek["uvod"].'</p>-->
              <p>'.$clanek["obsah"].'</p>
              </body>
            </html>
          ';
}
?>
