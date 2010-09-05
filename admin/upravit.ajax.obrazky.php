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
If( $_POST["obrazekSmaz"] ){
    If( Mysql_query("UPDATE $CONF[sqlPrefix]zbozi SET obrazky=REPLACE(obrazky,'$_POST[obrazekSmaz];','')") ){
        If( @unlink("../zbozi/obrazky/$_POST[obrazekSmaz]") ){
            Echo '';
        }
    }
}


/* -- Zobrazení -- */
Function inputyProObrazky($max=10){
        $inputy = '';
        $i = 1;
        While($i<=$max){
              $cssDisplay = ($i<=2)?'inline':'none';

              $inputy.= '
                        <span id="spanObrazek'.$i.'" style="display: '.$cssDisplay.';"><input type="file" name="obrazky['.$i.']" onChange="if(this.value){document.getElementById(\'spanObrazek'.($i+2).'\').style.display=\'inline\';}else{document.getElementById(\'spanObrazek'.($i+2).'\').style.display=\'none\';}"><br></span>
                        ';
              $i++;
        }

        Return $inputy;
}


Echo '<div id="divUploadHlaska" style="display: none; text-align: center; font-weight: bold; color: #800000; margin-bottom: 12px;"></div>
      Zde můžete přidávat, mazat a upravovat obrázky ve fotogalerii produktu. <p>
      <script type="text/javascript">
      konecUploadu = function(cislo){
                            if( cislo==1 )
                                easyAjaxPopup(\'Správa obrázků\', \'upravit.ajax.obrazky.php\', \'id='.$_POST["id"].'\');
                            else{
                                document.getElementById(\'divUploadHlaska\').style.display = \'block\';
                                document.getElementById(\'divUploadHlaska\').innerHTML = \'Chyba: Možná je plný server.\';
                            }
      }
      </script>
      <form method="post" action="upravit.ajax.obrazky_upload.php" target="uploadIframe" enctype="multipart/form-data">
      <input type="hidden" name="id" value="'.$_POST["id"].'">';

$produkt = mysql_fetch_assoc( Mysql_query("SELECT obrazky FROM $CONF[sqlPrefix]zbozi WHERE id=$_POST[id]") );

$obrazky = explode(";", $produkt["obrazky"]);
$i = 1;
Echo '<div style="text-align: center;">';
Foreach($obrazky as $key=>$value){
        If( $value ){
            Echo '<div style="height: 96px; position: relative; float: left;">
                    <img src="../zbozi/obrazky/'.$value.'" height="96" border="0">
                    <img src="images/delete.png" style="cursor: pointer; position: absolute; top: 2px; right: 2px;" title="smazat" onClick="if(confirm(\'Opravdu chcete tento obrázek smazat?\'))easyAjaxPopup(\'Správa obrázků\', \'upravit.ajax.obrazky.php\', \'id='.$_POST["id"].'&obrazekSmaz='.$value.'\');">
                  </div>';
            $i++;
        }
}
Echo '</div><div style="clear: both;">&nbsp;</div>';

Echo '<p align="center">
        <table border="0" align="center"><tr><td>
          <strong>Přidat přílohy: </strong><br>
          '.inputyProObrazky().'<br>
          <div style="text-align: right;"><input type="submit" name="submit" value="Odeslat">
        </td></tr></table>
        <iframe id="uploadIframe" name="uploadIframe" src="upravit.ajax.prilohy_upload.php" style="border: 0px solid red; width: 0px; height: 0px;"></iframe>
      </p>';

Echo '</form>';
?>
