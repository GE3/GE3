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
// Přílohy

/* -- Smazání -- */
If( $_POST["prilohaSmaz"] ){
    If( Mysql_query("UPDATE $CONF[sqlPrefix]zbozi SET prilohy=REPLACE(prilohy,'$_POST[prilohaSmaz];','')") ){
        If( @unlink("../zbozi/prilohy/$_POST[prilohaSmaz]") ){
            Echo '';
        }
    }
}

/* -- Přidání -- */
If( $_GET["n"]=="zmena_zakladnich_udaju" AND is_array($_FILES["prilohy"]["name"]) ){
    $priponyZakazane = array("php", "php2", "php3", "php4");
    Foreach($_FILES["prilohy"]["name"] as $key=>$value){
            If( $value AND !in_array($pripona, $priponyZakazane) ){
                $nazevSouboruBezPripony = eregi_replace("^(.*)\.[a-z]+$", "\\1", $_FILES["prilohy"]["name"][$key]);
                $nazevSouboru = getFriendlyFilename($_FILES["prilohy"]["name"][$key], $nazevSouboruBezPripony);

                Mysql_query("UPDATE $CONF[sqlPrefix]zbozi SET prilohy=CONCAT(prilohy,'$nazevSouboru;') WHERE produkt=
                              (SELECT produkt FROM (SELECT * FROM $CONF[sqlPrefix]zbozi) as pomTable WHERE id=$_GET[produkt]) 
                            ") or die(mysql_error());
                If( move_uploaded_file($_FILES["prilohy"]["tmp_name"][$key], "../zbozi/prilohy/$nazevSouboru") ){
                    Echo '<table><tr><td bgcolor="#FFDEDE">
                           Příloha úspěšně změněna.
                          </td></tr></table>';
                }
            }            
    }
}



/* -- Zobrazení -- */
Function inputyProPrilohy($max=10){
        $inputy = '';
        $i = 1;
        While($i<=$max){
              $cssDisplay = ($i<=2)?'inline':'none';

              $inputy.= '
                        <span id="spanPriloha'.$i.'" style="display: '.$cssDisplay.';"><input type="file" name="prilohy['.$i.']" onChange="if(this.value){document.getElementById(\'spanPriloha'.($i+2).'\').style.display=\'inline\';}else{document.getElementById(\'spanPriloha'.($i+2).'\').style.display=\'none\';}"><br></span>
                        ';
              $i++;
        }

        Return $inputy;
}
Function ikonaSouboru($nazevSouboru){
        $CONF = &$GLOBALS["config"];

        $pripona = eregi_replace("^.*\.([a-z]*)$", "\\1", $nazevSouboru);
        $pripona = strtolower($pripona);

        If(!file_exists("ikonySouboru/$pripona.png")) 
           $pripona="default";  
        
        Return "$pripona.png";
}


Echo '<div id="divUploadHlaska" style="display: none; text-align: center; font-weight: bold; color: #800000; margin-bottom: 12px;"></div>
      Následuje seznam příloh, které patří k produktu. Můžete i nové přílohy přidávat. <p>
      <script type="text/javascript">
      konecUploadu = function(cislo){
                            if( cislo==1 )
                                easyAjaxPopup(\'Administrace příloh\', \'upravit.ajax.prilohy.php\', \'id='.$_POST["id"].'\');
                            else{
                                document.getElementById(\'divUploadHlaska\').style.display = \'block\';
                                document.getElementById(\'divUploadHlaska\').innerHTML = \'Chyba: Některý ze souborů už pravděpodobně existuje.\';
                            }
      }
      </script>
      <form method="post" action="upravit.ajax.prilohy_upload.php" target="uploadIframe" enctype="multipart/form-data">
      <input type="hidden" name="id" value="'.$_POST["id"].'">';

$produkt = mysql_fetch_assoc( Mysql_query("SELECT prilohy FROM $CONF[sqlPrefix]zbozi WHERE id=$_POST[id]") );
$prilohy = explode(";", $produkt["prilohy"]);
$i = 1;
Foreach($prilohy as $key=>$value){
        If( $value ){
            Echo '<div id="divPriloha'.$i.'">
                    <img src="ikonySouboru/'.ikonaSouboru($value).'" style="position: relative; top: 3px;"> '.$value.' 
                    &nbsp;&nbsp;
                    <img src="images/delete.png" style="cursor: pointer;" onClick="if(confirm(\'Opravdu chcete tento soubor smazat?\'))easyAjaxPopup(\'Administrace příloh\', \'upravit.ajax.prilohy.php\', \'id='.$_POST["id"].'&prilohaSmaz='.$value.'\');">
                  </div>';
            $i++;
        }
}

Echo '<p align="center">
        <table border="0" align="center"><tr><td>
          <strong>Přidat přílohy: </strong><br>
          '.inputyProPrilohy().'<br>
          <div style="text-align: right;"><input type="submit" name="submit" value="Odeslat">
        </td></tr></table>
        <iframe id="uploadIframe" name="uploadIframe" src="upravit.ajax.prilohy_upload.php" style="border: 0px solid red ; width: 0px; height: 0px;"></iframe>
      </p>';

Echo '</form>';
?>
