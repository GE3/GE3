<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="7" align="center">&nbsp;</td>
                  <td width="49" valign="top">
                  <img border="0" src="'.$CONF["absDir"].'templates/'.$CONF["vzhled"].'/images/soubory.png" width="47" height="47"></td>
                  <td width="591" align="center" colspan="2">
                  <p align="left"><font face="Arial" size="2">
                  <span style="font-weight: 700;">Správce souborů</span></font><font face="Arial" style="font-size: 8pt"><br>
                  Tato funkce Vám umožní ukládání či mazání dat na Váš server.</font></td>
                </tr>
              </table>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>



<?php
$cesta = $_GET["cesta"]? $_GET["cesta"]: "../userfiles/";
$cesta = preg_replace("#/[^/]+/\.\./$#Usi", "/", $cesta);
If(!eregi("^../userfiles/",$cesta) OR eregi(".+\.\./",$cesta)) $cesta="../userfiles/";


/* -- Upload souboru -- */
If( $_FILES["soubor"]["name"] AND !eregi("\.php",$_FILES["soubor"]["name"]) ){
    If( move_uploaded_file($_FILES["soubor"]["tmp_name"], $cesta.$_FILES["soubor"]["name"]) )
        Echo "<p>Soubor úspěšně odeslán.<p>";
    Else Echo "<p>Při nahrávání souboru nastala neznámá chyba.<p>";
}


/* -- Mazání souborů -- */
If( $_GET["delete"] AND !$_FILES["soubor"]["name"] ){
    If( unlink($cesta.$_GET["delete"]) )
        Echo "<p>Soubor úspěšně smazán.<p>";
    Else Echo "<p>Při mazání souboru nastala neznámá chyba.<p>";    
}


/* -- Zobrazení souborů -- */
Echo "<h3>Prohlížení souborů (adresář ".preg_replace("#^\.\./#Usi", "", $cesta).")</h3>
      <div style=\"padding-left: 24px;\">";

$soubory = array();
$slozky = array();

$adresar = opendir($cesta);
While($soubor=readdir($adresar)){
      If( is_dir($cesta.$soubor) AND $soubor!='.' AND ($soubor!='..' OR $cesta!='../userfiles/') ){
          if($soubor!='..') $slozky[] = '+ <a href="?m=soubory&cesta='.$cesta.$soubor.'/" style="font-family: Arial;">'.substr($soubor,0,32).'/</a> <br>';
          else array_unshift($slozky, '+ <a href="?m=soubory&cesta='.$cesta.$soubor.'/" style="font-family: Arial;">'.substr($soubor,0,32).'/</a> <br>');
      }
      Elseif( $soubor!='.' AND ($soubor!='..' OR $cesta!='../userfiles/') ){
          $soubory[] = '- '.substr($soubor,0,32).' <a href="?m=soubory&cesta='.$_GET["cesta"].'&delete='.$soubor.'"><img src="images/delete.png" border="0" height="10"></a> <br>';
      }
}
closedir($adresar);

Foreach($slozky as $key=>$value){
        Echo $value;
}
Foreach($soubory as $key=>$value){
        Echo $value;
}

Echo "</div>";
?>


<hr>
<h3>Přidávání souborů</h3>
<form method="post" action="" enctype="multipart/form-data">
  Přidat soubor: 
  <input type="file" name="soubor">
  <input type="submit" name="odeslat" value="odeslat">
</form>



<?php Include 'grafika_kon.inc'; ?>
