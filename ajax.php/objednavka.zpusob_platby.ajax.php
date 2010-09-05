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

?>



<?php
$dopravaFirma = $_POST["dopravaFirma"];

If( $dopravaFirma ){
    $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]doprava WHERE firma='$dopravaFirma' ORDER BY zpusob_platby ASC");
    Echo '*Způsob platby: &nbsp;&nbsp;&nbsp;<select name="zpusobPlatby" id="selectZpusobPlatby" onChange="prepocitej();">';
    Echo '<option value=""> - vyberte - </option>';
    While($radek=mysql_fetch_assoc($dotaz)){
          Echo '<option value="'.$radek["zpusob_platby"].'">'.$radek["zpusob_platby"].'</option>';
    }
    Echo '</select>';
}Else{
    Echo '<input type="hidden" name="zpusobPlatby" id="selectZpusobPlatby" value="">';
}
?>
