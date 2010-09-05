<?php
/* -- Include configu -- */
Include "../../config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");

?>



<?php
/* -- Zjištění informací -- */
$produkt = mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi x,$CONF[sqlPrefix]zbozi y  WHERE x.produkt=y.produkt AND y.id=$_POST[id] LIMIT 1") );


/* -- Provedení změny -- */
$info = 'Pokoušíte se změnit název produktu \'<i>'.$produkt["produkt"].'</i>\'. Zadejte prosím nový název.';
If( $_POST["noveJmeno"] ){
    $noveJmeno = str_replace("\"","´",$_POST["noveJmeno"]);
    If( Mysql_query("UPDATE $CONF[sqlPrefix]zbozi SET produkt='".$noveJmeno."' WHERE produkt=(SELECT produkt FROM (SELECT * FROM $CONF[sqlPrefix]zbozi) as pomTable WHERE id=$_POST[id]) ") ){
        $info = '<span style="color: #008000;">
                 Název produktu byl úspěšně změněn na \'<i>'.$_POST["noveJmeno"].'</i>\'. 
                 </span>
                 <p>
                 <span style="color: #808080;">
                 Pro pokračování klikněte <span onClick="location.replace(location.href);" style="text-decoration: underline; cursor: pointer;">zde</span>, 
                 nebo počkejte <span id="spanOdpocitavani">10</span> sekund.
                 </span>
                 <script type="text/javascript">
                 odpocitavani(10,\'spanOdpocitavani\');
                 setTimeout("location.replace(location.href)", 10000);
                 </script>';    
    }
}


/* -- Zobrazení -- */
Echo '<form action="" method="post">
      <table>
        <tr>
          <td colspan="3">
            '.$info.'
            <br>&nbsp;
          </td>
        </tr>
        <tr>
          <td>Nové jméno: </td>
          <td><input type="text" name="noveJmeno" id="inputNoveJmeno" value="'.( $_POST["noveJmeno"]? $_POST["noveJmeno"]: $produkt["produkt"] ).'"></td>
          <td><input type="submit" name="button" value="Změnit" onClick="easyAjaxPopup(\'Změna názvu produktu\', \'ajax.php/ajax.zmena_nazvu_produktu.php\', \'id='.$produkt["id"].'&noveJmeno=\'+document.getElementById(\'inputNoveJmeno\').value)"></td>
        </tr>
      </table>
      </form>';
?>
