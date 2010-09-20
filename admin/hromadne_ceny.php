<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="images/penize.png" width="48" height="48"></td>
                  <td width="335"><font face="Arial" size="2">
                  <span style="font-weight: 700">Hromadná změna cen</span></font><font face="Arial" style="font-size: 8pt"><br>
                  Dovoluje změnit cenu o určitý počet procent u více zboží
                  najednou.</font></td>
                </tr>
              </table>
              <script type="text/javascript">
              posledniDiv=\'\';
              function ukazSkryjProdukty(divId){
                       if( posledniDiv!=\'\' ){
                           ukazSkryj(posledniDiv);
                       }
                       ukazSkryj(divId);
                       posledniDiv=divId;
              }
              posledniKategorie=\'\';
              function ukazSkryjNazevKategorie(divId){
                       /*if( posledniKategorie!=\'\' ){
                           document.getElementById(\'divNazevKategorie\'+posledniKategorie).style.backgroundColor = \'#DFDFDF\';
                       }
                       document.getElementById(\'divNazevKategorie\'+divId).style.backgroundColor = \'#F0F0FF\';*/
                       posledniKategorie=divId;              
              }
              </script>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>



<h3 style="padding-top: 0px; margin-top: 0px;">Slevy a zdražení</h3>
<?php /* Změna cen */
If( $_POST["cena_do"] AND $_POST["zmena_na"]>0 ){
    $cena_od=$_POST["cena_od"];
    $cena_do=$_POST["cena_do"];
    $zmena_na=$_POST["zmena_na"];

    //zjištění názvu
    $zmenaPovolena = ",";
    Foreach($_POST["zboziCheck"] as $key=>$value){
            $zmenaPovolena.= zjisti_z("$CONF[sqlPrefix]zbozi","produkt","id=$value").",";
    }

    // Provedení změn
    $i=0;
    $log="";
    $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE (cenaSDph>=$cena_od AND cenaSDph<=$cena_do) OR (cenaBezDph>=$cena_od AND cenaBezDph<=$cena_do)");
    While($radek=Mysql_fetch_array($dotaz)){
          If( eregi(','.$radek["produkt"].',',$zmenaPovolena) ){
              $cenaSloupec = $radek["cenaSDph"]? "cenaSDph": "cenaBezDph";

              $cena=ceil($radek["$cenaSloupec"]*($zmena_na/100));
              $id=$radek["id"];
              $dotaz2=Mysql_query("UPDATE $CONF[sqlPrefix]zbozi SET $cenaSloupec=$cena WHERE id=$id") or die("Mysql chyba: ".mysql_error());
              $log.='<span style="color: #666666; font-sie: 80%;">'.($i+1).'.</span>&nbsp;U produktu <b style="color: #B90000;">'.$radek["produkt"].'</b>-&gt;<b style="color: #B90000;">'.$radek["varianta"].'</b> byla změněna cena z <b style="color: #B90000;">'.$radek["$cenaSloupec"].'</b> na <b style="color: #B90000;">'.$cena.'</b> Kč. <br>';
              $i++;
          }
    }
    $log.='<span style="color: #0000FF;">Celkem provedeno úprav: <b>'.$i.'</b></span>';

    // Zobrazení výsledků
    If( $i>0 ){
        Echo '
              <div style="color: red;"><b>Změny úspěšně provedeny.</b></div>
              <div style="padding: 2px 4px 2px 4px; width: 80%; font-size: 80%;">
                   <div style="color: blue; cursor: pointer;" onClick="ukazSkryj(\'divPrubeh\')">Ukázat průběh =&gt;</div>
                   <div id="divPrubeh" style="display: none; height: 320px; overflow: scroll;">'.$log.'</div>
              </div>
              &nbsp;<br>
              ';
    }
    Else{
         Echo '<div style="color: red;"><b>Žádné změny nebyly provedeny.</b></div>';
    }
}
?>



<form action="" method="post" style="margin: 0px 0px 0px 0px;">
<b>Cenu změnit pouze u:</b>
<div style="clear: both;"></div>
<?php /* Zobrazení */
/* Kategorie */
Echo '<div style="padding: 2px 2px 2px 2px; float: left;"><b>Kategorie</b> <br>';
$dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL GROUP BY kategorie ORDER BY kategorie ASC ");
While($radek=mysql_fetch_array($dotaz)){
      Echo '<div id="divNazevKategorie'.$radek["id"].'" style="position: relative; left: 2px;">
              <input type="checkbox" onChange="fCheckZbozi'.$radek["id"].'();" id="checkKategorie'.$radek["id"].'" name="kategorieCheck'.$radek["id"].'" value="'.$radek["id"].'" style="position: relative; top: 2px; _top: 1px;">
              <span onClick="ukazSkryjProdukty(\'divProdukty'.$radek["id"].'\'); ukazSkryjNazevKategorie('.$radek["id"].');" style="cursor: pointer; text-decoration: underline;">
                '.$radek["kategorie"].'
              </span> 
            </div>
            ';
}
Echo '</div>';


