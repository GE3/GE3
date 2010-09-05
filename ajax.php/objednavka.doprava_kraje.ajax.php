<?php
Include "../fce/fce.inc";

/* -- Include configu -- */
Include "../config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");
?>



<?php
$firma = $_POST["firma"];
$prefix = $_POST["prefix"];

If( @zjisti_z($prefix."doprava","id","firma='$firma' AND priplatek_za_kraj='ano' ") ){
    $dotaz=Mysql_query("SELECT * FROM ".$prefix."kraje ORDER BY nazev ASC");
    Echo '<b>*Kraj:</b> <select name="kraj" id="selectKraj" onChange="prepocitej();">';
    Echo '<option value=""> - vyberte - </option>';
    While($radek=mysql_fetch_array($dotaz)){
          Echo '<option value="'.$radek["nazev"].'">'.$radek["nazev"].'</option>';
    }
    Echo '</select>';
}Elseif($firma){
      Echo '
            <b>'.$firma.'</b>:
            Při tomto druhu dopravy <br>neplatíte žádné příplatky za kraj.
            <input type="hidden" name="kraj" id="selectKraj" value="">
            ';
}
?>
