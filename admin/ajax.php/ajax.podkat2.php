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
Echo '
   <select name="podkat2" id="selectPodkat2" style="font-size: 80%;" onChange="novaPodkat(this.value,2);skryjPodkat(4);if(this.value==\'\')skryjPodkat(3);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat3.php\',\'kategorie='.$kategorie.'&podkat1='.$podkat1.'&podkat2=\'+this.value+\'&prefix='.$GLOBALS["prefix"].'\',\'divPodkat3\');ukazPodkat(3);}">
      <option value="">-nová podkategorie- =&gt;</option>';

$dotaz=Mysql_query("SELECT * FROM $GLOBALS[prefix]zbozi WHERE kategorie='$kategorie' AND podkat1='$podkat1' ORDER BY podkat2 ASC");
while($radek=mysql_fetch_array($dotaz)){
   if($kategorie_tmp!=$radek["podkat2"]){
      echo '
         <option value="'.$radek["podkat2"].'">'.$radek["podkat2"].'</option>
         ';
      }
   $kategorie_tmp=$radek["podkat2"];
   }

echo '
   </select>
   <input type="text" id="inputNovaPodkat2" name="nova_podkat2" value="'.$nova_podkat2.'" style="font-size: 80%;" onChange="skryjPodkat(4);if(this.value==\'\')skryjPodkat(3);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat3.php\',\'kategorie='.$kategorie.'&podkat1='.$podkat1.'&podkat1=\'+this.value+\'&prefix='.$GLOBALS["prefix"].'\',\'divPodkat3\');ukazPodkat(3);}">
      ';
?>
