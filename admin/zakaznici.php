<?php
Include '../fce/easy_mail.inc';
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="templates/'.$CONF["vzhled"].'/images/zakaznici.png"></td>
                  <td width="616" colspan="2">
                  <p align="left"><span style="font-weight: 700">
                  <font face="Arial" size="2">Informace o návštěvnících vašeho portálu</font></span><font face="Arial" style="font-size: 8pt"><br>
                  Přehled registrovaných zákazníků a také návštěvníků, kteří mají zájem o zasílání novinek na e-mail.
                  </font></td>
                </tr>
              </table>
              ';

Function _ikonaPoznamky($obsah, $typ){
        $ikona = 'info.png';
        If($obsah=='') $ikona='info-prazdne.png';
        If($typ=='kladná') $ikona='info-kladne.png';
        If($typ=='záporná') $ikona='info-zaporne.png';
        Return $ikona;
}
              
?>
<?php Include 'grafika_zac.inc'; ?>



<form method="post" action="">
<b>Přehled registrovaných zákazníků: </b>
<p>
Vyhledat zákazníka: <input type="text" name="vyhledavani" value="<?php Echo $_POST["vyhledavani"]; ?>" style="font-size: 80%;"> <input type="submit" name="odeslat" value="Vyhledat" style="font-size: 80%;">
<p>
<?php
/* Smazání zákazníka */
If( $_GET["zakaznikSmaz"] ){
    If( Mysql_query("DELETE FROM $CONF[sqlPrefix]zakaznici WHERE id=".$_GET["zakaznikSmaz"]." ") ){
        Echo '<div style="border: 2px solid #008000; background-color: #EEFFEE; color: #008000; font-weight: bold; margin: 4px 18px 4px 18px; padding: 2px 4px 2px 4px; text-align: center;">
              Smazání proběhlo úspěšně. [<a href="?m=zakaznici">OK</a>]
              </div>
              ';
      }Else{
        Echo '<div style="border: 2px solid #800000; background-color: #FFEEEE; color: #800000; font-weight: bold; margin: 4px 18px 4px 18px; padding: 2px 4px 2px 4px; text-align: center;">
              Při mazání došlo k chybám. [<a href="?m=zakaznici">OK</a>] <br>'.mysql_error().'
              </div>
              ';
    }
}

/* Vypsání zákazníků */
Echo '<table border="0" width="100%">';
Echo '<tr>
        <td style="font-weight: bold;"><a href="?m=zakaznici&orderBy=prijmeni">Jméno</a></td>
        <td style="font-weight: bold;">Adresa</td>
        <td style="font-weight: bold;"><a href="?m=zakaznici&orderBy=telefon">Telefon</a></td>
        <td style="font-weight: bold;"><a href="?m=zakaznici&orderBy=email">E-mail</a></td>
        <td>&nbsp;</td>
      </tr>
      ';