/* Zboží v kategorii */
$dotaz=Mysql_query("SELECT kategorie,id FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL GROUP BY kategorie ORDER BY kategorie ASC ");
While($radek=mysql_fetch_array($dotaz)){
      //Právě načítaná kategorie kategorie
      Echo '<div id="divProdukty'.$radek["id"].'" style="display: none; padding-left: 2px;"><div>(Produkty)</div>';
      $dotaz2=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL AND kategorie='".$radek["kategorie"]."' GROUP BY produkt ORDER BY kategorie ASC ");
      While($radek2=mysql_fetch_array($dotaz2)){
            Echo '<div style="width: 196px; height: 20px; float: left; overflow: hidden;">
                    <input type="checkbox" id="checkZbozi'.$radek2["id"].'" name="zboziCheck['.$radek2["id"].']" value="'.$radek2["id"].'" style="position: relative; top: 2px;">
                    '.$radek2["produkt"].'
                  </div>
                  ';
      }
      Echo '  <div style="width: 100%; clear: both;"></div>
            </div>';
}

Echo '<div style="clear: both;"></div>';


/* Javascripty pro check zboží */
Echo '<script type="text/javascript">';
$dotaz=Mysql_query("SELECT kategorie,id FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL GROUP BY kategorie ORDER BY kategorie ASC ");
While($radek=mysql_fetch_array($dotaz)){
      Echo 'function fCheckZbozi'.$radek["id"].'(){';
      $dotaz2=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL AND kategorie='".$radek["kategorie"]."' GROUP BY produkt ORDER BY id ASC ");
      While($radek2=mysql_fetch_array($dotaz2)){
            Echo 'document.getElementById(\'checkZbozi'.$radek2["id"].'\').checked=document.getElementById(\'checkKategorie'.$radek["id"].'\').checked;
                  ';
      }
      Echo '}';
}
Echo '</script>';
?>

<br>&nbsp;
<div style="width: 100%; border: 0px solid #008000; padding: 0px 2px 2px 2px; clear: both;">
  <b>Nastavení limitů:</b>
  <table border="0">
      <tr>
         <td>Cena od: </td>
         <td>
            <input type="text" name="cena_od" size="6" value="<?php Echo $_POST["cena_od"]?$_POST["cena_od"]:"0"; ?>"> Kč
         </td>
      </tr>
      <tr>
         <td>Cena do: </td>
         <td>
            <input type="text" name="cena_do" size="6" value="<?php Echo $_POST["cena_do"]?$_POST["cena_do"]:"99999"; ?>"> Kč
         </td>
      </tr>
      <tr>
         <td>Změnit cenu na: </td>
         <td>
            <input type="text" name="zmena_na" size="3" value="<?php Echo $_POST["zmena_na"]?$_POST["zmena_na"]:"100"; ?>"> %
            &nbsp;&nbsp;&nbsp;
            <span style="font-size: 80%; color: #666666;">...základní hodnota je 100%. Pokud chcete např. zdražit o 20%, napište zde číslo 120. </span>
         </td>
      </tr>
      <tr>
         <td> </td>
         <td><input type="submit" name="odeslat" value="Odeslat"></td>
      </tr>
  </table>
</div>
</form>



<br>&nbsp;
<hr size="1">
<h3>Zobrazovač původní ceny</h3>
<?php
if( $_POST["akce"]=='puvodni-ceny' ){
    Mysql_query("UPDATE $CONF[sqlPrefix]nastaveni SET zbozi_puvodni_cena_active='$_POST[zbozi_puvodni_cena_active]', zbozi_usetrite_jednotky='$_POST[zbozi_usetrite_jednotky]' WHERE id=1");
    echo '<div style="color: red; padding-bottom: 12px;"><b>Nastavení uloženo.</b></div>';
}
?>
<form action="" method="post" style="margin: 0px 0px 0px 0px;">
<div style="width: 100%; border: 0px solid #008000; padding: 0px 2px 2px 2px; clear: both;">
  <b>Původní ceny zboží:</b>
  <table border="0">
      <tr>
         <td>Zobrazovat u zboží Původní cenu: </td>
         <td>
            <input type="checkbox" name="zbozi_puvodni_cena_active" value="1" <?php Echo zjisti_z("$CONF[sqlPrefix]nastaveni","zbozi_puvodni_cena_active","id=1")? 'checked': ''; ?>>
         </td>
      </tr>
      <tr>
         <td>Slevu zobrazovat v jednotkách: </td>
         <td>
            <select name="zbozi_usetrite_jednotky">
              <option value="Kč">Kč</option>
              <option value="%" <?php Echo zjisti_z("$CONF[sqlPrefix]nastaveni","zbozi_usetrite_jednotky","id=1")=='%'? 'selected': ''; ?>>%</option>
            </select>
         </td>
      </tr>
      <tr>
         <td> </td>
         <td>
           <input type="hidden" name="akce" value="puvodni-ceny">
           <input type="submit" name="odeslat" value="Uložit">
         </td>
      </tr>
  </table>
</div>
</form>



<?php Include 'grafika_kon.inc'; ?>
