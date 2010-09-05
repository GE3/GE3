<?php
/* -- Include configu -- */
Include "../../config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");

$GLOBALS["prefix"] = $_POST["prefix"];
?>



<?php
$kategorie = $_POST["kategorie"];
$podkat1 = $_POST["podkat1"];
$podkat2 = $_POST["podkat2"];
Echo '
   <select name="podkat3" id="selectPodkat3" style="font-size: 80%;" onChange="novaPodkat(this.value,3);">
      <option value="">-nová podkategorie- =&gt;</option>';

$dotaz=Mysql_query("SELECT * FROM $GLOBALS[prefix]zbozi WHERE kategorie='$kategorie' AND podkat1='$podkat1' AND podkat2='$podkat2' ORDER BY podkat3 ASC");
while($radek=mysql_fetch_array($dotaz)){
   if($kategorie_tmp!=$radek["podkat3"]){
      echo '
         <option value="'.$radek["podkat3"].'">'.$radek["podkat3"].'</option>
         ';
      }
   $kategorie_tmp=$radek["podkat3"];
   }

echo '
   </select>
   <input type="text" id="inputNovaPodkat3" name="nova_podkat3" value="'.$nova_podkat3.'" style="font-size: 80%;">
      ';
?>
