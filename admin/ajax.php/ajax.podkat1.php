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
Echo '
   <select name="podkat1" id="selectPodkat1" style="font-size: 80%;" onChange="novaPodkat(this.value,1);skryjPodkat(3);if(this.value==\'\')skryjPodkat(2);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat2.php\',\'kategorie='.$kategorie.'&podkat1=\'+this.value+\'&prefix='.$GLOBALS["prefix"].'\',\'divPodkat2\');ukazPodkat(2);}">
      <option value="">-nová podkategorie- =&gt;</option>';

$dotaz=Mysql_query("SELECT * FROM $GLOBALS[prefix]zbozi WHERE kategorie='$kategorie' ORDER BY podkat1 ASC");
while($radek=mysql_fetch_array($dotaz)){
   if($kategorie_tmp!=$radek["podkat1"]){
      echo '
         <option value="'.$radek["podkat1"].'">'.$radek["podkat1"].'</option>
         ';
      }
   $kategorie_tmp=$radek["podkat1"];
   }

echo '
   </select>
   <input type="text" id="inputNovaPodkat1" name="nova_podkat1" value="'.$nova_podkat1.'" style="font-size: 80%;" onChange="skryjPodkat(3);if(this.value==\'\')skryjPodkat(2);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat2.php\',\'kategorie='.$kategorie.'&podkat1=\'+this.value+\'&prefix='.$GLOBALS["prefix"].'\',\'divPodkat2\');ukazPodkat(2);}">
      ';
?>
