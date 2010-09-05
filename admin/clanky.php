<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="images/clanky.png"></td>
                  <td width="335"><font face="Arial" size="2">
                  <span style="font-weight: 700">Textové stránky</span></font><font face="Arial" style="font-size: 8pt"><br>
                  Editace či přidávání textových stránek pro Váš web.</font></td>
                </tr>
              </table>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>



<?php
If( $_GET["clanek"]) $clanek=$_GET["clanek"];
If(!$_GET["clanek"]) $clanek=zjisti_z("$CONF[sqlPrefix]clanky","id","typ='vodorovne' ORDER BY id ASC LIMIT 1");

///////////
// Funkce
///////////
Function upravProJS($text){
         $text=str_replace("\r","",$text);
         $text=str_replace("\n","",$text);
         $text=str_replace("'","\\'",$text);
         $text=str_replace("'","\\'",$text);
         $text=eregi_replace("<script.*</script>","",$text);
         Return $text;
}
?>



<?php /* SQL */
If( $_POST["zmenit_clanek"]=="ano" ){
    $dotaz=Mysql_query("UPDATE $CONF[sqlPrefix]clanky SET nazev='$_POST[nazev]',uvod='$_POST[uvod]',obsah='$_POST[obsah]',datum='".time()."' WHERE id='$clanek' ");
    If( !mysql_error() ){
        Echo '
              <span style="background-color: #FFDEDE;">
                    Editace proběhla úspěšně.
              </span>
              ';
    }
    Else{
         Echo '
               <span style="background-color: #FFDEDE;">
                     Neznámá chyba. <br>
                     '.mysql_error().'
               </span>
               ';
         }
}
?>



<?php /* Další články */

// Vytvoření
If( $_POST["akce"]=="pridatClanek" AND $_POST["nazev"] ){
    If( !zjisti_z("$CONF[sqlPrefix]clanky","id","nazev='".urlText($_POST["nazev"])."'") ){
        If( Mysql_query("INSERT INTO $CONF[sqlPrefix]clanky(nazev,uvod,obsah,typ,datum) VALUES ('$_POST[nazev]','Stránce zatím nebyl vytvořen obsah...','Stránce zatím nebyl vytvořen obsah...','$_POST[typ]','".time()."') ") ){
            Echo '
                  <span style="background-color: #FFDEDE;">
                        Stránka byla úspěšně vytvořena.
                  </span>
                  ';
        }
        Else{
             Echo '
                   <span style="background-color: #FFDEDE;">
                         Neznámá chyba. <br>
                         '.mysql_error().'
                   </span>
                   ';
        }
    }
    Else{
             Echo '
                   <span style="background-color: #FFDEDE;">
                         Název již existuje.
                   </span>
                   ';
    }
}

// Smazání
If( $_GET["clanek_smaz"] ){
    If( Mysql_query("DELETE FROM $CONF[sqlPrefix]clanky WHERE id='".$_GET["clanek_smaz"]."' ORDER BY id DESC LIMIT 1") ){
        Echo '
              <span style="background-color: #FFDEDE;">
                    Stránka byla úspěšně smazána.
              </span>
              ';
    }Else{
          Echo '
                <span style="background-color: #FFDEDE;">
                      Neznámá chyba. <br>
                      '.mysql_error().'
                </span>
                ';
    }
}

?>



<?php
/* -- Odkazy na články -- */
// Hlavní menu
Echo '<p></p>
      <b>Hlavní menu:</b>
      <div style="width: 100%; background-color: #DEFFDE; border: 1px solid green; text-align: center;">
      ';
$dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE typ='vodorovne' ORDER BY id ASC") or die(mysql_error());
$pocetClanku = 0;
While($radek=mysql_fetch_array($dotaz)){
      Echo '
            <a href="?m=clanky&clanek='.$radek["id"].'" title="upravit tuto stránku">'.$radek["nazev"].'</a>
            '.($CONF["admin"]["ctrlClankyVodorovne"]? '<span style="cursor: pointer;" onClick="if(confirm(\'Opravdu chcete stránku '.$radek["nazev"].' smazat?\'))document.location.href=\'?m=clanky&clanek_smaz='.$radek["id"].'\';"><img src="images/delete.png" height="12px" width="12px" border="0" alt="del" title="smazat" style="position: relative; top: 1px; left: 1px;" /></span>': '').'
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            ';
      $pocetClanku++;
}
If( $pocetClanku==0 ){
    Echo 'V této kategorii nejsou vytvořeny žádné stránky. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
}
Echo '</div>';