If( $_POST["vyhledavani"] ){
    $vyhledavani=str_replace("*","%",$_POST["vyhledavani"]);
    $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zakaznici WHERE jmeno LIKE '%$vyhledavani%' OR prijmeni LIKE '%$vyhledavani%' OR mesto LIKE '%$vyhledavani%' OR ulice LIKE '%$vyhledavani%' OR psc LIKE '%$vyhledavani%' OR telefon LIKE '%$vyhledavani%' OR email LIKE '%$vyhledavani%' ");
  }Else{
    $orderBy = ($_GET["orderBy"]) ? ($_GET["orderBy"].' ASC') : ("id DESC");
    $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zakaznici ORDER BY $orderBy ");
}
While($radek=mysql_fetch_array($dotaz)){
      $poznamka = mysql_fetch_assoc( Db_query("SELECT * FROM $CONF[sqlPrefix]zakazniciPoznamky WHERE email='$radek[email]'") );
      $poznamkaTyp = $poznamka["typ"];
      $poznamkaObsah = $poznamka["poznamka"];
      Echo '<tr>
              <td>
              <b>'.$radek["prijmeni"].' '.$radek["jmeno"].'</b>
              </td>
              <td>
              '.$radek["mesto"].' <br>
              '.$radek["ulice"].' <br>
              '.$radek["psc"].'
              </td>
              <td>
              Tel.: '.$radek["telefon"].' <br>
              </td>
              <td>
              E-mail: '.$radek["email"].' <br>
              heslo: '.$radek["heslo"].'
              </td>
              <td width="40" valign="top" align="center">
                <span style="cursor: pointer;" onClick="easyAjaxPopup(\'Poznámka k zákazníkovi\', \'zakazniciPoznamky.ajax.php\', \'email='.$radek["email"].'\');">
                  <img src="images/'._ikonaPoznamky($poznamkaObsah,$poznamkaTyp).'" title="'.($poznamkaObsah? $poznamkaObsah: 'Žádná poznámka k tomuto zákazníkovi.').'" onMouseMove="cotojatko(this);">
                </span>
                <img src="images/delete.png" border="0" width="16" onClick="if( confirm(\'Opravdu chcete tohoto zákazníka smazat?\') ){window.location.href=\'?m=zakaznici&zakaznikSmaz='.$radek["id"].'\';}" style="cursor: pointer;">
              </td>
            </tr>
            <tr>
              <td colspan="5">
                <div style="text-align: center;"><a href="javascript: void(0);" style="color: #666666;" onClick="ukazSkryj(\'divViceInfo'.$radek["id"].'\');">více informací</a></div>
                <div id="divViceInfo'.$radek["id"].'" style="display: none;">
                  <table border="0" width="100%">
                    <tr>
                      <td valign="top">
                          Firma: '.$radek["firma"].'<br>
                          IČO: '.$radek["ico"].'<br>
                          DIČ: '.$radek["dic"].'</td>
                      <td valign="top">
                          Dodací adresa (pokud je jiná než fakturační): <br>
                          Jméno: '.$radek["jmeno_2"].'<br>
                          Firma: '.$radek["firma_2"].'<br>
                          Ulice: '.$radek["ulice_2"].'<br>
                          Město: '.$radek["mesto_2"].'<br>
                          PSČ: '.$radek["psc_2"].'<br>
                          IČO: '.$radek["ico_2"].'<br>
                          DIČ: '.$radek["dic_2"].'</td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="5">&nbsp;</td>
            </tr>
            ';
}
Echo '</table>';
?>
</form>



<?php If(!zjisti_z("$CONF[sqlPrefix]novinky_emailem","COUNT(*)","1=1")) Echo '<!--';?>
<hr><p>
<b>Přejí si být informováni o novinkách: </b>
<p>
<?php
/* Smazání emailu */
If( $_GET["emailSmaz"] ){
    If( Mysql_query("DELETE FROM $CONF[sqlPrefix]novinky_emailem WHERE id=".$_GET["emailSmaz"]." ") ){
        Echo '<div style="border: 2px solid #008000; background-color: #EEFFEE; color: #008000; font-weight: bold; margin: 4px 18px 4px 18px; padding: 2px 4px 2px 4px; text-align: center;">
              Smazání proběhlo úspěšně. [<a href="?m=zakaznici">OK</a>]
              </div>
              ';
      }Else{
        Echo '<div style="border: 2px solid #800000; background-color: #FFEEEE; color: #800000; font-weight: bold; margin: 4px 18px 4px 18px; padding: 2px 4px 2px 4px; text-align: center;">
              Při mazání došlo k chybám. [<a href="?m=zakaznici">OK</a>] <br>'.mysql_error().'
              </div>
              ';
    }
}

/* Vypsání emailů */
$dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]novinky_emailem ORDER BY email asc ");
While($radek=mysql_fetch_array($dotaz)){
      Echo $radek["email"].'<img src="images/delete.png" border="0" width="12" onClick="if( confirm(\'Opravdu chcete tento email smazat?\') ){window.location.href=\'?m=zakaznici&emailSmaz='.$radek["id"].'\';}" style="cursor: pointer; margin-left: 4px;">, ';
}
Echo '<p>';
?>
<?php If(!zjisti_z("$CONF[sqlPrefix]novinky_emailem","COUNT(*)","1=1")) Echo '-->';?>


<?php Include 'grafika_kon.inc'; ?>