// Novinky
If( $CONF["admin"]["ctrlClankyNovinky"] ){
    Echo '
          &nbsp;<br>
          <b>Novinky:</b>
          <div style="width: 100%; background-color: #DEFFDE; border: 1px solid green; text-align: center;">
          ';
    $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE typ='novinka' ORDER BY id ASC");
    $pocetClanku = 0;
    While($radek=mysql_fetch_array($dotaz)){
          Echo '
                <nobr>
                <a href="?m=clanky&clanek='.$radek["id"].'" title="upravit tuto stránku">'.$radek["nazev"].'</a>
                <span style="cursor: pointer;" onClick="if(confirm(\'Opravdu chcete stránku '.$radek["nazev"].' smazat?\'))document.location.href=\'?m=clanky&clanek_smaz='.$radek["id"].'\';"><img src="images/delete.png" height="12px" width="12px" border="0" alt="del" title="smazat" style="position: relative; top: 1px; left: 1px;" /></span>
                </nobr>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                ';
          $pocetClanku++;
    }
    If( $pocetClanku==0 ){
        Echo 'V této kategorii nejsou vytvořeny žádné stránky. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    }
    Echo '</div>';
}


// Vedlejší články
Echo '
      &nbsp;<br>
      <b>Další stránky:</b>
      <div style="width: 100%; background-color: #DEFFDE; border: 1px solid green; text-align: center;">
      ';
$dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE typ='' ORDER BY id ASC");
$pocetClanku = 0;
While($radek=mysql_fetch_array($dotaz)){
      Echo '
            <a href="?m=clanky&clanek='.$radek["id"].'" title="upravit tuto stránku">'.$radek["nazev"].'</a>
            <span style="cursor: pointer;" onClick="if(confirm(\'Opravdu chcete stránku '.$radek["nazev"].' smazat?\'))document.location.href=\'?m=clanky&clanek_smaz='.$radek["id"].'\';"><img src="images/delete.png" height="12px" width="12px" border="0" alt="del" title="smazat" style="position: relative; top: 1px; left: 1px;" /></span>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            ';
      $pocetClanku++;
}
If( $pocetClanku==0 ){
    Echo 'V této kategorii nejsou vytvořeny žádné stránky. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
}
Echo '</div>';

// Formulář pro vytvoření nových článků
Echo '&nbsp;<br><b>Nová stránka:</b> <br>
      <div style="width: 100%; background-color: #DEFFDE; border: 1px solid green; text-align: center;">
      <form action="" method="post"><input type="hidden" name="akce" value="pridatClanek">
      <span style="font-size: 80%">Vytvořit novou stránku: </span>
      <input type="text" name="nazev" value="název" onClick="if(this.value==\'název\'){this.value=\'\';}" style="font-size: 7pt;" size="8">
      <select name="typ" style="font-size: 7pt;">
        '.($CONF["admin"]["ctrlClankyNovinky"]? '<option value="novinka">novinka</option>': '').'
        '.($CONF["admin"]["ctrlClankyVodorovne"]? '<option value="vodorovne">vodorovné menu</option>': '').'
        <option value="">textová stránka</option>
      </select>
      <input type="submit" name="odeslat" value="OK" style="font-size: 7pt;">
      </form>
      </div>
      ';



////////////////////////
// Zobrazení formuláře
////////////////////////

/* SQL Dotaz */
$dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE id='$clanek'");
$radek=mysql_fetch_array($dotaz);


/* Nadpis */
Echo '&nbsp;<br>&nbsp;<br><h2 style="margin: auto; text-align: center; align: center;">'.$radek["nazev"].'</h2>';
If( $CONF["mod_rewrite"]) Echo '<span style="border: 2px solid #EFEFDE; padding-left: 4px; padding-right: 6px;"><b>Odkaz na tuto stránku: </b>'.ereg_replace("admin/$","",$CONF["absDir"]).'clanky/'.$radek["id"].'-'.urlText($radek["nazev"]).'.html</span>';
If(!$CONF["mod_rewrite"]) Echo '<span style="border: 2px solid #EFEFDE; padding-left: 4px; padding-right: 6px;"><b>Odkaz na tuto stránku: </b>'.ereg_replace("admin/$","",$CONF["absDir"]).'index.php?a=clanky&clanek='.$radek["id"].'-'.urlText($radek["nazev"]).'</span>';


/* Obsah */
Echo '
    <form action="" method="post">
    <input type="hidden" name="zmenit_clanek" value="ano">
    '.( $_GET["novinka"]?('Název: <input type="text" name="nazev" value="'.$radek["nazev"].'">'):'' ).'<br>

    <b>Název:</b> <input type="text" name="nazev" value="'.$radek["nazev"].'"><br>

    &nbsp;<br>
    <b>Perex:</b>
    <script type="text/javascript">
    var oFCKeditor = new FCKeditor(\'uvod\');
    oFCKeditor.BasePath = "fckeditor/";
    oFCKeditor.ToolbarSet = "Small";
    oFCKeditor.Value = \''.upravProJS($radek["uvod"]).'\';
    oFCKeditor.Height = "220px";
    oFCKeditor.Create();
    </script>
    <noscript><textarea name="uvod" rows="12" cols="65">'.$radek["uvod"].'</textarea></noscript>

    &nbsp;<br>
    <b>Obsah:</b>
    <div id="divObsah" style="display: none;">'.$radek["obsah"].'</div>
    <script type="text/javascript">
    var oFCKeditor = new FCKeditor(\'obsah\');
    oFCKeditor.BasePath = "fckeditor/";
    oFCKeditor.ToolbarSet = "Grafart";
    oFCKeditor.Value = document.getElementById(\'divObsah\').innerHTML;
    oFCKeditor.Height = "400px";
    oFCKeditor.Create();
    </script>
    <noscript><textarea name="obsah" rows="12" cols="65">'.$radek["obsah"].'</textarea></noscript>

    <div style="text-align: right;"><input type="submit" name="odeslat" value="odeslat">
    </form>
      ';
?>



<?php Include 'grafika_kon.inc'; ?>
